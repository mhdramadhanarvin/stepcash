<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use App\Models\RewardClaim;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userCount = User::count();
        $partnerCount = Partner::count();
        return [
            Stat::make('Total User dan Mitra', $userCount . ' User : ' . $partnerCount. ' Mitra')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Jumlah Koin Diberikan', User::sum('coin') / 1000 . "k")
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Jumlah Koin Ditukarkan', RewardClaim::sum('price') / 1000 . 'k')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
        ];
    }
}
