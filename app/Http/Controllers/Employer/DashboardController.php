<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display employer dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get user's company (single company model)
        $company = Company::where('owner_id', $user->id)
            ->with(['jobPostings' => function($query) {
                $query->withCount('applications');
            }])
            ->first();
        
        // Calculate dashboard statistics
        $stats = $this->calculateStats($company);
        
        // Get recent jobs (last 5)
        $recentJobs = collect([]);
        if ($company) {
            $recentJobs = $company->jobPostings()
                ->withCount('applications')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        // Get recent applications (last 5)
        $recentApplications = $this->getRecentApplications($company);
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'has_company' => !is_null($company),
                'recent_jobs_count' => $recentJobs->count(),
                'recent_applications_count' => $recentApplications->count(),
            ]);
        }
        
        return view('employer.dashboard', compact('company', 'stats', 'recentJobs', 'recentApplications'));
    }
    
    /**
     * Calculate dashboard statistics
     */
    protected function calculateStats($company)
    {
        $stats = [
            'total_jobs' => 0,
            'active_jobs' => 0,
            'total_applications' => 0,
            'pending_applications' => 0,
            'job_trend' => 0,
        ];
        
        if ($company) {
            $stats['total_jobs'] = $company->jobPostings->count();
            $stats['active_jobs'] = $company->jobPostings->where('status', 'active')->count();
            $stats['total_applications'] = $company->jobPostings->sum('applications_count');
            
            // Count pending applications
            foreach ($company->jobPostings as $job) {
                $stats['pending_applications'] += $job->applications()
                    ->where('status', 'applied')
                    ->count();
            }
            
            // Calculate job trend (compare with last month)
            $stats['job_trend'] = $this->calculateJobTrend($company);
        }
        
        return $stats;
    }
    
    /**
     * Calculate job posting trend
     */
    protected function calculateJobTrend($company)
    {
        $now = now();
        $lastMonth = $now->copy()->subMonth();
        
        $currentMonthJobs = $company->jobPostings()
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();
            
        $lastMonthJobs = $company->jobPostings()
            ->whereBetween('created_at', [$lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth()])
            ->count();
        
        if ($lastMonthJobs > 0) {
            return round((($currentMonthJobs - $lastMonthJobs) / $lastMonthJobs) * 100, 1);
        }
        
        return $currentMonthJobs > 0 ? 100 : 0;
    }
    
    /**
     * Get recent applications
     */
    protected function getRecentApplications($company)
    {
        if (!$company) {
            return collect([]);
        }
        
        $jobIds = $company->jobPostings->pluck('id')->toArray();
        
        if (empty($jobIds)) {
            return collect([]);
        }
        
        return JobApplication::with(['user', 'jobPosting'])
            ->whereIn('job_posting_id', $jobIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($application) {
                return (object) [
                    'id' => $application->id,
                    'applicant' => $application->user,
                    'job_title' => $application->jobPosting->title,
                    'status' => $application->status,
                    'created_at' => $application->created_at,
                ];
            });
    }
}