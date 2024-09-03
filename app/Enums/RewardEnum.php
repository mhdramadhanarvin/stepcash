<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RewardEnum: string implements HasColor, HasLabel
{
    case WaitingApproving = "waiting_approving";
    case Draft = "draft";
    case Publish = "publish";

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Publish => 'success',
            self::WaitingApproving => 'warning',
            self::Draft => 'warning',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Publish => 'Publish',
            self::Draft => 'Draft',
            self::WaitingApproving => 'Menunggu Persetujuan',
        };
    }
}
