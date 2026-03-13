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
        'cover_path',
        'website',
        'industry',
        'size',
        'founded_year',
        'location',
        'headquarters',
        'contact_email',
        'phone',
        'verification_status',
        'owner_id',
        'culture_images',
        'video_link',
        'social_links',
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'culture_images' => 'array',
        'social_links' => 'array',
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
     * Get team members of the company.
     */
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'company_team_members')
                    ->withPivot('role', 'is_active', 'permissions')
                    ->withTimestamps();
    }

    /**
     * Get active team members.
     */
    public function activeTeamMembers()
    {
        return $this->teamMembers()->wherePivot('is_active', true);
    }

    /**
     * Get job postings for this company.
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
     * Get the cover URL attribute.
     */
    public function getCoverUrlAttribute()
    {
        return $this->cover_path
            ? asset('storage/' . $this->cover_path)
            : asset('images/default-cover.jpg');
    }

    /**
     * Check if user can manage this company.
     */
    public function canUserManage(User $user): bool
    {
        // Owner can always manage
        if ($user->id === $this->owner_id) {
            return true;
        }
        
        // Check if user is active team member
        $teamMember = $this->teamMembers()
            ->where('user_id', $user->id)
            ->wherePivot('is_active', true)
            ->first();
            
        return !is_null($teamMember);
    }
}