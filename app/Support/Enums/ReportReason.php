<?php

namespace App\Support\Enums;

enum ReportReason: string
{
    case FAKE_JOB           = 'fake_job';
    case SPAM               = 'spam';
    case INAPPROPRIATE      = 'inappropriate';
    case FRAUD              = 'fraud';
    case LOW_SALARY_MISLEAD = 'low_salary_mislead';
    case OTHER              = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FAKE_JOB           => 'Fake or Non-existent Job',
            self::SPAM               => 'Spam or Promotional Content',
            self::INAPPROPRIATE      => 'Inappropriate / Offensive Content',
            self::FRAUD              => 'Fraudulent / Scam',
            self::LOW_SALARY_MISLEAD => 'Misleading Salary Information',
            self::OTHER              => 'Other',
        };
    }
}