<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = $user->notifications();
        
        // Filter by read/unread
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }
        
        $notifications = $query->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }
        
        return back()->with('success', 'Notification marked as read');
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        }
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        }
        
        return back()->with('success', 'Notification deleted');
    }
    
    /**
     * Get unread count for AJAX polling
     */
    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(auth()->id());
        
        return response()->json([
            'count' => $count
        ]);
    }
    
    /**
     * Get recent notifications for dropdown (AJAX)
     */
    public function getRecent()
    {
        $notifications = auth()->user()
            ->notifications()
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'action_url' => $notification->getActionUrl(),
                    'icon' => $notification->getIcon(),
                    'color' => $notification->getColor(),
                ];
            });
        
        $unreadCount = NotificationService::getUnreadCount(auth()->id());
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'total' => $notifications->count()
        ]);
    }
}