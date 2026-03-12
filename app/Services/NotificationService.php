<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(User $user, string $type, string $title, ?string $message = null, array $data = [], $notifiable = null)
    {
        $notification = new Notification([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        if ($notifiable) {
            $notification->notifiable()->associate($notifiable);
        }

        $notification->save();

        return $notification;
    }

    /**
     * Send job application notification to employer.
     */
    public function jobApplicationSubmitted($job, $applicant)
    {
        $employers = $job->company->users;
        
        foreach ($employers as $employer) {
            $this->send(
                $employer,
                'job_application',
                'New Job Application',
                "{$applicant->name} has applied for {$job->title}",
                [
                    'job_id' => $job->id,
                    'job_title' => $job->title,
                    'applicant_id' => $applicant->id,
                    'applicant_name' => $applicant->name,
                ],
                $job
            );
        }
    }

    /**
     * Send application status update to job seeker.
     */
    public function applicationStatusUpdated($application)
    {
        $this->send(
            $application->user,
            'application_status',
            'Application Status Updated',
            "Your application for {$application->jobPosting->title} has been {$application->status}",
            [
                'application_id' => $application->id,
                'job_id' => $application->jobPosting->id,
                'job_title' => $application->jobPosting->title,
                'status' => $application->status,
            ],
            $application
        );
    }

    /**
     * Send job alert to job seekers.
     */
    public function jobAlert($job)
    {
        // Get users with matching preferences
        $users = User::whereHas('jobPreference', function ($query) use ($job) {
            // Match by location, job type, etc.
            $query->where('preferred_location', 'LIKE', "%{$job->location}%")
                  ->orWhere('preferred_job_type', 'LIKE', "%{$job->job_type}%");
        })->get();

        foreach ($users as $user) {
            $this->send(
                $user,
                'job_alert',
                'New Job Matching Your Preferences',
                "New {$job->title} position at {$job->company->name}",
                [
                    'job_id' => $job->id,
                    'job_title' => $job->title,
                    'company_name' => $job->company->name,
                ],
                $job
            );
        }
    }
}