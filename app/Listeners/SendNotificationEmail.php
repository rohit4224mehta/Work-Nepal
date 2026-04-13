<?php
// app/Listeners/SendNotificationEmail.php

namespace App\Listeners;

use App\Events\NotificationSent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationEmail implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        // This will be implemented in Phase 2 for email queue processing
        // The NotificationService already handles email sending directly
    }
}