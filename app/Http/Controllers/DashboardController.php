<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $rid = $restaurant->id;

        // ── Stats cacheadas 2 minutos (evita 41 queries en cada recarga) ──
        $stats = Cache::remember("dashboard_stats_{$rid}", 120, function () use ($rid) {

            $now   = now();
            $month = $now->month;
            $year  = $now->year;

            // ── Query 1: KPIs de órdenes en una sola pasada ───────────
            $orderKpis = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->selectRaw("
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ?
                             THEN total ELSE 0 END) AS month_revenue,
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ?
                             THEN total ELSE 0 END) AS prev_revenue,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total ELSE 0 END) AS today_revenue,
                    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) AS today_orders
                ", [$month, $year, $now->copy()->subMonth()->month, $now->copy()->subMonth()->year])
                ->first();

            $monthRevenue = (float) ($orderKpis->month_revenue ?? 0);
            $prevRevenue  = (float) ($orderKpis->prev_revenue  ?? 0);
            $todayRevenue = (float) ($orderKpis->today_revenue ?? 0);
            $todayOrders  = (int)   ($orderKpis->today_orders  ?? 0);
            $revenueTrend = $prevRevenue > 0
                ? round((($monthRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
                : 0;

            // ── Query 2: Egresos y propinas del mes ───────────────────
            $monthExpenses = (float) DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
                ->sum('amount');

            $monthTips = (float) DB::table('tips')
                ->where('restaurant_id', $rid)
                ->whereMonth('date', $month)->whereYear('date', $year)
                ->sum('amount');

            // ── Query 3: Conteos de mesas en una sola pasada ──────────
            $tableCounts = DB::table('tables')
                ->where('restaurant_id', $rid)
                ->selectRaw("
                    COUNT(*) AS total,
                    SUM(status = 'ocupada')     AS occupied,
                    SUM(status = 'disponible')  AS available
                ")
                ->first();

            // ── Query 4: Conteo de empleados ──────────────────────────
            $staffCount = DB::table('employees')
                ->where('restaurant_id', $rid)
                ->where('status', 'active')
                ->count();

            // ── Query 5: Menú e inventario bajo stock ─────────────────
            $menuCount = DB::table('menu_items')
                ->where('restaurant_id', $rid)->where('available', true)->count();

            $lowStockCount = DB::table('inventory_items')
                ->where('restaurant_id', $rid)
                ->whereColumn('quantity', '<=', 'min_stock')
                ->where('status', 'active')
                ->count();

            // ── Query 6: Gráfica de barras — últimos 7 días ───────────
            // Una sola query GROUP BY en lugar de 14 queries individuales
            $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();

            $weeklyOrdersRaw = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->where('created_at', '>=', $sevenDaysAgo)
                ->selectRaw('DATE(created_at) AS day, SUM(total) AS revenue')
                ->groupBy('day')
                ->pluck('revenue', 'day');

            $weeklyExpensesRaw = DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->where('expense_date', '>=', $sevenDaysAgo->toDateString())
                ->selectRaw('expense_date AS day, SUM(amount) AS total')
                ->groupBy('day')
                ->pluck('total', 'day');

            // Rellenar todos los 7 días (aunque no haya datos ese día → 0)
            $weekDays       = collect(range(6, 0))->map(fn ($d) => $now->copy()->subDays($d));
            $weeklyRevenue  = $weekDays->map(fn ($d) => (float) ($weeklyOrdersRaw[$d->toDateString()] ?? 0));
            $weeklyExpenses = $weekDays->map(fn ($d) => (float) ($weeklyExpensesRaw[$d->toDateString()] ?? 0));
            $weekLabels     = $weekDays->map(fn ($d) => mb_strtoupper(mb_substr($d->locale('es')->dayName, 0, 3)));

            // ── Query 7: Gráfica de línea — últimos 13 días ───────────
            // Una sola query GROUP BY en lugar de 13 queries individuales
            $thirteenDaysAgo = $now->copy()->subDays(12)->startOfDay();

            $dailyOrdersRaw = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->where('created_at', '>=', $thirteenDaysAgo)
                ->selectRaw('DATE(created_at) AS day, COUNT(*) AS orders')
                ->groupBy('day')
                ->pluck('orders', 'day');

            $chartDays   = collect(range(12, 0))->map(fn ($d) => $now->copy()->subDays($d));
            $dailyOrders = $chartDays->map(fn ($d) => (int) ($dailyOrdersRaw[$d->toDateString()] ?? 0));
            $chartLabels = $chartDays->map(fn ($d) => $d->format('d M'));

            // ── Query 8: Últimos 5 pedidos ────────────────────────────
            $recentOrders = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->latest()
                ->take(5)
                ->get();

            return compact(
                'monthRevenue', 'monthExpenses', 'monthTips',
                'revenueTrend', 'todayOrders', 'todayRevenue',
                'tableCounts', 'staffCount', 'menuCount', 'lowStockCount',
                'weeklyRevenue', 'weeklyExpenses', 'weekLabels',
                'dailyOrders', 'chartLabels', 'recentOrders'
            );
        });

        // Extraer conteos de mesas del objeto stdClass
        $tc = $stats['tableCounts'];
        $stats['totalTables']     = (int) ($tc->total    ?? 0);
        $stats['activeTables']    = (int) ($tc->occupied  ?? 0);
        $stats['availableTables'] = (int) ($tc->available ?? 0);
        $stats['activeStaff']     = $stats['staffCount'];
        $stats['shiftStaff']      = $stats['staffCount'];
        $stats['totalMenuItems']  = $stats['menuCount'];
        $stats['netProfit']       = $stats['monthRevenue'] - $stats['monthExpenses'];

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
