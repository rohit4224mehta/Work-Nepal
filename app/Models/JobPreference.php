<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPreference extends Model
{
    use HasFactory;

    protected $table = 'job_preferences';

    protected $fillable = [
        'user_id',
        'preferred_location',
        'preferred_job_type',
        'expected_salary',
        'fresher',
    ];

    protected $casts = [
        'fresher' => 'boolean',
    ];

    /**
     * Get the user that owns the job preference.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get job types as array.
     */
    public function getJobTypesArrayAttribute()
    {
        return $this->preferred_job_type 
            ? explode(',', $this->preferred_job_type) 
            : [];
    }

    /**
     * Set job types from array.
     */
    public function setJobTypesArrayAttribute($value)
    {
        $this->preferred_job_type = is_array($value) 
            ? implode(',', $value) 
            : $value;
    }
}