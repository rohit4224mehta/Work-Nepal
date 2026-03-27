<?php
// app/Http/Controllers/Employer/ApplicantController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicantController extends Controller
{
    /**
     * Display a listing of applications.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get all companies the user has access to
        $companies = $user->accessibleCompanies()->pluck('id');

        $query = JobApplication::whereHas('jobPosting', function ($query) use ($companies) {
            $query->whereIn('company_id', $companies);
        })
        ->with(['applicant', 'jobPosting.company'])
        ->latest();

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply company filter
        if ($request->filled('company')) {
            $query->whereHas('jobPosting', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(15);

        return view('employer.applicants.index', compact('applications'));
    }

    /**
     * Display the specified application.
     */
    public function show(JobApplication $application)
    {
        // ✅ FIXED: Use canAccessCompany instead of canManageCompany
        if (!auth()->user()->canAccessCompany($application->jobPosting->company)) {
            abort(403, 'You do not have permission to view this application.');
        }

        $application->load(['applicant', 'jobPosting.company']);

        return view('employer.applicants.show', compact('application'));
    }

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        // ✅ FIXED: Use canAccessCompany instead of canManageCompany
        if (!auth()->user()->canAccessCompany($application->jobPosting->company)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this application.'
                ], 403);
            }
            abort(403, 'You do not have permission to update this application.');
        }

        $request->validate([
            'status' => 'required|in:applied,viewed,shortlisted,rejected,hired'
        ]);

        try {
            $oldStatus = $application->status;
            $application->update([
                'status' => $request->status,
                'status_updated_at' => now(),
            ]);

            // Log the activity
            Log::info('Application status updated', [
                'application_id' => $application->id,
                'job_id' => $application->job_posting_id,
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $request->status,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Application status updated successfully.',
                    'status' => $request->status
                ]);
            }

            return redirect()
                ->route('employer.applicants.index')
                ->with('success', 'Application status updated successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to update application status: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Add feedback/notes to application.
     */
    public function addFeedback(Request $request, JobApplication $application)
    {
        // ✅ FIXED: Use canAccessCompany instead of canManageCompany
        if (!auth()->user()->canAccessCompany($application->jobPosting->company)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to add feedback.'
                ], 403);
            }
            abort(403, 'You do not have permission to add feedback.');
        }

        $request->validate([
            'feedback' => 'nullable|string|max:1000'
        ]);

        try {
            $application->update([
                'employer_feedback' => $request->feedback,
            ]);

            Log::info('Application feedback added', [
                'application_id' => $application->id,
                'user_id' => auth()->id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notes saved successfully.'
                ]);
            }

            return back()->with('success', 'Notes saved successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to save feedback: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save notes. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to save notes. Please try again.');
        }
    }

    /**
     * Download resume for an application.
     */
    public function downloadResume(JobApplication $application)
    {
        // ✅ FIXED: Use canAccessCompany instead of canManageCompany
        if (!auth()->user()->canAccessCompany($application->jobPosting->company)) {
            abort(403, 'You do not have permission to download this resume.');
        }

        $applicant = $application->applicant;
        
        if (!$applicant->resume_path) {
            return back()->with('error', 'No resume found for this applicant.');
        }

        $resumePath = storage_path('app/public/' . $applicant->resume_path);
        
        if (!file_exists($resumePath)) {
            return back()->with('error', 'Resume file not found.');
        }

        return response()->download($resumePath, $applicant->name . '_resume.pdf');
    }

    /**
     * Bulk update application statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:applied,viewed,shortlisted,rejected,hired'
        ]);

        $user = auth()->user();
        $updatedCount = 0;
        $failedCount = 0;

        foreach ($request->application_ids as $applicationId) {
            $application = JobApplication::find($applicationId);
            
            if (!$application) {
                $failedCount++;
                continue;
            }

            // Check permission for each application's company
            if (!$user->canAccessCompany($application->jobPosting->company)) {
                $failedCount++;
                continue;
            }

            try {
                $application->update([
                    'status' => $request->status,
                    'status_updated_at' => now(),
                ]);
                $updatedCount++;
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Bulk update failed for application: ' . $applicationId);
            }
        }

        $message = "Updated {$updatedCount} applications.";
        if ($failedCount > 0) {
            $message .= " {$failedCount} applications could not be updated.";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $updatedCount,
                'failed' => $failedCount
            ]);
        }

        return redirect()
            ->route('employer.applicants.index')
            ->with('success', $message);
    }

    /**
     * Export applications to CSV.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        
        // Get companies the user has access to
        $companies = $user->accessibleCompanies()->pluck('id');

        $applications = JobApplication::whereHas('jobPosting', function ($query) use ($companies) {
            $query->whereIn('company_id', $companies);
        })
        ->with(['applicant', 'jobPosting.company'])
        ->latest()
        ->get();

        $filename = 'applications_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // Add headers
        fputcsv($handle, [
            'Application ID', 'Applicant Name', 'Applicant Email', 
            'Job Title', 'Company', 'Status', 'Applied Date', 
            'Status Updated', 'Feedback'
        ]);

        // Add data
        foreach ($applications as $application) {
            fputcsv($handle, [
                $application->id,
                $application->applicant->name,
                $application->applicant->email,
                $application->jobPosting->title,
                $application->jobPosting->company->name,
                $application->status,
                $application->created_at->format('Y-m-d H:i:s'),
                $application->status_updated_at ? $application->status_updated_at->format('Y-m-d H:i:s') : '',
                $application->employer_feedback,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->withHeaders([
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
    }
}