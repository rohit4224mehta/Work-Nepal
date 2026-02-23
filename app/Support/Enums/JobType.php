<?php

namespace App\Support\Enums;

enum JobType: string
{
    case FULL_TIME     = 'full_time';
    case PART_TIME     = 'part_time';
    case CONTRACT      = 'contract';
    case INTERNSHIP    = 'internship';
    case FREELANCE     = 'freelance';
    case TEMPORARY     = 'temporary';

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME   => 'Full Time',
            self::PART_TIME   => 'Part Time',
            self::CONTRACT    => 'Contract',
            self::INTERNSHIP  => 'Internship',
            self::FREELANCE   => 'Freelance',
            self::TEMPORARY   => 'Temporary',
        };
    }
}