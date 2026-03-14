<?php
// app/Models/Testimonial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'rating',
        'job_title',
        'company_name',
        'is_approved',
        'featured',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'featured' => 'boolean',
        'rating' => 'integer',
        'moderated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false)->whereNull('rejection_reason');
    }

    public function scopeRejected($query)
    {
        return $query->whereNotNull('rejection_reason');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}