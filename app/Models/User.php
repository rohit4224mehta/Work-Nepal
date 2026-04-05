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

    /**
     * Get the user's age attribute.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn (): ?int =>
                $this->date_of_birth
                ? $this->date_of_birth->age
                : null,
        );
    }

    /**
     * Get the user's display name with role.
     */
    protected function displayNameWithRole(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->name . ' (' . $this->getRoleDisplayName() . ')',
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
     * Get profile completion level with message.
     */
    public function getProfileCompletionLevel(): array
    {
        $percentage = $this->profileCompletionPercentage();
        
        $level = match(true) {
            $percentage >= 90 => 'excellent',
            $percentage >= 75 => 'good',
            $percentage >= 50 => 'average',
            $percentage >= 25 => 'poor',
            default => 'very_poor',
        };

        $message = match($level) {
            'excellent' => 'Your profile is complete and ready to impress employers!',
            'good' => 'Your profile looks good! A few more details will make it perfect.',
            'average' => 'Your profile is average. Add more details to stand out.',
            'poor' => 'Your profile needs improvement. Complete it to get better job matches.',
            'very_poor' => 'Your profile is incomplete. Add your information to get started.',
        };

        return [
            'percentage' => $percentage,
            'level' => $level,
            'message' => $message,
        ];
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
     * Determine if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
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
        if ($this->isSuperAdmin()) return 'Super Administrator';
        if ($this->isAdmin()) return 'Administrator';
        if ($this->isEmployer()) return 'Employer';
        if ($this->isJobSeeker()) return 'Job Seeker';
        return 'User';
    }

    /**
     * Get user's primary role for display
     */
    public function getPrimaryRole(): string
    {
        if ($this->isSuperAdmin()) return 'Super Administrator';
        if ($this->isAdmin()) return 'Administrator';
        if ($this->isEmployer()) return 'Employer';
        if ($this->isJobSeeker()) return 'Job Seeker';
        return 'User';
    }

    /**
     * Get user's role badge color.
     */
    public function getRoleBadgeColor(): string
    {
        if ($this->isSuperAdmin()) return 'purple';
        if ($this->isAdmin()) return 'red';
        if ($this->isEmployer()) return 'green';
        if ($this->isJobSeeker()) return 'blue';
        return 'gray';
    }

    /**
     * Get user's available dashboard options
     */
    public function getAvailableDashboards(): array
    {
        $dashboards = [];
        
        if ($this->isJobSeeker()) {
            $dashboards['job_seeker'] = [
                'name' => 'Job Seeker Dashboard',
                'route' => 'dashboard.jobseeker',
                'icon' => 'user',
            ];
        }
        
        if ($this->isEmployer()) {
            $dashboards['employer'] = [
                'name' => 'Employer Dashboard',
                'route' => 'employer.dashboard',
                'icon' => 'briefcase',
            ];
        }
        
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            $dashboards['admin'] = [
                'name' => 'Admin Dashboard',
                'route' => 'admin.dashboard',
                'icon' => 'shield',
            ];
        }
        
        return $dashboards;
    }

    /**
     * Get primary dashboard route
     */
    public function getPrimaryDashboardRoute(): string
    {
        if ($this->isSuperAdmin() || $this->isAdmin()) {
            return route('admin.dashboard');
        }
        if ($this->isEmployer()) {
            return route('employer.dashboard');
        }
        return route('dashboard.jobseeker');
    }

    /*
    |--------------------------------------------------------------------------
    | Company Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Companies the user belongs to (as member/team member)
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Companies where user is a team member
     */
    public function teamMemberCompanies()
    {
        return $this->belongsToMany(Company::class, 'company_team_members')
                    ->withPivot('role', 'is_active', 'permissions')
                    ->withTimestamps()
                    ->select('companies.*');
    }

    /**
     * Companies owned by the user
     */
    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    /**
     * Get all companies that the user has access to
     */
    public function accessibleCompanies()
    {
        $ownedIds = $this->ownedCompanies()->pluck('id')->toArray();
        $teamIds = $this->teamMemberCompanies()->pluck('companies.id')->toArray();
        $allIds = array_merge($ownedIds, $teamIds);
        
        return Company::whereIn('id', array_unique($allIds));
    }

    /**
     * Get all company IDs that the user has access to
     */
    public function accessibleCompanyIds(): array
    {
        $ownedIds = $this->ownedCompanies()->pluck('id')->toArray();
        $teamIds = $this->teamMemberCompanies()->pluck('companies.id')->toArray();
        
        return array_unique(array_merge($ownedIds, $teamIds));
    }

    /**
   
 * Check if user can manage a company (owner or active team member with manage permissions)
 */
public function canManageCompany(Company $company): bool
{
    // Owner can manage
    if ($this->id === $company->owner_id) {
        return true;
    }
    
    // Team member with manage permissions
    $teamMember = $this->teamMemberCompanies()
        ->where('company_id', $company->id)
        ->wherePivot('is_active', true)
        ->first();
    
    if (!$teamMember) {
        return false;
    }
    
    // Check if team member has manage permissions
    $permissions = json_decode($teamMember->pivot->permissions, true);
    
    return isset($permissions['manage_jobs']) && $permissions['manage_jobs'] === true;
}

/**
 * Check if user can access a company (view applications, etc.)
 */
public function canAccessCompany(Company $company): bool
{
    return $this->id === $company->owner_id
        || $this->teamMemberCompanies()
            ->where('company_id', $company->id)
            ->wherePivot('is_active', true)
            ->exists();
}

    /**
     * Get current active company for the user
     */
    public function currentCompany()
    {
        return $this->companies()
            ->wherePivot('is_active', true)
            ->first();
    }
    public function allCompanies()
    {
        // This returns a relationship that can be used in Blade
        return $this->hasMany(Company::class, 'owner_id')
            ->union($this->belongsToMany(Company::class, 'company_team_members', 'user_id', 'company_id'));
    }

    /*
    |--------------------------------------------------------------------------
    | Other Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Social accounts
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Job applications submitted by the user
     */
    public function jobApplications()
{
    return $this->hasMany(JobApplication::class, 'user_id');
}
    /**
     * Skills possessed by the user
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
    return $this->belongsToMany(JobPosting::class, 'saved_jobs', 'user_id', 'job_posting_id')
                ->withTimestamps();
}

    /**
 * Get notifications for the user
 */
public function notifications()
{
    return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
}

/**
 * Get unread notifications
 */
public function unreadNotifications()
{
    return $this->hasMany(Notification::class)->where('is_read', false);
}

/**
 * Get unread notification count
 */
public function getUnreadNotificationCountAttribute()
{
    return $this->unreadNotifications()->count();
}

    /**
     * Activity logs for the user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Admin activity logs performed by this user
     */
    public function adminActivityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('account_status', AccountStatus::ACTIVE);
    }

    public function scopeSuspended($query)
    {
        return $query->where('account_status', AccountStatus::SUSPENDED);
    }

    public function scopeAdmins($query)
    {
        return $query->role(['admin', 'super_admin']);
    }

    public function scopeEmployers($query)
    {
        return $query->role('employer');
    }

    public function scopeJobSeekers($query)
    {
        return $query->role('job_seeker');
    }

    public function scopeWithResume($query)
    {
        return $query->whereNotNull('resume_path');
    }

    public function scopeEmailVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeMobileVerified($query)
    {
        return $query->whereNotNull('mobile_verified_at');
    }

    public function scopeRecentlyActive($query)
    {
        return $query->where('last_login_at', '>=', now()->subDays(7));
    }

    public function scopeRegisteredBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%")
              ->orWhere('mobile', 'LIKE', "%{$term}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function updateLastLogin($ip = null)
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ])->save();
    }

    public function hasSavedJob($jobId): bool
    {
        return $this->savedJobs()->where('job_posting_id', $jobId)->exists();
    }

    public function toggleSavedJob($jobId): bool
    {
        if ($this->hasSavedJob($jobId)) {
            $this->savedJobs()->detach($jobId);
            return false;
        } else {
            $this->savedJobs()->attach($jobId);
            return true;
        }
    }

    public function appliedJobIds(): array
    {
        return $this->jobApplications()->pluck('job_posting_id')->toArray();
    }

    public function hasAppliedToJob($jobId): bool
    {
        return $this->jobApplications()->where('job_posting_id', $jobId)->exists();
    }

    public function getApplicationCountByStatus($status = null): int
    {
        $query = $this->jobApplications();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }

    /**
     * Get application statistics
     */
    public function getApplicationStats(): array
    {
        return [
            'total' => $this->jobApplications()->count(),
            'applied' => $this->jobApplications()->where('status', 'applied')->count(),
            'viewed' => $this->jobApplications()->where('status', 'viewed')->count(),
            'shortlisted' => $this->jobApplications()->where('status', 'shortlisted')->count(),
            'rejected' => $this->jobApplications()->where('status', 'rejected')->count(),
            'hired' => $this->jobApplications()->where('status', 'hired')->count(),
        ];
    }

    public function getRecentApplications($limit = 5)
    {
        return $this->jobApplications()
                    ->with('jobPosting.company')
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    public function getRecommendedJobs($limit = 6)
    {
        $skillIds = $this->skills()->pluck('skills.id')->toArray();
        $preference = $this->jobPreference;
        
        $query = JobPosting::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereDate('deadline', '>=', now())
            ->with('company');
        
        if (!empty($skillIds)) {
            $query->whereHas('skills', function ($q) use ($skillIds) {
                $q->whereIn('skills.id', $skillIds);
            });
        }
        
        if ($preference && $preference->preferred_location) {
            $query->where('location', 'like', '%' . $preference->preferred_location . '%');
        }
        
        if ($preference && $preference->preferred_job_type) {
            $jobTypes = explode(',', $preference->preferred_job_type);
            $query->whereIn('job_type', $jobTypes);
        }
        
        $appliedIds = $this->appliedJobIds();
        if (!empty($appliedIds)) {
            $query->whereNotIn('id', $appliedIds);
        }
        
        // Calculate match score
        $jobs = $query->latest()->limit($limit * 2)->get();
        
        $jobsWithScore = $jobs->map(function ($job) use ($skillIds, $preference) {
            $score = 0;
            
            // Skills match (50%)
            if (!empty($skillIds)) {
                $jobSkillIds = $job->skills()->pluck('skills.id')->toArray();
                $matchCount = count(array_intersect($skillIds, $jobSkillIds));
                $score += ($matchCount / count($skillIds)) * 50;
            }
            
            // Location match (25%)
            if ($preference && $preference->preferred_location) {
                if (stripos($job->location, $preference->preferred_location) !== false) {
                    $score += 25;
                }
            }
            
            // Job type match (25%)
            if ($preference && $preference->preferred_job_type) {
                $jobTypes = explode(',', $preference->preferred_job_type);
                if (in_array($job->job_type, $jobTypes)) {
                    $score += 25;
                }
            }
            
            $job->match_score = $score;
            return $job;
        });
        
        return $jobsWithScore->sortByDesc('match_score')->take($limit);
    }

    /**
     * Get user's activity summary.
     */
    public function getActivitySummary(): array
    {
        return [
            'last_login' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,
            'applications_count' => $this->jobApplications()->count(),
            'saved_jobs_count' => $this->savedJobs()->count(),
            'companies_count' => $this->ownedCompanies()->count(),
            'team_member_count' => $this->teamMemberCompanies()->count(),
            'account_age_days' => $this->created_at->diffInDays(now()),
        ];
    }

    /**
     * Send password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}