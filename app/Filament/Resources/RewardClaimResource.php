<?php

namespace App\Filament\Resources;

use App\Enums\RewardClaimEnum;
use App\Filament\Resources\RewardClaimResource\Pages;
use App\Filament\Resources\RewardClaimResource\RelationManagers;
use App\Models\CoinRate;
use App\Models\RewardClaim;
use App\Models\User;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RewardClaimResource extends Resource
{
    protected static ?string $model = RewardClaim::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return __('Riwayat Penukaran');
    }

    public static function getModelLabel(): string
    {
        return __('Riwayat Penukaran');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Tanggal Penukaran')->dateTime("d M Y H:i:s"),
                TextColumn::make('reward.title')->label('Nama Produk'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->suffix(function (TextColumn $column): ?string {
                        $coinRate = CoinRate::find(1);
                        $state = $column->getState();

                        return ' Coin ~= Rp. ' . $state * ($coinRate->rupiah / $coinRate->coin);
                    }),
                TextColumn::make('status')->badge()
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->icon('heroicon-c-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status == RewardClaimEnum::WAITING_CONFIRMATION)
                    ->requiresConfirmation()
                    /*->action(fn (RewardClaim $record) => (new PekanEsportController())->approve($record)),*/
                /*Action::make('reject')*/
                /*    ->icon('heroicon-c-x-mark')*/
                /*    ->color('danger')*/
                /*    ->form([*/
                /*        TextInput::make('reason')*/
                /*            ->label('Alasan Penolakan')*/
                /*            ->required(),*/
                /*    ])*/
                /*    ->requiresConfirmation()*/
                /*    ->visible(fn ($record) => $record->status == PekanEsportStatusEnum::WAITING_CONFIRMATION && $record->trashed() != true)*/
                /*    ->action(fn (array $data, PekanEsport $record) => (new PekanEsportController())->reject($record, $data)),*/
                /*Tables\Actions\EditAction::make(),*/
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
            'index' => Pages\ManageRewardClaims::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('partner')) {
            return parent::getEloquentQuery()->whereIn('reward_id', $user->partner->rewards()->pluck('id'))->latest();
        }
        return parent::getEloquentQuery()->latest();
    }
}
