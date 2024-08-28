<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/sync', [DashboardController::class, 'sync'])->name('dashboard.sync');
    Route::get('/rewards/getData', [RewardController::class, 'getData'])->name('rewards.get');
    Route::post('/rewards/exchange/{id}', [RewardController::class, 'exchange'])->name('rewards.exchange');
    Route::get('/rewards/claims', [RewardController::class, 'claimsAll'])->name('rewards.claims.index');
    Route::get('/rewards/claimAll', [RewardController::class, 'getDataClaims'])->name('rewards.claims.all');
    Route::get('/rewards/show/{id}', [RewardController::class, 'showGetData'])->name('rewards.show.get');
    Route::resource('rewards', RewardController::class);
    Route::get('/profile/getData', [ProfileController::class, 'getData'])->name('profile.get');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifications/getData', [NotificationController::class, 'getData'])->name('notifications.get');
    Route::post('/notifications/setRead/{id}', [NotificationController::class, 'setRead'])->name('notifications.setRead');
    Route::post('/notifications/setReadAll', [NotificationController::class, 'setReadAll'])->name('notifications.setReadAll');
    Route::resource('notifications', NotificationController::class);
});

require __DIR__.'/auth.php';
