<?php

namespace App\Filament\Resources\RewardClaimResource\Pages;

use App\Filament\Resources\RewardClaimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRewardClaims extends ListRecords
{
    protected static string $resource = RewardClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
