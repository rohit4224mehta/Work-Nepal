<?php
// app/Http/Controllers/JobSeeker/SavedJobController.php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    /**
     * Display saved jobs.
     */
    public function index(): View
    {
        $user = auth()->user();
        $savedJobs = $user->savedJobs()
            ->with(['company'])
            ->paginate(12);

        // ✅ FIXED: Use the correct view path
        // Your file is at resources/views/dashboard/jobseeker/saved-jobs.blade.php
        return view('dashboard.jobseeker.saved-jobs', compact('savedJobs'));
    }

    /**
     * Save a job.
     */
    public function save(Request $request, $jobId)
    {
        try {
            $user = auth()->user();
            $job = JobPosting::findOrFail($jobId);
            
            if ($user->savedJobs()->where('job_posting_id', $jobId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job already saved'
                ]);
            }

            $user->savedJobs()->attach($jobId);

            return response()->json([
                'success' => true,
                'message' => 'Job saved successfully',
                'saved_count' => $user->savedJobs()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save job'
            ], 500);
        }
    }

    /**
     * Unsave a job.
     */
    public function unsave(Request $request, $jobId)
    {
        try {
            $user = auth()->user();
            $user->savedJobs()->detach($jobId);

            return response()->json([
                'success' => true,
                'message' => 'Job removed from saved',
                'saved_count' => $user->savedJobs()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove job'
            ], 500);
        }
    }
}