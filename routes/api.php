<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Staff\Controllers\Api\EmployeeController;
use App\Domains\Menu\Controllers\Api\MenuItemController;
use App\Domains\Inventory\Controllers\Api\InventoryController;
use App\Domains\Tables\Controllers\Api\TableController;
use App\Domains\Orders\Controllers\Api\OrderController;
use App\Domains\Finance\Controllers\Api\ExpenseController;
use App\Domains\Finance\Controllers\Api\TipController;
use App\Domains\AI\Controllers\Api\AIController;

/*
|--------------------------------------------------------------------------
| API Routes — GoToEat v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── Public ────────────────────────────────────────────────────────
    Route::get('/health', fn () => response()->json([
        'status'    => 'ok',
        'version'   => 'v1',
        'timestamp' => now()->toIso8601String(),
    ]))->name('api.health');

    // ── Protected (Sanctum) ───────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', fn (\Illuminate\Http\Request $r) => $r->user())->name('api.user');

        // ── Staff ─────────────────────────────────────────────────────
        Route::apiResource('staff', EmployeeController::class);

        // ── Menu ──────────────────────────────────────────────────────
        Route::apiResource('menu-items', MenuItemController::class);
        Route::patch('menu-items/{id}/toggle', [MenuItemController::class, 'toggleAvailability'])
            ->name('menu-items.toggle');

        // ── Inventory ─────────────────────────────────────────────────
        Route::apiResource('inventory', InventoryController::class);
        Route::get('inventory/alerts/low-stock', [InventoryController::class, 'lowStock'])
            ->name('inventory.low-stock');

        // ── Tables ────────────────────────────────────────────────────
        Route::apiResource('tables', TableController::class);
        Route::patch('tables/{id}/status/{status}', [TableController::class, 'updateStatus'])
            ->name('tables.status');

        // ── Orders ────────────────────────────────────────────────────
        Route::apiResource('orders', OrderController::class);

        // ── Finance ───────────────────────────────────────────────────
        Route::apiResource('expenses', ExpenseController::class);
        Route::get('tips', [TipController::class, 'index'])->name('tips.index');
        Route::get('tips/liquidation', [TipController::class, 'liquidation'])->name('tips.liquidation');

        // ── AI ────────────────────────────────────────────────────────
        Route::get('ai/conversations', [AIController::class, 'index'])->name('ai.index');
        Route::post('ai/ask', [AIController::class, 'ask'])->name('ai.ask');
        Route::get('ai/conversations/{id}', [AIController::class, 'show'])->name('ai.show');
        Route::get('ai/alerts', [AIController::class, 'alerts'])->name('ai.alerts');
    });
});
