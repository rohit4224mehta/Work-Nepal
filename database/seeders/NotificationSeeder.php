<?php
// database/seeders/NotificationSeeder.php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Create notification preferences for existing users
        $users = User::all();
        
        foreach ($users as $user) {
            NotificationPreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'email_job_alerts' => true,
                    'email_application_updates' => true,
                    'push_job_alerts' => false,
                    'push_application_updates' => true,
                    'db_notifications' => true,
                    'email_digest_frequency' => 'daily',
                ]
            );
        }
        
        // Sample notifications for testing (optional)
        if (app()->environment('local')) {
            $testUser = User::first();
            
            if ($testUser) {
                $sampleNotifications = [
                    [
                        'type' => 'job_applied',
                        'title' => 'Application Submitted',
                        'message' => 'You applied for Senior Laravel Developer at Tech Nepal',
                        'priority' => 'medium',
                    ],
                    [
                        'type' => 'job_shortlisted',
                        'title' => 'Congratulations! You\'ve been Shortlisted',
                        'message' => 'Tech Nepal has shortlisted you for Senior Laravel Developer position',
                        'priority' => 'high',
                    ],
                    [
                        'type' => 'job_alert',
                        'title' => '5 New Jobs Matching Your Profile',
                        'message' => 'We found 5 new jobs that match your preferences',
                        'priority' => 'medium',
                    ],
                    [
                        'type' => 'new_applicant',
                        'title' => 'New Application Received',
                        'message' => 'John Doe applied for Senior Laravel Developer at your company',
                        'priority' => 'high',
                    ],
                ];
                
                foreach ($sampleNotifications as $sample) {
                    Notification::create([
                        'user_id' => $testUser->id,
                        'type' => $sample['type'],
                        'title' => $sample['title'],
                        'message' => $sample['message'],
                        'data' => ['sample' => true],
                        'priority' => $sample['priority'],
                        'channel' => 'database',
                        'sent_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Notification preferences created for all users');
    }
}