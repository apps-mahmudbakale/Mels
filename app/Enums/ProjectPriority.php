<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProjectPriority: string implements HasLabel
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
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
