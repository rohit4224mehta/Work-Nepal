<?php

namespace App\Support\Enums;

enum JobStatus: string
{
    case DRAFT     = 'draft';
    case PENDING   = 'pending';     // waiting for admin approval
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case ACTIVE    = 'active';
    case CLOSED    = 'closed';      // manually closed by employer
    case EXPIRED   = 'expired';     // auto after expiry date

    public function label(): string
    {
        return match ($this) {
            self::DRAFT    => 'Draft',
            self::PENDING  => 'Pending Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::ACTIVE   => 'Active',
            self::CLOSED   => 'Closed',
            self::EXPIRED  => 'Expired',
        };
    }

    public function isPubliclyVisible(): bool
    {
        return $this === self::ACTIVE || $this === self::APPROVED;
    }

    public function canBeEditedByEmployer(): bool
    {
        return in_array($this, [self::DRAFT, self::PENDING, self::ACTIVE]);
    }
}