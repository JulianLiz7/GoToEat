<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — GoToEat v1
|--------------------------------------------------------------------------
|
| All API routes are prefixed with /api/v1 and use Sanctum for
| authentication. Each domain registers its own routes below.
|
*/

Route::prefix('v1')->group(function () {

    // ── Public routes (no auth required) ──────────────────────────
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'version' => 'v1',
            'timestamp' => now()->toIso8601String(),
        ]);
    })->name('api.health');

    // ── Auth routes ───────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        // Future: login, register, logout API endpoints
    });

    // ── Protected routes (require Sanctum token) ──────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // User profile
        Route::get('/user', function (\Illuminate\Http\Request $request) {
            return $request->user();
        })->name('api.user');

        // ── Restaurant domain ─────────────────────────────────────
        Route::prefix('restaurants')->group(function () {
            // Future: RestaurantController CRUD
        });

        // ── Menu domain ───────────────────────────────────────────
        Route::prefix('menu-items')->group(function () {
            // Future: MenuItemController CRUD
        });

        // ── Orders domain ─────────────────────────────────────────
        Route::prefix('orders')->group(function () {
            // Future: OrderController CRUD
        });

        // ── Inventory domain ──────────────────────────────────────
        Route::prefix('inventory')->group(function () {
            // Future: InventoryController CRUD
        });

        // ── Staff domain ──────────────────────────────────────────
        Route::prefix('staff')->group(function () {
            // Future: EmployeeController CRUD
        });

        // ── Tables domain ─────────────────────────────────────────
        Route::prefix('tables')->group(function () {
            // Future: TableController CRUD
        });

        // ── Finance domain ────────────────────────────────────────
        Route::prefix('finance')->group(function () {
            // Future: ExpenseController, TipController CRUD
        });

        // ── AI domain ─────────────────────────────────────────────
        Route::prefix('ai')->group(function () {
            // Future: AIController endpoints
        });
    });
});
