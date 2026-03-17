<?php
// app/Services/ActivityLogger.php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    protected $user = null;
    protected $admin = null;
    protected $action = null;
    protected $description = null;
    protected $subject = null;
    protected $properties = [];
    protected $level = ActivityLog::LEVEL_INFO;

    /**
     * Set the user who performed the action.
     */
    public function by(User $user): self
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            $this->admin = $user;
        } else {
            $this->user = $user;
        }
        return $this;
    }

    /**
     * Set the admin who performed the action.
     */
    public function byAdmin(User $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Set the user who is affected by the action.
     */
    public function on(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the action type.
     */
    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Set the description.
     */
    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the subject (model) being acted upon.
     */
    public function subject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Add additional properties.
     */
    public function withProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    /**
     * Add a single property.
     */
    public function withProperty(string $key, $value): self
    {
        $this->properties[$key] = $value;
        return $this;
    }

    /**
     * Set the log level.
     */
    public function level(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Set as warning level.
     */
    public function warning(): self
    {
        $this->level = ActivityLog::LEVEL_WARNING;
        return $this;
    }

    /**
     * Set as danger level.
     */
    public function danger(): self
    {
        $this->level = ActivityLog::LEVEL_DANGER;
        return $this;
    }

    /**
     * Set as critical level.
     */
    public function critical(): self
    {
        $this->level = ActivityLog::LEVEL_CRITICAL;
        return $this;
    }

    /**
     * Log the activity.
     */
    public function log(): ActivityLog
    {
        $data = [
            'user_id' => $this->user?->id,
            'admin_id' => $this->admin?->id ?? auth()->id(),
            'action' => $this->action,
            'description' => $this->description,
            'subject_type' => $this->subject ? get_class($this->subject) : null,
            'subject_id' => $this->subject?->id,
            'properties' => array_merge($this->properties, $this->getRequestMetadata()),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'level' => $this->level,
            'timestamp' => now(),
        ];

        return ActivityLog::create($data);
    }

    /**
     * Get request metadata.
     */
    protected function getRequestMetadata(): array
    {
        return [
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'input' => $this->sanitizeInput(Request::except(['_token', 'password', 'password_confirmation'])),
        ];
    }

    /**
     * Sanitize input data (remove sensitive info, truncate long values).
     */
    protected function sanitizeInput(array $input): array
    {
        array_walk_recursive($input, function (&$value) {
            if (is_string($value) && strlen($value) > 500) {
                $value = substr($value, 0, 500) . '... [truncated]';
            }
        });
        return $input;
    }

    /**
     * Quick method to log admin action.
     */
    public static function adminAction(string $action, string $description, $subject = null): ActivityLog
    {
        return (new static)
            ->byAdmin(auth()->user())
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->log();
    }

    /**
     * Quick method to log user action.
     */
    public static function userAction(User $user, string $action, string $description, $subject = null): ActivityLog
    {
        return (new static)
            ->on($user)
            ->action($action)
            ->description($description)
            ->subject($subject)
            ->log();
    }

    /**
     * Quick method to log login.
     */
    public static function login(User $user, bool $success = true): ActivityLog
    {
        return (new static)
            ->on($user)
            ->action(ActivityLog::ACTION_LOGIN)
            ->description($success ? 'User logged in successfully' : 'Failed login attempt')
            ->level($success ? ActivityLog::LEVEL_INFO : ActivityLog::LEVEL_WARNING)
            ->withProperty('success', $success)
            ->log();
    }

    /**
     * Quick method to log logout.
     */
    public static function logout(User $user): ActivityLog
    {
        return (new static)
            ->on($user)
            ->action(ActivityLog::ACTION_LOGOUT)
            ->description('User logged out')
            ->log();
    }

    /**
     * Quick method to log suspension.
     */
    public static function suspend(User $admin, User $target, string $reason): ActivityLog
    {
        return (new static)
            ->byAdmin($admin)
            ->on($target)
            ->action(ActivityLog::ACTION_SUSPEND)
            ->description("Admin #{$admin->id} suspended user #{$target->id}")
            ->withProperty('reason', $reason)
            ->warning()
            ->log();
    }

    /**
     * Quick method to log deletion.
     */
    public static function delete(User $admin, $subject, string $type): ActivityLog
    {
        return (new static)
            ->byAdmin($admin)
            ->action(ActivityLog::ACTION_DELETE)
            ->description("Admin #{$admin->id} deleted {$type} #{$subject->id}")
            ->subject($subject)
            ->danger()
            ->log();
    }
}