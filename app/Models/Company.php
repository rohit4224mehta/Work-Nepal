<?php
// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'website',
        'industry',
        'location',
        'verification_status',
        'owner_id',
    ];

    protected $casts = [
        'verification_status' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    /**
     * Get the owner of the company.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users associated with this company.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')
                    ->withPivot('role', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get the job postings for this company.
     */
    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    /**
     * Get active job postings.
     */
    public function activeJobs()
    {
        return $this->hasMany(JobPosting::class)
                    ->where('status', 'active')
                    ->whereDate('deadline', '>=', now());
    }

    /**
     * Get the logo URL attribute.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : asset('images/default-company.png');
    }

    /**
     * Scope a query to only include verified companies.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope a query to only include pending companies.
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Get the verification badge color.
     */
    public function getVerificationBadgeAttribute()
    {
        return match($this->verification_status) {
            'verified' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }
}