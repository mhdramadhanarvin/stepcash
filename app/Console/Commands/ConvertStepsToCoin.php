<?php

namespace App\Console\Commands;

use App\Enums\NotificationEnum;
use Illuminate\Console\Command;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\CoinHistoryRepositoryInterface;
use App\Repositories\CoinRateRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\StepActivityRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ConvertStepsToCoin extends Command
{
    protected $signature = 'app:convert-steps-to-coin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert user steps to coin';

    protected $stepActivityRepository;
    protected $tokenRepository;
    protected $coinHistoryRepository;
    protected $coinRateRepository;
    protected $userRepository;
    protected $notificationRepository;
    protected $googleApiService;

    /**
     * Execute the console command.
     */

    public function __construct(
        StepActivityRepositoryInterface $stepActivityRepository,
        TokenRepositoryInterface $tokenRepository,
        UserRepositoryInterface $userRepository,
        CoinHistoryRepositoryInterface $coinHistoryRepository,
        CoinRateRepositoryInterface $coinRateRepository,
        NotificationRepositoryInterface $notificationRepository,
        GoogleApiService $googleApiService,
    ) {
        parent::__construct();
        $this->stepActivityRepository = $stepActivityRepository;
        $this->tokenRepository = $tokenRepository;
        $this->coinHistoryRepository = $coinHistoryRepository;
        $this->coinRateRepository = $coinRateRepository;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        $this->googleApiService = $googleApiService;
    }

    public function handle()
    {
        $this->info('Starting...');

        DB::beginTransaction();
        try {
            $stepsInToday = $this->stepActivityRepository->getAllTodayNotConvert();
            $coinRate = $this->coinRateRepository->getById(1);
            $limitStep = 10000;

            foreach ($stepsInToday as $step) {
                $user = $step->user;

                // convert data to coin
                $this->info('Convert Steps to Coin...');
                $stepCanConvert = $step->step > $limitStep ? $limitStep : $step->step;

                $coinGet = round(($stepCanConvert / $coinRate->step) * $coinRate->coin, 1);

                $this->coinHistoryRepository->create($user, [
                    'coin' => $coinGet,
                    'type' => 'add',
                    'description' => "Converted $stepCanConvert steps"
                ]);
                $this->userRepository->addCoin($user, $coinGet);
                $this->stepActivityRepository->update([
                    "is_convert" => true
                ], $step->id);
                $this->coinRateRepository->update(['coin_balance' => $coinRate->coin_balance - $coinGet], 1);
                $this->notificationRepository->create($user, [
                    'title' => NotificationEnum::COIN_CONVERT,
                    'message' => 'Konversi otomatis ' . $stepCanConvert . ' langkah dengan ' .$coinGet .' koin'
                ]);
            }

            $this->info('Successfully.');
            DB::commit();
        } catch (\Exception $e) {
            $this->info("ERROR");
            Log::error($e->getMessage());
            DB::rollback();
        }
    }
}
