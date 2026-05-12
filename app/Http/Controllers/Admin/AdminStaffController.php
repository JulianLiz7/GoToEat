<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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

        return view('admin.staff-create', compact('restaurant'));
    }
}
