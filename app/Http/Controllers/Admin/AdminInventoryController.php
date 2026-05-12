<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index()
    {
        $restaurant    = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        // Usamos get() para calcular valores en PHP (para la barra de progreso y el valor total)
        $items         = $restaurant->inventoryItems()->latest()->get();
        $lowStockItems = $items->filter(fn($i) => $i->isLowStock());
        $lowStockCount = $lowStockItems->count();

        return view('admin.inventory', compact('restaurant', 'items', 'lowStockItems', 'lowStockCount'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'category'   => 'nullable|string|max:100',
            'quantity'   => 'required|numeric|min:0',
            'unit'       => 'nullable|string|max:50',
            'min_stock'  => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $restaurant->inventoryItems()->create($data + ['status' => 'active']);

        return redirect()->route('admin.inventory')->with('success', 'Ítem agregado al inventario.');
    }
}
