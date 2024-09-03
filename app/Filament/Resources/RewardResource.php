<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardResource\Pages;
use App\Filament\Resources\RewardResource\RelationManagers;
use App\Models\CoinRate;
use App\Models\Reward;
use App\Models\User;
use App\Repositories\CoinRateRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
                    ->numeric()
                    ->required(),
                Textarea::make('description')
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->helperText('1 coin ~= Rp. ' . $coinRate->rupiah / $coinRate->coin)
                    ->required(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                Hidden::make('partner_id')->default($user->partner->id)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = User::find(Auth::id());
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama Produk'),
                TextColumn::make('quantity')->numeric(),
                TextColumn::make('price')
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
                Tables\Actions\EditAction::make()->icon(!$user->partner->is_active ? 'heroicon-s-lock-closed' : '')->disabled(!$user->partner->is_active),
                Tables\Actions\DeleteAction::make()->icon(!$user->partner->is_active ? 'heroicon-s-lock-closed' : '')->disabled(!$user->partner->is_active),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->icon(!$user->partner->is_active ? 'heroicon-s-lock-closed' : '')->disabled(!$user->partner->is_active),
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
            return parent::getEloquentQuery()->where('partner_id', $user->partner->id);
        }
        return parent::getEloquentQuery();
    }
}
