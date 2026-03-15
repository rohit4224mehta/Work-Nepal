<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Setting groups
    const GROUP_GENERAL = 'general';
    const GROUP_JOB = 'job';
    const GROUP_APPLICATION = 'application';
    const GROUP_USER = 'user';
    const GROUP_COMPANY = 'company';
    const GROUP_EMAIL = 'email';
    const GROUP_NOTIFICATION = 'notification';
    const GROUP_SECURITY = 'security';
    const GROUP_PAYMENT = 'payment';
    const GROUP_API = 'api';

    // Setting types
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_SELECT = 'select';
    const TYPE_MULTISELECT = 'multiselect';
    const TYPE_FILE = 'file';
    const TYPE_JSON = 'json';

    /**
     * Get setting value cast to proper type.
     */
    public function getTypedValueAttribute()
    {
        return match($this->type) {
            self::TYPE_NUMBER => (float) $this->value,
            self::TYPE_BOOLEAN => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            self::TYPE_JSON => json_decode($this->value, true),
            self::TYPE_MULTISELECT => explode(',', $this->value),
            default => $this->value,
        };
    }

    /**
     * Get setting by key with caching.
     */
    public static function get($key, $default = null)
    {
        static $settings = null;
        
        if ($settings === null) {
            $settings = cache()->remember('system_settings', 3600, function () {
                return self::all()->keyBy('key');
            });
        }

        $setting = $settings->get($key);
        
        if (!$setting) {
            return $default;
        }

        return $setting->typed_value;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache()
    {
        cache()->forget('system_settings');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}