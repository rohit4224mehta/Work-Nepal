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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login_ip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'mobile_verified_at'   => 'datetime',
        'date_of_birth'        => 'date',
        'last_login_at'        => 'datetime',
        'account_status'       => 'string',
        'password'             => 'hashed',
    ];

    /**
     * Default attributes.
     *
     * @var array
     */
    protected $attributes = [
        'account_status' => 'active',
    ];

    // ────────────────────────────────────────────────
    //  Accessors & Mutators
    // ────────────────────────────────────────────────

    /**
     * Get the user's profile photo URL.
     */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => $value
                ? asset('storage/' . $value)
                : $this->defaultProfilePhotoUrl(),
        );
    }

    /**
     * Default avatar when no photo is set.
     */
    protected function defaultProfilePhotoUrl(): string
    {
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=random&size=256";
    }

    /**
     * Check if user has completed basic profile.
     */
    public function hasCompletedProfile(): bool
    {
        return filled($this->name)
            && filled($this->gender)
            && filled($this->date_of_birth)
            && ($this->email_verified_at || $this->mobile_verified_at);
    }

    /**
     * Check if user is considered an employer (has at least one company)
     */
    public function isEmployer(): bool
    {
        return $this->companies()->exists();
    }

    // ────────────────────────────────────────────────
    //  Relationships
    // ────────────────────────────────────────────────

    /**
     * Social login connections
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Companies this user belongs to (as member/owner/hr/etc.)
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Companies owned by this user
     */
    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    /**
     * Currently active company (can be stored in session later)
     */
    public function currentCompany()
    {
        return $this->companies()->wherePivot('is_active', true)->first();
    }

    // ────────────────────────────────────────────────
    //  Scopes
    // ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('account_status', 'active');
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