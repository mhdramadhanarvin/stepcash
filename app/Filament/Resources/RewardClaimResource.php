<?php

namespace App\Filament\Resources;

use App\Enums\NotificationEnum;
use App\Enums\RewardClaimEnum;
use App\Filament\Resources\RewardClaimResource\Pages;
use App\Filament\Resources\RewardClaimResource\RelationManagers;
use App\Models\CoinRate;
use App\Models\RewardClaim;
use App\Models\User;
use App\Notifications\ExchangeRewardProcess;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions\Action as ActionInfolist;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Tables\Columns\ImageColumn;

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
                TextColumn::make('code')->label('Kode Penukaran')->searchable(),
                ImageColumn::make('reward.thumbnail')->label('Thumbnail'),
                TextColumn::make('reward.title')->label('Nama Produk')->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->suffix(function (TextColumn $column): ?string {
                        $coinRate = CoinRate::find(1);
                        $state = $column->getState();

                        return ' Coin ~= Rp. ' . $state * ($coinRate->rupiah / $coinRate->coin);
                    }),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->label('Tanggal Penukaran')->dateTime("d M Y H:i:s"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Grid::make()->columns(4)->schema([
                    TextEntry::make('status')->badge(),
                    TextEntry::make('code')->label('Kode Penukaran')->badge(),
                    TextEntry::make('reason_rejection')
                        ->label('Alasan Pembatalan')
                        ->visible(fn ($record) => $record->status == RewardClaimEnum::REJECTED)
                        ->badge()
                        ->color('gray'),
                    TextEntry::make('created_at')->label('Tanggal Permintaan')->dateTimeTooltip(),
                ]),
                Section::make('Informasi Pengguna')->columns(2)->schema([
                    TextEntry::make('user.name')->label('Nama Pemesan'),
                    TextEntry::make('user.email')->label('Email Pemesan'),
                ]),
                Section::make('Informasi Produk')->columns(4)->schema([
                    ImageEntry::make('reward.thumbnail')
                        ->height(60)->width(60)
                        ->label(''),
                    TextEntry::make('reward.title')->label('Nama Produk Hadiah'),
                    TextEntry::make('price')->label('Harga Saat Permintaan')
                        ->suffix(function (TextEntry $column): ?string {
                            $coinRate = CoinRate::find(1);
                            $state = $column->getState();

                            return ' Coin ~= Rp. ' . $state * ($coinRate->rupiah / $coinRate->coin);
                        }),
                ]),
                Actions::make([
                    ActionInfolist::make('approve')
                        ->label('Siapkan Hadiah')
                        ->icon('heroicon-c-check')
                        ->color('success')
                        ->visible(fn ($record) => $record->status == RewardClaimEnum::WAITING_CONFIRMATION)
                        ->action(function ($record) {
                            $record->update(['status' => RewardClaimEnum::ON_PROGRESS]);
                            $record->user->notify(new ExchangeRewardProcess(
                                NotificationEnum::getValue('EXCHANGE_ON_PROGRESS'),
                                'Permintaan penukaran '. $record->reward->title .' diterima, saat ini sedang disiapkan',
                                route('rewards.claims.index', ['id' => $record->id])
                            ));
                        })
                        ->requiresConfirmation(),
                    ActionInfolist::make('reject')
                        ->label("Tolak Permintaan")
                        ->icon('heroicon-c-x-mark')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status == RewardClaimEnum::WAITING_CONFIRMATION)
                        ->form([
                            TextInput::make('reason_rejection')->label('Alasan Penolakan')->required(),
                        ])
                        ->action(function (array $data, $record) {
                            $record->update(['status' => RewardClaimEnum::REJECTED, 'reason_rejection' => $data['reason_rejection']]);
                            $record->reward->update(['quantity' => $record->reward->quantity + 1]);
                            $user = User::find($record->user_id);
                            $user->update(['coin' => $user->coin + $record->price]);
                            $record->user->notify(new ExchangeRewardProcess(
                                NotificationEnum::getValue('EXCHANGE_CANCELED'),
                                'Permintaan penukaran '. $record->reward->title .' ditolak karena alasan ' . $data['reason_rejection'],
                                route('rewards.claims.index', ['id' => $record->id])
                            ));
                        })
                        ->requiresConfirmation(),
                    ActionInfolist::make('delivery')
                        ->label('Hadiah Siap Diambil')
                        ->color('success')
                        ->visible(fn ($record) => $record->status == RewardClaimEnum::ON_PROGRESS)
                        ->action(function ($record) {
                            $record->update(['status' => RewardClaimEnum::READY_TO_PICKUP]);
                            $record->user->notify(new ExchangeRewardProcess(
                                NotificationEnum::getValue('EXCHANGE_READY_TO_PICKUP'),
                                'Hadiah '. $record->reward->title .' siap diambil',
                                route('rewards.claims.index', ['id' => $record->id])
                            ));
                        })
                        ->requiresConfirmation(),
                    ActionInfolist::make('done')
                        ->label('Selesaikan Permintaan')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->status == RewardClaimEnum::READY_TO_PICKUP)
                        ->action(function ($record) {
                            $record->update(['status' => RewardClaimEnum::DONE]);
                            $record->user->notify(new ExchangeRewardProcess(
                                NotificationEnum::getValue('EXCHANGE_DONE'),
                                'Hadiah telah diambil dan permintaan penukaran '. $record->reward->title .' diselesaikan',
                                route('rewards.claims.index', ['id' => $record->id])
                            ));
                        })
                        ->requiresConfirmation(),
                ])->fullWidth(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewardClaims::route('/'),
            'view' => Pages\ViewRewardClaims::route('/{record}'),
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
            $query = parent::getEloquentQuery()->whereIn('reward_id', $user->partner->rewards()->pluck('id'));
        } else {
            $query = parent::getEloquentQuery();
        }
        return $query->latest();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', RewardClaimEnum::WAITING_CONFIRMATION)->count();
    }
}
