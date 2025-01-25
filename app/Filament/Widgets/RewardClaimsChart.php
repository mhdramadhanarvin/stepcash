<?php

namespace App\Filament\Widgets;

use App\Models\StepActivity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RewardClaimsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Langkah Harian User';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';


    protected function getData(): array
    {
        $data = StepActivity::selectRaw('DATE(created_at) AS day, SUM(step) AS total_steps, SUM(step) / 1000 AS total_coins')
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderByDesc('day')
        ->limit(30)
        ->get();
        $days = $data->pluck('day')->toArray();
        $totalSteps = $data->pluck('total_steps')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Langkah',
                    'data' => $totalSteps,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
