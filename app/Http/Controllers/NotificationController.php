<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = $user->notifications();
        
        // Apply filters
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // ✅ FIXED: Statistics without ONLY_FULL_GROUP_BY error
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->where('is_read', false)->count(),
            'by_type' => $this->getNotificationCountsByType($user->id),
            'by_priority' => $this->getNotificationCountsByPriority($user->id),
        ];
        
        return view('notifications.index', compact('notifications', 'stats'));
    }
    
    /**
     * Get notification counts grouped by type
     */
    protected function getNotificationCountsByType($userId): array
    {
        // Using query builder to avoid Eloquent issues
        $results = DB::table('notifications')
            ->where('user_id', $userId)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();
        
        return $results->pluck('count', 'type')->toArray();
    }
    
    /**
     * Get notification counts grouped by priority
     */
    protected function getNotificationCountsByPriority($userId): array
    {
        $results = DB::table('notifications')
            ->where('user_id', $userId)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();
        
        return $results->pluck('count', 'priority')->toArray();
    }
    
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
            ]);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => NotificationService::getUnreadCount($user->id),
        ]);
    }
    
    public function markAsRead(Notification $notification, Request $request)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead(Request $request)
    {
        NotificationService::markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }
    
    public function destroy(Notification $notification, Request $request)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        return response()->json(['success' => true]);
    }
    
    public function clearAll(Request $request)
    {
        auth()->user()->notifications()->delete();
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount(Request $request)
    {
        return response()->json([
            'count' => NotificationService::getUnreadCount(auth()->id()),
        ]);
    }
}