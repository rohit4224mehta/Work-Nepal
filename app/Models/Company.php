<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verification_status' => 'string',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
    ];

    /**
     * Default attributes.
     *
     * @var array
     */
    protected $attributes = [
        'verification_status' => 'pending',
    ];

    // -------------------------------------------------------------------------
    //  Relationships
    // -------------------------------------------------------------------------

    /**
     * The user who owns/created this company
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * All users who belong to this company (including owner)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Currently active members only
     */
    public function activeMembers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_active', true);
    }

    /**
     * All job postings created under this company
     */
    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class);
    }

    // -------------------------------------------------------------------------
    //  Accessors & Mutators
    // -------------------------------------------------------------------------

    /**
     * Get the full URL to the company logo
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => $value
                ? asset('storage/' . $value)
                : $this->defaultLogoUrl(),
        );
    }

    /**
     * Fallback logo when none is uploaded
     */
    protected function defaultLogoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) .
               '&background=0D8ABC&color=fff&size=256&bold=true';
    }

    /**
     * Is this company verified?
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    // -------------------------------------------------------------------------
    //  Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a unique slug from company name
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    // -------------------------------------------------------------------------
    //  Scopes
    // -------------------------------------------------------------------------

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}