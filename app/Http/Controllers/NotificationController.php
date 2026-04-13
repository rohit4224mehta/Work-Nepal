<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = $user->notifications();
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by read/unread
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistics
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->where('is_read', false)->count(),
            'by_type' => $user->notifications()
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_priority' => $user->notifications()
                ->selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray(),
        ];
        
        return view('notifications.index', compact('notifications', 'stats'));
    }
    
    /**
     * Get recent notifications for dropdown (AJAX)
     */
    public function getRecent(Request $request)
    {
        $user = auth()->user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'is_read' => $n->is_read,
                'time_ago' => $n->created_at->diffForHumans(),
                'action_url' => $n->action_url,
                'icon' => $n->icon,
                'color' => $n->color,
                'priority' => $n->priority,
            ]);
        
        $unreadCount = NotificationService::getUnreadCount($user->id);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'total' => $notifications->count(),
        ]);
    }
    
    /**
     * Mark single notification as read
     */
    public function markAsRead(Notification $notification, Request $request)
    {
        if ($notification->user_id !== auth()->id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }
        
        $notification->markAsRead();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification marked as read');
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        NotificationService::markAllAsRead(auth()->id());
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    /**
     * Delete a notification
     */
    public function destroy(Notification $notification, Request $request)
    {
        if ($notification->user_id !== auth()->id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }
        
        $notification->delete();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification deleted');
    }
    
    /**
     * Clear all notifications
     */
    public function clearAll(Request $request)
    {
        auth()->user()->notifications()->delete();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('notifications.index')
            ->with('success', 'All notifications cleared');
    }
    
    /**
     * Get notification preferences page
     */
    public function preferences(): View
    {
        $preferences = auth()->user()->getNotificationPreferences();
        $notificationTypes = NotificationService::getNotificationTypes();
        
        return view('notifications.preferences', compact('preferences', 'notificationTypes'));
    }
    
    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $preferences = auth()->user()->notificationPreference ?? new NotificationPreference();
        $preferences->user_id = auth()->id();
        $preferences->fill($request->validate([
            'email_job_alerts' => 'boolean',
            'email_application_updates' => 'boolean',
            'push_job_alerts' => 'boolean',
            'push_application_updates' => 'boolean',
            'db_notifications' => 'boolean',
            'email_digest_frequency' => 'in:daily,weekly',
        ]));
        $preferences->save();
        
        return back()->with('success', 'Notification preferences updated');
    }
    
    /**
     * Get unread count for AJAX polling
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => NotificationService::getUnreadCount(auth()->id()),
        ]);
    }
}