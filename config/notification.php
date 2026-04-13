<?php
// config/notification.php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    */
    'channels' => [
        'database' => [
            'enabled' => env('NOTIFICATION_DB_ENABLED', true),
            'retention_days' => env('NOTIFICATION_RETENTION_DAYS', 30),
        ],
        'email' => [
            'enabled' => env('NOTIFICATION_EMAIL_ENABLED', true),
            'queue' => env('NOTIFICATION_EMAIL_QUEUE', true),
        ],
        'push' => [
            'enabled' => env('NOTIFICATION_PUSH_ENABLED', false),
            'driver' => env('NOTIFICATION_PUSH_DRIVER', 'pusher'), // pusher, onesignal, fcm
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Notification Priorities
    |--------------------------------------------------------------------------
    */
    'priorities' => [
        'urgent' => [
            'channels' => ['database', 'email', 'push'],
            'retry_after' => 300,
        ],
        'high' => [
            'channels' => ['database', 'email'],
            'retry_after' => 600,
        ],
        'medium' => [
            'channels' => ['database'],
            'retry_after' => 3600,
        ],
        'low' => [
            'channels' => ['database'],
            'retry_after' => 86400,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Job Alert Settings
    |--------------------------------------------------------------------------
    */
    'job_alerts' => [
        'max_per_user' => 20,
        'batch_size' => 100,
        'default_frequency' => 'daily',
        'frequencies' => [
            'daily' => [
                'schedule' => '0 8 * * *', // 8 AM daily
                'cron' => '0 8 * * *',
            ],
            'weekly' => [
                'schedule' => '0 9 * * 1', // 9 AM Monday
                'cron' => '0 9 * * 1',
            ],
            'instant' => [
                'schedule' => '*/15 * * * *', // Every 15 minutes
                'cron' => '*/15 * * * *',
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Real-time Settings (Phase 2)
    |--------------------------------------------------------------------------
    */
    'realtime' => [
        'enabled' => env('NOTIFICATION_REALTIME_ENABLED', false),
        'driver' => env('NOTIFICATION_REALTIME_DRIVER', 'pusher'),
        'pusher' => [
            'app_id' => env('PUSHER_APP_ID'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'cluster' => env('PUSHER_APP_CLUSTER'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Notification Types Configuration
    |--------------------------------------------------------------------------
    */
    'types' => [
        'job_applied' => [
            'name' => 'Job Application Submitted',
            'default_priority' => 'medium',
            'channels' => ['database'],
            'icon' => '📝',
            'color' => 'blue',
        ],
        'job_shortlisted' => [
            'name' => 'Application Shortlisted',
            'default_priority' => 'high',
            'channels' => ['database', 'email'],
            'icon' => '⭐',
            'color' => 'green',
        ],
        'job_rejected' => [
            'name' => 'Application Rejected',
            'default_priority' => 'medium',
            'channels' => ['database'],
            'icon' => '❌',
            'color' => 'red',
        ],
        'job_hired' => [
            'name' => 'You\'re Hired!',
            'default_priority' => 'urgent',
            'channels' => ['database', 'email', 'push'],
            'icon' => '🎉',
            'color' => 'emerald',
        ],
        'new_applicant' => [
            'name' => 'New Applicant',
            'default_priority' => 'high',
            'channels' => ['database', 'email'],
            'icon' => '📩',
            'color' => 'orange',
        ],
        'job_alert' => [
            'name' => 'Job Alert',
            'default_priority' => 'medium',
            'channels' => ['database', 'email'],
            'icon' => '🔔',
            'color' => 'yellow',
        ],
        'company_verified' => [
            'name' => 'Company Verified',
            'default_priority' => 'high',
            'channels' => ['database', 'email'],
            'icon' => '✅',
            'color' => 'teal',
        ],
        'company_created' => [
            'name' => 'New Company Registered',
            'default_priority' => 'high',
            'channels' => ['database'],
            'icon' => '🏢',
            'color' => 'purple',
        ],
        'verification_pending' => [
            'name' => 'Verification Required',
            'default_priority' => 'high',
            'channels' => ['database'],
            'icon' => '⏳',
            'color' => 'orange',
        ],
        'job_expired' => [
            'name' => 'Job Expired',
            'default_priority' => 'medium',
            'channels' => ['database', 'email'],
            'icon' => '⚠️',
            'color' => 'red',
        ],
        'welcome' => [
            'name' => 'Welcome',
            'default_priority' => 'low',
            'channels' => ['database', 'email'],
            'icon' => '👋',
            'color' => 'indigo',
        ],
    ],
];