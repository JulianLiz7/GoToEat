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

    public function store(\Illuminate\Http\Request $request)
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'prep_time'   => 'nullable|integer|min:1',
            'available'   => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data['available']   = $request->boolean('available', true);
        $data['is_featured'] = $request->boolean('is_featured', false);

        $restaurant->menuItems()->create($data);

        return redirect()->route('admin.menu')->with('success', 'Plato creado exitosamente.');
    }
}
