<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReportController extends AdminController
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request): View
    {
        $query = Report::with(['reporter', 'reportedUser', 'assignedTo']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('reporter', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Entity type filter
        if ($request->filled('entity_type')) {
            $query->where('reported_entity_type', $request->entity_type);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'priority_high':
                $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')");
                break;
            case 'priority_low':
                $query->orderByRaw("FIELD(priority, 'low', 'medium', 'high', 'critical')");
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $reports = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total' => Report::count(),
            'pending' => Report::pending()->count(),
            'in_review' => Report::inReview()->count(),
            'resolved' => Report::resolved()->count(),
            'dismissed' => Report::dismissed()->count(),
            'critical' => Report::critical()->count(),
            'high' => Report::highPriority()->count(),
        ];

        // Get counts by entity type
        $entityStats = [
            'jobs' => Report::entityType(Report::ENTITY_JOB)->count(),
            'companies' => Report::entityType(Report::ENTITY_COMPANY)->count(),
            'users' => Report::entityType(Report::ENTITY_USER)->count(),
            'reviews' => Report::entityType(Report::ENTITY_REVIEW)->count(),
        ];

        // Get recent activity for chart
        $trends = $this->getReportTrends();

        return view('admin.reports.index', compact(
            'reports',
            'stats',
            'entityStats',
            'trends',
            'request'
        ));
    }

    /**
     * Display report details.
     */
    public function show(Report $report): View
    {
        $report->load([
            'reporter',
            'reportedUser',
            'assignedTo',
            'reportedEntity'
        ]);

        // Load the reported entity details
        $reportedEntity = null;
        switch ($report->reported_entity_type) {
            case Report::ENTITY_JOB:
                $reportedEntity = JobPosting::with('company')->find($report->reported_entity_id);
                break;
            case Report::ENTITY_COMPANY:
                $reportedEntity = Company::with('owner')->find($report->reported_entity_id);
                break;
            case Report::ENTITY_USER:
                $reportedEntity = User::find($report->reported_entity_id);
                break;
        }

        // Get similar reports
        $similarReports = Report::where('reported_entity_type', $report->reported_entity_type)
            ->where('reported_entity_id', $report->reported_entity_id)
            ->where('id', '!=', $report->id)
            ->with(['reporter'])
            ->latest()
            ->limit(5)
            ->get();

        // Get admin users for assignment
        $admins = User::role(['admin', 'super_admin'])->get(['id', 'name']);

        return view('admin.reports.show', compact(
            'report',
            'reportedEntity',
            'similarReports',
            'admins'
        ));
    }

    /**
     * Update report status.
     */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_review,resolved,dismissed',
            'resolution_notes' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $report->status;
            
            $report->update([
                'status' => $request->status,
                'resolution_notes' => $request->resolution_notes,
                'action_taken' => $request->action_taken,
                'assigned_to' => $request->assigned_to ?? $report->assigned_to,
                'resolved_at' => in_array($request->status, ['resolved', 'dismissed']) ? now() : null,
            ]);

            $this->logAdminAction(
                'report_status_updated',
                "Changed report #{$report->id} status from {$oldStatus} to {$request->status}",
                $report
            );

            DB::commit();

            return back()->with('success', 'Report status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update report status.');
        }
    }

    /**
     * Assign report to admin.
     */
    public function assign(Request $request, Report $report): RedirectResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $report->update([
                'assigned_to' => $request->assigned_to,
                'status' => Report::STATUS_IN_REVIEW,
            ]);

            $this->logAdminAction(
                'report_assigned',
                "Assigned report #{$report->id} to user ID: {$request->assigned_to}",
                $report
            );

            // Send notification to assigned admin
            // $assignedAdmin = User::find($request->assigned_to);
            // Notification::send($assignedAdmin, new ReportAssignedNotification($report));

            DB::commit();

            return back()->with('success', 'Report assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign report.');
        }
    }

    /**
     * Take action on reported content.
     */
    public function takeAction(Request $request, Report $report): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:warn,suspend,ban,delete,dismiss',
            'action_details' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $action = $request->action;
            $entity = $report->reportedEntity;

            switch ($action) {
                case 'warn':
                    // Send warning to user
                    // Notification::send($report->reportedUser, new WarningNotification($request->action_details));
                    $actionTaken = "Warning sent to user";
                    break;

                case 'suspend':
                    if ($entity && method_exists($entity, 'update')) {
                        if ($entity instanceof User) {
                            $entity->update(['account_status' => 'suspended']);
                            $actionTaken = "User account suspended";
                        } elseif ($entity instanceof Company) {
                            $entity->update(['verification_status' => 'suspended']);
                            $actionTaken = "Company suspended";
                        }
                    }
                    break;

                case 'ban':
                    if ($entity && $entity instanceof User) {
                        $entity->update(['account_status' => 'banned']);
                        $actionTaken = "User banned";
                    }
                    break;

                case 'delete':
                    if ($entity && method_exists($entity, 'delete')) {
                        $entity->delete();
                        $actionTaken = "Content deleted";
                    }
                    break;

                case 'dismiss':
                    $actionTaken = "Report dismissed - no action taken";
                    break;
            }

            $report->update([
                'status' => Report::STATUS_RESOLVED,
                'action_taken' => $actionTaken,
                'resolution_notes' => $request->action_details,
                'resolved_at' => now(),
            ]);

            $this->logAdminAction(
                'report_action_taken',
                "Action '{$action}' taken on report #{$report->id}",
                $report
            );

            DB::commit();

            return back()->with('success', 'Action taken successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to take action.');
        }
    }

    /**
     * Bulk action on reports.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:mark_in_review,mark_resolved,mark_dismissed,assign,delete',
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id'
        ]);

        $action = $request->action;
        $reportIds = $request->report_ids;

        DB::beginTransaction();

        try {
            $reports = Report::whereIn('id', $reportIds)->get();

            foreach ($reports as $report) {
                switch ($action) {
                    case 'mark_in_review':
                        $report->update(['status' => Report::STATUS_IN_REVIEW]);
                        break;
                    case 'mark_resolved':
                        $report->update([
                            'status' => Report::STATUS_RESOLVED,
                            'resolved_at' => now(),
                        ]);
                        break;
                    case 'mark_dismissed':
                        $report->update([
                            'status' => Report::STATUS_DISMISSED,
                            'resolved_at' => now(),
                        ]);
                        break;
                    case 'assign':
                        if ($request->filled('assign_to')) {
                            $report->update([
                                'assigned_to' => $request->assign_to,
                                'status' => Report::STATUS_IN_REVIEW,
                            ]);
                        }
                        break;
                    case 'delete':
                        $report->delete();
                        break;
                }
            }

            $this->logAdminAction(
                "bulk_reports_{$action}",
                "Bulk {$action} on " . count($reportIds) . " reports"
            );

            DB::commit();

            return back()->with('success', count($reportIds) . ' reports processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process bulk action.');
        }
    }

    /**
     * Delete report.
     */
    public function destroy(Report $report): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $reportId = $report->id;
            $report->delete();

            $this->logAdminAction(
                'report_deleted',
                "Deleted report #{$reportId}"
            );

            DB::commit();

            return redirect()->route('admin.reports.index')
                ->with('success', 'Report deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete report.');
        }
    }

    /**
     * Get report trends for charts.
     */
    private function getReportTrends(): array
    {
        $trends = [];
        
        // Last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trends['labels'][] = now()->subDays($i)->format('M d');
            $trends['reports'][] = Report::whereDate('created_at', $date)->count();
        }

        // Reports by type
        $trends['by_type'] = [
            'jobs' => Report::entityType(Report::ENTITY_JOB)->count(),
            'companies' => Report::entityType(Report::ENTITY_COMPANY)->count(),
            'users' => Report::entityType(Report::ENTITY_USER)->count(),
        ];

        // Reports by priority
        $trends['by_priority'] = [
            'critical' => Report::critical()->count(),
            'high' => Report::highPriority()->count(),
            'medium' => Report::where('priority', Report::PRIORITY_MEDIUM)->count(),
            'low' => Report::where('priority', Report::PRIORITY_LOW)->count(),
        ];

        return $trends;
    }

    /**
     * Export reports data.
     */
    public function export(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('entity_type')) {
            $query->where('reported_entity_type', $request->entity_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reports = $query->get();

        $filename = 'reports_export_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, [
            'Report ID',
            'Reporter',
            'Reporter Email',
            'Reported Entity Type',
            'Reported Entity ID',
            'Reason',
            'Description',
            'Priority',
            'Status',
            'Assigned To',
            'Created At',
            'Resolved At',
            'Action Taken',
            'Resolution Notes'
        ]);

        foreach ($reports as $report) {
            fputcsv($handle, [
                $report->id,
                $report->reporter->name ?? 'N/A',
                $report->reporter->email ?? 'N/A',
                $report->reported_entity_type,
                $report->reported_entity_id,
                $report->reason,
                $report->description ?? '',
                $report->priority,
                $report->status,
                $report->assignedTo->name ?? 'Unassigned',
                $report->created_at->format('Y-m-d H:i:s'),
                $report->resolved_at ? $report->resolved_at->format('Y-m-d H:i:s') : '',
                $report->action_taken ?? '',
                $report->resolution_notes ?? ''
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
     * Get report statistics for dashboard.
     */
    public function getStats(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'total' => Report::count(),
            'pending' => Report::pending()->count(),
            'in_review' => Report::inReview()->count(),
            'resolved' => Report::resolved()->count(),
            'critical' => Report::critical()->count(),
            'high' => Report::highPriority()->count(),
            'avg_response_time' => $this->calculateAvgResponseTime(),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate average response time.
     */
    private function calculateAvgResponseTime(): string
    {
        $resolvedReports = Report::whereNotNull('resolved_at')
            ->where('status', Report::STATUS_RESOLVED)
            ->get();

        if ($resolvedReports->isEmpty()) {
            return 'N/A';
        }

        $totalHours = 0;
        foreach ($resolvedReports as $report) {
            $hours = $report->created_at->diffInHours($report->resolved_at);
            $totalHours += $hours;
        }

        $avgHours = round($totalHours / $resolvedReports->count());

        if ($avgHours < 24) {
            return $avgHours . ' hours';
        } else {
            return round($avgHours / 24, 1) . ' days';
        }
    }
}