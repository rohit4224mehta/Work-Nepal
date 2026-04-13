<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->notifications();
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => auth()->user()->notifications()->count(),
            'unread' => auth()->user()->notifications()->where('is_read', false)->count(),
            'application' => auth()->user()->notifications()->where('category', 'application')->count(),
            'job' => auth()->user()->notifications()->where('category', 'job')->count(),
            'company' => auth()->user()->notifications()->where('category', 'company')->count(),
            'system' => auth()->user()->notifications()->where('category', 'system')->count(),
        ];
        
        return view('notifications.index', compact('notifications', 'stats'));
    }
    
    // In NotificationController.php

public function getRecent()
{
    $notifications = auth()->user()
        ->notifications()
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'is_read' => (bool) $notification->is_read,
                'time_ago' => $notification->created_at->diffForHumans(),
                'action_url' => $notification->action_url ?? '#',
                'icon' => $notification->icon ?? $this->getIconForType($notification->type),
                'color' => $notification->color ?? 'gray',
            ];
        });
    
    return response()->json([
        'notifications' => $notifications,
        'unread_count' => auth()->user()->notifications()->where('is_read', false)->count(),
    ]);
}

protected function getIconForType($type)
{
    $icons = [
        'job_applied' => '📝',
        'job_shortlisted' => '⭐',
        'job_rejected' => '❌',
        'job_hired' => '🎉',
        'new_application' => '📩',
        'job_approved' => '✅',
        'company_verified' => '🏢',
        'new_job_pending' => '📄',
        'new_company_pending' => '🏢',
        'new_report' => '🚩',
    ];
    
    return $icons[$type] ?? '🔔';
}
    
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }
    
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->delete();
        return response()->json(['success' => true]);
    }

    /**
 * Clear all notifications for the user
 */
public function clearAll()
{
    auth()->user()->notifications()->delete();
    
    if (request()->ajax()) {
        return response()->json(['success' => true, 'message' => 'All notifications cleared']);
    }
    
    return redirect()->route('notifications.index')
        ->with('success', 'All notifications cleared');
}
}