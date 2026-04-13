<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckExpiringJobs extends Command
{
    protected $signature = 'jobs:check-expiring';
    protected $description = 'Check for jobs expiring soon and notify employers';

    public function handle()
    {
        // Jobs expiring in 3 days
        $expiringJobs = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(3))
            ->with('company')
            ->get();

        foreach ($expiringJobs as $job) {
            NotificationService::jobExpiring($job->company->owner_id, $job);
        }

        $this->info('Checked ' . $expiringJobs->count() . ' expiring jobs');
    }
}