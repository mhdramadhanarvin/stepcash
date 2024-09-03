<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RewardClaimEnum: string implements HasColor, HasLabel
{
    case WAITING_CONFIRMATION = "waiting_confirmation";
    case ON_PROGRESS = "on_progress";
    case READY_TO_PICKUP = "ready_to_pickup";
    case DONE = "done";
    case REJECTED = "rejected";

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::WAITING_CONFIRMATION => 'warning',
            self::ON_PROGRESS => 'primary',
            self::READY_TO_PICKUP => 'success',
            self::DONE => 'secondary',
            self::REJECTED => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WAITING_CONFIRMATION => 'Menunggu Konfirmasi',
            self::ON_PROGRESS => 'SEDANG DIPROSES',
            self::READY_TO_PICKUP => 'SIAP DIAMBIL',
            self::DONE => 'SELESAI',
            self::REJECTED => 'DIBATALKAN',
        };
    }
}
