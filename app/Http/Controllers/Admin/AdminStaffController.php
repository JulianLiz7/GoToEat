<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Auth\Models\User;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $employees = $restaurant->employees()->with('user')->latest()->get();

        return view('admin.staff', compact('restaurant', 'employees'));
    }

    public function create()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        // Usuarios que aún no son empleados de este restaurante
        $existingUserIds = $restaurant->employees()->pluck('user_id');
        $availableUsers  = User::whereNotIn('id', $existingUserIds)
            ->where('id', '!=', $restaurant->owner_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.staff-create', compact('restaurant', 'availableUsers'));
    }

    public function store(Request $request)
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $data = $request->validate([
            'user_id'           => ['required', 'exists:users,id'],
            'position'          => ['required', 'string', 'max:100'],
            'salary'            => ['nullable', 'numeric', 'min:0'],
            'hire_date'         => ['nullable', 'date'],
            'status'            => ['in:active,inactive,on_leave'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string'],
        ]);

        $restaurant->employees()->create($data);

        return redirect()->route('admin.staff')
            ->with('success', 'Empleado agregado al equipo exitosamente.');
    }
}
