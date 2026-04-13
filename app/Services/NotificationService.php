<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    // ========== NOTIFICATION TYPES ==========
    const TYPE_JOB_APPLIED = 'job_applied';
    const TYPE_JOB_SHORTLISTED = 'job_shortlisted';
    const TYPE_JOB_REJECTED = 'job_rejected';
    const TYPE_JOB_HIRED = 'job_hired';
    const TYPE_JOB_ALERT = 'job_alert';
    const TYPE_JOB_EXPIRED = 'job_expired';
    const TYPE_NEW_APPLICANT = 'new_applicant';
    const TYPE_APPLICATION_STATUS = 'application_status';
    const TYPE_COMPANY_VERIFIED = 'company_verified';
    const TYPE_COMPANY_CREATED = 'company_created';
    const TYPE_VERIFICATION_PENDING = 'verification_pending';
    const TYPE_WELCOME = 'welcome';
    
    // ========== PRIORITY LEVELS ==========
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';
    
    // ========== CHANNELS ==========
    const CHANNEL_DATABASE = 'database';
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_PUSH = 'push';
    
    /**
     * Main method to send notification through all channels
     */
    public static function send(
        int $userId, 
        string $type, 
        string $title, 
        string $message, 
        array $data = [], 
        string $priority = self::PRIORITY_MEDIUM
    ): ?Notification {
        try {
            $user = User::find($userId);
            if (!$user) {
                Log::warning("User not found for notification: {$userId}");
                return null;
            }
            
            // Get user preferences
            $preferences = self::getUserPreferences($userId);
            
            // Determine channels based on type and preferences
            $channels = $preferences->getEnabledChannelsForType($type);
            
            // Create database notification
            $notification = null;
            if (in_array(self::CHANNEL_DATABASE, $channels)) {
                $notification = self::storeInDatabase($userId, $type, $title, $message, $data, $priority);
            }
            
            // Send email if enabled
            if (in_array(self::CHANNEL_EMAIL, $channels)) {
                self::sendEmail($user, $type, $title, $message, $data);
            }
            
            // Queue for real-time (Phase 2 - WebSockets)
            if (in_array(self::CHANNEL_PUSH, $channels)) {
                self::queueForRealTime($userId, $type, $title, $message, $data);
            }
            
            Log::info("Notification sent to user {$userId}", [
                'type' => $type,
                'channels' => $channels,
            ]);
            
            return $notification;
            
        } catch (\Exception $e) {
            Log::error('Notification failed: ' . $e->getMessage(), [
                'user_id' => $userId,
                'type' => $type,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
    
    /**
     * Store notification in database
     */
    protected static function storeInDatabase(
        int $userId, 
        string $type, 
        string $title, 
        string $message, 
        array $data, 
        string $priority
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $priority,
            'channel' => self::CHANNEL_DATABASE,
            'sent_at' => now(),
        ]);
    }
    
    /**
     * Send email notification
     */
    protected static function sendEmail(User $user, string $type, string $title, string $message, array $data): void
    {
        // Only send email for important notifications
        $importantTypes = [
            self::TYPE_JOB_SHORTLISTED,
            self::TYPE_JOB_HIRED,
            self::TYPE_JOB_ALERT,
            self::TYPE_NEW_APPLICANT,
            self::TYPE_COMPANY_VERIFIED,
            self::TYPE_JOB_EXPIRED,
        ];
        
        if (!in_array($type, $importantTypes)) {
            return;
        }
        
        try {
            Mail::send('emails.notification', [
                'user' => $user,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'type' => $type,
            ], function ($mail) use ($user, $title) {
                $mail->to($user->email)
                     ->subject($title . ' - WorkNepal');
            });
        } catch (\Exception $e) {
            Log::error('Email notification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }
    }
    
    /**
     * Queue for real-time broadcasting (Phase 2)
     */
    protected static function queueForRealTime(int $userId, string $type, string $title, string $message, array $data): void
    {
        // Phase 2 implementation with Pusher/Laravel WebSockets
        // event(new \App\Events\NotificationSent($userId, $type, $title, $message, $data));
    }
    
    /**
     * Get user notification preferences
     */
    protected static function getUserPreferences(int $userId): NotificationPreference
    {
        $preferences = NotificationPreference::firstOrCreate(
            ['user_id' => $userId],
            [
                'email_job_alerts' => true,
                'email_application_updates' => true,
                'push_job_alerts' => false,
                'push_application_updates' => true,
                'db_notifications' => true,
                'email_digest_frequency' => 'daily',
            ]
        );
        
        return $preferences;
    }
    
    // ========== JOB SEEKER NOTIFICATIONS ==========
    
    public static function jobApplied($application): ?Notification
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_APPLIED,
            'Application Submitted Successfully',
            "You applied for {$job->title} at {$job->company->name}",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $job->company_id,
                'company_name' => $job->company->name,
                'application_id' => $application->id,
            ],
            self::PRIORITY_MEDIUM
        );
    }
    
    public static function applicationStatusUpdated($application, string $oldStatus, string $newStatus): ?Notification
    {
        $job = $application->jobPosting;
        
        $statusMessages = [
            'shortlisted' => 'Congratulations! You have been shortlisted 🎯',
            'rejected' => 'Application Update',
            'hired' => '🎉 Congratulations! You got the job!',
            'viewed' => 'Application Viewed',
        ];
        
        $statusDetails = [
            'shortlisted' => "Great news! {$job->company->name} has shortlisted you for {$job->title}",
            'rejected' => "Thank you for your interest. {$job->company->name} has moved forward with other candidates for {$job->title}",
            'hired' => "Amazing news! {$job->company->name} has offered you the position of {$job->title}",
            'viewed' => "{$job->company->name} has viewed your application for {$job->title}",
        ];
        
        $priority = $newStatus === 'hired' ? self::PRIORITY_URGENT : self::PRIORITY_MEDIUM;
        
        return self::send(
            $application->user_id,
            self::TYPE_APPLICATION_STATUS,
            $statusMessages[$newStatus] ?? 'Application Status Updated',
            $statusDetails[$newStatus] ?? "Your application status has been updated to {$newStatus}",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
            $priority
        );
    }
    
    // ========== EMPLOYER NOTIFICATIONS ==========
    
    public static function newApplicant($application, int $employerId): ?Notification
    {
        $job = $application->jobPosting;
        return self::send(
            $employerId,
            self::TYPE_NEW_APPLICANT,
            'New Application Received 📩',
            "{$application->applicant->name} applied for {$job->title}",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'application_id' => $application->id,
                'applicant_id' => $application->applicant->id,
                'applicant_name' => $application->applicant->name,
            ],
            self::PRIORITY_HIGH
        );
    }
    
    public static function jobExpired($job, int $employerId): ?Notification
    {
        return self::send(
            $employerId,
            self::TYPE_JOB_EXPIRED,
            'Job Posting Expired ⚠️',
            "Your job posting '{$job->title}' has expired. Renew it to continue receiving applications.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
            ],
            self::PRIORITY_MEDIUM
        );
    }
    
    // ========== ADMIN NOTIFICATIONS ==========
    
    public static function companyCreated($company, int $adminId): ?Notification
    {
        return self::send(
            $adminId,
            self::TYPE_COMPANY_CREATED,
            'New Company Registered 🏢',
            "{$company->name} has registered and needs verification",
            [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'owner_id' => $company->owner_id,
                'owner_name' => $company->owner->name ?? 'Unknown',
            ],
            self::PRIORITY_HIGH
        );
    }
    
    public static function verificationPending($company, int $adminId): ?Notification
    {
        return self::send(
            $adminId,
            self::TYPE_VERIFICATION_PENDING,
            'Company Verification Required ⏳',
            "Company '{$company->name}' is pending verification. Please review their documents.",
            [
                'company_id' => $company->id,
                'company_name' => $company->name,
            ],
            self::PRIORITY_HIGH
        );
    }
    
    // ========== COMPANY OWNER NOTIFICATIONS ==========
    
    public static function companyVerified($company, int $ownerId): ?Notification
    {
        return self::send(
            $ownerId,
            self::TYPE_COMPANY_VERIFIED,
            'Company Verified ✅',
            "Congratulations! Your company '{$company->name}' has been verified. You can now post jobs.",
            [
                'company_id' => $company->id,
                'company_name' => $company->name,
            ],
            self::PRIORITY_HIGH
        );
    }
    
    // ========== JOB ALERT NOTIFICATIONS ==========
    
    public static function jobAlert(int $userId, $jobs, string $alertName = 'Your Job Alert'): ?Notification
    {
        $jobCount = $jobs->count();
        
        if ($jobCount === 0) {
            return null;
        }
        
        $jobTitles = $jobs->take(3)->pluck('title')->implode(', ');
        $moreText = $jobCount > 3 ? " and {$jobCount->sub(3)} more" : '';
        
        return self::send(
            $userId,
            self::TYPE_JOB_ALERT,
            "{$jobCount} New Jobs Matching '{$alertName}' 🔔",
            "{$jobTitles}{$moreText} - New opportunities waiting for you!",
            [
                'jobs' => $jobs->map(fn($j) => ['id' => $j->id, 'title' => $j->title])->toArray(),
                'count' => $jobCount,
                'alert_name' => $alertName,
            ],
            self::PRIORITY_MEDIUM
        );
    }
    
    // ========== WELCOME NOTIFICATIONS ==========
    
    public static function welcome(int $userId, string $name): ?Notification
    {
        return self::send(
            $userId,
            self::TYPE_WELCOME,
            'Welcome to WorkNepal! 👋',
            "Hi {$name}, welcome to Nepal's #1 job platform. Complete your profile to get started.",
            [
                'user_name' => $name,
            ],
            self::PRIORITY_MEDIUM
        );
    }
    
    // ========== UTILITY METHODS ==========
    
    public static function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
    
    public static function deleteOldNotifications(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }
    
    public static function getNotificationTypes(): array
    {
        return [
            'job_seeker' => [
                self::TYPE_JOB_APPLIED => 'Job Applied',
                self::TYPE_APPLICATION_STATUS => 'Application Status Update',
                self::TYPE_JOB_ALERT => 'Job Alerts',
            ],
            'employer' => [
                self::TYPE_NEW_APPLICANT => 'New Applicant',
                self::TYPE_JOB_EXPIRED => 'Job Expired',
                self::TYPE_COMPANY_VERIFIED => 'Company Verified',
            ],
            'admin' => [
                self::TYPE_COMPANY_CREATED => 'New Company Registration',
                self::TYPE_VERIFICATION_PENDING => 'Verification Pending',
            ],
            'common' => [
                self::TYPE_WELCOME => 'Welcome Message',
            ],
        ];
    }
}