<?php

namespace App\Support\Enums;

enum AccountStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case DELETED = 'deleted'; // soft-deleted is separate, this is explicit permanent deletion flag

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE    => 'Active',
            self::SUSPENDED => 'Suspended',
            self::DELETED   => 'Deleted',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}