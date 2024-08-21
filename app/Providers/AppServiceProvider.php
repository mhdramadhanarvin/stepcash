<?php

namespace App\Providers;

use App\Repositories\TokenRepository;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\StepActivityRepository;
use App\Repositories\StepActivityRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->bind(StepActivityRepositoryInterface::class, StepActivityRepository::class);
        /*$this->app->bind(CoinActivityRepositoryInterface::class, CoinActivityRepository::class);*/
        /*$this->app->bind(RewardRepositoryInterface::class, RewardRepository::class);*/
        /*$this->app->bind(BonusRepositoryInterface::class, BonusRepository::class);*/
        /*$this->app->bind(BonusHistoryRepositoryInterface::class, BonusHistoryRepository::class);*/
        /*$this->app->bind(CoinRateRepositoryInterface::class, CoinRateRepository::class);*/
        /**/
        $this->app->bind(GoogleApiService::class, function ($app) {
            return new GoogleApiService($app->make(TokenRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
