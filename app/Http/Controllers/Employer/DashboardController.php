<?php
// app/Http/Controllers/Employer/DashboardController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show employer dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get all companies user manages
        $companies = $user->accessibleCompanies()->with([
            'jobPostings' => function ($query) {
                $query->withCount('applications')->latest();
            }
        ])->get();
        
        // Get recent applications across all companies
        $recentApplications = collect();
        foreach ($companies as $company) {
            foreach ($company->jobPostings as $job) {
                $job->applications->each(function ($application) use ($job, $company, &$recentApplications) {
                    $application->job_title = $job->title;
                    $application->company_name = $company->name;
                    $application->company_id = $company->id;
                });
                $recentApplications = $recentApplications->concat($job->applications);
            }
        }
        
        $recentApplications = $recentApplications->sortByDesc('created_at')->take(10);
        
        // Statistics
        $stats = [
            'total_jobs' => $companies->sum(fn($c) => $c->jobPostings->count()),
            'active_jobs' => $companies->sum(fn($c) => $c->jobPostings->where('status', 'active')->count()),
            'total_applications' => $companies->sum(fn($c) => $c->jobPostings->sum(fn($j) => $j->applications_count)),
            'pending_applications' => $companies->sum(fn($c) => $c->jobPostings->sum(fn($j) => $j->applications()->where('status', 'applied')->count())),
        ];
        
        return view('employer.dashboard', compact('companies', 'recentApplications', 'stats'));
    }
}