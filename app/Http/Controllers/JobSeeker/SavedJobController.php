<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $savedJobs = $user->savedJobs()
            ->with('company')
            ->latest()
            ->paginate(10);

        return view('dashboard.jobseeker.saved-jobs', compact('savedJobs'));
    }
}