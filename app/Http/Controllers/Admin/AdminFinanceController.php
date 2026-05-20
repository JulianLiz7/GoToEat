<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminFinanceController extends Controller
{
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        if (!$r) abort(redirect()->route('onboarding.step1'));
        return $r;
    }

    // ── Resumen ──────────────────────────────────────────────────────
    public function resumen()
    {
        $restaurant = $this->restaurant();
        $rid   = $restaurant->id;
        $now   = now();
        $month = $now->month;
        $year  = $now->year;

        $monthRevenue  = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('total');

        $monthExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->sum('amount');

        // Sumar depósitos de cierres de caja del mes como ingresos adicionales
        try {
            $cashRegisterDeposits = (float) DB::table('cash_registers')
                ->where('restaurant_id', $rid)
                ->whereMonth('shift_date', $month)->whereYear('shift_date', $year)
                ->where('status', 'completed')
                ->where('deposit_amount', '>', 0)
                ->sum('deposit_amount');
        } catch (\Exception $e) {
            $cashRegisterDeposits = 0;
        }

        // El efectivo real contado en cierres complementa los ingresos del mes
        $monthRevenue += $cashRegisterDeposits;
        $netProfit = $monthRevenue - $monthExpenses;

        // Flujo de caja (aproximaciones con datos disponibles)
        $cajaFisica = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', $now->toDateString())
            ->sum('total');

        $semanaRevenue = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $now->copy()->subDays(7)->startOfDay())
            ->sum('total');

        $pasarelas = max(0, $semanaRevenue - $cajaFisica);

        // Gráfica 7 días: ingresos vs egresos
        $sevenDaysAgo      = $now->copy()->subDays(6)->startOfDay();
        $weeklyIngresosRaw = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) AS day, SUM(total) AS total')
            ->groupBy('day')->pluck('total', 'day');

        $weeklyEgresosRaw = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->where('expense_date', '>=', $sevenDaysAgo->toDateString())
            ->selectRaw('expense_date AS day, SUM(amount) AS total')
            ->groupBy('day')->pluck('total', 'day');

        $weekDays       = collect(range(6, 0))->map(fn ($d) => $now->copy()->subDays($d));
        $weeklyIngresos = $weekDays->map(fn ($d) => (float) ($weeklyIngresosRaw[$d->toDateString()] ?? 0));
        $weeklyEgresos  = $weekDays->map(fn ($d) => (float) ($weeklyEgresosRaw[$d->toDateString()] ?? 0));
        $weekLabels     = $weekDays->map(fn ($d) => mb_strtoupper(mb_substr($d->locale('es')->dayName, 0, 3)));

        // Desglose por categoría
        try {
            $catRaw = DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
                ->selectRaw('COALESCE(NULLIF(category, ""), "Operativos") as cat, SUM(amount) as total')
                ->groupBy('cat')->orderByDesc('total')->get();
        } catch (\Exception $e) {
            $catRaw = collect([(object) ['cat' => 'Operativos', 'total' => $monthExpenses]]);
        }

        $totalCat   = (float) $catRaw->sum('total');
        $expenseChart = $catRaw->map(fn ($c) => [
            'label'  => $c->cat,
            'amount' => (float) $c->total,
            'pct'    => $totalCat > 0 ? round(($c->total / $totalCat) * 100, 1) : 0,
        ])->values();

        // Transacciones recientes (órdenes + egresos mezclados)
        $recentOrders = DB::table('orders')
            ->where('restaurant_id', $rid)->latest()->take(6)->get()
            ->map(fn ($o) => [
                'type'     => 'ingreso',
                'concept'  => 'Venta #' . $o->id,
                'amount'   => (float) $o->total,
                'date'     => $o->created_at,
                'category' => 'Ventas',
                'method'   => $o->payment_method ?? 'POS',
            ]);

        $recentExpenses = DB::table('expenses')
            ->where('restaurant_id', $rid)->latest('expense_date')->take(4)->get()
            ->map(fn ($e) => [
                'type'     => 'egreso',
                'concept'  => $e->name,
                'amount'   => (float) $e->amount,
                'date'     => $e->expense_date,
                'category' => $e->category ?? 'Operativos',
                'method'   => 'Transferencia',
            ]);

        $recentTransactions = collect($recentOrders)->merge($recentExpenses)
            ->sortByDesc('date')->take(10)->values();

        return view('admin.finanzas.resumen', compact(
            'restaurant', 'monthRevenue', 'monthExpenses', 'netProfit',
            'cajaFisica', 'pasarelas', 'semanaRevenue',
            'weeklyIngresos', 'weeklyEgresos', 'weekLabels',
            'expenseChart', 'recentTransactions'
        ));
    }

    // ── Ingresos ─────────────────────────────────────────────────────
    public function ingresos(Request $request)
    {
        $restaurant = $this->restaurant();
        $rid    = $restaurant->id;
        $period = $request->get('period', 'month');

        $startDate = match ($period) {
            'day'   => now()->startOfDay(),
            'week'  => now()->subDays(6)->startOfDay(),
            default => now()->startOfMonth(),
        };

        $periodRevenue = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $startDate)->sum('total');

        $periodCount = (int) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $startDate)->count();

        $avgTicket = $periodCount > 0 ? round($periodRevenue / $periodCount, 2) : 0;

        // Comparativa período anterior
        [$prevStart, $prevEnd] = match ($period) {
            'day'   => [now()->subDay()->startOfDay(),    now()->subDay()->endOfDay()],
            'week'  => [now()->subDays(13)->startOfDay(), now()->subDays(7)->endOfDay()],
            default => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
        };

        $prevRevenue  = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereBetween('created_at', [$prevStart, $prevEnd])->sum('total');

        $revenueTrend = $prevRevenue > 0
            ? round((($periodRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : 0;

        // Tendencia semanal (siempre 7 días para el gráfico)
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $trendRaw     = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) AS day, SUM(total) AS total')
            ->groupBy('day')->pluck('total', 'day');

        $weekDays    = collect(range(6, 0))->map(fn ($d) => now()->subDays($d));
        $trendData   = $weekDays->map(fn ($d) => (float) ($trendRaw[$d->toDateString()] ?? 0));
        $trendLabels = $weekDays->map(fn ($d) => mb_strtoupper(mb_substr($d->locale('es')->dayName, 0, 3)));

        // Historial paginado
        $orders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->where('created_at', '>=', $startDate)
            ->latest()->paginate(12)->withQueryString();

        return view('admin.finanzas.ingresos', compact(
            'restaurant', 'period', 'periodRevenue', 'periodCount', 'avgTicket',
            'revenueTrend', 'trendData', 'trendLabels', 'orders'
        ));
    }

    // ── Gastos ───────────────────────────────────────────────────────
    public function gastos()
    {
        $restaurant = $this->restaurant();
        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $monthExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->sum('amount');

        // Categorías para donut
        try {
            $catRaw = DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
                ->selectRaw('COALESCE(NULLIF(category, ""), "Operativos") as cat, SUM(amount) as total')
                ->groupBy('cat')->orderByDesc('total')->get();
        } catch (\Exception $e) {
            $catRaw = collect([(object) ['cat' => 'Operativos', 'total' => $monthExpenses]]);
        }

        $totalCat     = (float) $catRaw->sum('total');
        $expenseChart = $catRaw->map(fn ($c) => [
            'label'  => $c->cat,
            'amount' => (float) $c->total,
            'pct'    => $totalCat > 0 ? round(($c->total / $totalCat) * 100, 1) : 0,
        ])->values();

        // Presupuesto estimado (mes anterior × 1.1)
        $prevExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', now()->subMonth()->month)
            ->whereYear('expense_date', now()->subMonth()->year)
            ->sum('amount');
        $budget = $prevExpenses > 0 ? round($prevExpenses * 1.1) : max($monthExpenses * 1.4, 1000000);

        // Alertas de vencimiento (si existe due_date)
        try {
            $invoiceAlerts = DB::table('expenses')
                ->where('restaurant_id', $rid)
                ->where('due_date', '>=', now()->toDateString())
                ->where('due_date', '<=', now()->addDays(7)->toDateString())
                ->orderBy('due_date')->take(3)->get();
        } catch (\Exception $e) {
            $invoiceAlerts = collect();
        }

        // Listado paginado
        $expenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->latest('expense_date')->paginate(12);

        return view('admin.finanzas.gastos', compact(
            'restaurant', 'monthExpenses', 'budget', 'expenseChart',
            'invoiceAlerts', 'expenses'
        ));
    }

    public function storeGasto(Request $request)
    {
        $restaurant = $this->restaurant();
        $validated  = $request->validate([
            'name'         => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category'     => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:1000',
            'due_date'     => 'nullable|date',
            'comprobante'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:10240',
        ]);

        $comprobantePath = null;
        if ($request->hasFile('comprobante') && $request->file('comprobante')->isValid()) {
            $comprobantePath = $request->file('comprobante')
                ->store("comprobantes/{$restaurant->id}", 'public');
        }

        DB::table('expenses')->insert([
            'restaurant_id'   => $restaurant->id,
            'name'            => $validated['name'],
            'amount'          => $validated['amount'],
            'expense_date'    => $validated['expense_date'],
            'category'        => $validated['category'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'due_date'        => $validated['due_date'] ?? null,
            'comprobante_path' => $comprobantePath,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Gasto registrado correctamente.');
    }

    // ── Cierre de Caja ───────────────────────────────────────────────
    public function cierreCaja()
    {
        $restaurant = $this->restaurant();
        $rid = $restaurant->id;

        $expectedCash = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        $todayOrdersCount = (int) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $todayExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->where('expense_date', now()->toDateString())
            ->sum('amount');

        // Ventas por método de pago
        try {
            $salesByMethod = DB::table('orders')
                ->where('restaurant_id', $rid)
                ->whereDate('created_at', now()->toDateString())
                ->selectRaw('COALESCE(NULLIF(payment_method,""), "Efectivo") as method, SUM(total) as total, COUNT(*) as cnt')
                ->groupBy('method')->get();
        } catch (\Exception $e) {
            $salesByMethod = collect([
                (object) ['method' => 'Efectivo', 'total' => $expectedCash, 'cnt' => $todayOrdersCount],
            ]);
        }

        // Cierre borrador de hoy
        try {
            $existingCierre = DB::table('cash_registers')
                ->where('restaurant_id', $rid)
                ->where('shift_date', now()->toDateString())
                ->first();
        } catch (\Exception $e) {
            $existingCierre = null;
        }

        return view('admin.finanzas.cierre-caja', compact(
            'restaurant', 'expectedCash', 'todayOrdersCount',
            'todayExpenses', 'salesByMethod', 'existingCierre'
        ));
    }

    public function storeCierre(Request $request)
    {
        $restaurant = $this->restaurant();
        $validated  = $request->validate([
            'cash_counted'        => 'required|numeric|min:0',
            'cash_expected'       => 'required|numeric|min:0',
            'deposit_amount'      => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
            'denomination_counts' => 'nullable|string',
            'shift_name'          => 'nullable|string|max:50',
        ]);

        $difference = (float) $validated['cash_counted'] - (float) $validated['cash_expected'];

        try {
            DB::table('cash_registers')->updateOrInsert(
                ['restaurant_id' => $restaurant->id, 'shift_date' => now()->toDateString()],
                [
                    'user_id'             => auth()->id(),
                    'shift_name'          => $validated['shift_name'] ?? 'completo',
                    'cash_expected'       => $validated['cash_expected'],
                    'cash_counted'        => $validated['cash_counted'],
                    'difference'          => $difference,
                    'deposit_amount'      => $validated['deposit_amount'] ?? 0,
                    'notes'               => $validated['notes'] ?? null,
                    'denomination_counts' => $validated['denomination_counts'] ?? null,
                    'status'              => 'completed',
                    'updated_at'          => now(),
                    'created_at'          => now(),
                ]
            );
        } catch (\Exception $e) {
            // La tabla puede no existir aún
        }

        // ── Si el cierre es positivo (sobrante), registrar como ingreso ──
        // Si lo contado supera lo esperado, el sobrante va a ingresos del restaurante.
        // Si hay depósito bancario, también se registra como un ingreso (movimiento de caja).
        $depositAmount = (float) ($validated['deposit_amount'] ?? 0);
        $cashCounted   = (float) $validated['cash_counted'];

        // Registrar cash_counted como ingreso del día en la tabla orders
        // Así aparece en los dashboards de ingresos diarios, semanales y mensuales
        if ($cashCounted > 0) {
            DB::table('orders')->insert([
                'restaurant_id' => $restaurant->id,
                'table_id'      => null,
                'waiter_id'     => null,
                'status'        => 'completed',
                'total'         => $cashCounted,
                'tips'          => 0,
                'items'         => json_encode([['name' => 'Cierre de Caja — ' . now()->format('d/m/Y'), 'qty' => 1, 'price' => $cashCounted]]),
                'notes'         => 'Ingreso registrado desde cierre de caja. Depósito: $' . number_format($depositAmount, 0, ',', '.'),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Cache bust para que los dashboards reflejen el nuevo cierre
        \Cache::forget("dashboard_stats_{$restaurant->id}");

        $msg = $difference >= 0
            ? 'Cierre de caja registrado con sobrante de $' . number_format(abs($difference), 0, ',', '.') . '. ¡Excelente trabajo!'
            : 'Cierre de caja registrado con faltante de $' . number_format(abs($difference), 0, ',', '.') . '. Revisa el ajuste.';

        return redirect()->route('admin.finance.cierre')->with('success', $msg);
    }

    // ── Preview imprimible del cierre (GET con datos del conteo) ─────
    public function cierrePreview(Request $request)
    {
        $restaurant = $this->restaurant();
        $rid = $restaurant->id;

        $cashCounted   = (float) ($request->cash_counted   ?? 0);
        $depositAmount = (float) ($request->deposit_amount ?? 0);
        $notes         = strip_tags($request->notes ?? '');

        $denominations = [];
        if ($request->denominations) {
            $decoded = json_decode($request->denominations, true);
            if (is_array($decoded)) {
                $denominations = array_filter($decoded, fn ($d) => (int) ($d['qty'] ?? 0) > 0);
            }
        }

        $expectedCash = (float) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        $todayExpenses = (float) DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->where('expense_date', now()->toDateString())
            ->sum('amount');

        $todayOrdersCount = (int) DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $difference = $cashCounted - $expectedCash;

        return view('admin.finanzas.cierre-preview', compact(
            'restaurant', 'cashCounted', 'depositAmount', 'notes',
            'denominations', 'expectedCash', 'todayExpenses',
            'todayOrdersCount', 'difference'
        ));
    }

    // ── Ajustes ──────────────────────────────────────────────────────
    public function ajustes()
    {
        $restaurant = $this->restaurant();
        return view('admin.finanzas.ajustes', compact('restaurant'));
    }

    // ── Export CSV ───────────────────────────────────────────────────
    public function exportCsv()
    {
        $restaurant = $this->restaurant();
        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $orders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->orderBy('created_at')->get();

        $expenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->orderBy('expense_date')->get();

        $filename = 'finanzas_' . now()->format('Y-m') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'no-cache',
        ];

        $callback = function () use ($orders, $expenses, $restaurant) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF"); // BOM para Excel

            fputcsv($f, ['REPORTE FINANCIERO — ' . strtoupper($restaurant->name)]);
            fputcsv($f, ['Período:', now()->locale('es')->isoFormat('MMMM YYYY')]);
            fputcsv($f, ['Generado:', now()->format('d/m/Y H:i')]);
            fputcsv($f, []);

            fputcsv($f, ['=== INGRESOS (ÓRDENES) ===']);
            fputcsv($f, ['ID', 'Fecha', 'Total', 'Estado']);
            foreach ($orders as $o) {
                fputcsv($f, [$o->id, $o->created_at, number_format($o->total, 2, '.', ''), $o->status ?? 'completado']);
            }
            fputcsv($f, ['', 'TOTAL INGRESOS', number_format($orders->sum('total'), 2, '.', ''), '']);
            fputcsv($f, []);

            fputcsv($f, ['=== EGRESOS ===']);
            fputcsv($f, ['Concepto', 'Fecha', 'Monto', 'Categoría', 'Notas']);
            foreach ($expenses as $e) {
                fputcsv($f, [
                    $e->name,
                    $e->expense_date,
                    number_format($e->amount, 2, '.', ''),
                    $e->category ?? 'Operativos',
                    $e->notes ?? '',
                ]);
            }
            fputcsv($f, ['', 'TOTAL EGRESOS', number_format($expenses->sum('amount'), 2, '.', ''), '', '']);
            fputcsv($f, []);

            $totalIngresos = $orders->sum('total');
            $totalEgresos  = $expenses->sum('amount');
            fputcsv($f, ['=== RESUMEN ===']);
            fputcsv($f, ['Ingresos totales',  number_format($totalIngresos, 2, '.', '')]);
            fputcsv($f, ['Egresos totales',   number_format($totalEgresos, 2, '.', '')]);
            fputcsv($f, ['Profit neto',       number_format($totalIngresos - $totalEgresos, 2, '.', '')]);

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Export PDF (vista imprimible) ────────────────────────────────
    public function exportPdf()
    {
        $restaurant = $this->restaurant();
        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $orders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->orderBy('created_at')->get();

        $expenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->orderBy('expense_date')->get();

        $monthRevenue  = (float) $orders->sum('total');
        $monthExpenses = (float) $expenses->sum('amount');
        $netProfit     = $monthRevenue - $monthExpenses;

        // Propinas (Ley 1935)
        $tipLiquidation = DB::table('tips')
            ->join('employees', 'tips.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('tips.restaurant_id', $rid)
            ->whereMonth('tips.date', $month)->whereYear('tips.date', $year)
            ->selectRaw('users.name as employee_name, employees.position,
                SUM(tips.amount) as total_tips,
                SUM(CASE WHEN tips.payment_method = "cash" THEN tips.amount ELSE 0 END) as cash_tips,
                SUM(CASE WHEN tips.payment_method IN ("card","transfer") THEN tips.amount ELSE 0 END) as transfer_pending')
            ->groupBy('employees.id', 'users.name', 'employees.position')
            ->get();

        return view('admin.finanzas.export-pdf', compact(
            'restaurant', 'orders', 'expenses',
            'monthRevenue', 'monthExpenses', 'netProfit', 'tipLiquidation'
        ));
    }
}
