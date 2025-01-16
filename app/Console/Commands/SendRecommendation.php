<?php

namespace App\Console\Commands;

use App\Enums\NotificationEnum;
use App\Notifications\UserNotification;
use App\Repositories\StepActivityRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendRecommendation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-recommendation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $stepActivityRepository;
    protected $tokenRepository;
    protected $coinHistoryRepository;
    protected $coinRateRepository;
    protected $userRepository;
    protected $googleApiService;

    /**
     * Execute the console command.
     */

    public function __construct(
        StepActivityRepositoryInterface $stepActivityRepository,
        UserRepositoryInterface $userRepository,
    ) {
        parent::__construct();
        $this->stepActivityRepository = $stepActivityRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Send Recommendation : Starting...');

        DB::beginTransaction();
        try {
            $stepsInToday = $this->stepActivityRepository->getAllTodayNotConvert();

            foreach ($stepsInToday as $step) {
                $user = $this->userRepository->getById($step->user);

                // convert data to coin
                $this->info('Send Recommendation : Collect steps...');
                $achieveTarget = $step->step > $user->step_target;

                if ($achieveTarget) {
                    $user->notify(new UserNotification(
                        NotificationEnum::TARGET_NOT_ACHIEVED,
                        'Target langkah hari ini belum tercapai, ikuti rekomendasi kami untuk kebugaran kamu (dari sistem)',
                        route('recommendation')
                    ));
                }
            }

            $this->info('Send Recommendation : Successfully.');
            DB::commit();
        } catch (\Exception $e) {
            $this->info("Send Recommendation : ERROR");
            Log::error($e->getMessage());
            DB::rollback();
        }
        //
    }
}
