<?php
// app/Http/Controllers/Admin/JobController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobController extends AdminController
{
    /**
     * Display a listing of jobs.
     */
    public function index(Request $request): View
    {
        $query = JobPosting::query()
            ->with(['company' => function ($q) {
                $q->select('id', 'name', 'slug', 'verification_status');
            }])
            ->withCount('applications');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Verification status filter
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Job type filter
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Experience level filter
        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }

        // Featured filter
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Deadline filter
        if ($request->filled('deadline_from')) {
            $query->whereDate('deadline', '>=', $request->deadline_from);
        }
        if ($request->filled('deadline_to')) {
            $query->whereDate('deadline', '<=', $request->deadline_to);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'applications_desc':
                $query->orderBy('applications_count', 'desc');
                break;
            case 'deadline_asc':
                $query->orderBy('deadline', 'asc');
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

        $jobs = $query->paginate(15)->withQueryString();

        // Get filter options
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $categories = JobPosting::whereNotNull('category')
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();
        $locations = JobPosting::whereNotNull('location')
            ->distinct('location')
            ->pluck('location')
            ->filter()
            ->values()
            ->toArray();

        $jobTypes = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'internship' => 'Internship',
            'remote' => 'Remote',
            'freelance' => 'Freelance',
        ];

        $experienceLevels = [
            'entry' => 'Entry Level',
            'mid' => 'Mid Level',
            'senior' => 'Senior Level',
            'lead' => 'Lead / Manager',
            'executive' => 'Executive',
        ];

        // Statistics
        $stats = [
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')
                ->whereDate('deadline', '>=', now())
                ->count(),
            'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
            'expired_jobs' => JobPosting::whereDate('deadline', '<', now())->count(),
            'featured_jobs' => JobPosting::where('is_featured', true)->count(),
        ];

        return view('admin.jobs.index', compact(
            'jobs',
            'companies',
            'categories',
            'locations',
            'jobTypes',
            'experienceLevels',
            'stats',
            'request'
        ));
    }

    /**
     * Display pending jobs for approval.
     */
    public function pending(Request $request): View
    {
        $query = JobPosting::where('verification_status', 'pending')
            ->with(['company' => function ($q) {
                $q->select('id', 'name', 'slug');
            }])
            ->withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.jobs.pending', compact('jobs'));
    }

    /**
     * Display featured jobs.
     */
    public function featured(Request $request): View
    {
        $query = JobPosting::where('is_featured', true)
            ->with(['company' => function ($q) {
                $q->select('id', 'name', 'slug');
            }])
            ->withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.jobs.featured', compact('jobs'));
    }

    /**
     * Display job details.
     */
    public function show(JobPosting $job): View
    {
        $job->load([
            'company',
            'applications' => function ($q) {
                $q->with('applicant')
                  ->latest()
                  ->limit(10);
            }
        ]);

        // Get statistics
        $stats = [
            'total_applications' => $job->applications()->count(),
            'viewed_applications' => $job->applications()->where('status', 'viewed')->count(),
            'shortlisted' => $job->applications()->where('status', 'shortlisted')->count(),
            'rejected' => $job->applications()->where('status', 'rejected')->count(),
            'hired' => $job->applications()->where('status', 'hired')->count(),
        ];

        // Get similar jobs
        $similarJobs = JobPosting::where('company_id', $job->company_id)
            ->where('id', '!=', $job->id)
            ->where('status', 'active')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.jobs.show', compact('job', 'stats', 'similarJobs'));
    }

    /**
     * Approve job posting.
     */
    public function approve(JobPosting $job): RedirectResponse
    {
        if ($job->verification_status === 'verified') {
            return back()->with('info', 'Job is already verified.');
        }

        DB::beginTransaction();

        try {
            $job->update([
                'verification_status' => 'verified',
                'status' => 'active'
            ]);

            $this->logAdminAction(
                'job_approved',
                "Approved job: {$job->title} (ID: {$job->id})",
                $job
            );

            // Send notification to company
            // $job->company->owner->notify(new JobApprovedNotification($job));

            DB::commit();

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Job approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve job. Please try again.');
        }
    }

    /**
     * Reject job posting.
     */
    public function reject(Request $request, JobPosting $job): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $job->update([
                'verification_status' => 'rejected',
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            $this->logAdminAction(
                'job_rejected',
                "Rejected job: {$job->title} (ID: {$job->id})",
                $job
            );

            // Send notification to company
            // $job->company->owner->notify(new JobRejectedNotification($job, $request->rejection_reason));

            DB::commit();

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Job rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject job. Please try again.');
        }
    }

    /**
     * Feature/unfeature job.
     */
    public function feature(JobPosting $job): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $newStatus = !$job->is_featured;
            $job->update(['is_featured' => $newStatus]);

            $this->logAdminAction(
                $newStatus ? 'job_featured' : 'job_unfeatured',
                ($newStatus ? 'Featured' : 'Unfeatured') . " job: {$job->title} (ID: {$job->id})",
                $job
            );

            DB::commit();

            $message = $newStatus ? 'Job featured successfully.' : 'Job unfeatured successfully.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update job feature status.');
        }
    }

    /**
     * Toggle job status (active/closed).
     */
    public function toggleStatus(JobPosting $job): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $newStatus = $job->status === 'active' ? 'closed' : 'active';
            $job->update(['status' => $newStatus]);

            $this->logAdminAction(
                'job_status_toggled',
                "Changed job status to {$newStatus}: {$job->title} (ID: {$job->id})",
                $job
            );

            DB::commit();

            return back()->with('success', "Job status changed to {$newStatus}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update job status.');
        }
    }

    /**
     * Delete job.
     */
    public function destroy(JobPosting $job): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $jobTitle = $job->title;
            $jobId = $job->id;

            // Delete related applications first
            $job->applications()->delete();
            $job->delete();

            $this->logAdminAction(
                'job_deleted',
                "Deleted job: {$jobTitle} (ID: {$jobId})"
            );

            DB::commit();

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Job deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete job. Please try again.');
        }
    }

    /**
     * Bulk action on jobs.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,reject,feature,unfeature,activate,close,delete',
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:job_postings,id'
        ]);

        $action = $request->action;
        $jobIds = $request->job_ids;

        DB::beginTransaction();

        try {
            $jobs = JobPosting::whereIn('id', $jobIds)->get();

            foreach ($jobs as $job) {
                switch ($action) {
                    case 'approve':
                        $job->update([
                            'verification_status' => 'verified',
                            'status' => 'active'
                        ]);
                        break;
                    case 'reject':
                        $job->update([
                            'verification_status' => 'rejected',
                            'status' => 'rejected'
                        ]);
                        break;
                    case 'feature':
                        $job->update(['is_featured' => true]);
                        break;
                    case 'unfeature':
                        $job->update(['is_featured' => false]);
                        break;
                    case 'activate':
                        $job->update(['status' => 'active']);
                        break;
                    case 'close':
                        $job->update(['status' => 'closed']);
                        break;
                    case 'delete':
                        $job->applications()->delete();
                        $job->delete();
                        break;
                }
            }

            $this->logAdminAction(
                "bulk_jobs_{$action}",
                "Bulk {$action} on " . count($jobIds) . " jobs"
            );

            DB::commit();

            $message = count($jobIds) . ' jobs processed successfully.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process bulk action. Please try again.');
        }
    }

    /**
     * Export jobs data.
     */
    public function export(Request $request)
    {
        $query = JobPosting::with(['company' => function ($q) {
            $q->select('id', 'name');
        }])->withCount('applications');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $jobs = $query->get();

        // Generate CSV
        $filename = 'jobs_export_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Add UTF-8 BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($handle, [
            'ID',
            'Job Title',
            'Company',
            'Category',
            'Job Type',
            'Location',
            'Experience Level',
            'Salary Range',
            'Status',
            'Verification Status',
            'Featured',
            'Applications',
            'Posted Date',
            'Deadline',
            'Created At'
        ]);

        // Add data
        foreach ($jobs as $job) {
            fputcsv($handle, [
                $job->id,
                $job->title,
                $job->company->name ?? 'N/A',
                $job->category ?? 'N/A',
                $job->job_type ?? 'N/A',
                $job->location ?? 'N/A',
                $job->experience_level ?? 'N/A',
                $job->salary_range ?? 'N/A',
                $job->status,
                $job->verification_status,
                $job->is_featured ? 'Yes' : 'No',
                $job->applications_count ?? 0,
                $job->created_at->format('Y-m-d'),
                $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : 'N/A',
                $job->created_at->format('Y-m-d H:i:s')
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
     * Get job insights.
     */
    public function insights(JobPosting $job): View
    {
        $job->load(['company', 'applications.applicant']);

        // Application trends
        $applicationTrends = $job->applications()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(1))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Application status breakdown
        $statusBreakdown = $job->applications()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.jobs.insights', compact('job', 'applicationTrends', 'statusBreakdown'));
    }
}