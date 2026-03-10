<?php

namespace App\Support\Enums;

enum ExperienceLevel: string
{
    case FRESHER = 'fresher';
    case JUNIOR = 'junior';
    case MID_LEVEL = 'mid-level';
    case SENIOR = 'senior';
    case LEAD = 'lead';
    case MANAGER = 'manager';
    case EXECUTIVE = 'executive';

    public function label(): string
    {
        return match($this) {
            self::FRESHER => 'Fresher / 0–1 year',
            self::JUNIOR => 'Junior (1–3 years)',
            self::MID_LEVEL => 'Mid-level (3–6 years)',
            self::SENIOR => 'Senior (6–10 years)',
            self::LEAD => 'Lead / Team Lead (8+ years)',
            self::MANAGER => 'Managerial',
            self::EXECUTIVE => 'Executive / Director',
        };
    }
}