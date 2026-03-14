<?php
// app/Models/JobApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';
    
    protected $fillable = [
        'user_id',
        'job_posting_id',
        'status',
        'applied_at',
        'employer_feedback',
        'status_updated_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    /**
     * Get the job posting that this application belongs to.
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * Alternative relationship name (if you're using this instead)
     */
    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * Get the applicant (user) who submitted this application.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the company through the job posting.
     */
    public function company()
    {
        return $this->jobPosting->company();
    }
}