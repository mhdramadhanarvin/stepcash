<?php

namespace App\Filament\Resources;

use App\Enums\PartnerEnum;
use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Toko')->required(),
                Select::make('sector')->label('Sektor')->options(PartnerEnum::class)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Toko'),
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
                Tables\Actions\EditAction::make(),
                /*Tables\Actions\DeleteAction::make(),*/
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            return parent::getEloquentQuery()->where('user_id', Auth::id());
        }
        return parent::getEloquentQuery();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
