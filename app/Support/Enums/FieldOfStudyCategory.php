<?php

namespace App\Support\Enums;

enum FieldCategory: string
{
    case ENGINEERING = 'Engineering';
    case COMPUTER_SCIENCE = 'Computer Science & IT';
    case MANAGEMENT = 'Management / MBA';
    case COMMERCE = 'Commerce / Finance';
    case SCIENCE = 'Science';
    case ARTS = 'Arts / Humanities';
    case MEDICAL = 'Medical / Healthcare';
    case LAW = 'Law';
    case DESIGN = 'Design / Creative';
    case OTHER = 'Other';

    public function label(): string
    {
        return $this->value;
    }
}