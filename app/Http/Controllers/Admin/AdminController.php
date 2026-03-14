<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $perPage = 15;

    /**
     * Log admin action for audit trail.
     */
    protected function logAdminAction(string $action, string $description, $subject = null): void
    {
        Activity::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'input' => request()->except(['_token', 'password', 'password_confirmation']),
            ],
        ]);
    }

    /**
     * Get paginated activities.
     */
    protected function getActivities($filters = [], $perPage = 50)
    {
        $query = Activity::with('user')->latest();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Handle bulk delete with proper logging.
     */
    protected function bulkDelete($model, array $ids, string $modelName): array
    {
        $success = [];
        $failed = [];

        foreach ($ids as $id) {
            try {
                $item = $model::find($id);
                if ($item) {
                    $identifier = $item->name ?? $item->title ?? "ID: {$id}";
                    $item->delete();
                    $success[] = $identifier;
                    
                    $this->logAdminAction(
                        "bulk_delete_{$modelName}",
                        "Deleted {$modelName}: {$identifier}"
                    );
                }
            } catch (\Exception $e) {
                $failed[] = "ID: {$id}";
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
        ];
    }

    /**
     * Export data to CSV.
     */
    protected function exportToCsv(array $headers, array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
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
     * Check if user has permission for action.
     */
    protected function authorizeAction(string $permission, $subject = null): bool
    {
        $user = auth()->user();

        // Super admin can do anything
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Check specific permission
        if ($user->can($permission)) {
            return true;
        }

        // Additional checks based on subject
        if ($subject) {
            // Add custom authorization logic here
        }

        return false;
    }
}