<?php

namespace App\Http\Controllers;

use App\Jobs\FetchGoogleFit;
use App\Repositories\StepActivityRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $stepActivityRepository;
    protected $googleApiService;

    public function __construct(
        GoogleApiService $googleApiService,
        StepActivityRepositoryInterface $stepActivityRepository
    ) {
        $this->stepActivityRepository = $stepActivityRepository;
        $this->googleApiService = $googleApiService;
    }

    public function index()
    {
        return Inertia::render('Dashboard');
    }

    public function sync()
    {
        $user = Auth::user();
        FetchGoogleFit::dispatch($user);
        Log::info("Running queue...");
        return $this->stepActivityRepository->getInToday($user->id);
    }

    public function recommendation()
    {
        return Inertia::render('Recommendation', [
            'recommendation' => [
                [
                    'name' => 'Push Up 10x',
                    'description' => 'Lakukan push up sebanyak 10x',
                    'thumbnail' => asset('images/push-up.gif')
                ],
                [
                    'name' => 'Plank Selama 15 detik',
                    'description' => 'Lakukan plank selama 15 detik',
                    'thumbnail' => asset('images/plank.gif')
                ]
            ]
        ]);
    }

}
