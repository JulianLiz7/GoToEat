<?php

namespace App\Http\Controllers;

use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Models\StaffNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminView($user);
        }

        if ($user->hasAnyRole(['chef', 'cajero', 'mesero'])) {
            return $this->empleadoView($user);
        }

        return $this->clienteView($request);
    }

    private function empleadoView($user)
    {
        // Obtener el registro de empleado del usuario
        $employee = Employee::where('user_id', $user->id)
            ->with('restaurant')
            ->first();

        // Si por alguna razón no tiene registro de empleado, redirigir a la vista de cliente
        if (! $employee) {
            return $this->clienteView($request ?? request());
        }

        // Datos del dashboard de empleado
        $cargo = $employee->position ?? 'Empleado';
        $badgeId = 'GE-'.str_pad($employee->id, 6, '0', STR_PAD_LEFT);
        $fechaIngreso = $employee->hire_date?->locale('es')->format('d M Y') ?? 'No registrada';
        $salarioBase = number_format($employee->salary ?? 0, 0, ',', '.');

        // Valores de turnos y horas reales/simulados
        $horasHoy = '8h';
        $horasTarget = '8h';
        $horasPct = 100;
        $horasExtrasSemana = '0h';
        $horasSemana = '40h';

        // Turnos de ejemplo
        $turnos = [
            ['dia' => 'Lunes — Viernes', 'hora' => '08:00 AM — 04:00 PM'],
            ['dia' => 'Sábado', 'hora' => '09:00 AM — 02:00 PM'],
        ];

        // Notificaciones del restaurante para el staff
        $notificaciones = StaffNotification::where('restaurant_id', $employee->restaurant_id)
            ->active()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($n) {
                return [
                    'titulo' => $n->title,
                    'mensaje' => $n->message,
                    'icono' => $n->type === 'urgente' ? 'alarm' : ($n->type === 'aviso' ? 'groups' : 'check_circle'),
                    'color' => $n->type === 'urgente' ? 'orange' : ($n->type === 'aviso' ? 'blue' : 'emerald'),
                ];
            })
            ->toArray();

        if (empty($notificaciones)) {
            $notificaciones = [
                [
                    'titulo' => '¡Bienvenido a tu nuevo Panel de Empleado!',
                    'mensaje' => 'Aquí podrás ver tus turnos, programar tus tareas y mantenerte al día con las comunicaciones.',
                    'icono' => 'check_circle',
                    'color' => 'emerald',
                ],
            ];
        }

        return view('empleado.dashboard', compact(
            'cargo', 'badgeId', 'fechaIngreso', 'salarioBase',
            'horasHoy', 'horasTarget', 'horasPct', 'horasExtrasSemana', 'horasSemana',
            'turnos', 'notificaciones', 'employee'
        ));
    }

    private function adminView($user)
    {
        $restaurant = $user->ownedRestaurants()->first();

        if (! $restaurant) {
            return redirect()->route('onboarding.step1');
        }

        $rid = $restaurant->id;

        // ── Stats cacheadas 2 minutos (evita 41 queries en cada recarga) ──
        $stats = Cache::remember("dashboard_stats_{$rid}", 120, function () use ($rid) {

            $now = now();
            $month = $now->month;
            $year = $now->year;

            // ── Query 1: KPIs de órdenes en una sola pasada ───────────
            $orderKpis = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->selectRaw('
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ?
                             THEN total ELSE 0 END) AS month_revenue,
                    SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ?
                             THEN total ELSE 0 END) AS prev_revenue,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total ELSE 0 END) AS today_revenue,
                    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) AS today_orders
                ', [$month, $year, $now->copy()->subMonth()->month, $now->copy()->subMonth()->year])
                ->first();

            $monthRevenue = (float) ($orderKpis->month_revenue ?? 0);
            $prevRevenue = (float) ($orderKpis->prev_revenue ?? 0);
            $todayRevenue = (float) ($orderKpis->today_revenue ?? 0);
            $todayOrders = (int) ($orderKpis->today_orders ?? 0);
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
            $weekDays = collect(range(6, 0))->map(fn ($d) => $now->copy()->subDays($d));
            $weeklyRevenue = $weekDays->map(fn ($d) => (float) ($weeklyOrdersRaw[$d->toDateString()] ?? 0));
            $weeklyExpenses = $weekDays->map(fn ($d) => (float) ($weeklyExpensesRaw[$d->toDateString()] ?? 0));
            $weekLabels = $weekDays->map(fn ($d) => mb_strtoupper(mb_substr($d->locale('es')->dayName, 0, 3)));

            // ── Query 7: Gráfica de línea — últimos 13 días ───────────
            // Una sola query GROUP BY en lugar de 13 queries individuales
            $thirteenDaysAgo = $now->copy()->subDays(12)->startOfDay();

            $dailyOrdersRaw = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->where('created_at', '>=', $thirteenDaysAgo)
                ->selectRaw('DATE(created_at) AS day, COUNT(*) AS orders')
                ->groupBy('day')
                ->pluck('orders', 'day');

            $chartDays = collect(range(12, 0))->map(fn ($d) => $now->copy()->subDays($d));
            $dailyOrders = $chartDays->map(fn ($d) => (int) ($dailyOrdersRaw[$d->toDateString()] ?? 0));
            $chartLabels = $chartDays->map(fn ($d) => $d->format('d M'));

            // ── Query 8: Últimos 5 pedidos ────────────────────────────
            $recentOrders = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->latest()
                ->take(5)
                ->get();

            // ── Query 9: Distribución de ventas por categoría ─────────
            // Intenta calcular desde órdenes completadas con items.
            // Fallback: distribución por cantidad de ítems en el menú.
            $categoryDistribution = collect();

            $ordersWithItems = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->whereIn('status', ['completed'])
                ->whereNotNull('items')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get(['items']);

            if ($ordersWithItems->isNotEmpty()) {
                $revenueByCategory = [];
                $allMenuItemIds = [];

                foreach ($ordersWithItems as $ord) {
                    $items = json_decode($ord->items, true) ?? [];
                    foreach ($items as $it) {
                        if (! empty($it['menu_item_id'])) {
                            $allMenuItemIds[] = (int) $it['menu_item_id'];
                        }
                    }
                }

                if (! empty($allMenuItemIds)) {
                    $categories = DB::table('menu_items')
                        ->whereIn('id', array_unique($allMenuItemIds))
                        ->pluck('category', 'id');

                    foreach ($ordersWithItems as $ord) {
                        $items = json_decode($ord->items, true) ?? [];
                        foreach ($items as $it) {
                            $mid = (int) ($it['menu_item_id'] ?? 0);
                            $cat = $categories[$mid] ?? 'Sin categoría';
                            $total = (float) ($it['unit_price'] ?? 0) * (int) ($it['quantity'] ?? 1);
                            $revenueByCategory[$cat] = ($revenueByCategory[$cat] ?? 0) + $total;
                        }
                    }

                    arsort($revenueByCategory);
                    $totalRev = array_sum($revenueByCategory);

                    if ($totalRev > 0) {
                        $categoryDistribution = collect($revenueByCategory)
                            ->map(fn ($v) => round(($v / $totalRev) * 100, 1));
                    }
                }
            }

            // Fallback: distribución por cantidad de ítems disponibles en el menú
            if ($categoryDistribution->isEmpty()) {
                $menuByCategory = DB::table('menu_items')
                    ->where('restaurant_id', $rid)
                    ->where('available', true)
                    ->whereNotNull('category')
                    ->selectRaw('category, COUNT(*) as qty')
                    ->groupBy('category')
                    ->orderByDesc('qty')
                    ->pluck('qty', 'category');

                $totalItems = $menuByCategory->sum();
                if ($totalItems > 0) {
                    $categoryDistribution = $menuByCategory
                        ->map(fn ($v) => round(($v / $totalItems) * 100, 1));
                }
            }

            return compact(
                'monthRevenue', 'monthExpenses', 'monthTips',
                'revenueTrend', 'todayOrders', 'todayRevenue',
                'tableCounts', 'staffCount', 'menuCount', 'lowStockCount',
                'weeklyRevenue', 'weeklyExpenses', 'weekLabels',
                'dailyOrders', 'chartLabels', 'recentOrders',
                'categoryDistribution'
            );
        });

        // Extraer conteos de mesas del objeto stdClass
        $tc = $stats['tableCounts'];
        $stats['totalTables'] = (int) ($tc->total ?? 0);
        $stats['activeTables'] = (int) ($tc->occupied ?? 0);
        $stats['availableTables'] = (int) ($tc->available ?? 0);
        $stats['activeStaff'] = $stats['staffCount'];
        $stats['shiftStaff'] = $stats['staffCount'];
        $stats['totalMenuItems'] = $stats['menuCount'];
        $stats['netProfit'] = $stats['monthRevenue'] - $stats['monthExpenses'];

        return view('admin.dashboard', compact('restaurant', 'stats'));
    }

    public function exportCsv()
    {
        $user       = auth()->user();
        $restaurant = $user->ownedRestaurants()->first();
        if (!$restaurant) return redirect()->route('onboarding.step1');

        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $orders   = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->orderBy('created_at')->get();

        $expenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->orderBy('expense_date')->get();

        $todayOrders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', now()->toDateString())
            ->get();

        $filename = 'dashboard_' . $restaurant->name . '_' . now()->format('Y-m') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($restaurant, $orders, $expenses, $todayOrders) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF");

            fputcsv($f, ['DASHBOARD OPERATIVO — ' . strtoupper($restaurant->name)]);
            fputcsv($f, ['Período:', now()->locale('es')->isoFormat('MMMM YYYY')]);
            fputcsv($f, ['Generado:', now()->format('d/m/Y H:i')]);
            fputcsv($f, []);

            fputcsv($f, ['=== RESUMEN DEL MES ===']);
            fputcsv($f, ['Indicador', 'Valor']);
            $monthRevenue  = $orders->sum('total');
            $monthExpenses = $expenses->sum('amount');
            fputcsv($f, ['Ingresos del mes',  '$' . number_format($monthRevenue, 0, ',', '.')]);
            fputcsv($f, ['Egresos del mes',   '$' . number_format($monthExpenses, 0, ',', '.')]);
            fputcsv($f, ['Profit neto',       '$' . number_format($monthRevenue - $monthExpenses, 0, ',', '.')]);
            fputcsv($f, ['Órdenes hoy',       $todayOrders->count()]);
            fputcsv($f, ['Ventas hoy',        '$' . number_format($todayOrders->sum('total'), 0, ',', '.')]);
            fputcsv($f, []);

            fputcsv($f, ['=== ÓRDENES DEL MES ===']);
            fputcsv($f, ['ID', 'Fecha', 'Total', 'Estado']);
            foreach ($orders as $o) {
                fputcsv($f, ['#GT-' . $o->id, $o->created_at, number_format($o->total, 2, '.', ''), $o->status ?? 'completado']);
            }
            fputcsv($f, ['', 'TOTAL', number_format($monthRevenue, 2, '.', ''), '']);
            fputcsv($f, []);

            fputcsv($f, ['=== EGRESOS DEL MES ===']);
            fputcsv($f, ['Concepto', 'Fecha', 'Monto', 'Categoría']);
            foreach ($expenses as $e) {
                fputcsv($f, [$e->name, $e->expense_date, number_format($e->amount, 2, '.', ''), $e->category ?? 'Operativos']);
            }
            fputcsv($f, ['', 'TOTAL', number_format($monthExpenses, 2, '.', ''), '']);

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $user       = auth()->user();
        $restaurant = $user->ownedRestaurants()->first();
        if (!$restaurant) return redirect()->route('onboarding.step1');

        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $monthRevenue  = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('total');

        $monthExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)->sum('amount');

        $todayRevenue = (float) DB::table('orders')
            ->where('restaurant_id', $rid)->whereDate('created_at', now()->toDateString())->sum('total');

        $todayOrders = (int) DB::table('orders')
            ->where('restaurant_id', $rid)->whereDate('created_at', now()->toDateString())->count();

        $recentOrders = DB::table('orders')
            ->where('restaurant_id', $rid)->latest()->take(10)->get();

        $totalTables  = (int) DB::table('tables')->where('restaurant_id', $rid)->count();
        $activeTables = (int) DB::table('tables')->where('restaurant_id', $rid)->where('status', 'ocupada')->count();
        $staffCount   = (int) DB::table('employees')->where('restaurant_id', $rid)->where('status', 'active')->count();
        $menuCount    = (int) DB::table('menu_items')->where('restaurant_id', $rid)->where('available', true)->count();

        return view('admin.dashboard-export-pdf', compact(
            'restaurant', 'monthRevenue', 'monthExpenses', 'todayRevenue',
            'todayOrders', 'recentOrders', 'totalTables', 'activeTables',
            'staffCount', 'menuCount'
        ));
    }

    private function clienteView(Request $request)
    {
        $search = $request->get('search', '');
        $cuisine = $request->get('cuisine', '');

        $restaurants = Restaurant::where('status', 'active')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('cuisine_type', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%"))
            ->when($cuisine, fn ($q) => $q->where('cuisine_type', $cuisine))
            ->latest()
            ->paginate(12);

        $cuisines = Restaurant::select('cuisine_type')
            ->distinct()->whereNotNull('cuisine_type')->orderBy('cuisine_type')->pluck('cuisine_type');

        return view('cliente.dashboard', compact('restaurants', 'cuisines', 'search', 'cuisine'));
    }
}
