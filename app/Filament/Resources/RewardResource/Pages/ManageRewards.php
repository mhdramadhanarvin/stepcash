<?php

namespace App\Filament\Resources\RewardResource\Pages;

use App\Filament\Resources\RewardResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ManageRewards extends ManageRecords
{
    protected static string $resource = RewardResource::class;

    protected function getHeaderActions(): array
    {
        $user = User::find(Auth::id());
        $isLocked = false;
        if ($user->hasRole('partner')) {
            $isLocked =  $user->partner->is_active == false;
        }
        return [
            Actions\CreateAction::make()
                ->icon($isLocked || $user->hasRole('super_admin') ? 'heroicon-s-lock-closed' : '')
                ->disabled($isLocked || $user->hasRole('super_admin'))
                ->after(function (Model $record) {
                    // Runs after the form fields are saved to the database.
                    Notification::make()
                        ->info()
                        ->title('Penambahan Produk Hadiah')
                        ->body('Ada produk hadiah baru ditambahkan oleh mitra ' . $record->partner->name . ' harap segera diproses!')
                        ->actions([
                            Action::make('lihat')
                                ->button()
                                ->url(RewardResource::getUrl('index')),
                        ])
                        ->sendToDatabase(User::find(1));
                })
            ,
        ];
    }
}
