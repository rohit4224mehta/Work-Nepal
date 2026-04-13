<?php
// app/Jobs/ProcessNotification.php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $backoff = [60, 300, 600]; // Retry after 1, 5, 10 minutes
    
    protected $notificationId;
    protected $channels;
    
    /**
     * Create a new job instance.
     */
    public function __construct($notificationId, array $channels)
    {
        $this->notificationId = $notificationId;
        $this->channels = $channels;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = Notification::find($this->notificationId);
        
        if (!$notification) {
            Log::warning("Notification not found for processing: {$this->notificationId}");
            return;
        }
        
        // Mark as sent
        $notification->update(['sent_at' => now()]);
        
        // Process each channel
        foreach ($this->channels as $channel) {
            $this->processChannel($notification, $channel);
        }
        
        // Mark as delivered
        $notification->update(['delivered_at' => now()]);
        
        Log::info("Notification processed", [
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
            'channels' => $this->channels,
        ]);
    }
    
    protected function processChannel($notification, $channel)
    {
        switch ($channel) {
            case 'email':
                $this->sendEmail($notification);
                break;
            case 'push':
                $this->sendPush($notification);
                break;
            case 'database':
                // Already stored
                break;
            default:
                Log::warning("Unknown channel: {$channel}");
        }
    }
    
    protected function sendEmail($notification)
    {
        // Email sending logic - handled by NotificationService
        // This is a fallback for queue processing
        try {
            $user = $notification->user;
            \Illuminate\Support\Facades\Mail::send('emails.notification', [
                'user' => $user,
                'title' => $notification->title,
                'message' => $notification->message,
                'data' => $notification->data,
                'type' => $notification->type,
            ], function ($mail) use ($user, $notification) {
                $mail->to($user->email)
                     ->subject($notification->title . ' - WorkNepal');
            });
        } catch (\Exception $e) {
            Log::error("Email sending failed for notification {$notification->id}: " . $e->getMessage());
            throw $e;
        }
    }
    
    protected function sendPush($notification)
    {
        // Phase 3: Push notification implementation
        // This will integrate with Firebase Cloud Messaging or OneSignal
        Log::info("Push notification would be sent", [
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
        ]);
    }
    
    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Notification processing failed", [
            'notification_id' => $this->notificationId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
        
        // Update notification status
        $notification = Notification::find($this->notificationId);
        if ($notification) {
            $notification->update([
                'meta' => array_merge($notification->meta ?? [], [
                    'failed_at' => now()->toDateTimeString(),
                    'failure_reason' => $exception->getMessage(),
                ]),
            ]);
        }
    }
}