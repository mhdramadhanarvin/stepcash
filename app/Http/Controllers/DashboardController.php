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
}
