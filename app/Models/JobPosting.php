<?php
// app/Models/JobPosting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title', 'slug', 'description', 'company_id', 'location', 
        'job_type', 'salary_range', 'category', 'experience_level', 
        'deadline', 'status', 'verification_status', 'is_featured', 
        'job_source', 'skills', 'benefits', 'requirements'
    ];
    
    protected $casts = [
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate slug when creating a new job
        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = static::generateUniqueSlug($job->title);
            }
        });
        
        // Update slug when title changes
        static::updating(function ($job) {
            if ($job->isDirty('title') && empty($job->slug)) {
                $job->slug = static::generateUniqueSlug($job->title);
            }
        });
    }
    
    /**
     * Generate a unique slug for the job
     */
    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * Get the URL for this job
     */
    public function getUrlAttribute()
    {
        return route('jobs.show', $this->slug);
    }
    
    /**
     * Relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Relationship with applications
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }
    
    /**
     * Scope for active jobs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('verification_status', 'verified')
                     ->where(function($q) {
                         $q->whereNull('deadline')
                           ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                     });
    }
}