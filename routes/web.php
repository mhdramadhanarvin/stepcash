<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConsoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});

Route::get('/term-of-service', function () {
    return Inertia::render('TermOfService');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/sync', [DashboardController::class, 'sync'])->name('dashboard.sync');
    Route::get('/rewards/getData', [RewardController::class, 'getData'])->name('rewards.get');
    Route::post('/rewards/exchange/{id}', [RewardController::class, 'exchange'])->name('rewards.exchange');
    Route::get('/rewards/claims/{id?}', [RewardController::class, 'claimsAll'])->name('rewards.claims.index');
    Route::get('/rewards/claimAll', [RewardController::class, 'getDataClaims'])->name('rewards.claims.all');
    Route::get('/rewards/show/{id}', [RewardController::class, 'showGetData'])->name('rewards.show.get');
    Route::resource('rewards', RewardController::class);
    Route::get('/profile/getData', [ProfileController::class, 'getData'])->name('profile.get');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/step', [ProfileController::class, 'updateStep'])->name('profile.step.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifications/getData', [NotificationController::class, 'getData'])->name('notifications.get');
    Route::get('/notifications/getDataUnread', [NotificationController::class, 'getDataUnread'])->name('notifications.getUnread');
    Route::post('/notifications/setRead/{id}', [NotificationController::class, 'setRead'])->name('notifications.setRead');
    Route::post('/notifications/setReadAll', [NotificationController::class, 'setReadAll'])->name('notifications.setReadAll');
    Route::resource('notifications', NotificationController::class);
    Route::get('/recommendation', [DashboardController::class, 'recommendation'])->name('recommendation');
});

Route::get('/run-command/{name_of_command}', [ConsoleController::class, 'index']);

require __DIR__.'/auth.php';
