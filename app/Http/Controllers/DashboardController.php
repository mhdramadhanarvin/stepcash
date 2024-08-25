<?php

namespace App\Http\Controllers;

use App\Jobs\FetchGoogleFit;
use App\Repositories\StepActivityRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $target = 10000;

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
        $step = $this->stepActivityRepository->getInToday(Auth::id());
        FetchGoogleFit::dispatch(Auth::user());

        return Inertia::render('Dashboard', [
            'tracker' => [
                'step' => $step->step,
                'calory' => $step->calory,
                'time_spent' => $step->time_spent,
                'distance' => $step->distance,
                'target' => $this->target,
            ]
        ]);
    }

    public function sync()
    {
        $user = Auth::user();
        FetchGoogleFit::dispatch($user);
        return $this->stepActivityRepository->getInToday($user->id);
    }
}
