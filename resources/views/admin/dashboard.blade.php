<x-app-layout>

@if(!$restaurant)
    {{-- El DashboardController redirige al onboarding antes de llegar aquí --}}
    <div class="flex items-center justify-center py-20">
        <a href="{{ route('onboarding.step1') }}" class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all">
            <span class="material-symbols-outlined">add_business</span> Configurar mi restaurante
        </a>
    </div>
@else
    {{-- ── Dashboard del administrador ──────────────────────────── --}}

    {{-- Header de bienvenida --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-sm text-on-surface-variant font-semibold uppercase tracking-wider mb-1">
                Panel de administración
            </p>
            <h1 class="text-2xl font-bold font-heading text-on-background">
                Bienvenido, {{ auth()->user()->name }}
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="material-symbols-outlined text-sm text-secondary">store</span>
                <span class="text-sm text-on-surface-variant font-medium">{{ $restaurant->name }}</span>
                <span class="w-1.5 h-1.5 rounded-full {{ $restaurant->status === 'active' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                <span class="text-xs {{ $restaurant->status === 'active' ? 'text-green-600' : 'text-gray-500' }} font-medium">
                    {{ $restaurant->status === 'active' ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-on-surface-variant">Hoy, {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
        </div>
    </div>

    {{-- ── Métricas del día ─────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Pedidos de hoy --}}
        <div class="bg-white rounded-2xl border border-outline-variant p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-primary-container">receipt_long</span>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-2 py-1 rounded-full">Hoy</span>
            </div>
            <p class="text-2xl font-bold font-heading text-on-background">{{ $stats['todayOrders'] }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Pedidos recibidos</p>
        </div>

        {{-- Ingresos de hoy --}}
        <div class="bg-white rounded-2xl border border-outline-variant p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-secondary">payments</span>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-2 py-1 rounded-full">Hoy</span>
            </div>
            <p class="text-2xl font-bold font-heading text-on-background">${{ number_format($stats['todayRevenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Ingresos del día</p>
        </div>

        {{-- Mesas activas --}}
        <div class="bg-white rounded-2xl border border-outline-variant p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-tertiary">table_restaurant</span>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-2 py-1 rounded-full">Ahora</span>
            </div>
            <p class="text-2xl font-bold font-heading text-on-background">
                {{ $stats['activeTables'] }}<span class="text-sm text-on-surface-variant font-normal">/{{ $stats['totalTables'] }}</span>
            </p>
            <p class="text-xs text-on-surface-variant mt-0.5">Mesas ocupadas</p>
        </div>

        {{-- Personal activo --}}
        <div class="bg-white rounded-2xl border border-outline-variant p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-purple-600">groups</span>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-2 py-1 rounded-full">Activo</span>
            </div>
            <p class="text-2xl font-bold font-heading text-on-background">{{ $stats['activeStaff'] }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Empleados</p>
        </div>
    </div>

    {{-- ── Segunda fila de métricas ─────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

        {{-- Ingresos del mes --}}
        <div class="bg-gradient-to-br from-secondary to-green-800 rounded-2xl p-6 text-white">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-white/70">bar_chart</span>
                <span class="text-sm font-semibold text-white/80">
                    Ingresos de {{ now()->locale('es')->isoFormat('MMMM') }}
                </span>
            </div>
            <p class="text-4xl font-bold font-heading">${{ number_format($stats['monthRevenue'], 0, ',', '.') }}</p>
            <p class="text-white/60 text-sm mt-1">Total acumulado del mes</p>
        </div>

        {{-- Platos en menú --}}
        <div class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-6 text-white">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-white/70">menu_book</span>
                <span class="text-sm font-semibold text-white/80">Menú activo</span>
            </div>
            <p class="text-4xl font-bold font-heading">{{ $stats['totalMenuItems'] }}</p>
            <p class="text-white/60 text-sm mt-1">Platos disponibles</p>
        </div>
    </div>

    {{-- ── Fila inferior: Pedidos recientes + Acciones rápidas ──── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pedidos recientes --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between">
                <h2 class="font-semibold text-on-background flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg text-primary-container">receipt_long</span>
                    Pedidos recientes
                </h2>
                <span class="text-xs text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full">
                    Últimos 5
                </span>
            </div>

            @if($stats['recentOrders']->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <span class="material-symbols-outlined text-4xl text-outline mb-3">receipt_long</span>
                    <p class="text-sm font-medium text-on-surface-variant">Sin pedidos aún</p>
                    <p class="text-xs text-outline mt-1">Los pedidos de tus clientes aparecerán aquí</p>
                </div>
            @else
                <div class="divide-y divide-outline-variant">
                    @foreach($stats['recentOrders'] as $order)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-container-low transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center">
                                <span class="text-xs font-bold text-on-surface-variant">#{{ $order->id }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-on-surface">Mesa {{ $order->table_id ?? '—' }}</p>
                                <p class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-on-surface">${{ number_format($order->total, 0) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ match($order->status ?? '') {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                    default     => 'bg-surface-container text-on-surface-variant'
                                } }}">
                                {{ ucfirst($order->status ?? 'pendiente') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Acciones rápidas --}}
        <div class="bg-white rounded-2xl border border-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant">
                <h2 class="font-semibold text-on-background flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg text-primary-container">bolt</span>
                    Acciones rápidas
                </h2>
            </div>
            <div class="p-4 space-y-2">
                @php
                $actions = [
                    ['icon' => 'menu_book',        'label' => 'Gestionar menú',     'color' => 'orange'],
                    ['icon' => 'table_restaurant',  'label' => 'Ver mesas',          'color' => 'blue'],
                    ['icon' => 'groups',            'label' => 'Ver personal',       'color' => 'purple'],
                    ['icon' => 'inventory_2',       'label' => 'Inventario',         'color' => 'yellow'],
                    ['icon' => 'bar_chart',         'label' => 'Reportes financieros','color' => 'green'],
                    ['icon' => 'psychology',        'label' => 'Asistente IA',       'color' => 'indigo'],
                ];
                $colors = [
                    'orange' => 'bg-orange-50 text-primary-container hover:bg-orange-100',
                    'blue'   => 'bg-blue-50 text-tertiary hover:bg-blue-100',
                    'purple' => 'bg-purple-50 text-purple-600 hover:bg-purple-100',
                    'yellow' => 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100',
                    'green'  => 'bg-green-50 text-secondary hover:bg-green-100',
                    'indigo' => 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100',
                ];
                @endphp

                @foreach($actions as $action)
                <button disabled title="Próximamente"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all cursor-not-allowed opacity-60
                           {{ $colors[$action['color']] }}">
                    <span class="material-symbols-outlined text-lg">{{ $action['icon'] }}</span>
                    <span class="text-sm font-medium">{{ $action['label'] }}</span>
                    <span class="ml-auto text-xs opacity-60">Pronto</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

@endif

</x-app-layout>
