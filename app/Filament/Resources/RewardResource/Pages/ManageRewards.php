<?php

namespace App\Filament\Resources\RewardResource\Pages;

use App\Filament\Resources\RewardResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageRewards extends ManageRecords
{
    protected static string $resource = RewardResource::class;

    protected function getHeaderActions(): array
    {
        $user = User::find(Auth::id());
        return [
            Actions\CreateAction::make()->icon(!$user->partner->is_active ? 'heroicon-s-lock-closed' : '')->disabled(!$user->partner->is_active),
        ];
    }
}
