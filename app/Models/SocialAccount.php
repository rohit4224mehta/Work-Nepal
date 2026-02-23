<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'email',
        'name',
        'avatar',
        'raw',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'raw' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    //  Relationships
    // -------------------------------------------------------------------------

    /**
     * The user this social account belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------------------------
    //  Helpers
    // -------------------------------------------------------------------------

    /**
     * Check if this social account belongs to a specific provider
     */
    public function isProvider(string $provider): bool
    {
        return $this->provider === strtolower($provider);
    }

    /**
     * Get avatar URL (fallback to user's profile photo if missing)
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return $this->user?->profile_photo_url;
    }

    // -------------------------------------------------------------------------
    //  Scopes
    // -------------------------------------------------------------------------

    public function scopeGoogle($query)
    {
        return $query->where('provider', 'google');
    }

    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider', strtolower($provider));
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}