<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index()
    {
        $restaurant   = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $items        = $restaurant->inventoryItems()->latest()->paginate(25);
        $lowStockItems= $restaurant->inventoryItems()->whereColumn('quantity', '<=', 'min_stock')->where('status', 'active')->get();
        $lowStockCount= $lowStockItems->count();

        return view('admin.inventory', compact('restaurant', 'items', 'lowStockItems', 'lowStockCount'));
    }
}
