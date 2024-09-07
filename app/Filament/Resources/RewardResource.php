<?php

namespace App\Filament\Resources;

use App\Enums\RewardEnum;
use App\Filament\Resources\RewardResource\Pages;
use App\Filament\Resources\RewardResource\RelationManagers;
use App\Models\CoinRate;
use App\Models\Reward;
use App\Models\User;
use App\Repositories\CoinRateRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RewardResource extends Resource
{
    protected static $rewardRepository;
    protected static $coinRateRepository;

    protected static ?string $model = Reward::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return __('Produk');
    }

    public static function getModelLabel(): string
    {
        return __('Produk');
    }

    public function __construct(
        RewardRepositoryInterface $rewardRepository,
        CoinRateRepositoryInterface $coinRateRepository,
    ) {
        self::$rewardRepository = $rewardRepository;
        self::$coinRateRepository = $coinRateRepository;
    }

    public static function form(Form $form): Form
    {
        $coinRate = CoinRate::find(1);
        $user = User::find(Auth::id());

        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Nama Produk')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Stok')
                    ->numeric()
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required(),
                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->helperText('1 coin ~= Rp. ' . $coinRate->rupiah / $coinRate->coin)
                    ->required(),
                FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                Select::make('status')
                    ->options(RewardEnum::class)
                    ->hidden($user->hasRole('partner')),
                Hidden::make('partner_id')
                    ->default(fn ($record) => $user->partner->id ?? $record->partner_id)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = User::find(Auth::id());
        $isLocked = false;
        if ($user->hasRole('partner')) {
            $isLocked =  $user->partner->is_active == false;
        }
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama Produk')->searchable(),
                TextColumn::make('quantity')
                    ->label('Stok')
                    ->numeric(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->suffix(function (TextColumn $column): ?string {
                        $coinRate = CoinRate::find(1);
                        $state = $column->getState();

                        return ' Coin ~= Rp. ' . $state * ($coinRate->rupiah / $coinRate->coin);
                    }),
                TextColumn::make('status')->badge(),
                ImageColumn::make('thumbnail'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon($isLocked ? 'heroicon-s-lock-closed' : '')
                    ->disabled($isLocked),
                /*Tables\Actions\DeleteAction::make()*/
                /*    ->icon(fn ($record) => $isLocked || $record->status != RewardEnum::WaitingApproving ? 'heroicon-s-lock-closed' : '')*/
                /*    ->disabled(fn ($record) => $isLocked || $record->status != RewardEnum::WaitingApproving),*/
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon($isLocked ? 'heroicon-s-lock-closed' : '')
                        ->disabled($isLocked),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRewards::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('partner')) {
            return parent::getEloquentQuery()
                ->where('partner_id', $user->partner->id)->latest();
        }
        return parent::getEloquentQuery()->latest();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', RewardEnum::WaitingApproving)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'danger' : 'primary';
    }
}
