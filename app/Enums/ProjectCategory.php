<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProjectCategory: string implements HasLabel
{
    case INFRASTRUCTURE = 'infrastructure';
    case EDUCATION = 'education';
    case HEALTH = 'health';
    case AGRICULTURE = 'agriculture';
    case TECHNOLOGY = 'technology';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::INFRASTRUCTURE => 'Infrastructure',
            self::EDUCATION => 'Education',
            self::HEALTH => 'Health',
            self::AGRICULTURE => 'Agriculture',
            self::TECHNOLOGY => 'Technology',
            self::OTHER => 'Other',
        };
    }

    public static function getOptions(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn(self $case) => $case->getLabel(), self::cases())
        );
    }
}
