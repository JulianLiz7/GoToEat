<x-admin-layout :restaurant="$restaurant" :stats="$stats">
<x-slot name="title">Dashboard Operativo</x-slot>

{{-- ── Encabezado de página ────────────────────────────────────── --}}
<div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Dashboard Operativo</h2>
        <p class="text-gray-500 mt-1">Resumen de rendimiento en tiempo real para <span class="font-semibold text-orange-500">{{ $restaurant->name }}</span>.</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <span class="text-xs text-gray-400 font-semibold hidden sm:block">Exportar:</span>
        <a href="{{ route('admin.export.pdf') }}" target="_blank"
           class="flex items-center gap-2 px-4 py-2.5 bg-white text-error rounded-xl shadow-sm border border-red-100
                  font-bold text-sm hover:bg-red-50 hover:shadow-md active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
            PDF
        </a>
        <a href="{{ route('admin.export.csv') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-primary-container text-white rounded-xl shadow-sm shadow-orange-200
                  font-bold text-sm hover:bg-primary hover:shadow-md active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">table_chart</span>
            CSV
        </a>
    </div>
</div>

{{-- ── KPI Cards ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Ingresos del mes --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm shadow-gray-200/50 border-b-2 border-transparent hover:border-orange-500 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-50 text-orange-500 rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
            </div>
            @php $trend = $stats['revenueTrend']; @endphp
            <span class="text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1
                {{ $trend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                <span class="material-symbols-outlined text-[14px]">{{ $trend >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
            </span>
        </div>
        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Ingresos del mes</p>
        <p class="text-2xl font-bold font-heading text-on-background">${{ number_format($stats['monthRevenue'], 0, ',', '.') }}</p>
    </div>

    {{-- Egresos del mes --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm shadow-gray-200/50 border-b-2 border-transparent hover:border-red-400 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 text-red-500 rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">shopping_cart_checkout</span>
            </div>
            <span class="text-xs font-bold bg-red-50 text-red-500 px-2.5 py-1 rounded-full flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                Este mes
            </span>
        </div>
        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Egresos del mes</p>
        <p class="text-2xl font-bold font-heading text-red-600">${{ number_format($stats['monthExpenses'], 0, ',', '.') }}</p>
    </div>

    {{-- Profit neto --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm shadow-gray-200/50 border-b-2 border-transparent hover:border-emerald-500 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-50 text-emerald-500 rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">account_balance_wallet</span>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1
                {{ $stats['netProfit'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                <span class="material-symbols-outlined text-[14px]">{{ $stats['netProfit'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                Neto
            </span>
        </div>
        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Profit neto</p>
        <p class="text-2xl font-bold font-heading {{ $stats['netProfit'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
            ${{ number_format(abs($stats['netProfit']), 0, ',', '.') }}
        </p>
    </div>

    {{-- Propinas del mes --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm shadow-gray-200/50 border-b-2 border-transparent hover:border-purple-500 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-purple-50 text-purple-500 rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">volunteer_activism</span>
            </div>
            <span class="text-xs font-bold bg-purple-50 text-purple-500 px-2.5 py-1 rounded-full flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">gavel</span>
                Ley 1935
            </span>
        </div>
        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Propinas del mes</p>
        <p class="text-2xl font-bold font-heading text-purple-600">${{ number_format($stats['monthTips'], 0, ',', '.') }}</p>
    </div>

    {{-- Reservas de hoy --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm shadow-gray-200/50 border-b-2 border-transparent hover:border-sky-500 hover:shadow-md transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-sky-50 text-sky-500 rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">event_seat</span>
            </div>
            <a href="{{ route('admin.reservations', ['status' => 'today']) }}"
               class="text-xs font-bold bg-sky-50 text-sky-500 px-2.5 py-1 rounded-full flex items-center gap-1 hover:bg-sky-100 transition-colors">
                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                Ver todas
            </a>
        </div>
        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Reservas hoy</p>
        <p class="text-2xl font-bold font-heading text-sky-600">{{ $stats['todayReservationsCount'] ?? 0 }}</p>
    </div>
</div>

{{-- ── Gráficas ─────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Bar Chart: Ingresos vs Egresos --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm shadow-gray-200/50">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold font-heading">Ingresos vs Egresos</h3>
            <span class="text-xs text-gray-400 font-medium bg-gray-50 px-3 py-1.5 rounded-full">Últimos 7 días</span>
        </div>
        <div class="flex gap-4 mb-4 text-xs">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-orange-500 inline-block"></span>Ingresos</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-red-400 inline-block"></span>Egresos</span>
        </div>
        <div class="relative h-56">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    {{-- Line Chart: Órdenes por día --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm shadow-gray-200/50">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold font-heading">Órdenes por Día</h3>
            <div class="flex items-center gap-2 text-xs">
                <span class="w-3 h-3 rounded-full bg-orange-500 inline-block"></span>
                <span class="text-gray-500">Pedidos</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Fila inferior ────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Productos activos --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 group hover:shadow-md transition-shadow">
        <div class="w-16 h-16 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
            <span class="material-symbols-outlined text-3xl">menu_book</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Productos activos</p>
            <p class="text-3xl font-bold font-heading mt-1">{{ $stats['totalMenuItems'] }}</p>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[11px] text-emerald-600 font-semibold">Sistema Online</span>
            </div>
        </div>
    </div>

    {{-- Empleados --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 group hover:shadow-md transition-shadow">
        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
            <span class="material-symbols-outlined text-3xl">badge</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Empleados activos</p>
            <p class="text-3xl font-bold font-heading mt-1">{{ $stats['activeStaff'] }}</p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $stats['shiftStaff'] }} en turno actual</p>
        </div>
    </div>

    {{-- Mesas disponibles --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 group hover:shadow-md transition-shadow">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
            <span class="material-symbols-outlined text-3xl">event_seat</span>
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Mesas disponibles</p>
            <p class="text-3xl font-bold font-heading mt-1">
                {{ $stats['availableTables'] }}
                <span class="text-base font-normal text-gray-400">/ {{ $stats['totalTables'] }}</span>
            </p>
            @if($stats['totalTables'] > 0)
            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                     style="width: {{ $stats['totalTables'] > 0 ? round(($stats['availableTables'] / $stats['totalTables']) * 100) : 0 }}%"></div>
            </div>
            @endif
        </div>
    </div>

    {{-- Distribución de ventas --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Distribución</p>

        @php
        $distribution = $stats['categoryDistribution'] ?? collect();
        $chartColors  = ['#f97316','#006398','#006c49','#a855f7','#f59e0b','#ec4899','#0ea5e9','#84cc16'];
        @endphp

        @if($distribution->isNotEmpty())
        {{-- Donut chart --}}
        <div class="relative flex items-center justify-center mb-4">
            <canvas id="donutChart" width="120" height="120" style="max-width:120px;max-height:120px"></canvas>
            <div class="absolute text-center pointer-events-none">
                <p class="text-xl font-black font-heading text-on-surface leading-none">100%</p>
                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">Ventas</p>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="space-y-2">
            @foreach($distribution->take(4) as $cat => $pct)
            @php $color = $chartColors[$loop->index % count($chartColors)]; @endphp
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $color }}"></span>
                    <span class="text-xs text-gray-600 truncate">{{ $cat }}</span>
                </div>
                <span class="text-xs font-bold text-gray-700 shrink-0 ml-2">{{ $pct }}%</span>
            </div>
            @endforeach
            @if($distribution->count() > 4)
            <p class="text-[10px] text-gray-400 text-center">+{{ $distribution->count() - 4 }} más</p>
            @endif
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-32 text-center">
            <span class="material-symbols-outlined text-3xl text-gray-200 mb-2">donut_large</span>
            <p class="text-xs text-gray-400">Sin datos de distribución aún</p>
        </div>
        @endif
    </div>
</div>

{{-- ── Pedidos recientes ────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-on-background flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-primary-container">receipt_long</span>
            Pedidos recientes
        </h3>
        <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Últimos 5</span>
    </div>

    @if($stats['recentOrders']->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-center px-6">
            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">receipt_long</span>
            <p class="text-sm font-medium text-gray-400">Sin pedidos aún</p>
            <p class="text-xs text-gray-300 mt-1">Los pedidos aparecerán aquí en tiempo real</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($stats['recentOrders'] as $order)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-orange-50 flex items-center justify-center">
                        <span class="text-xs font-bold text-orange-600">#{{ $order->id }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-on-surface">Mesa {{ $order->table_id ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold">${{ number_format($order->total, 0) }}</p>
                    <span class="text-[11px] px-2 py-0.5 rounded-full font-medium
                        {{ match($order->status ?? '') {
                            'completed' => 'bg-emerald-100 text-emerald-700',
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'preparing' => 'bg-blue-100 text-blue-700',
                            'cancelled' => 'bg-red-100 text-red-600',
                            default     => 'bg-gray-100 text-gray-500'
                        } }}">
                        {{ match($order->status ?? '') {
                            'completed' => 'Completado',
                            'pending'   => 'Pendiente',
                            'preparing' => 'Preparando',
                            'cancelled' => 'Cancelado',
                            default     => ucfirst($order->status ?? 'Desconocido')
                        } }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>


@push('scripts')
<script>
// ── Bar Chart: Ingresos vs Egresos ────────────────────────────────
const barCtx = document.getElementById('barChart');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: @json($stats['weekLabels']->values()),
        datasets: [
            {
                label: 'Ingresos',
                data: @json($stats['weeklyRevenue']->values()),
                backgroundColor: '#f97316',
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'Egresos',
                data: @json($stats['weeklyExpenses']->values()),
                backgroundColor: '#fca5a5',
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
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
                ticks: { callback: v => '$' + v.toLocaleString() }
            }
        }
    }
});

// ── Line Chart: Órdenes por día ───────────────────────────────────
const lineCtx = document.getElementById('lineChart');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: @json($stats['chartLabels']->values()),
        datasets: [{
            label: 'Pedidos',
            data: @json($stats['dailyOrders']->values()),
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,0.08)',
            borderWidth: 3,
            pointBackgroundColor: '#f97316',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { maxTicksLimit: 5, font: { size: 11 } }
            },
            y: {
                grid: { color: '#f3f4f6' },
                border: { display: false },
                ticks: { stepSize: 1, font: { size: 11 } },
                beginAtZero: true,
            }
        }
    }
});

// ── Donut Chart: Distribución por categoría ───────────────────────
@php
$distribution = $stats['categoryDistribution'] ?? collect();
$chartColors  = ['#f97316','#006398','#006c49','#a855f7','#f59e0b','#ec4899','#0ea5e9','#84cc16'];
@endphp

@if($distribution->isNotEmpty())
const donutCtx = document.getElementById('donutChart');
if (donutCtx) {
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: @json($distribution->keys()->values()),
            datasets: [{
                data:            @json($distribution->values()->values()),
                backgroundColor: @json(array_values(array_slice($chartColors, 0, $distribution->count()))),
                borderWidth:     3,
                borderColor:     '#ffffff',
                hoverBorderWidth: 4,
                hoverOffset:     6,
            }]
        },
        options: {
            responsive:          false,
            cutout:              '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed}%`
                    }
                }
            },
            animation: {
                // Animación de entrada: el donut se dibuja desde 0 con ease-out
                animateRotate:  true,
                animateScale:   false,
                duration:       1000,
                easing:         'easeOutQuart',
            },
        }
    });
}
@endif
</script>
@endpush

</x-admin-layout>
