<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a user
     */
    public static function send($userId, $type, $title, $message, $data = [])
    {
        try {
            return Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Notify job seeker when they apply for a job
     */
    public static function jobApplied(JobApplication $application)
    {
        $job = $application->jobPosting;
        $company = $job->company;
        
        return self::send(
            $application->user_id,
            'job_applied',
            'Application Submitted Successfully!',
            "You have successfully applied for {$job->title} at {$company->name}.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'application_id' => $application->id,
            ]
        );
    }
    
    /**
     * Notify job seeker when their application is viewed
     */
    public static function applicationViewed(JobApplication $application)
    {
        $job = $application->jobPosting;
        $company = $job->company;
        
        return self::send(
            $application->user_id,
            'application_viewed',
            'Application Viewed',
            "{$company->name} has viewed your application for {$job->title}.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'application_id' => $application->id,
            ]
        );
    }
    
    /**
     * Notify job seeker when shortlisted
     */
    public static function applicationShortlisted(JobApplication $application)
    {
        $job = $application->jobPosting;
        $company = $job->company;
        
        return self::send(
            $application->user_id,
            'application_shortlisted',
            'Congratulations! You\'ve been Shortlisted 🎯',
            "Great news! {$company->name} has shortlisted you for {$job->title}. They will contact you soon.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'application_id' => $application->id,
            ]
        );
    }
    
    /**
     * Notify job seeker when rejected
     */
    public static function applicationRejected(JobApplication $application)
    {
        $job = $application->jobPosting;
        $company = $job->company;
        
        return self::send(
            $application->user_id,
            'application_rejected',
            'Application Update',
            "Thank you for your interest. {$company->name} has moved forward with other candidates for {$job->title}. Keep applying!",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'application_id' => $application->id,
            ]
        );
    }
    
    /**
     * Notify job seeker when hired
     */
    public static function applicationHired(JobApplication $application)
    {
        $job = $application->jobPosting;
        $company = $job->company;
        
        return self::send(
            $application->user_id,
            'application_hired',
            '🎉 Congratulations! You\'re Hired!',
            "Amazing news! {$company->name} has offered you the position of {$job->title}. Check your email for details.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'application_id' => $application->id,
            ]
        );
    }
    
    /**
     * Notify employer about new application
     */
    public static function newApplication(JobApplication $application, $employerId)
    {
        $job = $application->jobPosting;
        $applicant = $application->applicant;
        
        return self::send(
            $employerId,
            'new_application',
            'New Application Received 📩',
            "{$applicant->name} applied for {$job->title} at your company.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'applicant_id' => $applicant->id,
                'applicant_name' => $applicant->name,
                'application_id' => $application->id,
                'company_id' => $job->company_id,
            ]
        );
    }
    
    /**
     * Notify employer when company is verified
     */
    public static function companyVerified(Company $company, $ownerId)
    {
        return self::send(
            $ownerId,
            'company_verified',
            'Company Verified! ✅',
            "Great news! Your company {$company->name} has been verified. You can now post jobs.",
            [
                'company_id' => $company->id,
                'company_name' => $company->name,
            ]
        );
    }
    
    /**
     * Notify employer when job is about to expire
     */
    public static function jobExpiring($employerId, $job)
    {
        $daysLeft = now()->diffInDays($job->deadline);
        
        return self::send(
            $employerId,
            'job_expired',
            'Job Posting Expiring Soon ⚠️',
            "Your job posting \"{$job->title}\" will expire in {$daysLeft} days. Renew it to keep receiving applications.",
            [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'days_left' => $daysLeft,
            ]
        );
    }
    
    /**
     * Get unread count for a user
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}