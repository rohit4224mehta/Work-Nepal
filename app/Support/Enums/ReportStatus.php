<?php

namespace App\Support\Enums;

enum ReportStatus: string
{
    case OPEN       = 'open';
    case UNDER_REVIEW = 'under_review';
    case RESOLVED   = 'resolved';
    case REJECTED   = 'rejected';   // report found invalid

    public function label(): string
    {
        return match ($this) {
            self::OPEN        => 'Open',
            self::UNDER_REVIEW => 'Under Review',
            self::RESOLVED    => 'Resolved',
            self::REJECTED    => 'Rejected',
        };
    }
}