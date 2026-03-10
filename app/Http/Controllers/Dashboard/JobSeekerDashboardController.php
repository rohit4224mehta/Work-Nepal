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

    public function profileCompletionPercentage(): int
{
    $points = 0;
    $total = 5; // adjust as needed

    if ($this->profile_photo_path) $points++;
    if ($this->resume_path ?? false) $points++;

    // Safe check: only load if relationship exists
    if (method_exists($this, 'skills') && $this->skills?->count() > 0) $points++;
    if (method_exists($this, 'experience') && $this->experience?->count() > 0) $points++;
    if (method_exists($this, 'education') && $this->education?->count() > 0) $points++;

    return (int) round(($points / $total) * 100);
}
}