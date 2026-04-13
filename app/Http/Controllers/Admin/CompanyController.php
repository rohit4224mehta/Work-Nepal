<?php
// app/Http/Controllers/Admin/CompanyController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request): View
    {
        $query = Company::query()
            ->with(['owner' => function ($q) {
                $q->select('id', 'name', 'email');
            }])
            ->withCount(['jobPostings as active_jobs_count' => function ($q) {
                $q->where('status', 'active')
                  ->whereDate('deadline', '>=', now());
            }])
            ->withCount('jobPostings as total_jobs_count');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('industry', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhereHas('owner', function ($oq) use ($search) {
                      $oq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Verification status filter
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Industry filter
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Company size filter
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        // Has active jobs filter
        if ($request->filled('has_jobs')) {
            if ($request->has_jobs === 'yes') {
                $query->has('jobPostings', '>', 0);
            } elseif ($request->has_jobs === 'no') {
                $query->doesntHave('jobPostings');
            }
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'jobs_desc':
                $query->orderBy('active_jobs_count', 'desc');
                break;
            case 'jobs_asc':
                $query->orderBy('active_jobs_count', 'asc');
                break;
            case 'verified':
                $query->orderBy('verification_status', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $companies = $query->paginate(15)->withQueryString();

        // Get filter options
        $industries = Cache::remember('admin_company_industries', 3600, function () {
            return Company::whereNotNull('industry')
                ->distinct('industry')
                ->pluck('industry')
                ->filter()
                ->values()
                ->toArray();
        });

        $locations = Cache::remember('admin_company_locations', 3600, function () {
            return Company::whereNotNull('location')
                ->distinct('location')
                ->pluck('location')
                ->filter()
                ->values()
                ->toArray();
        });

        $sizes = [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '501-1000' => '501-1000 employees',
            '1000+' => '1000+ employees',
        ];

        // Statistics
        $stats = [
            'total_companies' => Company::count(),
            'verified_companies' => Company::where('verification_status', 'verified')->count(),
            'pending_companies' => Company::where('verification_status', 'pending')->count(),
            'rejected_companies' => Company::where('verification_status', 'rejected')->count(),
            'companies_with_jobs' => Company::has('jobPostings')->count(),
        ];

        return view('admin.companies.index', compact(
            'companies', 
            'industries', 
            'locations',
            'sizes',
            'stats',
            'request'
        ));
    }

    /**
     * Display pending companies for verification.
     */
    public function pending(Request $request): View
    {
        $query = Company::where('verification_status', 'pending')
            ->with(['owner' => function ($q) {
                $q->select('id', 'name', 'email');
            }])
            ->withCount(['jobPostings as active_jobs_count' => function ($q) {
                $q->where('status', 'active')
                  ->whereDate('deadline', '>=', now());
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('owner', function ($oq) use ($search) {
                      $oq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('admin.companies.pending', compact('companies'));
    }

    /**
     * Display verified companies.
     */
    public function verified(Request $request): View
    {
        $query = Company::where('verification_status', 'verified')
            ->with(['owner' => function ($q) {
                $q->select('id', 'name', 'email');
            }])
            ->withCount(['jobPostings as active_jobs_count' => function ($q) {
                $q->where('status', 'active')
                  ->whereDate('deadline', '>=', now());
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('admin.companies.verified', compact('companies'));
    }

    /**
     * Display company details.
     */
    public function show(Company $company): View
    {
        $company->load([
            'owner',
            'teamMembers' => function ($q) {
                $q->withPivot('role', 'is_active');
            },
            'jobPostings' => function ($q) {
                $q->withCount('applications')
                  ->latest()
                  ->limit(10);
            }
        ]);

        // Get statistics
        $stats = [
            'total_jobs' => $company->jobPostings()->count(),
            'active_jobs' => $company->jobPostings()->where('status', 'active')
                ->whereDate('deadline', '>=', now())
                ->count(),
            'total_applications' => DB::table('job_applications')
                ->whereIn('job_posting_id', $company->jobPostings()->pluck('id'))
                ->count(),
            'team_members' => $company->teamMembers()->count(),
        ];

        // Get recent activity
        $recentJobs = $company->jobPostings()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.companies.show', compact('company', 'stats', 'recentJobs'));
    }

    /**
     * Verify company with notification.
     */
    public function verify(Company $company): RedirectResponse
    {
        if ($company->verification_status === 'verified') {
            return back()->with('info', 'Company is already verified.');
        }

        DB::beginTransaction();

        try {
            $oldStatus = $company->verification_status;
            $company->update(['verification_status' => 'verified']);

            // ✅ NOTIFICATION: Send notification to company owner
            NotificationService::companyVerified($company, $company->owner_id);

            // Log activity
            Log::info('Company verified', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'admin_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'verified'
            ]);

            DB::commit();

            return redirect()->route('admin.companies.show', $company)
                ->with('success', 'Company verified successfully. Owner has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to verify company: ' . $e->getMessage());
            return back()->with('error', 'Failed to verify company. Please try again.');
        }
    }

    /**
     * Reject company verification with notification.
     */
    public function reject(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $company->verification_status;
            $company->update([
                'verification_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // ✅ NOTIFICATION: Send rejection notification to company owner
            NotificationService::send(
                $company->owner_id,
                'company_rejected',
                'Company Application Update',
                "Your company \"{$company->name}\" verification request has been reviewed. Reason: " . $request->rejection_reason,
                [
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'reason' => $request->rejection_reason,
                ]
            );

            // Log activity
            Log::info('Company rejected', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'admin_id' => auth()->id(),
                'reason' => $request->rejection_reason,
                'old_status' => $oldStatus,
                'new_status' => 'rejected'
            ]);

            DB::commit();

            return redirect()->route('admin.companies.index')
                ->with('success', 'Company rejected successfully. Owner has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject company: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject company. Please try again.');
        }
    }

    /**
     * Suspend company.
     */
    public function suspend(Company $company): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $oldStatus = $company->verification_status;
            $company->update(['verification_status' => 'suspended']);

            // Also suspend all active jobs
            $suspendedJobs = JobPosting::where('company_id', $company->id)
                ->where('status', 'active')
                ->update(['status' => 'suspended']);

            // ✅ NOTIFICATION: Send suspension notification to company owner
            NotificationService::send(
                $company->owner_id,
                'company_suspended',
                'Important: Your Company Has Been Suspended',
                "Your company \"{$company->name}\" has been suspended. Please contact support for more information.",
                [
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'jobs_suspended' => $suspendedJobs,
                ]
            );

            Log::info('Company suspended', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'admin_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'suspended',
                'jobs_suspended' => $suspendedJobs
            ]);

            DB::commit();

            return back()->with('success', 'Company suspended successfully. Owner has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to suspend company: ' . $e->getMessage());
            return back()->with('error', 'Failed to suspend company. Please try again.');
        }
    }

    /**
     * Activate suspended company.
     */
    public function activate(Company $company): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $oldStatus = $company->verification_status;
            $company->update(['verification_status' => 'verified']);

            // Reactivate jobs (optional)
            $activatedJobs = JobPosting::where('company_id', $company->id)
                ->where('status', 'suspended')
                ->update(['status' => 'active']);

            // ✅ NOTIFICATION: Send activation notification to company owner
            NotificationService::send(
                $company->owner_id,
                'company_activated',
                'Great News! Your Company Has Been Activated',
                "Your company \"{$company->name}\" has been reactivated. You can now post jobs again.",
                [
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'jobs_activated' => $activatedJobs,
                ]
            );

            Log::info('Company activated', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'admin_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'verified',
                'jobs_activated' => $activatedJobs
            ]);

            DB::commit();

            return back()->with('success', 'Company activated successfully. Owner has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to activate company: ' . $e->getMessage());
            return back()->with('error', 'Failed to activate company. Please try again.');
        }
    }

    /**
     * Delete company.
     */
    public function destroy(Company $company): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $companyName = $company->name;
            $companyId = $company->id;
            $ownerId = $company->owner_id;

            // Delete logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            // Delete cover if exists
            if ($company->cover_path) {
                Storage::disk('public')->delete($company->cover_path);
            }

            // Delete culture images
            if ($company->culture_images) {
                $images = is_string($company->culture_images) 
                    ? json_decode($company->culture_images, true) 
                    : $company->culture_images;
                
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if ($image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
            }

            $company->delete();

            Log::info('Company deleted', [
                'company_id' => $companyId,
                'company_name' => $companyName,
                'admin_id' => auth()->id(),
                'owner_id' => $ownerId
            ]);

            DB::commit();

            return redirect()->route('admin.companies.index')
                ->with('success', 'Company deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete company: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete company. Please try again.');
        }
    }

    /**
     * Bulk action on companies.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:verify,reject,suspend,activate,delete',
            'company_ids' => 'required|array',
            'company_ids.*' => 'exists:companies,id'
        ]);

        $action = $request->action;
        $companyIds = $request->company_ids;

        DB::beginTransaction();

        try {
            $companies = Company::whereIn('id', $companyIds)->get();
            $processedCount = 0;
            $notificationCount = 0;

            foreach ($companies as $company) {
                switch ($action) {
                    case 'verify':
                        if ($company->verification_status !== 'verified') {
                            $company->update(['verification_status' => 'verified']);
                            NotificationService::companyVerified($company, $company->owner_id);
                            $notificationCount++;
                            $processedCount++;
                        }
                        break;

                    case 'reject':
                        if ($company->verification_status !== 'rejected') {
                            $company->update(['verification_status' => 'rejected']);
                            NotificationService::send(
                                $company->owner_id,
                                'company_rejected',
                                'Company Application Update',
                                "Your company \"{$company->name}\" verification request has been reviewed and was not approved.",
                                ['company_id' => $company->id, 'company_name' => $company->name]
                            );
                            $notificationCount++;
                            $processedCount++;
                        }
                        break;

                    case 'suspend':
                        if ($company->verification_status !== 'suspended') {
                            $company->update(['verification_status' => 'suspended']);
                            JobPosting::where('company_id', $company->id)
                                ->where('status', 'active')
                                ->update(['status' => 'suspended']);
                            NotificationService::send(
                                $company->owner_id,
                                'company_suspended',
                                'Company Suspended',
                                "Your company \"{$company->name}\" has been suspended.",
                                ['company_id' => $company->id, 'company_name' => $company->name]
                            );
                            $notificationCount++;
                            $processedCount++;
                        }
                        break;

                    case 'activate':
                        if ($company->verification_status !== 'verified') {
                            $company->update(['verification_status' => 'verified']);
                            JobPosting::where('company_id', $company->id)
                                ->where('status', 'suspended')
                                ->update(['status' => 'active']);
                            NotificationService::send(
                                $company->owner_id,
                                'company_activated',
                                'Company Activated',
                                "Your company \"{$company->name}\" has been reactivated.",
                                ['company_id' => $company->id, 'company_name' => $company->name]
                            );
                            $notificationCount++;
                            $processedCount++;
                        }
                        break;

                    case 'delete':
                        // Delete files
                        if ($company->logo_path) {
                            Storage::disk('public')->delete($company->logo_path);
                        }
                        if ($company->cover_path) {
                            Storage::disk('public')->delete($company->cover_path);
                        }
                        $company->delete();
                        $processedCount++;
                        break;
                }
            }

            Log::info('Bulk action on companies', [
                'action' => $action,
                'companies_processed' => $processedCount,
                'notifications_sent' => $notificationCount,
                'admin_id' => auth()->id()
            ]);

            DB::commit();

            $message = $processedCount . ' companies processed successfully.';
            if ($notificationCount > 0) {
                $message .= ' ' . $notificationCount . ' notifications sent.';
            }
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process bulk action. Please try again.');
        }
    }

    /**
     * Export companies data.
     */
    public function export(Request $request)
    {
        $query = Company::with(['owner' => function ($q) {
            $q->select('id', 'name', 'email');
        }])->withCount('jobPostings');

        // Apply filters
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $companies = $query->get();

        $filename = 'companies_export_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Add UTF-8 BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, [
            'ID', 'Company Name', 'Owner', 'Owner Email', 'Industry',
            'Location', 'Size', 'Founded Year', 'Verification Status',
            'Total Jobs', 'Active Jobs', 'Website', 'Contact Email',
            'Phone', 'Created At', 'Last Updated'
        ]);

        foreach ($companies as $company) {
            fputcsv($handle, [
                $company->id,
                $company->name,
                $company->owner->name ?? 'N/A',
                $company->owner->email ?? 'N/A',
                $company->industry ?? 'N/A',
                $company->location ?? 'N/A',
                $company->size ?? 'N/A',
                $company->founded_year ?? 'N/A',
                $company->verification_status,
                $company->jobPostings->count(),
                $company->jobPostings()->where('status', 'active')
                    ->whereDate('deadline', '>=', now())
                    ->count(),
                $company->website ?? 'N/A',
                $company->contact_email ?? 'N/A',
                $company->phone ?? 'N/A',
                $company->created_at->format('Y-m-d H:i:s'),
                $company->updated_at->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get company insights.
     */
    public function insights(Company $company): View
    {
        $company->load(['owner', 'jobPostings.applications']);

        $jobTrends = $company->jobPostings()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $applicationTrends = DB::table('job_applications')
            ->join('job_postings', 'job_applications.job_posting_id', '=', 'job_postings.id')
            ->where('job_postings.company_id', $company->id)
            ->select(DB::raw('DATE(job_applications.created_at) as date'), DB::raw('count(*) as count'))
            ->where('job_applications.created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCategories = $company->jobPostings()
            ->select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.companies.insights', compact(
            'company', 
            'jobTrends', 
            'applicationTrends', 
            'topCategories'
        ));
    }
}