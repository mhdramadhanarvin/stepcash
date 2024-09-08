<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PartnerEnum: string implements HasColor, HasLabel
{
    case F_AND_B = "food_and_bevarage";
    case SPORTS = "sports";
    case OTHER = "other";

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::F_AND_B => 'success',
            self::SPORTS => 'primary',
            self::OTHER => 'secondary',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::F_AND_B => 'Makanan dan Minuman',
            self::SPORTS => 'Olahraga',
            self::OTHER => 'Lainnya',
        };
    }
}
