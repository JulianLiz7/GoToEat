<?php

use App\Domains\Auth\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/register/step-2', function () {
    return view('auth.register-step2');
})->name('register.step2');

Route::get('/register/step-3', function () {
    return view('auth.register-step3');
})->name('register.step3');

Route::get('/register/welcome', function () {
    return view('auth.welcome-setup');
})->name('register.welcome');

require __DIR__.'/auth.php';
