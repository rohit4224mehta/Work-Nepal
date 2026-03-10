<?php

namespace App\Support\Enums;

enum DegreeLevel: string
{
    case SSC = 'SSC';
    case HSC = 'HSC';
    case DIPLOMA = 'Diploma';
    case BACHELOR = 'Bachelor';
    case MASTER = 'Master';
    case PHD = 'PhD';
    case OTHER = 'Other';

    public function label(): string
    {
        return match($this) {
            self::SSC => 'Secondary School Certificate (SSC)',
            self::HSC => 'Higher Secondary Certificate (HSC)',
            self::DIPLOMA => 'Diploma',
            self::BACHELOR => 'Bachelor’s Degree',
            self::MASTER => 'Master’s Degree',
            self::PHD => 'Doctorate (PhD)',
            self::OTHER => 'Other Qualification',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}