<?php
// app/Console/Commands/SendJobAlerts.php

namespace App\Console\Commands;

use App\Models\JobAlert;
use App\Models\JobPosting;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendJobAlerts extends Command
{
    protected $signature = 'notifications:send-job-alerts
                            {--frequency=daily : Frequency of alerts (daily, weekly, instant)}';
    
    protected $description = 'Send job alerts to users based on their preferences';
    
    public function handle()
    {
        $frequency = $this->option('frequency');
        $this->info("Sending {$frequency} job alerts...");
        
        $alerts = JobAlert::where('is_active', true)
            ->where('frequency', $frequency)
            ->where(function($q) {
                $q->whereNull('last_sent_at')
                  ->orWhere('last_sent_at', '<=', $this->getCutoffDate());
            })
            ->get();
        
        $sentCount = 0;
        $totalJobsFound = 0;
        
        foreach ($alerts as $alert) {
            $jobs = $this->findMatchingJobs($alert);
            
            if ($jobs->isNotEmpty()) {
                NotificationService::jobAlert($alert->user_id, $jobs, $alert->name);
                $alert->update(['last_sent_at' => now()]);
                $sentCount++;
                $totalJobsFound += $jobs->count();
                
                $this->line("✓ Alert #{$alert->id}: {$alert->name} - Found {$jobs->count()} jobs");
            }
        }
        
        $this->newLine();
        $this->info("✅ Sent {$sentCount} job alerts with {$totalJobsFound} job matches");
        
        return Command::SUCCESS;
    }
    
    protected function getCutoffDate()
    {
        $frequency = $this->option('frequency');
        
        return match($frequency) {
            'daily' => now()->subDay(),
            'weekly' => now()->subWeek(),
            'instant' => now()->subHour(),
            default => now()->subDay(),
        };
    }
    
    protected function findMatchingJobs($alert)
    {
        $query = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where(function($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->format('Y-m-d'));
            });
        
        // Only get jobs posted after last alert sent
        if ($alert->last_sent_at) {
            $query->where('created_at', '>', $alert->last_sent_at);
        } else {
            $query->where('created_at', '>', now()->subDays(7));
        }
        
        // Apply filters
        if ($alert->keywords) {
            $keywords = explode(',', $alert->keywords);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%");
                }
            });
        }
        
        if ($alert->location) {
            $query->where('location', 'LIKE', "%{$alert->location}%");
        }
        
        if ($alert->job_type) {
            $query->where('job_type', $alert->job_type);
        }
        
        if ($alert->category) {
            $query->where('category', $alert->category);
        }
        
        if ($alert->experience_level) {
            $query->where('experience_level', $alert->experience_level);
        }
        
        if ($alert->salary_min) {
            $query->whereRaw('CAST(REGEXP_SUBSTR(salary_range, "[0-9]+") AS UNSIGNED) >= ?', [$alert->salary_min]);
        }
        
        if ($alert->salary_max) {
            $query->whereRaw('CAST(REGEXP_SUBSTR(salary_range, "[0-9]+") AS UNSIGNED) <= ?', [$alert->salary_max]);
        }
        
        return $query->limit(20)->get();
    }
}