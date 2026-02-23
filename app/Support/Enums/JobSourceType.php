<?php

namespace App\Support\Enums;

enum JobSourceType: string
{
    case LOCAL   = 'local';
    case FOREIGN = 'foreign';

    public function label(): string
    {
        return match ($this) {
            self::LOCAL   => 'Local (Nepal)',
            self::FOREIGN => 'Foreign Employment',
        };
    }

    public function requiresSafetyNotice(): bool
    {
        return $this === self::FOREIGN;
    }
}