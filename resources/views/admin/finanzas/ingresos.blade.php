<x-finance-layout :restaurant="$restaurant">
<x-slot name="title">Ingresos</x-slot>
<x-slot name="subtitle">Detalle de ventas y tendencias</x-slot>

{{-- ── Header + Período ───────────────────────────────────── --}}
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-3xl font-black text-on-surface">Detalle de Ingresos</h2>
        <p class="text-on-surface-variant text-sm mt-1">Analiza el rendimiento de ventas en tiempo real.</p>
    </div>
    {{-- Selector de período --}}
    <div class="flex items-center bg-surface-container-low p-1 rounded-xl border border-outline-variant/20 gap-1">
        @foreach(['day' => 'Hoy', 'week' => 'Semana', 'month' => 'Mes'] as $key => $label)
        <a href="{{ route('admin.finance.ingresos', ['period' => $key]) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                  {{ $period === $key
                     ? 'bg-white text-primary shadow-sm border border-outline-variant/20'
                     : 'text-on-surface-variant hover:text-on-surface' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- ── KPIs ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    {{-- Ventas totales --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 bg-primary-container/20 rounded-xl">
                <span class="material-symbols-outlined text-primary-container text-[22px]">payments</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full
                         {{ $revenueTrend >= 0 ? 'text-secondary bg-secondary/10' : 'text-error bg-error/10' }}
                         flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">
                    {{ $revenueTrend >= 0 ? 'trending_up' : 'trending_down' }}
                </span>
                {{ $revenueTrend >= 0 ? '+' : '' }}{{ $revenueTrend }}%
            </span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ventas Totales</p>
        <p class="text-3xl font-black text-on-surface">${{ number_format($periodRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">vs período anterior</p>
    </div>

    {{-- Ticket promedio --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 bg-tertiary/10 rounded-xl">
                <span class="material-symbols-outlined text-tertiary text-[22px]">receipt</span>
            </div>
            <span class="text-xs font-bold text-secondary bg-secondary/10 px-2 py-1 rounded-full">Promedio</span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ticket Promedio</p>
        <p class="text-3xl font-black text-on-surface">${{ number_format($avgTicket, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Basado en {{ number_format($periodCount) }} órdenes</p>
    </div>

    {{-- N° órdenes --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:-translate-y-0.5 transition-transform">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 bg-secondary/10 rounded-xl">
                <span class="material-symbols-outlined text-secondary text-[22px]">format_list_numbered</span>
            </div>
            <span class="text-xs font-bold text-primary bg-primary-container/20 px-2 py-1 rounded-full">Órdenes</span>
        </div>
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Total Órdenes</p>
        <p class="text-3xl font-black text-on-surface">{{ number_format($periodCount) }}</p>
        <p class="text-xs text-gray-400 mt-2">
            @if($period === 'day') Hoy
            @elseif($period === 'week') Últimos 7 días
            @else Este mes
            @endif
        </p>
    </div>
</div>

{{-- ── Tendencia + Historial ──────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    {{-- Gráfica --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-on-surface">Tendencia de Ventas</h3>
            <span class="text-xs text-gray-400">Últimos 7 días</span>
        </div>
        <div class="relative h-52">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Card resumen rápido --}}
    <div class="bg-primary-container rounded-2xl p-6 shadow-lg text-white flex flex-col justify-between">
        <div>
            <span class="material-symbols-outlined text-4xl mb-3 block" style="font-variation-settings:'FILL' 1">bar_chart</span>
            <h3 class="font-bold text-xl mb-2">Rendimiento</h3>
            <p class="text-white/80 text-sm leading-relaxed">
                {{ $periodCount > 0
                   ? 'Promedio de $' . number_format($avgTicket, 0, ',', '.') . ' por orden en este período.'
                   : 'Sin ventas en este período aún.' }}
            </p>
        </div>
        <div class="space-y-2 mt-6">
            <div class="flex justify-between items-center py-2 border-t border-white/20">
                <span class="text-white/70 text-sm">Período</span>
                <span class="font-bold text-sm">
                    @if($period === 'day') Hoy
                    @elseif($period === 'week') 7 días
                    @else Este mes
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-t border-white/20">
                <span class="text-white/70 text-sm">Tendencia</span>
                <span class="font-bold text-sm {{ $revenueTrend >= 0 ? '' : 'text-red-200' }}">
                    {{ $revenueTrend >= 0 ? '▲' : '▼' }} {{ abs($revenueTrend) }}%
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ── Historial de Ventas ─────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-primary-container">history</span>
            Historial de Ventas
            <span class="text-xs font-normal text-gray-400 ml-1">({{ $orders->total() }} registros)</span>
        </h3>
        <a href="{{ route('admin.finance.export.csv') }}"
           class="flex items-center gap-2 px-3 py-1.5 bg-primary-container text-white rounded-lg text-xs font-bold hover:bg-primary transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px]">download</span>
            Exportar CSV
        </a>
    </div>

    @if($orders->isEmpty())
    <div class="py-12 text-center text-gray-400 text-sm">Sin ventas en este período.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID Venta</th>
                    <th class="px-6 py-3">Fecha & Hora</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-sm text-on-surface">#GT-{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @php $st = $order->status ?? 'completado'; @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full
                            {{ $st === 'completed' ? 'bg-secondary/10 text-secondary'
                              : ($st === 'pending'  ? 'bg-amber-100 text-amber-700'
                              : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($st) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-sm text-secondary">
                        ${{ number_format($order->total, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Mostrando {{ $orders->firstItem() }}–{{ $orders->lastItem() }} de {{ $orders->total() }}
        </p>
        <div class="flex items-center gap-1">
            @if($orders->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center text-gray-300 rounded-lg border border-gray-200">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $orders->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-orange-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            @foreach($orders->getUrlRange(max(1,$orders->currentPage()-1), min($orders->lastPage(),$orders->currentPage()+1)) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-container text-white font-bold text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-sm hover:bg-gray-100 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-orange-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center text-gray-300 rounded-lg border border-gray-200">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
<script>
const trendLabels = @json($trendLabels->values());
const trendData   = @json($trendData->values());

const trendCtx = document.getElementById('trendChart')?.getContext('2d');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Ventas',
                data: trendData,
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f97316',
                pointRadius: 4,
                pointHoverRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                y: {
                    grid: { color: '#f3f4f6' },
                    border: { display: false },
                    ticks: { callback: v => '$' + (v/1000).toFixed(0) + 'k', color: '#9ca3af', font: { size: 11 } },
                },
            },
            animation: { duration: 900, easing: 'easeOutQuart' },
        },
    });
}
</script>
</script>

</x-finance-layout>
