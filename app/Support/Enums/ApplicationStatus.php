<?php

namespace App\Support\Enums;

enum ApplicationStatus: string
{
    case APPLIED     = 'applied';
    case VIEWED      = 'viewed';
    case SHORTLISTED = 'shortlisted';
    case REJECTED    = 'rejected';
    case HIRED       = 'hired';
    case WITHDRAWN   = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::APPLIED     => 'Applied',
            self::VIEWED      => 'Viewed',
            self::SHORTLISTED => 'Shortlisted',
            self::REJECTED    => 'Rejected',
            self::HIRED       => 'Hired',
            self::WITHDRAWN   => 'Withdrawn',
        };
    }

    public function canChangeByEmployer(): bool
    {
        return in_array($this, [self::APPLIED, self::VIEWED, self::SHORTLISTED]);
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::REJECTED, self::HIRED, self::WITHDRAWN]);
    }
}