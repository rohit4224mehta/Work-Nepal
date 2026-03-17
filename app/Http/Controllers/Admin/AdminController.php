<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $perPage = 15;
    protected $activityLogger;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->activityLogger = app(ActivityLogger::class);
    }

    /**
     * Log admin action for audit trail.
     */
    protected function logAdminAction(string $action, string $description, $subject = null, string $level = 'info'): void
    {
        $this->activityLogger
            ->byAdmin(auth()->user())
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->level($level)
            ->log();
    }

    /**
     * Log admin action with warning level.
     */
    protected function logWarning(string $action, string $description, $subject = null): void
    {
        $this->activityLogger
            ->byAdmin(auth()->user())
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->warning()
            ->log();
    }

    /**
     * Log admin action with danger level.
     */
    protected function logDanger(string $action, string $description, $subject = null): void
    {
        $this->activityLogger
            ->byAdmin(auth()->user())
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->danger()
            ->log();
    }

    /**
     * Log admin action with critical level.
     */
    protected function logCritical(string $action, string $description, $subject = null): void
    {
        $this->activityLogger
            ->byAdmin(auth()->user())
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->critical()
            ->log();
    }

    /**
     * Get paginated activity logs with advanced filtering.
     */
    protected function getActivityLogs(array $filters = [], int $perPage = 50)
    {
        $query = ActivityLog::with(['admin', 'user'])->latest('timestamp');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['admin_id'])) {
            $query->where('admin_id', $filters['admin_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhereHas('admin', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('timestamp', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('timestamp', '<=', $filters['date_to']);
        }

        if (isset($filters['subject_type']) && isset($filters['subject_id'])) {
            $query->where('subject_type', $filters['subject_type'])
                  ->where('subject_id', $filters['subject_id']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get activity statistics for dashboard.
     */
    protected function getActivityStats()
    {
        return [
            'today' => ActivityLog::whereDate('timestamp', today())->count(),
            'this_week' => ActivityLog::where('timestamp', '>=', now()->startOfWeek())->count(),
            'this_month' => ActivityLog::whereMonth('timestamp', now()->month)->count(),
            'critical' => ActivityLog::where('level', ActivityLog::LEVEL_CRITICAL)
                ->whereDate('timestamp', '>=', now()->subDays(7))
                ->count(),
            'warnings' => ActivityLog::where('level', ActivityLog::LEVEL_WARNING)
                ->whereDate('timestamp', '>=', now()->subDays(7))
                ->count(),
            'by_action' => ActivityLog::select('action', DB::raw('count(*) as count'))
                ->whereDate('timestamp', '>=', now()->subDays(30))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'by_admin' => ActivityLog::select('admin_id', DB::raw('count(*) as count'))
                ->with('admin:id,name')
                ->whereNotNull('admin_id')
                ->whereDate('timestamp', '>=', now()->subDays(30))
                ->groupBy('admin_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Handle bulk delete with proper logging and activity tracking.
     */
    protected function bulkDelete($model, array $ids, string $modelName, ?string $identifierField = null): array
    {
        $success = [];
        $failed = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                try {
                    $item = $model::find($id);
                    if ($item) {
                        // Get identifier for logging
                        if ($identifierField) {
                            $identifier = $item->{$identifierField} ?? "ID: {$id}";
                        } else {
                            $identifier = $item->name ?? $item->title ?? $item->email ?? "ID: {$id}";
                        }

                        // Store data for logging before deletion
                        $itemData = [
                            'id' => $item->id,
                            'identifier' => $identifier,
                            'data' => $item->toArray(),
                        ];

                        $item->delete();
                        $success[] = $identifier;
                        
                        // Log each deletion individually
                        $this->logAdminAction(
                            "bulk_delete_{$modelName}",
                            "Deleted {$modelName}: {$identifier}",
                            null,
                            'warning'
                        )->withProperties([
                            'item_data' => $itemData,
                            'bulk_action' => true,
                        ]);
                    }
                } catch (\Exception $e) {
                    $failed[] = "ID: {$id}";
                    $errors[] = $e->getMessage();
                }
            }

            DB::commit();

            // Log the bulk action summary
            if (count($success) > 0) {
                $this->logAdminAction(
                    "bulk_delete_{$modelName}_summary",
                    "Bulk deleted " . count($success) . " {$modelName}(s)",
                    null,
                    count($failed) > 0 ? 'warning' : 'info'
                )->withProperties([
                    'success_count' => count($success),
                    'failed_count' => count($failed),
                    'failed_ids' => $failed,
                    'errors' => $errors,
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors,
            'success_count' => count($success),
            'failed_count' => count($failed),
        ];
    }

    /**
     * Export data to CSV with activity logging.
     */
    protected function exportToCsv(array $headers, array $data, string $filename, string $exportType = 'data'): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Log the export action
        $this->logAdminAction(
            'export',
            "Exported {$exportType} to CSV",
            null,
            'info'
        )->withProperties([
            'filename' => $filename,
            'record_count' => count($data),
            'headers' => $headers,
        ]);

        $callback = function() use ($headers, $data) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, $headers);
            
            // Add data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Import data from CSV with validation and logging.
     */
    protected function importFromCsv(Request $request, array $rules, callable $processor, string $importType = 'data'): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'warnings' => [],
        ];

        DB::beginTransaction();

        try {
            $file = $request->file('file');
            $handle = fopen($file->path(), 'r');
            
            // Read headers
            $headers = fgetcsv($handle);
            
            // Validate headers
            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                $data = array_combine($headers, $row);
                
                // Validate row
                $validator = validator($data, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }
                
                try {
                    // Process the row using the provided callback
                    $processor($data);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            
            fclose($handle);

            if ($results['failed'] === 0) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            // Log the import action
            $this->logAdminAction(
                'import',
                "Imported {$importType} from CSV",
                null,
                $results['failed'] > 0 ? 'warning' : 'info'
            )->withProperties([
                'filename' => $file->getClientOriginalName(),
                'success_count' => $results['success'],
                'failed_count' => $results['failed'],
                'errors' => $results['errors'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    /**
     * Check if user has permission for action with detailed logging.
     */
    protected function authorizeAction(string $permission, $subject = null, bool $logFailure = true): bool
    {
        $user = auth()->user();

        // Super admin can do anything
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Check specific permission
        $hasPermission = $user->can($permission);

        if (!$hasPermission && $logFailure) {
            // Log unauthorized attempt
            $this->logWarning(
                'unauthorized_attempt',
                "User #{$user->id} attempted {$permission} without permission",
                $subject
            )->withProperties([
                'permission' => $permission,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->id : null,
            ]);
        }

        return $hasPermission;
    }

    /**
     * Get audit trail for a specific model.
     */
    protected function getModelAuditTrail($model, int $limit = 50)
    {
        return ActivityLog::where('subject_type', get_class($model))
            ->where('subject_id', $model->id)
            ->with(['admin', 'user'])
            ->latest('timestamp')
            ->limit($limit)
            ->get();
    }

    /**
     * Compare two versions of a model for audit.
     */
    protected function logModelChanges($model, array $oldData, array $newData, string $action = 'update'): void
    {
        $changes = [];
        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] != $value) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        if (!empty($changes)) {
            $this->logAdminAction(
                $action,
                "Updated " . class_basename($model) . " #{$model->id}",
                $model,
                'info'
            )->withProperties([
                'changes' => $changes,
                'old_data' => $oldData,
                'new_data' => $newData,
            ]);
        }
    }

    /**
     * Get system health status with activity metrics.
     */
    protected function getSystemHealth(): array
    {
        $last24Hours = now()->subHours(24);
        $last7Days = now()->subDays(7);

        return [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->getStorageInfo(),
            'activity' => [
                'last_24h' => ActivityLog::where('timestamp', '>=', $last24Hours)->count(),
                'last_7d' => ActivityLog::where('timestamp', '>=', $last7Days)->count(),
                'unique_admins' => ActivityLog::where('timestamp', '>=', $last7Days)
                    ->distinct('admin_id')
                    ->count('admin_id'),
                'critical_events' => ActivityLog::where('level', ActivityLog::LEVEL_CRITICAL)
                    ->where('timestamp', '>=', $last7Days)
                    ->count(),
            ],
            'last_backup' => $this->getLastBackupTime(),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];
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
            $this->logCritical(
                'database_connection_failed',
                'Database connection failed: ' . $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Check cache connection.
     */
    protected function checkCacheConnection(): bool
    {
        try {
            cache()->store()->has('health-check-key');
            return true;
        } catch (\Exception $e) {
            $this->logWarning(
                'cache_connection_failed',
                'Cache connection failed: ' . $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Get storage information.
     */
    protected function getStorageInfo(): array
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
                "Storage is {$percentUsed}% full",
                null,
                'warning'
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
    protected function getLastBackupTime(): ?string
    {
        // This would integrate with your backup system
        // For now, return a placeholder
        return cache()->get('last_backup_time', 'Never');
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
     * Generate unique request ID for tracking.
     */
    protected function generateRequestId(): string
    {
        return uniqid('req_', true);
    }

    /**
     * Get client device info from user agent.
     */
    protected function getClientDeviceInfo(): array
    {
        $userAgent = request()->userAgent();
        
        return [
            'browser' => $this->parseBrowser($userAgent),
            'platform' => $this->parsePlatform($userAgent),
            'is_mobile' => preg_match('/(android|iphone|ipad|mobile)/i', $userAgent),
            'user_agent' => $userAgent,
        ];
    }

    /**
     * Parse browser from user agent.
     */
    protected function parseBrowser($userAgent): string
    {
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }

    /**
     * Parse platform from user agent.
     */
    protected function parsePlatform($userAgent): string
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'MacOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iPhone') !== false) return 'iOS';
        if (strpos($userAgent, 'iPad') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Validate and sanitize input for logging.
     */
    protected function sanitizeForLogging(array $input): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'current_password', 'new_password', 'token', 'secret'];
        
        return collect($input)
            ->map(function ($value, $key) use ($sensitiveFields) {
                if (in_array($key, $sensitiveFields)) {
                    return '[REDACTED]';
                }
                if (is_string($value) && strlen($value) > 500) {
                    return substr($value, 0, 500) . '... [truncated]';
                }
                return $value;
            })
            ->toArray();
    }
}