<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Report;
use App\Models\Testimonial;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
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

        // Get recent activities from activity logs
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
    protected function getDashboardStats($dates): array
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
                'reports' => Report::count(),
                'testimonials' => Testimonial::count(),
            ],
        ];
    }

    /**
     * Get chart data.
     */
    protected function getChartData($period): array
    {
        $days = match($period) {
            'today' => 24,
            'week' => 7,
            'month' => 30,
            'year' => 12,
            default => 7,
        };

        $data = [
            'labels' => [],
            'jobs' => [],
            'applications' => [],
            'users' => [],
            'companies' => [],
        ];

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
                $data['companies'][] = Company::whereBetween('created_at', [$start, $end])->count();
            }
        }

        return $data;
    }

    /**
     * Get recent activities from activity logs.
     */
    protected function getRecentActivities(): array
    {
        $activities = [];
        
        // Get recent activity logs
        $logs = ActivityLog::with(['admin', 'user'])
            ->latest()
            ->take(10)
            ->get();

        foreach ($logs as $log) {
            $color = match($log->level) {
                'critical' => 'red',
                'danger' => 'orange',
                'warning' => 'yellow',
                default => 'blue',
            };

            $icon = match($log->action) {
                'login', 'logout' => 'user',
                'create' => 'plus',
                'update' => 'pencil',
                'delete', 'suspend', 'ban' => 'trash',
                'verify' => 'check',
                default => 'activity',
            };

            $activities[] = [
                'type' => 'log',
                'action' => $log->action,
                'subject' => $log->description,
                'time' => $log->created_at->diffForHumans(),
                'icon' => $icon,
                'color' => $color,
                'admin' => $log->admin->name ?? 'System',
            ];
        }

        // If no logs yet, fallback to recent records
        if (empty($activities)) {
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
    protected function getPendingCounts(): array
    {
        return [
            'pending_companies' => Company::where('verification_status', 'pending')->count(),
            'pending_jobs' => JobPosting::where('verification_status', 'pending')->count(),
            'reported_jobs' => Report::where('status', 'pending')->count(),
            'pending_testimonials' => Testimonial::where('is_approved', false)->count(),
            'critical_logs' => ActivityLog::where('level', 'critical')
                ->whereDate('created_at', today())
                ->count(),
        ];
    }

    /**
     * Get system health status.
     */
    protected function getSystemHealth(): array
    {
        return [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageSpace(),
            'last_backup' => $this->getLastBackupTime(),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
        ];
    }

    /**
     * Get date range based on period.
     */
    protected function getDateRange(string $period): array
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
    protected function calculateGrowth($previous, $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Check database connection.
     */
    protected function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            $this->logWarning('database_connection_failed', 'Database connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check cache connection.
     */
    protected function checkCacheConnection(): bool
    {
        try {
            Cache::store()->has('health-check-key');
            return true;
        } catch (\Exception $e) {
            $this->logWarning('cache_connection_failed', 'Cache connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check storage space.
     */
    protected function checkStorageSpace(): array
    {
        $path = storage_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;
        
        $percentUsed = $total > 0 ? round(($used / $total) * 100, 1) : 0;

        // Log warning if storage is getting full
        if ($percentUsed > 90) {
            $this->logWarning(
                'storage_almost_full',
                "Storage is {$percentUsed}% full"
            );
        }

        return [
            'total' => $this->formatBytes($total),
            'free' => $this->formatBytes($free),
            'used' => $this->formatBytes($used),
            'percent' => $percentUsed,
            'is_critical' => $percentUsed > 90,
        ];
    }

    /**
     * Get last backup time.
     */
    protected function getLastBackupTime(): string
    {
        // You can implement actual backup check here
        // For now, check if backup file exists
        $backupPath = storage_path('app/backups');
        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.zip');
            if (!empty($files)) {
                $latest = max(array_combine($files, array_map('filemtime', $files)));
                return date('Y-m-d H:i:s', $latest);
            }
        }
        
        return 'Never';
    }

    /**
     * Format bytes to human readable.
     */
    protected function formatBytes($bytes, $precision = 2): string
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
    public function export(Request $request): JsonResponse
    {
        $period = $request->get('period', 'month');
        $dates = $this->getDateRange($period);
        
        $data = [
            'users' => User::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'companies' => Company::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'jobs' => JobPosting::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'applications' => JobApplication::whereBetween('created_at', [$dates['start'], $dates['end']])->count(),
            'period' => $period,
            'date_range' => [
                'from' => $dates['start']->format('Y-m-d H:i:s'),
                'to' => $dates['end']->format('Y-m-d H:i:s'),
            ],
        ];

        // Log the export action
        $this->logAdminAction(
            'dashboard_export',
            "Exported dashboard data for period: {$period}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Export feature coming soon. Data preview:',
            'data' => $data
        ]);
    }

    /**
     * Refresh dashboard data via AJAX.
     */
    public function refresh(Request $request): JsonResponse
    {
        $period = $request->get('period', 'today');
        $dates = $this->getDateRange($period);
        
        $stats = $this->getDashboardStats($dates);
        $charts = $this->getChartData($period);
        $pendingCounts = $this->getPendingCounts();
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'charts' => $charts,
            'pending' => $pendingCounts,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get quick stats for dashboard widgets.
     */
    public function quickStats(): JsonResponse
    {
        $stats = [
            'users_today' => User::whereDate('created_at', today())->count(),
            'jobs_today' => JobPosting::whereDate('created_at', today())->count(),
            'applications_today' => JobApplication::whereDate('created_at', today())->count(),
            'companies_today' => Company::whereDate('created_at', today())->count(),
            'pending_approvals' => [
                'companies' => Company::where('verification_status', 'pending')->count(),
                'jobs' => JobPosting::where('verification_status', 'pending')->count(),
                'testimonials' => Testimonial::where('is_approved', false)->count(),
                'reports' => Report::where('status', 'pending')->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}