<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\StepActivityRepositoryInterface;
use App\Repositories\TokenRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchGoogleFit implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleApiService $googleApiService, StepActivityRepositoryInterface $stepActivityRepository): void
    {
        DB::beginTransaction();
        Log::info("Queue FetchGoogleFit: STARTING..");
        try {
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
            DB::commit();
            Log::debug(json_encode($data));
            Log::info("Queue FetchGoogleFit: DONE..");
        } catch (\Throwable $e) {
            Log::error("Queue FetchGoogleFit: " . $e->getMessage());
            DB::rollBack();
        }
    }
}
