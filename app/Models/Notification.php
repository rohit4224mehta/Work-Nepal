<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
        'channel',
        'priority',
        'sent_at',
        'delivered_at',
        'meta'
    ];
    
    protected $casts = [
        'data' => 'array',
        'meta' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // ========== SCOPES ==========
    
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
    
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
    
    public function scopeByCategory($query, $category)
    {
        return $query->where('type', 'like', "{$category}%");
    }
    
    // ========== HELPER METHODS ==========
    
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
    
    public function markAsUnread(): void
    {
        if ($this->is_read) {
            $this->update([
                'is_read' => false,
                'read_at' => null,
            ]);
        }
    }
    
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'job_applied' => '📝',
            'job_shortlisted' => '⭐',
            'job_rejected' => '❌',
            'job_hired' => '🎉',
            'job_alert' => '🔔',
            'new_applicant' => '📩',
            'application_status' => '📊',
            'company_verified' => '✅',
            'company_created' => '🏢',
            'verification_pending' => '⏳',
            'job_expired' => '⚠️',
            'welcome' => '👋',
            default => '🔔',
        };
    }
    
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'job_applied', 'new_applicant' => 'blue',
            'job_shortlisted', 'company_verified' => 'green',
            'job_rejected', 'job_expired' => 'red',
            'job_hired' => 'emerald',
            'job_alert' => 'yellow',
            'application_status' => 'purple',
            'company_created' => 'teal',
            'verification_pending' => 'orange',
            'welcome' => 'indigo',
            default => 'gray',
        };
    }
    
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'blue',
            'low' => 'gray',
            default => 'gray',
        };
    }
    
    public function getActionUrlAttribute(): string
    {
        $data = $this->data ?? [];
        
        return match($this->type) {
            'job_applied', 'application_status' => route('applications.show', $data['application_id'] ?? 0),
            'job_alert' => route('jobs.show', $data['job_id'] ?? 0),
            'new_applicant' => route('employer.applicants.show', $data['application_id'] ?? 0),
            'company_verified', 'company_created' => route('employer.dashboard'),
            'verification_pending' => route('admin.companies.show', $data['company_id'] ?? 0),
            'job_expired' => route('employer.jobs.index'),
            default => '#',
        };
    }
    
    public function isUrgent(): bool
    {
        return $this->priority === 'urgent';
    }
    
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}