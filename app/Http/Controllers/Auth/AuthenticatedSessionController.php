<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\StepActivityRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\TokenRepositoryInterface;
use App\Services\GoogleApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    protected $userRepository;
    protected $tokenRepository;
    protected $stepActivityRepository;
    protected $googleApiService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenRepositoryInterface $tokenRepository,
        GoogleApiService $googleApiService,
        StepActivityRepositoryInterface $stepActivityRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->stepActivityRepository = $stepActivityRepository;
        $this->googleApiService = $googleApiService;
    }
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/fitness.activity.read',
                'https://www.googleapis.com/auth/fitness.location.read'
            ])
            ->with(["access_type" => "offline", "prompt" => "consent select_account"])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            DB::beginTransaction();

            $userAuth = Socialite::driver('google')->user();

            $findUser = $this->userRepository->getByGoogleId($userAuth->id);

            $api = $this->googleApiService;
            $api->setAccessToken($userAuth->token);
            $api->setRefreshToken($userAuth->refreshToken);
            $api->setExpirationToken(now()->addSeconds($userAuth->expiresIn));
            if (count($api->getDataSource()) == 0) {
                throw new \Error('Silahkan install Google Fit terlebih dahulu!');
            }

            if ($findUser) {

                $this->tokenRepository->update([
                    'token' => $userAuth->token,
                    'refresh_token' => $userAuth->refreshToken,
                    'expired_at' => now()->addSeconds($userAuth->expiresIn),
                ], $findUser->token->id);

                $this->getStepToday($findUser);

                Auth::login($findUser, remember: true);
                DB::commit();
                return redirect('/dashboard');

            } else {

                $user = $this->userRepository->create([
                    'name' => $userAuth->name,
                    'email' => $userAuth->email,
                    'password' => password_hash('', PASSWORD_BCRYPT),
                    'google_id' => $userAuth->id,
                ]);

                $this->tokenRepository->create($user, [
                    'token' => $userAuth->token,
                    'refresh_token' => $userAuth->refreshToken,
                    'expired_at' => now()->addSeconds($userAuth->expiresIn)
                ]);

                $this->stepActivityRepository->create($user, [
                    'step' => 0,
                    'calory' => 0,
                    'distance' => 0,
                    'time_spent' => 0,
                ]);

                Auth::login($user, remember: true);
                DB::commit();
                return redirect('/dashboard');
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->route('login')->with('status', $e->getMessage());
        }
    }

    public function getStepToday($user)
    {
        $step = $this->stepActivityRepository->getInToday($user->id);
        if (!$step) {
            $this->stepActivityRepository->create($user, [
                'step' => 0,
                'calory' => 0,
                'distance' => 0,
                'time_spent' => 0,
            ]);
        }
    }
}
