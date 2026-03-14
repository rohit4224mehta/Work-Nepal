<?php
// app/Http/Controllers/Admin/ApplicationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationController extends AdminController
{
    /**
     * Display a listing of applications.
     */
    public function index(Request $request): View
    {
        $query = JobApplication::query()
            ->with([
                'applicant' => function ($q) {
                    $q->select('id', 'name', 'email', 'profile_photo_path');
                },
                'jobPosting' => function ($q) {
                    $q->select('id', 'title', 'company_id', 'job_type', 'location', 'slug');
                },
                'jobPosting.company' => function ($q) {
                    $q->select('id', 'name', 'slug');
                }
            ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('applicant', function ($uq) use ($search) {
                    $uq->where('name', 'LIKE', "%{$search}%")
                       ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('jobPosting', function ($jq) use ($search) {
                    $jq->where('title', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('jobPosting.company', function ($cq) use ($search) {
                    $cq->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->whereHas('jobPosting', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // Job filter
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            case 'status_asc':
                $query->orderBy('status', 'asc');
                break;
            case 'status_desc':
                $query->orderBy('status', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $applications = $query->paginate(20)->withQueryString();

        // Get filter options
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $jobs = JobPosting::orderBy('title')->get(['id', 'title', 'company_id']);

        // Status statistics
        $stats = [
            'total' => JobApplication::count(),
            'applied' => JobApplication::where('status', 'applied')->count(),
            'viewed' => JobApplication::where('status', 'viewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
            'today' => JobApplication::whereDate('created_at', today())->count(),
            'this_week' => JobApplication::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => JobApplication::whereMonth('created_at', now()->month)->count(),
        ];

        // Application trends for chart
        $trends = $this->getApplicationTrends();

        return view('admin.applications.index', compact(
            'applications',
            'companies',
            'jobs',
            'stats',
            'trends',
            'request'
        ));
    }

    /**
     * Display application details.
     */
    public function show(JobApplication $application): View
    {
        $application->load([
            'applicant' => function ($q) {
                $q->with(['skills', 'education', 'experience']);
            },
            'jobPosting' => function ($q) {
                $q->with(['company', 'postedBy']);
            }
        ]);

        // Get similar applications from same applicant
        $otherApplications = JobApplication::where('user_id', $application->user_id)
            ->where('id', '!=', $application->id)
            ->with(['jobPosting.company'])
            ->latest()
            ->limit(5)
            ->get();

        // Get other applicants for same job
        $otherApplicants = JobApplication::where('job_posting_id', $application->job_posting_id)
            ->where('id', '!=', $application->id)
            ->with(['applicant'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.applications.show', compact('application', 'otherApplications', 'otherApplicants'));
    }

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:applied,viewed,shortlisted,rejected,hired',
            'feedback' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $application->status;
            
            $application->update([
                'status' => $request->status,
                'employer_feedback' => $request->feedback,
                'status_updated_at' => now(),
            ]);

            // Log the action
            $this->logAdminAction(
                'application_status_updated',
                "Changed application #{$application->id} status from {$oldStatus} to {$request->status}",
                $application
            );

            // Send notification to applicant
            // $application->applicant->notify(new ApplicationStatusUpdatedNotification($application));

            DB::commit();

            return back()->with('success', 'Application status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update application status.');
        }
    }

    /**
     * Bulk update application status.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:viewed,shortlisted,rejected,hired',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id'
        ]);

        $action = $request->action;
        $applicationIds = $request->application_ids;

        DB::beginTransaction();

        try {
            $applications = JobApplication::whereIn('id', $applicationIds)->get();
            
            foreach ($applications as $application) {
                $application->update([
                    'status' => $action,
                    'status_updated_at' => now(),
                ]);
            }

            $this->logAdminAction(
                'bulk_applications_update',
                "Updated " . count($applicationIds) . " applications to status: {$action}"
            );

            DB::commit();

            return back()->with('success', count($applicationIds) . ' applications updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update applications.');
        }
    }

    /**
     * Delete application.
     */
    public function destroy(JobApplication $application): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $applicationId = $application->id;
            $application->delete();

            $this->logAdminAction(
                'application_deleted',
                "Deleted application #{$applicationId}"
            );

            DB::commit();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete application.');
        }
    }

    /**
     * Bulk delete applications.
     */
    // public function bulkDelete(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'application_ids' => 'required|array',
    //         'application_ids.*' => 'exists:job_applications,id'
    //     ]);

    //     $applicationIds = $request->application_ids;

    //     DB::beginTransaction();

    //     try {
    //         JobApplication::whereIn('id', $applicationIds)->delete();

    //         $this->logAdminAction(
    //             'bulk_applications_delete',
    //             "Deleted " . count($applicationIds) . " applications"
    //         );

    //         DB::commit();

    //         return back()->with('success', count($applicationIds) . ' applications deleted successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to delete applications.');
    //     }
    // }

    /**
     * Export applications data.
     */
    public function export(Request $request)
    {
        $query = JobApplication::with([
            'applicant',
            'jobPosting.company'
        ]);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('company_id')) {
            $query->whereHas('jobPosting', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->get();

        // Generate CSV
        $filename = 'applications_export_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Add UTF-8 BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($handle, [
            'Application ID',
            'Applicant Name',
            'Applicant Email',
            'Job Title',
            'Company',
            'Status',
            'Applied Date',
            'Last Updated',
            'Feedback'
        ]);

        // Add data
        foreach ($applications as $application) {
            fputcsv($handle, [
                $application->id,
                $application->applicant->name ?? 'N/A',
                $application->applicant->email ?? 'N/A',
                $application->jobPosting->title ?? 'N/A',
                $application->jobPosting->company->name ?? 'N/A',
                $application->status,
                $application->created_at->format('Y-m-d H:i:s'),
                $application->updated_at->format('Y-m-d H:i:s'),
                $application->employer_feedback ?? ''
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
     * Get application trends for charts.
     */
    private function getApplicationTrends(): array
    {
        $trends = [];
        
        // Last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trends['labels'][] = now()->subDays($i)->format('M d');
            $trends['applications'][] = JobApplication::whereDate('created_at', $date)->count();
        }

        // Status breakdown
        $trends['status_breakdown'] = [
            'applied' => JobApplication::where('status', 'applied')->count(),
            'viewed' => JobApplication::where('status', 'viewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        // Applications by job (top 10)
        $trends['top_jobs'] = JobApplication::select('job_posting_id', DB::raw('count(*) as total'))
            ->with('jobPosting:id,title')
            ->groupBy('job_posting_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->jobPosting->title ?? 'Unknown',
                    'count' => $item->total
                ];
            });

        return $trends;
    }

    /**
     * Get application statistics for dashboard.
     */
    public function getStats(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'total' => JobApplication::count(),
            'applied' => JobApplication::where('status', 'applied')->count(),
            'viewed' => JobApplication::where('status', 'viewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
            'today' => JobApplication::whereDate('created_at', today())->count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate conversion rate from applied to hired.
     */
    private function calculateConversionRate(): float
    {
        $total = JobApplication::count();
        if ($total == 0) return 0;
        
        $hired = JobApplication::where('status', 'hired')->count();
        return round(($hired / $total) * 100, 2);
    }

    /**
     * Get applications for a specific job.
     */
    public function jobApplications(JobPosting $job): View
    {
        $applications = JobApplication::where('job_posting_id', $job->id)
            ->with(['applicant'])
            ->latest()
            ->paginate(20);

        return view('admin.applications.job', compact('job', 'applications'));
    }

    /**
     * Get applications for a specific applicant.
     */
    public function userApplications(User $user): View
    {
        $applications = JobApplication::where('user_id', $user->id)
            ->with(['jobPosting.company'])
            ->latest()
            ->paginate(20);

        return view('admin.applications.user', compact('user', 'applications'));
    }

    /**
     * Add feedback to application.
     */
    public function addFeedback(Request $request, JobApplication $application): RedirectResponse
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $application->update([
            'employer_feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Feedback added successfully.');
    }
}