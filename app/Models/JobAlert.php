<?php
// app/Models/JobAlert.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    protected $table = 'job_alerts';
    
    protected $fillable = [
        'user_id',
        'name',
        'keywords',
        'location',
        'job_type',
        'category',
        'experience_level',
        'salary_min',
        'salary_max',
        'frequency',
        'is_active',
        'last_sent_at',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
    ];
    
    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeByFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }
    
    public function scopeDueForDelivery($query)
    {
        return $query->where(function($q) {
            $q->whereNull('last_sent_at')
              ->orWhere('last_sent_at', '<=', now()->subDay());
        });
    }
    
    // ========== HELPER METHODS ==========
    
    public function getFrequencyLabelAttribute(): string
    {
        return match($this->frequency) {
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'instant' => 'Instant',
            default => 'Daily',
        };
    }
    
    public function getKeywordsArrayAttribute(): array
    {
        return array_map('trim', explode(',', $this->keywords ?? ''));
    }
    
    public function getSummaryAttribute(): string
    {
        $parts = [];
        
        if ($this->keywords) {
            $parts[] = "Keywords: {$this->keywords}";
        }
        if ($this->location) {
            $parts[] = "Location: {$this->location}";
        }
        if ($this->job_type) {
            $parts[] = "Type: " . ucfirst(str_replace('-', ' ', $this->job_type));
        }
        if ($this->category) {
            $parts[] = "Category: {$this->category}";
        }
        
        return implode(' • ', $parts);
    }
    
    public function markAsSent(): void
    {
        $this->update(['last_sent_at' => now()]);
    }
    
    public function toggleStatus(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}