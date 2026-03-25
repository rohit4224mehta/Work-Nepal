<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Company;
use App\Models\JobPosting;

class DashboardController extends Controller
{
    /**
     * Redirect users to their appropriate dashboard based on roles.
     */
    public function index()
    {
        $user = Auth::user();

        // Check for impersonation first
        if (session()->has('impersonate')) {
            $impersonatedUser = User::find(session('impersonate'));
            if ($impersonatedUser) {
                return $this->redirectToRoleDashboard($impersonatedUser);
            }
        }

        return $this->redirectToRoleDashboard($user);
    }

    /**
     * Redirect user based on their role
     */
    private function redirectToRoleDashboard($user)
    {
        // Check roles in priority order
        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Welcome to Super Admin Dashboard');
        }
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Welcome to Admin Dashboard');
        }

        if ($user->hasRole('employer')) {
            // Check if employer has companies
            $hasCompany = $user->ownedCompanies()->exists();
            
            if (!$hasCompany) {
                return redirect()->route('employer.company.create')
                    ->with('info', 'Create your company profile to start posting jobs');
            }
            
            return redirect()->route('employer.dashboard')
                ->with('success', 'Welcome back to your Employer Dashboard');
        }

        if ($user->hasRole('job_seeker')) {
            return redirect()->route('dashboard.jobseeker')
                ->with('success', 'Welcome back to your Job Seeker Dashboard');
        }

        // Fallback - if user has no role, assign job_seeker role
        if ($user->roles->isEmpty()) {
            $user->assignRole('job_seeker');
            return redirect()->route('dashboard.jobseeker')
                ->with('info', 'Your profile has been set up as Job Seeker');
        }

        return redirect()->route('home')
            ->with('error', 'Unable to determine dashboard. Please contact support.');
    }

    /**
     * Get dashboard statistics based on user role
     */
    public function getStats()
    {
        $user = Auth::user();
        
        if ($user->hasRole(['admin', 'super_admin'])) {
            $stats = $this->getAdminStats();
        } elseif ($user->hasRole('employer')) {
            $stats = $this->getEmployerStats($user);
        } else {
            $stats = $this->getJobSeekerStats($user);
        }
        
        return response()->json([
            'success' => true,
            'role' => $this->getPrimaryRole($user),
            'stats' => $stats,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats()
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('account_status', 'active')->count(),
                'total_employers' => User::role('employer')->count(),
                'total_job_seekers' => User::role('job_seeker')->count(),
                'total_companies' => Company::count(),
                'pending_companies' => Company::where('verification_status', 'pending')->count(),
                'verified_companies' => Company::where('verification_status', 'verified')->count(),
                'total_jobs' => JobPosting::count(),
                'active_jobs' => JobPosting::where('status', 'active')
                    ->whereDate('deadline', '>=', now())
                    ->count(),
                'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
                'applications_today' => \App\Models\JobApplication::whereDate('created_at', today())->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
            ];
        });
    }

    /**
     * Get employer dashboard statistics
     */
    private function getEmployerStats($user)
    {
        $companies = $user->ownedCompanies()->withCount('jobPostings')->get();
        
        $stats = [
            'total_companies' => $companies->count(),
            'total_jobs' => $companies->sum('job_postings_count'),
            'active_jobs' => 0,
            'total_applications' => 0,
            'new_applications' => 0,
            'profile_views' => $user->profile_views ?? 0,
        ];
        
        foreach ($companies as $company) {
            $activeJobs = $company->jobPostings()
                ->where('status', 'active')
                ->whereDate('deadline', '>=', now())
                ->count();
            $stats['active_jobs'] += $activeJobs;
            
            $totalApps = $company->jobPostings()
                ->withCount('applications')
                ->get()
                ->sum('applications_count');
            $stats['total_applications'] += $totalApps;
            
            $newApps = $company->jobPostings()
                ->whereHas('applications', function ($q) {
                    $q->whereDate('created_at', today());
                })
                ->count();
            $stats['new_applications'] += $newApps;
        }
        
        return $stats;
    }

    /**
     * Get job seeker dashboard statistics
     */
    private function getJobSeekerStats($user)
    {
        return [
            'total_applications' => $user->jobApplications()->count(),
            'pending_applications' => $user->jobApplications()
                ->whereIn('status', ['applied', 'viewed'])
                ->count(),
            'shortlisted' => $user->jobApplications()
                ->where('status', 'shortlisted')
                ->count(),
            'interviews' => $user->jobApplications()
                ->where('status', 'interview')
                ->count(),
            'rejected' => $user->jobApplications()
                ->where('status', 'rejected')
                ->count(),
            'hired' => $user->jobApplications()
                ->where('status', 'hired')
                ->count(),
            'saved_jobs' => $user->savedJobs()->count(),
            'profile_completion' => $user->profileCompletionPercentage(),
        ];
    }

    /**
     * Get user's primary role
     */
    private function getPrimaryRole($user)
    {
        if ($user->hasRole('super_admin')) return 'super_admin';
        if ($user->hasRole('admin')) return 'admin';
        if ($user->hasRole('employer')) return 'employer';
        if ($user->hasRole('job_seeker')) return 'job_seeker';
        return 'guest';
    }

    /**
     * Get dashboard info for AJAX
     */
    public function getDashboardInfo()
    {
        $user = Auth::user();
        
        $dashboardInfo = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'primary_role' => $this->getPrimaryRole($user),
            'dashboard_url' => $this->getDashboardUrl($user),
            'has_multiple_roles' => $user->roles->count() > 1,
            'all_roles' => $user->roles->pluck('name')->toArray(),
        ];

        return response()->json($dashboardInfo);
    }

    /**
     * Get appropriate dashboard URL for user
     */
    private function getDashboardUrl($user)
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('employer')) {
            return route('employer.dashboard');
        }
        if ($user->hasRole('job_seeker')) {
            return route('dashboard.jobseeker');
        }
        return route('home');
    }

    /**
     * Switch between dashboards for users with multiple roles
     */
    public function switchDashboard(Request $request)
    {
        $user = Auth::user();
        $targetRole = $request->input('role');
        
        if (!$user->hasRole($targetRole)) {
            return redirect()->back()
                ->with('error', 'You do not have permission to access that dashboard.');
        }
        
        // Store the selected role in session for dashboard switching
        session(['active_role' => $targetRole]);
        
        return $this->redirectToRoleDashboard($user);
    }

    /**
     * Get available dashboards for users with multiple roles
     */
    public function getAvailableDashboards()
    {
        $user = Auth::user();
        $dashboards = [];
        
        if ($user->hasRole(['admin', 'super_admin'])) {
            $dashboards['admin'] = [
                'name' => 'Admin Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'shield'
            ];
        }
        
        if ($user->hasRole('employer')) {
            $dashboards['employer'] = [
                'name' => 'Employer Dashboard',
                'url' => route('employer.dashboard'),
                'icon' => 'building'
            ];
        }
        
        if ($user->hasRole('job_seeker')) {
            $dashboards['job_seeker'] = [
                'name' => 'Job Seeker Dashboard',
                'url' => route('dashboard.jobseeker'),
                'icon' => 'user'
            ];
        }
        
        return response()->json([
            'success' => true,
            'dashboards' => $dashboards,
            'active_role' => session('active_role', $this->getPrimaryRole($user))
        ]);
    }

    /**
     * Quick stats widget for dashboard
     */
    public function quickStats()
    {
        $user = Auth::user();
        
        if ($user->hasRole(['admin', 'super_admin'])) {
            $stats = [
                'pending_companies' => Company::where('verification_status', 'pending')->count(),
                'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
            ];
        } elseif ($user->hasRole('employer')) {
            $companies = $user->ownedCompanies()->pluck('id');
            $stats = [
                'pending_applications' => \App\Models\JobApplication::whereHas('jobPosting', function($q) use ($companies) {
                    $q->whereIn('company_id', $companies);
                })->where('status', 'applied')->count(),
                'active_jobs' => JobPosting::whereIn('company_id', $companies)
                    ->where('status', 'active')
                    ->whereDate('deadline', '>=', now())
                    ->count(),
            ];
        } else {
            $stats = [
                'pending_applications' => $user->jobApplications()
                    ->whereIn('status', ['applied', 'viewed'])
                    ->count(),
                'profile_completion' => $user->profileCompletionPercentage(),
            ];
        }
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}