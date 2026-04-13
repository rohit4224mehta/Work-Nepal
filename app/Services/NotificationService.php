<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    // ========== NOTIFICATION TYPES FOR ALL USERS ==========
    
    // Job Seeker Types
    const TYPE_JOB_APPLIED = 'job_applied';
    const TYPE_JOB_VIEWED = 'job_viewed';
    const TYPE_JOB_SHORTLISTED = 'job_shortlisted';
    const TYPE_JOB_REJECTED = 'job_rejected';
    const TYPE_JOB_HIRED = 'job_hired';
    
    // Employer Types
    const TYPE_NEW_APPLICATION = 'new_application';
    const TYPE_JOB_APPROVED = 'job_approved';
    const TYPE_JOB_REJECTED_ADMIN = 'job_rejected_admin';
    const TYPE_JOB_EXPIRING = 'job_expiring';
    const TYPE_COMPANY_VERIFIED = 'company_verified';
    const TYPE_COMPANY_REJECTED = 'company_rejected';
    const TYPE_COMPANY_SUSPENDED = 'company_suspended';
    const TYPE_COMPANY_ACTIVATED = 'company_activated';
    
    // Admin Types
    const TYPE_NEW_JOB_PENDING = 'new_job_pending';
    const TYPE_NEW_COMPANY_PENDING = 'new_company_pending';
    const TYPE_NEW_REPORT = 'new_report';
    const TYPE_USER_REPORTED = 'user_reported';
    
    // Common Types
    const TYPE_WELCOME = 'welcome';
    const TYPE_PASSWORD_CHANGED = 'password_changed';
    const TYPE_ACCOUNT_SUSPENDED = 'account_suspended';
    
    // ========== CATEGORIES ==========
    const CATEGORY_APPLICATION = 'application';
    const CATEGORY_JOB = 'job';
    const CATEGORY_COMPANY = 'company';
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_MESSAGE = 'message';
    
    /**
     * Send notification to ANY user (Job Seeker, Employer, or Admin)
     */
    public static function send($userId, $type, $title, $message, $data = [], $actionUrl = null)
    {
        try {
            $user = User::find($userId);
            if (!$user) return null;
            
            $config = self::getNotificationConfig($type);
            
            return Notification::create([
                'user_id' => $userId,
                'user_type' => self::getUserType($user),
                'type' => $type,
                'category' => $config['category'],
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'action_url' => $actionUrl ?? $config['default_url'],
                'icon' => $config['icon'],
                'color' => $config['color'],
                'priority' => $config['priority'],
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get notification configuration based on type
     */
    protected static function getNotificationConfig($type)
    {
        $configs = [
            // ========== JOB SEEKER NOTIFICATIONS ==========
            self::TYPE_JOB_APPLIED => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '📝',
                'color' => 'blue',
                'priority' => 'medium',
                'default_url' => route('applications.index'),
            ],
            self::TYPE_JOB_VIEWED => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '👀',
                'color' => 'purple',
                'priority' => 'medium',
                'default_url' => route('applications.index'),
            ],
            self::TYPE_JOB_SHORTLISTED => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '⭐',
                'color' => 'green',
                'priority' => 'high',
                'default_url' => route('applications.index'),
            ],
            self::TYPE_JOB_REJECTED => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '❌',
                'color' => 'red',
                'priority' => 'medium',
                'default_url' => route('applications.index'),
            ],
            self::TYPE_JOB_HIRED => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '🎉',
                'color' => 'emerald',
                'priority' => 'urgent',
                'default_url' => route('applications.index'),
            ],
            
            // ========== EMPLOYER NOTIFICATIONS ==========
            self::TYPE_NEW_APPLICATION => [
                'category' => self::CATEGORY_APPLICATION,
                'icon' => '📩',
                'color' => 'orange',
                'priority' => 'high',
                'default_url' => route('employer.applicants.index'),
            ],
            self::TYPE_JOB_APPROVED => [
                'category' => self::CATEGORY_JOB,
                'icon' => '✅',
                'color' => 'green',
                'priority' => 'high',
                'default_url' => route('employer.jobs.index'),
            ],
            self::TYPE_JOB_REJECTED_ADMIN => [
                'category' => self::CATEGORY_JOB,
                'icon' => '⚠️',
                'color' => 'red',
                'priority' => 'high',
                'default_url' => route('employer.jobs.index'),
            ],
            self::TYPE_JOB_EXPIRING => [
                'category' => self::CATEGORY_JOB,
                'icon' => '⏰',
                'color' => 'yellow',
                'priority' => 'medium',
                'default_url' => route('employer.jobs.index'),
            ],
            self::TYPE_COMPANY_VERIFIED => [
                'category' => self::CATEGORY_COMPANY,
                'icon' => '🏢',
                'color' => 'teal',
                'priority' => 'high',
                'default_url' => route('employer.dashboard'),
            ],
            self::TYPE_COMPANY_REJECTED => [
                'category' => self::CATEGORY_COMPANY,
                'icon' => '📋',
                'color' => 'red',
                'priority' => 'high',
                'default_url' => route('employer.company.create'),
            ],
            self::TYPE_COMPANY_SUSPENDED => [
                'category' => self::CATEGORY_COMPANY,
                'icon' => '🚫',
                'color' => 'red',
                'priority' => 'urgent',
                'default_url' => route('contact'),
            ],
            self::TYPE_COMPANY_ACTIVATED => [
                'category' => self::CATEGORY_COMPANY,
                'icon' => '✅',
                'color' => 'green',
                'priority' => 'high',
                'default_url' => route('employer.dashboard'),
            ],
            
            // ========== ADMIN NOTIFICATIONS ==========
            self::TYPE_NEW_JOB_PENDING => [
                'category' => self::CATEGORY_JOB,
                'icon' => '📄',
                'color' => 'yellow',
                'priority' => 'high',
                'default_url' => route('admin.jobs.pending'),
            ],
            self::TYPE_NEW_COMPANY_PENDING => [
                'category' => self::CATEGORY_COMPANY,
                'icon' => '🏢',
                'color' => 'yellow',
                'priority' => 'high',
                'default_url' => route('admin.companies.pending'),
            ],
            self::TYPE_NEW_REPORT => [
                'category' => self::CATEGORY_SYSTEM,
                'icon' => '🚩',
                'color' => 'red',
                'priority' => 'urgent',
                'default_url' => route('admin.reports.index'),
            ],
            self::TYPE_USER_REPORTED => [
                'category' => self::CATEGORY_SYSTEM,
                'icon' => '👤',
                'color' => 'orange',
                'priority' => 'high',
                'default_url' => route('admin.users.index'),
            ],
            
            // ========== COMMON NOTIFICATIONS ==========
            self::TYPE_WELCOME => [
                'category' => self::CATEGORY_SYSTEM,
                'icon' => '👋',
                'color' => 'blue',
                'priority' => 'low',
                'default_url' => route('dashboard'),
            ],
            self::TYPE_PASSWORD_CHANGED => [
                'category' => self::CATEGORY_SYSTEM,
                'icon' => '🔒',
                'color' => 'gray',
                'priority' => 'low',
                'default_url' => '#',
            ],
            self::TYPE_ACCOUNT_SUSPENDED => [
                'category' => self::CATEGORY_SYSTEM,
                'icon' => '⚠️',
                'color' => 'red',
                'priority' => 'urgent',
                'default_url' => route('contact'),
            ],
        ];
        
        return $configs[$type] ?? [
            'category' => self::CATEGORY_SYSTEM,
            'icon' => '🔔',
            'color' => 'gray',
            'priority' => 'medium',
            'default_url' => '#',
        ];
    }
    
    /**
     * Get user type automatically
     */
    protected static function getUserType($user)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return 'admin';
        }
        if ($user->hasRole('employer')) {
            return 'employer';
        }
        return 'job_seeker';
    }
    
    /**
     * Get unread count for any user
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    /**
     * Mark all as read for any user
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }
    
    // ========== JOB SEEKER METHODS ==========
    
    public static function jobApplied($application)
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_APPLIED,
            'Application Submitted',
            "You applied for {$job->title} at {$job->company->name}",
            ['job_id' => $job->id, 'company_id' => $job->company_id],
            route('jobs.show', $job->slug)
        );
    }
    
    public static function applicationViewed($application)
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_VIEWED,
            'Application Viewed',
            "Your application for {$job->title} has been viewed by {$job->company->name}",
            ['job_id' => $job->id],
            route('applications.show', $application)
        );
    }
    
    public static function applicationShortlisted($application)
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_SHORTLISTED,
            'Congratulations! You\'ve been Shortlisted 🎯',
            "Great news! {$job->company->name} has shortlisted you for {$job->title}",
            ['job_id' => $job->id],
            route('applications.show', $application)
        );
    }
    
    public static function applicationRejected($application)
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_REJECTED,
            'Application Update',
            "Thank you for your interest. {$job->company->name} has moved forward with other candidates",
            ['job_id' => $job->id],
            route('jobs.index')
        );
    }
    
    public static function applicationHired($application)
    {
        $job = $application->jobPosting;
        return self::send(
            $application->user_id,
            self::TYPE_JOB_HIRED,
            '🎉 Congratulations! You\'re Hired!',
            "Amazing news! {$job->company->name} has offered you the position of {$job->title}",
            ['job_id' => $job->id],
            route('applications.show', $application)
        );
    }
    
    // ========== EMPLOYER METHODS ==========
    
    public static function newApplication($application, $employerId)
    {
        $job = $application->jobPosting;
        return self::send(
            $employerId,
            self::TYPE_NEW_APPLICATION,
            'New Application Received 📩',
            "{$application->applicant->name} applied for {$job->title}",
            ['job_id' => $job->id, 'application_id' => $application->id],
            route('employer.applicants.show', $application)
        );
    }
    
    public static function jobApproved($job, $employerId)
    {
        return self::send(
            $employerId,
            self::TYPE_JOB_APPROVED,
            'Job Approved ✅',
            "Your job \"{$job->title}\" has been approved and is now live",
            ['job_id' => $job->id],
            route('jobs.show', $job->slug)
        );
    }
    
    public static function jobRejected($job, $employerId, $reason)
    {
        return self::send(
            $employerId,
            self::TYPE_JOB_REJECTED_ADMIN,
            'Job Posting Update',
            "Your job \"{$job->title}\" was not approved. Reason: {$reason}",
            ['job_id' => $job->id, 'reason' => $reason],
            route('employer.jobs.index')
        );
    }
    
    public static function jobExpiring($employerId, $job)
    {
        $daysLeft = now()->diffInDays($job->deadline);
        return self::send(
            $employerId,
            self::TYPE_JOB_EXPIRING,
            'Job Expiring Soon ⏰',
            "Your job \"{$job->title}\" will expire in {$daysLeft} days",
            ['job_id' => $job->id, 'days_left' => $daysLeft],
            route('employer.jobs.edit', $job)
        );
    }
    
    public static function companyVerified($company, $ownerId)
    {
        return self::send(
            $ownerId,
            self::TYPE_COMPANY_VERIFIED,
            'Company Verified ✅',
            "Your company \"{$company->name}\" has been verified. You can now post jobs!",
            ['company_id' => $company->id],
            route('employer.dashboard')
        );
    }
    
    public static function companyRejected($company, $ownerId, $reason)
    {
        return self::send(
            $ownerId,
            self::TYPE_COMPANY_REJECTED,
            'Company Verification Update',
            "Your company \"{$company->name}\" verification was not approved. Reason: {$reason}",
            ['company_id' => $company->id, 'reason' => $reason],
            route('employer.company.create')
        );
    }
    
    // ========== ADMIN METHODS ==========
    
    public static function newJobPending($job, $adminId)
    {
        return self::send(
            $adminId,
            self::TYPE_NEW_JOB_PENDING,
            'New Job Pending Approval',
            "{$job->company->name} posted a new job: {$job->title}",
            ['job_id' => $job->id, 'company_id' => $job->company_id],
            route('admin.jobs.show', $job)
        );
    }
    
    public static function newCompanyPending($company, $adminId)
    {
        return self::send(
            $adminId,
            self::TYPE_NEW_COMPANY_PENDING,
            'New Company Pending Verification',
            "{$company->name} has registered and needs verification",
            ['company_id' => $company->id],
            route('admin.companies.show', $company)
        );
    }
    
    public static function newReport($report, $adminId)
    {
        return self::send(
            $adminId,
            self::TYPE_NEW_REPORT,
            'New Report Submitted 🚩',
            "A user has reported content. Please review.",
            ['report_id' => $report->id],
            route('admin.reports.show', $report)
        );
    }
    
    // ========== COMMON METHODS ==========
    
    public static function welcome($userId, $name)
    {
        return self::send(
            $userId,
            self::TYPE_WELCOME,
            'Welcome to WorkNepal! 👋',
            "Hi {$name}, welcome to Nepal's #1 job platform. Start exploring opportunities today!",
            [],
            route('dashboard')
        );
    }
}