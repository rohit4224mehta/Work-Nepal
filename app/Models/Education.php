<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Education
 *
 * Represents a user's educational background entry.
 */
class Education extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    
    protected $fillable = [
        'user_id',
        'degree',
        'field_of_study',
        'institution',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date'   => 'date:Y-m-d',
        'is_current' => 'boolean',
    ];

    

    /**
     * The user who owns this education record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted duration string (e.g., "2018 - 2022" or "2018 - Present").
     */
    public function getDurationAttribute(): string
    {
        if (!$this->start_date) {
            return 'N/A';
        }

        $start = $this->start_date->format('Y');

        if ($this->is_current) {
            return $start . ' - Present';
        }

        if (!$this->end_date) {
            return $start . ' - Ongoing';
        }

        return $start . ' - ' . $this->end_date->format('Y');
    }

    /**
     * Get human-readable status (Current / Completed).
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_current) {
            return 'Current';
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return 'Completed';
        }

        return 'Ongoing';
    }

    /**
     * Scope a query to only include current education entries.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope a query to only include completed education entries.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_current', false)
                     ->whereNotNull('end_date')
                     ->where('end_date', '<=', now());
    }

    /**
     * Get the duration in years (approximate).
     */
    public function getYearsAttribute(): ?string
    {
        if (!$this->start_date) {
            return null;
        }

        $end = $this->is_current ? now() : ($this->end_date ?? $this->start_date);

        $years = $this->start_date->diffInYears($end);

        return $years . ($years === 1 ? ' year' : ' years');
    }
}