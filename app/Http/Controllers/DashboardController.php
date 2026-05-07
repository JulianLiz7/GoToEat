<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        return $user->hasRole('admin')
            ? $this->adminView($user)
            : $this->clienteView($request);
    }

    private function adminView($user)
    {
        $restaurant = $user->ownedRestaurants()->first();
        $rid        = $restaurant?->id;

        $stats = [
            'todayOrders'    => DB::table('orders')->where('restaurant_id', $rid)->whereDate('created_at', today())->count(),
            'todayRevenue'   => DB::table('orders')->where('restaurant_id', $rid)->whereDate('created_at', today())->sum('total') ?? 0,
            'activeTables'   => DB::table('tables')->where('restaurant_id', $rid)->where('status', 'ocupada')->count(),
            'totalTables'    => DB::table('tables')->where('restaurant_id', $rid)->count(),
            'activeStaff'    => DB::table('employees')->where('restaurant_id', $rid)->where('status', 'active')->count(),
            'totalMenuItems' => DB::table('menu_items')->where('restaurant_id', $rid)->where('available', true)->count(),
            'monthRevenue'   => DB::table('orders')->where('restaurant_id', $rid)
                                   ->whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->sum('total') ?? 0,
            'recentOrders'   => DB::table('orders')->where('restaurant_id', $rid)->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('restaurant', 'stats'));
    }

    private function clienteView(Request $request)
    {
        $search  = $request->get('search', '');
        $cuisine = $request->get('cuisine', '');

        $restaurants = \App\Domains\Restaurant\Models\Restaurant::where('status', 'active')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('cuisine_type', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%"))
            ->when($cuisine, fn ($q) => $q->where('cuisine_type', $cuisine))
            ->latest()
            ->paginate(12);

        $cuisines = \App\Domains\Restaurant\Models\Restaurant::select('cuisine_type')
            ->distinct()
            ->whereNotNull('cuisine_type')
            ->orderBy('cuisine_type')
            ->pluck('cuisine_type');

        return view('cliente.dashboard', compact('restaurants', 'cuisines', 'search', 'cuisine'));
    }
}
