<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\StepActivityRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchGoogleFit implements ShouldQueue
{
    use Queueable;
    use Dispatchable;

    public $uniqueFor = 60;

    public function __construct(
        public User $user,
    ) {
    }

    public function handle(GoogleApiService $googleApiService, StepActivityRepositoryInterface $stepActivityRepository): void
    {
        Log::info("Queue FetchGoogleFit: STARTING..");
        $data = $googleApiService->syncData($this->user);
        $step = $stepActivityRepository->getInToday($this->user->id);

        DB::transaction(function () use ($stepActivityRepository, $step, $data) {

            if (!$step) {
                $stepActivityRepository->create($this->user, [
                    'step' => $data['steps'],
                    'calory' => $data['calories'],
                    'distance' => number_format($data['distances'], 3),
                    'time_spent' => $data['time_spent'],
                ]);
            } else {
                $stepActivityRepository->update([
                    'step' => $data['steps'],
                    'calory' => $data['calories'],
                    'distance' => number_format($data['distances'], 3),
                    'time_spent' => $data['time_spent'],
                ], $step->id);
            }
            Log::debug(json_encode($data));
            Log::info("Queue FetchGoogleFit: DONE..");
        }, 5);
    }
}
