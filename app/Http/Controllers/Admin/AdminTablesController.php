<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        if (!$restaurant) return redirect()->route('onboarding.step1');

        // Zonas ya creadas para el datalist de sugerencias
        $existingZones = $restaurant->tables()
            ->distinct()
            ->whereNotNull('zone')
            ->orderBy('zone')
            ->pluck('zone');

        return view('admin.tables-create', compact('restaurant', 'existingZones'));
    }

    public function store(Request $request)
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $data = $request->validate([
            'number'   => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'zone'     => ['nullable', 'string', 'max:100'],
            'status'   => ['in:disponible,reservada,mantenimiento'],
        ]);

        $restaurant->tables()->create($data);

        return redirect()->route('admin.tables')
            ->with('success', "Mesa {$data['number']} creada exitosamente.");
    }
}
