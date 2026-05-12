<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        if (!$restaurant) {
            return redirect()->route('onboarding.step1');
        }

        $rid   = $restaurant->id;
        $now   = now();
        $month = $now->month;
        $year  = $now->year;

        // ── KPI financieros del mes ───────────────────────────────────
        $monthRevenue  = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('total') ?? 0;

        $monthExpenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->sum('amount') ?? 0;

        $monthTips = DB::table('tips')
            ->where('restaurant_id', $rid)
            ->whereMonth('date', $month)->whereYear('date', $year)
            ->sum('amount') ?? 0;

        $netProfit = $monthRevenue - $monthExpenses;

        // ── Tendencias mes anterior ───────────────────────────────────
        $prevMonth   = $now->copy()->subMonth();
        $prevRevenue = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $prevMonth->month)->whereYear('created_at', $prevMonth->year)
            ->sum('total') ?? 0;

        $revenueTrend = $prevRevenue > 0
            ? round((($monthRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : 0;

        // ── Datos del día ─────────────────────────────────────────────
        $todayOrders  = DB::table('orders')
            ->where('restaurant_id', $rid)->whereDate('created_at', today())->count();
        $todayRevenue = DB::table('orders')
            ->where('restaurant_id', $rid)->whereDate('created_at', today())->sum('total') ?? 0;

        // ── Mesas, personal, menú ─────────────────────────────────────
        $totalTables     = DB::table('tables')->where('restaurant_id', $rid)->count();
        $activeTables    = DB::table('tables')->where('restaurant_id', $rid)->where('status', 'ocupada')->count();
        $availableTables = DB::table('tables')->where('restaurant_id', $rid)->where('status', 'disponible')->count();
        $activeStaff     = DB::table('employees')->where('restaurant_id', $rid)->where('status', 'active')->count();
        $shiftStaff      = DB::table('employees')->where('restaurant_id', $rid)->where('status', 'active')->count();
        $totalMenuItems  = DB::table('menu_items')->where('restaurant_id', $rid)->where('available', true)->count();
        $lowStockCount   = DB::table('inventory_items')
            ->where('restaurant_id', $rid)
            ->whereColumn('quantity', '<=', 'min_stock')
            ->where('status', 'active')
            ->count();

        // ── Gráfica de barras: últimos 7 días (ingresos vs egresos) ───
        $weekDays = collect(range(6, 0))->map(fn ($d) => $now->copy()->subDays($d));

        $weeklyRevenue = $weekDays->map(fn ($day) =>
            (float) (DB::table('orders')
                ->where('restaurant_id', $rid)
                ->whereDate('created_at', $day->toDateString())
                ->sum('total') ?? 0)
        );

        $weeklyExpenses = $weekDays->map(fn ($day) =>
            (float) (DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->where('expense_date', $day->toDateString())
                ->sum('amount') ?? 0)
        );

        $weekLabels = $weekDays->map(fn ($day) => mb_strtoupper($day->locale('es')->dayName))->map(fn ($n) => mb_substr($n, 0, 3));

        // ── Gráfica de línea: órdenes por día (últimos 13 días) ───────
        $chartDays = collect(range(12, 0))->map(fn ($d) => $now->copy()->subDays($d));

        $dailyOrders = $chartDays->map(fn ($day) =>
            DB::table('orders')
                ->where('restaurant_id', $rid)
                ->whereDate('created_at', $day->toDateString())
                ->count()
        );

        $chartLabels = $chartDays->map(fn ($day) => $day->format('d M'));

        // ── Pedidos recientes ─────────────────────────────────────────
        $recentOrders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->latest()
            ->take(5)
            ->get();

        $stats = compact(
            'monthRevenue', 'monthExpenses', 'monthTips', 'netProfit', 'revenueTrend',
            'todayOrders', 'todayRevenue',
            'totalTables', 'activeTables', 'availableTables',
            'activeStaff', 'shiftStaff', 'totalMenuItems', 'lowStockCount',
            'weeklyRevenue', 'weeklyExpenses', 'weekLabels',
            'dailyOrders', 'chartLabels',
            'recentOrders'
        );

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
            ->distinct()->whereNotNull('cuisine_type')->orderBy('cuisine_type')->pluck('cuisine_type');

        return view('cliente.dashboard', compact('restaurants', 'cuisines', 'search', 'cuisine'));
    }
}
