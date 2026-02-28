<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class UpdateLastLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Update last login timestamp and IP
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => Request::ip(),
        ]);

        // Optional: you can also log more context (user agent, country, etc.)
        // activity()->log("User logged in from IP: " . Request::ip());
    }
}