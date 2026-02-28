<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobSeekerDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Suggested jobs (basic matching - improve later)
       $suggestedJobs = JobPosting::where('status', 'active')
    ->where('verification_status', 'verified')
    ->latest()
    ->take(6)
    ->get(); // Later: real skill/location match

        // Recent applications
        $recentApplications = $user->jobApplications()
            ->with('job.company')
            ->latest()
            ->take(5)
            ->get();

        // Saved jobs (assuming you have savedJobs relationship)
        // $savedJobs = $user->savedJobs()->latest()->take(5)->get();

        return view('dashboard.jobseeker.index', compact(
            'suggestedJobs',
            'recentApplications'
        ));
    }
}