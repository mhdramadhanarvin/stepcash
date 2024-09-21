<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\StepActivityRepositoryInterface;
use App\Repositories\TokenRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchGoogleFit implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    use Dispatchable;

    public $uniqueFor = 60;

    public function __construct(
        public User $user,
    ) {
    }

    public function uniqueId(): string
    {
        return $this->user->id;
    }

    public function uniqueVia(): Repository
    {
        return Cache::driver('redis');
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->user->id))->releaseAfter(60)];
    }

    public function handle(GoogleApiService $googleApiService, StepActivityRepositoryInterface $stepActivityRepository): void
    {
        DB::transaction(function () use ($googleApiService, $stepActivityRepository) {
            Log::info("Queue FetchGoogleFit: STARTING..");
            $data = $googleApiService->syncData($this->user);
            $step = $stepActivityRepository->getInToday($this->user->id);

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
