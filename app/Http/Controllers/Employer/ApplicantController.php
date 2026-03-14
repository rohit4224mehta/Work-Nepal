<?php
// app/Http/Controllers/Employer/ApplicantController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicantController extends Controller
{
    /**
     * Display a listing of all applicants across companies.
     */
    public function index()
{
    $user = auth()->user();
    $companies = $user->accessibleCompanies()->pluck('id');
    
    $applications = JobApplication::whereHas('jobPosting', function ($query) use ($companies) {
            $query->whereIn('company_id', $companies);
        })
        ->with(['applicant', 'jobPosting.company'])
        ->latest()
        ->paginate(15);
    
    return view('employer.applicants.index', compact('applications'));
}

public function show(JobApplication $application)
{
    if (!auth()->user()->canManageCompany($application->jobPosting->company)) {
        abort(403);
    }
    
    $application->load(['applicant', 'jobPosting.company']);
    
    return view('employer.applicants.show', compact('application'));
}

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        // Check permission
        if (!auth()->user()->canManageCompany($application->jobPosting->company)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:applied,viewed,shortlisted,rejected,hired',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $request->status,
            'employer_feedback' => $request->feedback,
            'status_updated_at' => now(),
        ]);

        // Send notification to applicant
        $application->applicant->notify(new ApplicationStatusUpdated($application));

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully.'
        ]);
    }
}