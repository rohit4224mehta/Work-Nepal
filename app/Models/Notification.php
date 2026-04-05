<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'is_read', 'read_at'
    ];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];
    
    /**
     * Get the user who owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
    
    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    /**
     * Get action URL based on notification type
     */
    public function getActionUrl()
    {
        $data = $this->data;
        
        switch ($this->type) {
            case 'job_applied':
            case 'application_viewed':
            case 'application_shortlisted':
            case 'application_rejected':
            case 'application_hired':
                return isset($data['job_id']) ? route('jobs.show', $data['job_id']) : '#';
                
            case 'new_application':
                return isset($data['application_id']) 
                    ? route('employer.applicants.show', $data['application_id']) 
                    : '#';
                
            case 'company_verified':
                return route('employer.dashboard');
                
            case 'job_expired':
                return route('employer.jobs.index');
                
            default:
                return '#';
        }
    }
    
    /**
     * Get icon based on notification type
     */
    public function getIcon()
    {
        return match($this->type) {
            'job_applied' => '📝',
            'application_viewed' => '👀',
            'application_shortlisted' => '⭐',
            'application_rejected' => '❌',
            'application_hired' => '🎉',
            'new_application' => '📩',
            'company_verified' => '✅',
            'job_expired' => '⚠️',
            default => '🔔',
        };
    }
    
    /**
     * Get color based on notification type
     */
    public function getColor()
    {
        return match($this->type) {
            'job_applied' => 'blue',
            'application_viewed' => 'purple',
            'application_shortlisted' => 'green',
            'application_rejected' => 'red',
            'application_hired' => 'emerald',
            'new_application' => 'orange',
            'company_verified' => 'teal',
            'job_expired' => 'yellow',
            default => 'gray',
        };
    }
}