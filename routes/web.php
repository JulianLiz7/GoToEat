<?php

use App\Domains\Auth\Controllers\Web\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminTablesController;
use App\Http\Controllers\Admin\AdminFinanceController;
use App\Http\Controllers\Admin\AdminAIController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Dashboard: enruta al panel según el rol
Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

// ── Onboarding de restaurante ─────────────────────────────────────
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/step-1',  [OnboardingController::class, 'step1'])->name('step1');
    Route::post('/step-1', [OnboardingController::class, 'storeStep1'])->name('step1.store');
    Route::get('/step-2',  [OnboardingController::class, 'step2'])->name('step2');
    Route::post('/step-2', [OnboardingController::class, 'storeStep2'])->name('step2.store');
    Route::get('/step-3',  [OnboardingController::class, 'step3'])->name('step3');
    Route::post('/step-3', [OnboardingController::class, 'storeStep3'])->name('step3.store');
    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
});

// ── Panel de administrador del local ─────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/inventory',                   [AdminInventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory',                  [AdminInventoryController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}',              [AdminInventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}',           [AdminInventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::patch('/inventory/{id}/stock',      [AdminInventoryController::class, 'adjustStock'])->name('inventory.stock');
    Route::get('/menu',                 [AdminMenuController::class, 'index'])->name('menu');
    Route::post('/menu',                [AdminMenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}',            [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}',         [AdminMenuController::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{id}/toggle',   [AdminMenuController::class, 'toggleAvailable'])->name('menu.toggle');
    Route::get('/staff',      [AdminStaffController::class,  'index'])->name('staff');
    Route::get('/staff/new',  [AdminStaffController::class,  'create'])->name('staff.create');
    Route::post('/staff',     [AdminStaffController::class,  'store'])->name('staff.store');
    Route::get('/tables',     [AdminTablesController::class, 'index'])->name('tables');
    Route::get('/tables/new', [AdminTablesController::class, 'create'])->name('tables.create');
    Route::post('/tables',    [AdminTablesController::class, 'store'])->name('tables.store');
    Route::get('/finance',   [AdminFinanceController::class, 'index'])->name('finance');
    Route::get('/ai',        [AdminAIController::class, 'index'])->name('ai');
    Route::post('/ai/ask',   [AdminAIController::class, 'ask'])->name('ai.ask');
});

// ── Perfil y zona de cliente ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/welcome-comensal', fn () => view('welcome-comensal'))->name('welcome.comensal');
    Route::get('/reservas',         fn () => view('cliente.reservas'))->name('reservas');
    Route::get('/perfil',           fn () => view('cliente.perfil'))->name('perfil');
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
