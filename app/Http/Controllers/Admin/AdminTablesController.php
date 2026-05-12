<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminTablesController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $tables = $restaurant->tables()->orderBy('zone')->orderBy('number')->get();

        return view('admin.tables', compact('restaurant', 'tables'));
    }

    public function create()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        return view('admin.tables-create', compact('restaurant'));
    }
}
