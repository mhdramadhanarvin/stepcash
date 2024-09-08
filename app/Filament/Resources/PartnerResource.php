<?php

namespace App\Filament\Resources;

use App\Enums\PartnerEnum;
use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return __('Toko');
    }

    public static function getModelLabel(): string
    {
        return __('Toko');
    }

    public static function form(Form $form): Form
    {
        $user = User::find(Auth::id());
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Toko')->required(),
                TextInput::make('address')->label('Alamat Toko')->required(),
                Select::make('sector')->label('Sektor')->options(PartnerEnum::class)->required(),
                Select::make('is_active')
                    ->label('Status')
                    ->options([
                        '0' => 'Tidak Aktif',
                        '1' => 'Aktif',
                    ])
                    ->visible($user->hasRole('super_admin'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = User::find(Auth::id());
        $isLocked = false;
        if ($user->hasRole('partner')) {
            $isLocked =  $user->partner->is_active;
        }
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Toko'),
                TextColumn::make('address')->label('Alamat Toko')->limit(60),
                TextColumn::make('sector')->label('Sektor')->badge(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => $state == 1 ? 'Aktif' : 'Tidak Aktif')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon($isLocked ? 'heroicon-s-lock-closed' : '')
                    ->disabled($isLocked)
                    ->before(function (Model $record, array $data) {
                        // Runs after the form fields are saved to the database.
                        if ($record->is_active && $data['is_active'] == false) {
                            Notification::make()
                                ->danger()
                                ->title('Perubahan Akun Mitra')
                                ->body('Status akun dinonaktifkan karena alasan keamanan')
                                ->sendToDatabase($record->user);
                        } elseif ($record->is_active == false && $data['is_active']) {
                            Notification::make()
                                ->success()
                                ->title('Perubahan Akun Mitra')
                                ->body('Akun mitra telah berhasil diverifikasi admin')
                                ->sendToDatabase($record->user);
                        }
                    })
                ,
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePartners::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('partner')) {
            return parent::getEloquentQuery()->where('user_id', Auth::id())->latest();
        }
        return parent::getEloquentQuery()->latest();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $user = User::find(Auth::id());
        return $user->hasRole('super_admin') ? static::getModel()::where('is_active', false)->count() : static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $user = User::find(Auth::id());
        return static::getNavigationBadge() && $user->hasRole('super_admin') > 0 ? 'danger' : 'primary';
    }
}
