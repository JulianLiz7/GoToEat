<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminMenuController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $itemsByCategory = $restaurant->menuItems()->latest()->get()->groupBy('category');

        return view('admin.menu', compact('restaurant', 'itemsByCategory'));
    }
}
