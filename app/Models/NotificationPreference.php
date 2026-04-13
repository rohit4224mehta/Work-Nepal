<?php
// app/Models/NotificationPreference.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $table = 'notification_preferences';
    
    protected $fillable = [
        'user_id',
        'email_job_alerts',
        'email_application_updates',
        'push_job_alerts',
        'push_application_updates',
        'db_notifications',
        'email_digest_frequency',
    ];
    
    protected $casts = [
        'email_job_alerts' => 'boolean',
        'email_application_updates' => 'boolean',
        'push_job_alerts' => 'boolean',
        'push_application_updates' => 'boolean',
        'db_notifications' => 'boolean',
    ];
    
    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // ========== SCOPES ==========
    
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_job_alerts', true)
                     ->orWhere('email_application_updates', true);
    }
    
    public function scopePushEnabled($query)
    {
        return $query->where('push_job_alerts', true)
                     ->orWhere('push_application_updates', true);
    }
    
    // ========== HELPER METHODS ==========
    
    public function shouldSendEmailForType($type): bool
    {
        if (str_contains($type, 'alert')) {
            return $this->email_job_alerts;
        }
        
        if (in_array($type, ['job_shortlisted', 'job_hired', 'new_applicant'])) {
            return $this->email_application_updates;
        }
        
        return false;
    }
    
    public function shouldSendPushForType($type): bool
    {
        if (str_contains($type, 'alert')) {
            return $this->push_job_alerts;
        }
        
        if (in_array($type, ['job_shortlisted', 'job_hired', 'new_applicant'])) {
            return $this->push_application_updates;
        }
        
        return false;
    }
    
    public function getEnabledChannelsForType($type): array
    {
        $channels = [];
        
        if ($this->db_notifications) {
            $channels[] = 'database';
        }
        
        if ($this->shouldSendEmailForType($type)) {
            $channels[] = 'email';
        }
        
        if ($this->shouldSendPushForType($type)) {
            $channels[] = 'push';
        }
        
        return $channels;
    }
}