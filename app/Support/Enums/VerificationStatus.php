<?php

namespace App\Support\Enums;

enum VerificationStatus: string
{
    case PENDING   = 'pending';
    case VERIFIED  = 'verified';
    case REJECTED  = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Pending',
            self::VERIFIED => 'Verified',
            self::REJECTED => 'Rejected',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING  => 'bg-warning',
            self::VERIFIED => 'bg-success',
            self::REJECTED => 'bg-danger',
        };
    }
}