<?php

namespace App\Support\Enums;

enum CompanyRole: string
{
    case OWNER       = 'owner';
    case ADMIN       = 'admin';
    case RECRUITER   = 'recruiter';
    case HR_MANAGER  = 'hr_manager';
    case VIEWER      = 'viewer';     // can view but not edit

    public function label(): string
    {
        return match ($this) {
            self::OWNER       => 'Owner',
            self::ADMIN       => 'Admin',
            self::RECRUITER   => 'Recruiter',
            self::HR_MANAGER  => 'HR Manager',
            self::VIEWER      => 'Viewer',
        };
    }

    public function canManageJobs(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN, self::RECRUITER]);
    }

    public function canManageMembers(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN]);
    }
}