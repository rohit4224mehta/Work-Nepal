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
    | Accessors
    |--------------------------------------------------------------------------
    */

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string =>
                $value
                ? asset('storage/'.$value)
                : $this->defaultProfilePhotoUrl(),
        );
    }

    protected function defaultProfilePhotoUrl(): string
    {
        $name = urlencode($this->name ?? 'User');

        return "https://ui-avatars.com/api/?name={$name}&background=random&size=256";
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

        if ($this->profile_photo_path) {
            $score += 15;
        }

        if ($this->resume_path) {
            $score += 20;
        }

        if ($this->headline) {
            $score += 10;
        }

        if ($this->summary) {
            $score += 15;
        }

        if ($this->skills()->exists()) {
            $score += 20;
        }

        if ($this->education()->exists()) {
            $score += 10;
        }

        if ($this->experience()->exists()) {
            $score += 10;
        }

        return min($score, 100);
    }

    /**
     * Determine if user acts as employer
     */
    public function isEmployer(): bool
    {
        return $this->companies()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    public function currentCompany()
    {
        return $this->companies()
            ->wherePivot('is_active', true)
            ->first();
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user')
            ->withTimestamps();
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experience()
    {
        return $this->hasMany(Experience::class);
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

    public function scopeAdmins($query)
    {
        return $query->role('admin');
    }

    public function scopeJobSeekers($query)
    {
        return $query->role('job_seeker');
    }
}