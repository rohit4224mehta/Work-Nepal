<?php
// app/Http/Controllers/Admin/ContentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentController extends AdminController
{
    // ==================== TESTIMONIALS MANAGEMENT ====================

    /**
     * Display testimonials listing.
     */
    public function testimonials(Request $request): View
    {
        $query = Testimonial::query()
            ->with(['user', 'moderator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'rejected':
                    $query->rejected();
                    break;
                case 'featured':
                    $query->featured();
                    break;
            }
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $testimonials = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Testimonial::count(),
            'pending' => Testimonial::pending()->count(),
            'approved' => Testimonial::approved()->count(),
            'rejected' => Testimonial::rejected()->count(),
            'featured' => Testimonial::featured()->count(),
            'avg_rating' => Testimonial::approved()->avg('rating') ?? 0,
        ];

        return view('admin.content.testimonials', compact('testimonials', 'stats', 'request'));
    }

    /**
     * Approve testimonial.
     */
    public function approveTestimonial(Testimonial $testimonial): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $testimonial->update([
                'is_approved' => true,
                'rejection_reason' => null,
                'moderated_by' => auth()->id(),
                'moderated_at' => now(),
            ]);

            $this->logAdminAction(
                'testimonial_approved',
                "Approved testimonial #{$testimonial->id}",
                $testimonial
            );

            DB::commit();

            return back()->with('success', 'Testimonial approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve testimonial.');
        }
    }

    /**
     * Reject testimonial with reason.
     */
    public function rejectTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $testimonial->update([
                'is_approved' => false,
                'rejection_reason' => $request->rejection_reason,
                'moderated_by' => auth()->id(),
                'moderated_at' => now(),
            ]);

            $this->logAdminAction(
                'testimonial_rejected',
                "Rejected testimonial #{$testimonial->id}: {$request->rejection_reason}",
                $testimonial
            );

            DB::commit();

            return back()->with('success', 'Testimonial rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject testimonial.');
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Testimonial $testimonial): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $testimonial->update([
                'featured' => !$testimonial->featured,
            ]);

            $this->logAdminAction(
                $testimonial->featured ? 'testimonial_featured' : 'testimonial_unfeatured',
                ($testimonial->featured ? 'Featured' : 'Unfeatured') . " testimonial #{$testimonial->id}",
                $testimonial
            );

            DB::commit();

            return back()->with('success', 'Testimonial featured status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update featured status.');
        }
    }

    /**
     * Delete testimonial.
     */
    public function deleteTestimonial(Testimonial $testimonial): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $testimonial->delete();

            $this->logAdminAction(
                'testimonial_deleted',
                "Deleted testimonial #{$testimonial->id}"
            );

            DB::commit();

            return redirect()->route('admin.content.testimonials')
                ->with('success', 'Testimonial deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete testimonial.');
        }
    }

    /**
     * Bulk action on testimonials.
     */
    public function bulkTestimonials(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,reject,feature,unfeature,delete',
            'testimonial_ids' => 'required|array',
            'testimonial_ids.*' => 'exists:testimonials,id'
        ]);

        $action = $request->action;
        $testimonialIds = $request->testimonial_ids;

        DB::beginTransaction();

        try {
            $testimonials = Testimonial::whereIn('id', $testimonialIds)->get();

            foreach ($testimonials as $testimonial) {
                switch ($action) {
                    case 'approve':
                        $testimonial->update([
                            'is_approved' => true,
                            'rejection_reason' => null,
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                        ]);
                        break;
                    case 'reject':
                        $testimonial->update([
                            'is_approved' => false,
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                        ]);
                        break;
                    case 'feature':
                        $testimonial->update(['featured' => true]);
                        break;
                    case 'unfeature':
                        $testimonial->update(['featured' => false]);
                        break;
                    case 'delete':
                        $testimonial->delete();
                        break;
                }
            }

            $this->logAdminAction(
                "bulk_testimonials_{$action}",
                "Bulk {$action} on " . count($testimonialIds) . " testimonials"
            );

            DB::commit();

            return back()->with('success', count($testimonialIds) . ' testimonials processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process bulk action.');
        }
    }

    /**
     * Export testimonials.
     */
    public function exportTestimonials(Request $request)
    {
        $query = Testimonial::with(['user', 'moderator']);

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'rejected':
                    $query->rejected();
                    break;
            }
        }

        $testimonials = $query->get();

        $filename = 'testimonials_export_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, [
            'ID',
            'User',
            'Email',
            'Content',
            'Rating',
            'Job Title',
            'Company',
            'Status',
            'Featured',
            'Rejection Reason',
            'Moderated By',
            'Moderated At',
            'Created At'
        ]);

        foreach ($testimonials as $testimonial) {
            fputcsv($handle, [
                $testimonial->id,
                $testimonial->user->name ?? 'N/A',
                $testimonial->user->email ?? 'N/A',
                $testimonial->content,
                $testimonial->rating ?? 'N/A',
                $testimonial->job_title ?? 'N/A',
                $testimonial->company_name ?? 'N/A',
                $testimonial->is_approved ? 'Approved' : ($testimonial->rejection_reason ? 'Rejected' : 'Pending'),
                $testimonial->featured ? 'Yes' : 'No',
                $testimonial->rejection_reason ?? '',
                $testimonial->moderator->name ?? '',
                $testimonial->moderated_at ? $testimonial->moderated_at->format('Y-m-d H:i:s') : '',
                $testimonial->created_at->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // ==================== PAGES MANAGEMENT (CMS) ====================

    /**
     * Display pages listing.
     */
    public function pages(Request $request): View
    {
        $query = Page::query()
            ->with(['creator', 'updater']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $pages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.content.pages', compact('pages', 'request'));
    }

    /**
     * Show create page form.
     */
    public function createPage(): View
    {
        $templates = ['default', 'full-width', 'sidebar', 'landing'];
        
        return view('admin.content.page-form', [
            'page' => null,
            'templates' => $templates
        ]);
    }

    /**
     * Store new page.
     */
    public function storePage(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'required|string',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except('featured_image');
            
            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('pages', 'public');
                $data['featured_image'] = $path;
            }

            $data['slug'] = Str::slug($request->title);
            $data['created_by'] = auth()->id();
            
            if ($request->boolean('is_published')) {
                $data['published_at'] = now();
            }

            Page::create($data);

            $this->logAdminAction(
                'page_created',
                "Created page: {$request->title}"
            );

            DB::commit();

            return redirect()->route('admin.content.pages')
                ->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create page.');
        }
    }

    /**
     * Show edit page form.
     */
    public function editPage(Page $page): View
    {
        $templates = ['default', 'full-width', 'sidebar', 'landing'];
        
        return view('admin.content.page-form', compact('page', 'templates'));
    }

    /**
     * Update page.
     */
    public function updatePage(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'required|string',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except('featured_image');
            
            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($page->featured_image) {
                    Storage::disk('public')->delete($page->featured_image);
                }
                $path = $request->file('featured_image')->store('pages', 'public');
                $data['featured_image'] = $path;
            }

            $data['updated_by'] = auth()->id();
            
            if ($request->boolean('is_published') && !$page->is_published) {
                $data['published_at'] = now();
            }

            $page->update($data);

            $this->logAdminAction(
                'page_updated',
                "Updated page: {$page->title} (ID: {$page->id})",
                $page
            );

            DB::commit();

            return redirect()->route('admin.content.pages')
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update page.');
        }
    }

    /**
     * Delete page.
     */
    public function deletePage(Page $page): RedirectResponse
    {
        DB::beginTransaction();

        try {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }

            $pageTitle = $page->title;
            $page->delete();

            $this->logAdminAction(
                'page_deleted',
                "Deleted page: {$pageTitle}"
            );

            DB::commit();

            return redirect()->route('admin.content.pages')
                ->with('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete page.');
        }
    }

    /**
     * Toggle page status.
     */
    public function togglePageStatus(Page $page): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $page->update([
                'is_published' => !$page->is_published,
                'published_at' => !$page->is_published ? now() : null,
                'updated_by' => auth()->id(),
            ]);

            $this->logAdminAction(
                $page->is_published ? 'page_published' : 'page_unpublished',
                ($page->is_published ? 'Published' : 'Unpublished') . " page: {$page->title}",
                $page
            );

            DB::commit();

            return back()->with('success', 'Page status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update page status.');
        }
    }

    /**
     * Get content moderation statistics.
     */
    public function getStats(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'testimonials' => [
                'total' => Testimonial::count(),
                'pending' => Testimonial::pending()->count(),
                'approved' => Testimonial::approved()->count(),
                'rejected' => Testimonial::rejected()->count(),
                'featured' => Testimonial::featured()->count(),
            ],
            'pages' => [
                'total' => Page::count(),
                'published' => Page::published()->count(),
                'draft' => Page::draft()->count(),
            ],
        ];

        return response()->json($stats);
    }
}