<?php

namespace App\Support\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case CONTRACT = 'contract';
    case TEMPORARY = 'temporary';
    case FREELANCE = 'freelance';
    case INTERNSHIP = 'internship';

    public function label(): string
    {
        return match($this) {
            self::FULL_TIME => 'Full Time',
            self::PART_TIME => 'Part Time',
            self::CONTRACT => 'Contract',
            self::TEMPORARY => 'Temporary',
            self::FREELANCE => 'Freelance',
            self::INTERNSHIP => 'Internship',
        };
    }
}