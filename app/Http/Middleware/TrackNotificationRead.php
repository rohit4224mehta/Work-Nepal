<?php
// app/Http/Middleware/TrackNotificationRead.php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;

class TrackNotificationRead
{
    /**
     * Handle an incoming request.
     * Automatically marks notification as read when user clicks on it
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Check if the request has a notification_id parameter
        $notificationId = $request->query('notification_id');
        
        if ($notificationId && auth()->check()) {
            $notification = Notification::where('id', $notificationId)
                ->where('user_id', auth()->id())
                ->where('is_read', false)
                ->first();
            
            if ($notification) {
                $notification->markAsRead();
            }
        }
        
        return $response;
    }
}