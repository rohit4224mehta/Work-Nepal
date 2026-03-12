<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });

        static::updating(function ($skill) {
            if ($skill->isDirty('name')) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }

    /**
     * Get the users that have this skill.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user')
                    ->withTimestamps();
    }
}