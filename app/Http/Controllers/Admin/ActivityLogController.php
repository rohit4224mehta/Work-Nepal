<?php
// app/Http/Controllers/Admin/ActivityLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends AdminController
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with(['admin', 'user'])
            ->latest('timestamp');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhereHas('admin', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Admin filter
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Statistics
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('timestamp', today())->count(),
            'this_week' => ActivityLog::where('timestamp', '>=', now()->startOfWeek())->count(),
            'this_month' => ActivityLog::whereMonth('timestamp', now()->month)->count(),
            'critical' => ActivityLog::where('level', ActivityLog::LEVEL_CRITICAL)->count(),
            'warnings' => ActivityLog::where('level', ActivityLog::LEVEL_WARNING)->count(),
        ];

        // Action counts
        $actionCounts = ActivityLog::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Get admins for filter
        $admins = User::role(['admin', 'super_admin'])->get(['id', 'name']);

        // Available actions for filter
        $actions = [
            ActivityLog::ACTION_LOGIN,
            ActivityLog::ACTION_LOGOUT,
            ActivityLog::ACTION_CREATE,
            ActivityLog::ACTION_UPDATE,
            ActivityLog::ACTION_DELETE,
            ActivityLog::ACTION_SUSPEND,
            ActivityLog::ACTION_ACTIVATE,
            ActivityLog::ACTION_VERIFY,
            ActivityLog::ACTION_REJECT,
            ActivityLog::ACTION_BAN,
            ActivityLog::ACTION_EXPORT,
            ActivityLog::ACTION_IMPORT,
            ActivityLog::ACTION_SETTINGS,
        ];

        // Levels for filter
        $levels = [
            ActivityLog::LEVEL_INFO,
            ActivityLog::LEVEL_WARNING,
            ActivityLog::LEVEL_DANGER,
            ActivityLog::LEVEL_CRITICAL,
        ];

        return view('admin.logs.index', compact(
            'logs',
            'stats',
            'actionCounts',
            'admins',
            'actions',
            'levels',
            'request'
        ));
    }

    /**
     * Display activity log details.
     */
    public function show(ActivityLog $log): View
    {
        $log->load(['admin', 'user']);

        // Get similar logs from same admin
        $similarLogs = ActivityLog::where('admin_id', $log->admin_id)
            ->where('id', '!=', $log->id)
            ->latest()
            ->limit(5)
            ->get();

        // Get logs for same subject if exists
        $subjectLogs = [];
        if ($log->subject_type && $log->subject_id) {
            $subjectLogs = ActivityLog::where('subject_type', $log->subject_type)
                ->where('subject_id', $log->subject_id)
                ->where('id', '!=', $log->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('admin.logs.show', compact('log', 'similarLogs', 'subjectLogs'));
    }

    /**
     * Get activity statistics.
     */
    public function stats(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'by_hour' => ActivityLog::select(
                DB::raw('HOUR(timestamp) as hour'),
                DB::raw('count(*) as count')
            )
            ->whereDate('timestamp', today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get(),

            'by_action' => ActivityLog::select('action', DB::raw('count(*) as count'))
                ->whereDate('timestamp', '>=', now()->subDays(7))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get(),

            'by_level' => ActivityLog::select('level', DB::raw('count(*) as count'))
                ->whereDate('timestamp', '>=', now()->subDays(7))
                ->groupBy('level')
                ->get(),

            'top_admins' => ActivityLog::select('admin_id', DB::raw('count(*) as count'))
                ->with('admin:id,name')
                ->whereNotNull('admin_id')
                ->whereDate('timestamp', '>=', now()->subDays(30))
                ->groupBy('admin_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Export logs.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with(['admin', 'user']);

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        $logs = $query->limit(10000)->get(); // Limit to 10k records for performance

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, [
            'ID',
            'Timestamp',
            'Admin',
            'Admin Email',
            'Affected User',
            'Action',
            'Description',
            'Level',
            'IP Address',
            'User Agent',
            'Subject Type',
            'Subject ID',
            'Properties'
        ]);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->timestamp->format('Y-m-d H:i:s'),
                $log->admin->name ?? 'System',
                $log->admin->email ?? '',
                $log->user->name ?? '',
                $log->action,
                $log->description,
                $log->level,
                $log->ip_address,
                $log->user_agent,
                $log->subject_type,
                $log->subject_id,
                json_encode($log->properties)
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Clear old logs.
     */
    public function clear(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'older_than' => 'required|integer|min:1|max:365',
        ]);

        $days = $request->older_than;
        $cutoff = now()->subDays($days);

        $count = ActivityLog::where('timestamp', '<', $cutoff)->delete();

        $this->logAdminAction(
            'logs_cleared',
            "Cleared {$count} logs older than {$days} days"
        );

        return back()->with('success', "Cleared {$count} logs older than {$days} days.");
    }

    /**
     * Get real-time log stream for monitoring.
     */
    public function stream(Request $request): \Illuminate\Http\JsonResponse
    {
        $lastId = $request->get('last_id', 0);
        
        $logs = ActivityLog::with(['admin', 'user'])
            ->where('id', '>', $lastId)
            ->latest()
            ->limit(50)
            ->get();

        return response()->json([
            'logs' => $logs,
            'last_id' => $logs->isNotEmpty() ? $logs->first()->id : $lastId,
        ]);
    }
}