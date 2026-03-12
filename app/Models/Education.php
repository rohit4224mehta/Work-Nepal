<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';

    protected $fillable = [
        'user_id',
        'degree',
        'field_of_study',
        'institution',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the user that owns the education record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted duration.
     */
    public function getDurationAttribute()
    {
        $start = $this->start_date->format('Y');
        $end = $this->is_current ? 'Present' : $this->end_date->format('Y');
        return $start . ' - ' . $end;
    }
}