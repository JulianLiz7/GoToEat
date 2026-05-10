<?php

use App\Domains\Auth\Controllers\Web\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard: enruta al panel según el rol
Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

// Onboarding de restaurante (solo admins autenticados sin restaurante)
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/step-1',  [OnboardingController::class, 'step1'])->name('step1');
    Route::post('/step-1', [OnboardingController::class, 'storeStep1'])->name('step1.store');
    Route::get('/step-2',  [OnboardingController::class, 'step2'])->name('step2');
    Route::post('/step-2', [OnboardingController::class, 'storeStep2'])->name('step2.store');
    Route::get('/step-3',  [OnboardingController::class, 'step3'])->name('step3');
    Route::post('/step-3', [OnboardingController::class, 'storeStep3'])->name('step3.store');
    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/welcome-comensal', function () {
        return view('welcome-comensal');
    })->name('welcome.comensal');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
