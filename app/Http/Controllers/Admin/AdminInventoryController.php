<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInventoryController extends Controller
{
    public function index()
    {
        $restaurant    = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        // paginate() para que links() funcione en la vista
        $items = $restaurant->inventoryItems()->latest()->paginate(20);

        // Queries separadas para KPIs (no dependen de la página actual)
        $allItems      = $restaurant->inventoryItems()->get(['quantity','min_stock','cost_price','status']);
        $lowStockItems = $restaurant->inventoryItems()
            ->whereColumn('quantity', '<=', 'min_stock')
            ->where('status', 'active')
            ->get(['id','name','quantity','min_stock','unit']);
        $lowStockCount = $lowStockItems->count();

        // Valor total del inventario
        $totalValue = $allItems->sum(fn($i) => (float)($i->quantity ?? 0) * (float)($i->cost_price ?? 0));

        return view('admin.inventory', compact('restaurant', 'items', 'lowStockItems', 'lowStockCount', 'totalValue'));
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
