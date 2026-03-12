<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Support\Enums\AccountStatus;

use App\Support\Enums\Gender;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'profile_photo_path',
        'gender',
        'date_of_birth',
        'account_status',
        'last_login_at',
        'last_login_ip',
        'headline',
        'summary',
        'resume_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'last_login_ip',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'date_of_birth'      => 'date',
        'last_login_at'      => 'datetime',
        'gender'             => Gender::class,
        'account_status'     => AccountStatus::class,
        'password'           => 'hashed',
    ];

    protected $attributes = [
        'account_status' => AccountStatus::ACTIVE,
    ];

/*
|--------------------------------------------------------------------------
| Accessors & Mutators
|--------------------------------------------------------------------------
*/

protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string =>
                $this->profile_photo_path
                ? asset('storage/'.$this->profile_photo_path)
                : $this->defaultProfilePhotoUrl(),
        );
    }

    protected function defaultProfilePhotoUrl(): string
    {
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=random&size=256";
    }

    /**
     * Get the resume URL attribute.
     */
    protected function resumeUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string =>
                $this->resume_path
                ? asset('storage/'.$this->resume_path)
                : null,
        );
    }

    /**
     * Get the resume file name attribute.
     */
    protected function resumeName(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string =>
                $this->resume_path
                ? basename($this->resume_path)
                : null,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Helpers
    |--------------------------------------------------------------------------
    */

    public function hasCompletedProfile(): bool
    {
        return filled($this->name)
            && filled($this->gender?->value)
            && filled($this->date_of_birth)
            && ($this->email_verified_at || $this->mobile_verified_at);
    }

    /**
     * Profile completion calculation used by dashboard
     */
    public function profileCompletionPercentage(): int
    {
        $score = 0;
        $weights = [
            'photo' => 10,
            'basic_info' => 10,
            'headline' => 10,
            'summary' => 10,
            'skills' => 15,
            'education' => 15,
            'experience' => 15,
            'resume' => 10,
            'preferences' => 5,
        ];

        // Photo (10%)
        if ($this->profile_photo_path) {
            $score += $weights['photo'];
        }

        // Basic Info (10%)
        $basicInfoScore = 0;
        if ($this->name) $basicInfoScore += 2;
        if ($this->email) $basicInfoScore += 2;
        if ($this->mobile) $basicInfoScore += 2;
        if ($this->gender && $this->gender->value !== 'prefer_not_to_say') $basicInfoScore += 2;
        if ($this->date_of_birth) $basicInfoScore += 2;
        $score += min($weights['basic_info'], $basicInfoScore);

        // Headline (10%)
        if ($this->headline) {
            $wordCount = str_word_count($this->headline);
            if ($wordCount >= 5) {
                $score += $weights['headline'];
            } elseif ($wordCount >= 3) {
                $score += ($weights['headline'] * 0.7);
            } else {
                $score += ($weights['headline'] * 0.4);
            }
        }

        // Summary (10%)
        if ($this->summary) {
            $charCount = strlen($this->summary);
            if ($charCount >= 500) {
                $score += $weights['summary'];
            } elseif ($charCount >= 200) {
                $score += ($weights['summary'] * 0.7);
            } elseif ($charCount >= 50) {
                $score += ($weights['summary'] * 0.4);
            }
        }

        // Skills (15%)
        if ($this->skills()->exists()) {
            $skillCount = $this->skills()->count();
            if ($skillCount >= 8) {
                $score += $weights['skills'];
            } elseif ($skillCount >= 5) {
                $score += ($weights['skills'] * 0.7);
            } elseif ($skillCount >= 3) {
                $score += ($weights['skills'] * 0.4);
            } elseif ($skillCount >= 1) {
                $score += ($weights['skills'] * 0.2);
            }
        }

        // Education (15%)
        if ($this->education()->exists()) {
            $educationScore = 0;
            foreach ($this->education as $edu) {
                $entryScore = 0;
                if ($edu->degree) $entryScore += 3;
                if ($edu->field_of_study) $entryScore += 3;
                if ($edu->institution) $entryScore += 3;
                if ($edu->start_date) $entryScore += 3;
                if ($edu->description) $entryScore += 3;
                $educationScore += min(15, $entryScore);
            }
            $averageScore = ($educationScore / $this->education()->count());
            $score += min($weights['education'], $averageScore);
        }

        // Experience (15%)
        if ($this->experience()->exists()) {
            $experienceScore = 0;
            foreach ($this->experience as $exp) {
                $entryScore = 0;
                if ($exp->position) $entryScore += 3;
                if ($exp->company_name) $entryScore += 3;
                if ($exp->start_date) $entryScore += 3;
                if ($exp->description) $entryScore += 3;
                if ($exp->end_date || $exp->is_current) $entryScore += 3;
                $experienceScore += min(15, $entryScore);
            }
            $averageScore = ($experienceScore / $this->experience()->count());
            $score += min($weights['experience'], $averageScore);
        }

        // Resume (10%)
        if ($this->resume_path) {
            $score += $weights['resume'];
        }

        // Preferences (5%)
        if ($this->jobPreference) {
            $prefScore = 0;
            if ($this->jobPreference->preferred_location) $prefScore += 2;
            if ($this->jobPreference->preferred_job_type) $prefScore += 2;
            if ($this->jobPreference->expected_salary) $prefScore += 1;
            $score += min($weights['preferences'], $prefScore);
        }

        return min(100, (int) round($score));
    }

    /**
     * Determine if user acts as employer
     */
    public function isEmployer(): bool
    {
        return $this->companies()->exists() || $this->hasRole('employer');
    }

    /**
     * Determine if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin');
    }

    /**
     * Determine if user is job seeker
     */
    public function isJobSeeker(): bool
    {
        return $this->hasRole('job_seeker');
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        if ($this->isAdmin()) return 'Administrator';
        if ($this->isEmployer()) return 'Employer';
        return 'Job Seeker';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Social accounts (Google, Facebook, etc.)
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Companies the user belongs to (as member)
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Companies owned by the user
     */
    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    /**
     * Current active company for the user
     */
    public function currentCompany()
    {
        return $this->companies()
            ->wherePivot('is_active', true)
            ->first();
    }

    /**
     * Job applications submitted by the user
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Skills possessed by the user (many-to-many)
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user')
                    ->withTimestamps();
    }

    /**
     * Education records of the user
     */
    public function education()
    {
        return $this->hasMany(Education::class)->orderBy('start_date', 'desc');
    }

    /**
     * Experience records of the user
     */
    public function experience()
    {
        return $this->hasMany(Experience::class)->orderBy('start_date', 'desc');
    }

    /**
     * Job preferences for the user
     */
    public function jobPreference()
    {
        return $this->hasOne(JobPreference::class);
    }

    /**
     * Saved jobs/bookmarked by the user
     */
    public function savedJobs()
    {
        return $this->belongsToMany(JobPosting::class, 'saved_jobs')
                    ->withTimestamps();
    }

    /**
     * Notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope active users
     */
    public function scopeActive($query)
    {
        return $query->where('account_status', AccountStatus::ACTIVE);
    }

    /**
     * Scope suspended users
     */
    public function scopeSuspended($query)
    {
        return $query->where('account_status', AccountStatus::SUSPENDED);
    }

    /**
     * Scope admin users
     */
    public function scopeAdmins($query)
    {
        return $query->role(['admin', 'super_admin']);
    }

    /**
     * Scope employer users
     */
    public function scopeEmployers($query)
    {
        return $query->role('employer');
    }

    /**
     * Scope job seeker users
     */
    public function scopeJobSeekers($query)
    {
        return $query->role('job_seeker');
    }

    /**
     * Scope users who have completed their profile
     */
    public function scopeWithCompletedProfile($query)
    {
        return $query->whereNotNull('headline')
                     ->whereNotNull('summary')
                     ->whereNotNull('profile_photo_path')
                     ->whereHas('skills')
                     ->whereHas('education');
    }

    /**
     * Scope users who have uploaded resume
     */
    public function scopeWithResume($query)
    {
        return $query->whereNotNull('resume_path');
    }

    /**
     * Scope users who verified email
     */
    public function scopeEmailVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope users who verified mobile
     */
    public function scopeMobileVerified($query)
    {
        return $query->whereNotNull('mobile_verified_at');
    }

    /**
     * Scope users who logged in recently (last 7 days)
     */
    public function scopeRecentlyActive($query)
    {
        return $query->where('last_login_at', '>=', now()->subDays(7));
    }

    /**
     * Scope users by date range
     */
    public function scopeRegisteredBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Mark mobile as verified
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Update last login info
     */
    public function updateLastLogin($ip = null)
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ])->save();
    }

    /**
     * Check if user has saved a specific job
     */
    public function hasSavedJob($jobId)
    {
        return $this->savedJobs()->where('job_posting_id', $jobId)->exists();
    }

    /**
     * Toggle saved job (bookmark)
     */
    public function toggleSavedJob($jobId)
    {
        if ($this->hasSavedJob($jobId)) {
            $this->savedJobs()->detach($jobId);
            return false; // removed
        } else {
            $this->savedJobs()->attach($jobId);
            return true; // added
        }
    }

    /**
     * Get applied job IDs
     */
    public function appliedJobIds()
    {
        return $this->jobApplications()->pluck('job_posting_id')->toArray();
    }

    /**
     * Check if user has applied to a specific job
     */
    public function hasAppliedToJob($jobId)
    {
        return $this->jobApplications()->where('job_posting_id', $jobId)->exists();
    }

    /**
     * Get application count by status
     */
    public function getApplicationCountByStatus($status = null)
    {
        $query = $this->jobApplications();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }

    /**
     * Get recent applications
     */
    public function getRecentApplications($limit = 5)
    {
        return $this->jobApplications()
                    ->with('jobPosting.company')
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get recommended jobs based on skills and preferences
     */
    public function getRecommendedJobs($limit = 6)
    {
        $skillIds = $this->skills()->pluck('skills.id')->toArray();
        $preference = $this->jobPreference;
        
        $query = JobPosting::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereDate('deadline', '>=', now())
            ->with('company');
        
        // Match by skills
        if (!empty($skillIds)) {
            $query->whereHas('requiredSkills', function ($q) use ($skillIds) {
                $q->whereIn('skills.id', $skillIds);
            });
        }
        
        // Match by location preference
        if ($preference && $preference->preferred_location) {
            $query->where('location', 'like', '%' . $preference->preferred_location . '%');
        }
        
        // Match by job type preference
        if ($preference && $preference->preferred_job_type) {
            $jobTypes = explode(',', $preference->preferred_job_type);
            $query->whereIn('job_type', $jobTypes);
        }
        
        // Exclude jobs already applied to
        $appliedIds = $this->appliedJobIds();
        if (!empty($appliedIds)) {
            $query->whereNotIn('id', $appliedIds);
        }
        
        return $query->latest()->limit($limit)->get();
    }
}