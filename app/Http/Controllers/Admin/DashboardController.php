<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends AdminController
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', 'today');
        $dates = $this->getDateRange($period);
        
        // Get real-time stats with caching for performance
        $stats = Cache::remember('admin_dashboard_stats_' . $period, 300, function () use ($dates) {
            return $this->getDashboardStats($dates);
        });

        // Get chart data
        $charts = $this->getChartData($period);

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get pending items count
        $pendingCounts = $this->getPendingCounts();

        // Get system health status
        $systemHealth = $this->getSystemHealth();

        return view('admin.dashboard', compact(
            'stats',
            'charts',
            'recentActivities',
            'pendingCounts',
            'systemHealth',
            'period'
        ));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats($dates): array
    {
        $now = Carbon::now();
        
        // Current period stats
        $currentStats = [
            'users' => User::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'employers' => User::role('employer')->whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'job_seekers' => User::role('job_seeker')->whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'companies' => Company::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'verified_companies' => Company::where('verification_status', 'verified')
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->count(),
            'jobs' => JobPosting::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'active_jobs' => JobPosting::where('status', 'active')
                ->whereDate('deadline', '>=', $now)
                ->count(),
            'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
            'applications' => JobApplication::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'hired' => JobApplication::where('status', 'hired')
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->count(),
        ];

        // Previous period stats for comparison
        $previousStats = [
            'users' => User::whereBetween('created_at', [$dates['prev_start'], $dates['prev_end']])->count(),
            'companies' => Company::whereBetween('created_at', [$dates['prev_start'], $dates['prev_end']])->count(),
            'jobs' => JobPosting::whereBetween('created_at', [$dates['prev_start'], $dates['prev_end']])->count(),
            'applications' => JobApplication::whereBetween('created_at', [$dates['prev_start'], $dates['prev_end']])->count(),
        ];

        // Calculate growth percentages
        return [
            'current' => $currentStats,
            'growth' => [
                'users' => $this->calculateGrowth($previousStats['users'], $currentStats['users']),
                'companies' => $this->calculateGrowth($previousStats['companies'], $currentStats['companies']),
                'jobs' => $this->calculateGrowth($previousStats['jobs'], $currentStats['jobs']),
                'applications' => $this->calculateGrowth($previousStats['applications'], $currentStats['applications']),
            ],
            'totals' => [
                'users' => User::count(),
                'employers' => User::role('employer')->count(),
                'job_seekers' => User::role('job_seeker')->count(),
                'companies' => Company::count(),
                'verified_companies' => Company::where('verification_status', 'verified')->count(),
                'jobs' => JobPosting::count(),
                'applications' => JobApplication::count(),
            ],
        ];
    }

    /**
     * Get chart data.
     */
    private function getChartData($period): array
    {
        $days = match($period) {
            'today' => 24,
            'week' => 7,
            'month' => 30,
            'year' => 12,
            default => 7,
        };

        $data = [];

        if ($period === 'year') {
            // Monthly data for yearly view
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();

                $data['labels'][] = $month->format('M Y');
                $data['jobs'][] = JobPosting::whereBetween('created_at', [$start, $end])->count();
                $data['applications'][] = JobApplication::whereBetween('created_at', [$start, $end])->count();
                $data['users'][] = User::whereBetween('created_at', [$start, $end])->count();
                $data['companies'][] = Company::whereBetween('created_at', [$start, $end])->count();
            }
        } else {
            // Daily/hourly data for shorter periods
            $interval = $period === 'today' ? 'hour' : 'day';
            $format = $period === 'today' ? 'H:00' : 'M d';
            $points = $period === 'today' ? 24 : $days;

            for ($i = $points - 1; $i >= 0; $i--) {
                $date = $period === 'today' 
                    ? Carbon::now()->subHours($i)
                    : Carbon::now()->subDays($i);

                $start = $period === 'today' 
                    ? $date->copy()->startOfHour()
                    : $date->copy()->startOfDay();
                $end = $period === 'today'
                    ? $date->copy()->endOfHour()
                    : $date->copy()->endOfDay();

                $data['labels'][] = $date->format($format);
                $data['jobs'][] = JobPosting::whereBetween('created_at', [$start, $end])->count();
                $data['applications'][] = JobApplication::whereBetween('created_at', [$start, $end])->count();
                $data['users'][] = User::whereBetween('created_at', [$start, $end])->count();
            }
        }

        return $data;
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities(): array
    {
        // You can implement activity log here
        // For now, we'll fetch recent records from various tables
        $activities = [];

        // Recent users
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'action' => 'new_registration',
                'subject' => $user->name,
                'time' => $user->created_at->diffForHumans(),
                'icon' => 'user',
                'color' => 'blue',
            ];
        }

        // Recent companies
        $recentCompanies = Company::latest()->take(2)->get();
        foreach ($recentCompanies as $company) {
            $activities[] = [
                'type' => 'company',
                'action' => 'new_company',
                'subject' => $company->name,
                'time' => $company->created_at->diffForHumans(),
                'icon' => 'building',
                'color' => 'green',
            ];
        }

        // Recent jobs
        $recentJobs = JobPosting::latest()->take(2)->get();
        foreach ($recentJobs as $job) {
            $activities[] = [
                'type' => 'job',
                'action' => 'new_job',
                'subject' => $job->title,
                'time' => $job->created_at->diffForHumans(),
                'icon' => 'briefcase',
                'color' => 'purple',
            ];
        }

        // Sort by time (most recent first)
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Get pending counts for moderation.
     */
    private function getPendingCounts(): array
    {
        return [
            'pending_companies' => Company::where('verification_status', 'pending')->count(),
            'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
            'reported_jobs' => 0, // Add when you have reports table
            'pending_testimonials' => 0, // Add when you have testimonials table
        ];
    }

    /**
     * Get system health status.
     */
    private function getSystemHealth(): array
    {
        return [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageSpace(),
            'last_backup' => '2026-03-14 02:00 AM', // You'd get this from backup system
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];
    }

    /**
     * Get date range based on period.
     */
    private function getDateRange(string $period): array
    {
        $now = Carbon::now();
        
        return match($period) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'prev_start' => $now->copy()->subDay()->startOfDay(),
                'prev_end' => $now->copy()->subDay()->endOfDay(),
            ],
            'week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
                'prev_start' => $now->copy()->subWeek()->startOfWeek(),
                'prev_end' => $now->copy()->subWeek()->endOfWeek(),
            ],
            'month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
                'prev_start' => $now->copy()->subMonth()->startOfMonth(),
                'prev_end' => $now->copy()->subMonth()->endOfMonth(),
            ],
            'year' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear(),
                'prev_start' => $now->copy()->subYear()->startOfYear(),
                'prev_end' => $now->copy()->subYear()->endOfYear(),
            ],
            default => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'prev_start' => $now->copy()->subDay()->startOfDay(),
                'prev_end' => $now->copy()->subDay()->endOfDay(),
            ],
        };
    }

    /**
     * Calculate growth percentage.
     */
    private function calculateGrowth($previous, $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Check database connection.
     */
    private function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check cache connection.
     */
    private function checkCacheConnection(): bool
    {
        try {
            Cache::store()->has('test-key');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check storage space.
     */
    private function checkStorageSpace(): array
    {
        $path = storage_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;
        
        return [
            'total' => $this->formatBytes($total),
            'free' => $this->formatBytes($free),
            'used' => $this->formatBytes($used),
            'percent' => round(($used / $total) * 100, 1),
        ];
    }

    /**
     * Format bytes to human readable.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Export dashboard data.
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'month');
        $dates = $this->getDateRange($period);
        
        $data = [
            'users' => User::whereBetween('created_at', [$dates['start'], $dates['end']])->get(),
            'companies' => Company::whereBetween('created_at', [$dates['start'], $dates['end']])->get(),
            'jobs' => JobPosting::whereBetween('created_at', [$dates['start'], $dates['end']])->get(),
            'applications' => JobApplication::whereBetween('created_at', [$dates['start'], $dates['end']])->get(),
        ];

        // Generate CSV or Excel export
        // You can use Laravel Excel package for this
        
        return response()->json(['success' => true, 'message' => 'Export feature coming soon']);
    }

    /**
     * Refresh dashboard data via AJAX.
     */
    public function refresh(Request $request)
    {
        $period = $request->get('period', 'today');
        $dates = $this->getDateRange($period);
        
        $stats = $this->getDashboardStats($dates);
        $charts = $this->getChartData($period);
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'charts' => $charts,
        ]);
    }
}