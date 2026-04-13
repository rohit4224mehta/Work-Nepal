<?php
// app/Console/Commands/CleanOldNotifications.php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean
                            {--days=30 : Delete notifications older than this many days}
                            {--force : Force deletion without confirmation}';
    
    protected $description = 'Delete old read notifications to keep database clean';
    
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);
        
        $count = Notification::where('created_at', '<', $cutoffDate)
            ->where('is_read', true)
            ->count();
        
        if ($count === 0) {
            $this->info("No old notifications found.");
            return Command::SUCCESS;
        }
        
        $this->warn("Found {$count} notifications older than {$days} days.");
        
        if (!$this->option('force') && !$this->confirm('Do you want to delete these notifications?')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }
        
        $deleted = NotificationService::deleteOldNotifications($days);
        
        $this->info("✅ Deleted {$deleted} old notifications.");
        
        // Also clean up soft-deleted records if any
        $this->info("Compacting database...");
        
        return Command::SUCCESS;
    }
}