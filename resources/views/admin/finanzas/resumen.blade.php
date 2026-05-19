<x-finance-layout :restaurant="$restaurant">
<x-slot name="title">Gestión Financiera</x-slot>
<x-slot name="subtitle">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</x-slot>

{{-- ── Encabezado ─────────────────────────────────────────── --}}
<div class="mb-8">
    <h2 class="text-3xl font-black text-on-surface">Gestión Financiera</h2>
    <p class="text-on-surface-variant mt-1 text-sm">Controla el flujo de caja y la rentabilidad de tu restaurante.</p>
</div>

{{-- ── KPIs del mes ───────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    {{-- Ingresos --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 bg-secondary/10 rounded-xl">
                <span class="material-symbols-outlined text-secondary text-[22px]">trending_up</span>
            </div>
            <span class="text-xs font-bold text-secondary bg-secondary/10 px-2 py-1 rounded-full">Ingresos</span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ingresos del mes</p>
        <p class="text-3xl font-black text-on-surface">${{ number_format($monthRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Órdenes completadas</p>
    </div>

    {{-- Egresos --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 bg-error/10 rounded-xl">
                <span class="material-symbols-outlined text-error text-[22px]">receipt_long</span>
            </div>
            <span class="text-xs font-bold text-error bg-error/10 px-2 py-1 rounded-full">Egresos</span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Egresos del mes</p>
        <p class="text-3xl font-black text-error">${{ number_format($monthExpenses, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Gastos registrados</p>
    </div>

    {{-- Profit --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 rounded-xl {{ $netProfit >= 0 ? 'bg-secondary/10' : 'bg-error/10' }}">
                <span class="material-symbols-outlined text-[22px] {{ $netProfit >= 0 ? 'text-secondary' : 'text-error' }}">
                    {{ $netProfit >= 0 ? 'savings' : 'trending_down' }}
                </span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $netProfit >= 0 ? 'text-secondary bg-secondary/10' : 'text-error bg-error/10' }}">
                Profit
            </span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Profit neto</p>
        <p class="text-3xl font-black {{ $netProfit >= 0 ? 'text-secondary' : 'text-error' }}">
            {{ $netProfit < 0 ? '-' : '' }}${{ number_format(abs($netProfit), 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-2">Ingresos − Egresos</p>
    </div>
</div>

{{-- ── Flujo de Caja ──────────────────────────────────────── --}}
<div class="mb-8">
    <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary-container text-[22px]">account_balance_wallet</span>
        Flujo de Caja
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 group hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="p-2.5 bg-primary-container/20 rounded-xl">
                    <span class="material-symbols-outlined text-primary-container text-[22px]"
                          style="font-variation-settings:'FILL' 1">payments</span>
                </div>
                <span class="text-[10px] font-bold text-secondary bg-secondary/10 px-2 py-1 rounded-full uppercase">Hoy</span>
            </div>
            <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Caja del Día</p>
            <p class="text-2xl font-black text-on-surface">${{ number_format($cajaFisica, 0, ',', '.') }}</p>
            <p class="text-xs text-secondary mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">schedule</span>
                Ventas de hoy
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="p-2.5 bg-tertiary/10 rounded-xl">
                    <span class="material-symbols-outlined text-tertiary text-[22px]"
                          style="font-variation-settings:'FILL' 1">account_balance</span>
                </div>
                <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-full uppercase">Mes</span>
            </div>
            <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ingresos del Mes</p>
            <p class="text-2xl font-black text-on-surface">${{ number_format($semanaRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">sync</span>
                Últimos 7 días
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="p-2.5 bg-secondary-container/30 rounded-xl">
                    <span class="material-symbols-outlined text-on-secondary-container text-[22px]"
                          style="font-variation-settings:'FILL' 1">terminal</span>
                </div>
                <span class="text-[10px] font-bold text-primary bg-primary-container/20 px-2 py-1 rounded-full uppercase">Acumulado</span>
            </div>
            <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Pasarelas / Acumulado</p>
            <p class="text-2xl font-black text-on-surface">${{ number_format($pasarelas, 0, ',', '.') }}</p>
            <p class="text-xs text-error mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">history_toggle_off</span>
                Pendiente de consolidar
            </p>
        </div>
    </div>
</div>

{{-- ── Gráficas ────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-8">
    {{-- Barra: Ingresos vs Egresos --}}
    <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-on-surface">Ingresos vs Egresos</h3>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-secondary inline-block"></span>
                    Ingresos
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-primary-container inline-block"></span>
                    Egresos
                </span>
            </div>
        </div>
        <div class="relative h-52">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    {{-- Donut: Desglose de gastos --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-on-surface mb-5">Desglose de Gastos</h3>
        @if($expenseChart->isNotEmpty())
        <div class="flex items-center gap-4">
            <div class="relative w-32 h-32 shrink-0">
                <canvas id="donutChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[10px] text-gray-400 uppercase font-bold">Total</span>
                    <span class="text-base font-black text-on-surface">${{ number_format($monthExpenses/1000, 0) }}k</span>
                </div>
            </div>
            <div class="flex-1 space-y-2 min-w-0">
                @php $donutColors = ['#006c49','#006398','#f97316','#8c7164','#9d4300']; @endphp
                @foreach($expenseChart as $i => $cat)
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0"
                              style="background:{{ $donutColors[$i % count($donutColors)] }}"></span>
                        <span class="text-xs text-on-surface truncate">{{ $cat['label'] }}</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface shrink-0">{{ $cat['pct'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-32 text-gray-400 text-sm">
            <span class="material-symbols-outlined text-3xl mb-2">pie_chart</span>
            Sin egresos este mes
        </div>
        @endif
    </div>
</div>

{{-- ── Transacciones Recientes ────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-primary-container">swap_vert</span>
            Transacciones Recientes
        </h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.ingresos') }}"
               class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                Ver ingresos
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>
    </div>

    @if($recentTransactions->isEmpty())
    <div class="py-12 text-center text-gray-400 text-sm">Sin transacciones registradas.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-400">
                <tr>
                    <th class="px-6 py-3">Concepto</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Método</th>
                    <th class="px-6 py-3 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentTransactions as $tx)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0
                                        {{ $tx['type'] === 'ingreso' ? 'bg-secondary/10' : 'bg-error/10' }}">
                                <span class="material-symbols-outlined text-[18px]
                                             {{ $tx['type'] === 'ingreso' ? 'text-secondary' : 'text-error' }}">
                                    {{ $tx['type'] === 'ingreso' ? 'restaurant' : 'shopping_cart_checkout' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-on-surface">{{ $tx['concept'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                     {{ $tx['type'] === 'ingreso' ? 'bg-secondary/10 text-secondary' : 'bg-gray-100 text-gray-500' }}">
                            {{ $tx['category'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ \Carbon\Carbon::parse($tx['date'])->format('d M, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $tx['method'] }}</td>
                    <td class="px-6 py-4 text-right font-bold text-sm
                               {{ $tx['type'] === 'ingreso' ? 'text-secondary' : 'text-error' }}">
                        {{ $tx['type'] === 'ingreso' ? '+' : '-' }}${{ number_format($tx['amount'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
<script>
const weekLabels     = @json($weekLabels->values());
const weeklyIngresos = @json($weeklyIngresos->values());
const weeklyEgresos  = @json($weeklyEgresos->values());
const expenseChart   = @json($expenseChart);

// ── Gráfica de barras ──────────────────────────────────────────
const barCtx = document.getElementById('barChart')?.getContext('2d');
if (barCtx) {
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: weekLabels,
            datasets: [
                {
                    label: 'Ingresos',
                    data: weeklyIngresos,
                    backgroundColor: '#006c49',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Egresos',
                    data: weeklyEgresos,
                    backgroundColor: '#f97316',
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                y: {
                    grid: { color: '#f3f4f6' },
                    border: { display: false },
                    ticks: {
                        callback: v => '$' + (v/1000).toFixed(0) + 'k',
                        font: { size: 11 },
                        color: '#9ca3af',
                    },
                },
            },
            animation: { duration: 800, easing: 'easeOutQuart' },
        },
    });
}

// ── Gráfica donut ──────────────────────────────────────────────
const donutCtx = document.getElementById('donutChart')?.getContext('2d');
if (donutCtx && expenseChart.length) {
    const COLORS = ['#006c49','#006398','#f97316','#8c7164','#9d4300'];
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: expenseChart.map(c => c.label),
            datasets: [{
                data: expenseChart.map(c => c.amount),
                backgroundColor: expenseChart.map((_, i) => COLORS[i % COLORS.length]),
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderColor: '#ffffff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { callbacks: {
                label: ctx => ' $' + ctx.raw.toLocaleString('es-CO') + ' (' + expenseChart[ctx.dataIndex].pct + '%)',
            }}},
            animation: { duration: 1000, easing: 'easeOutQuart' },
        },
    });
}
</script>
</script>

</x-finance-layout>
