<?php
// app/Listeners/BroadcastNotification.php

namespace App\Listeners;

use App\Events\NotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BroadcastNotification implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        // The event is already broadcasting via ShouldBroadcast interface
        // This listener is for any additional processing
    }
}