<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel' }} — GoToEat Admin</title>
    @include('partials.head-assets')
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
</head>
<body class="bg-background font-body text-on-background antialiased">

{{-- ══ SIDEBAR FIJO ══════════════════════════════════════════════ --}}
<aside class="w-64 h-screen fixed left-0 top-0 border-r border-gray-100 bg-white flex flex-col py-6 px-4 shadow-lg shadow-gray-200/50 z-50 font-body text-sm font-medium">

    {{-- Logo --}}
    <div class="mb-10 px-2 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1">restaurant</span>
        </div>
        <div>
            <p class="text-2xl font-black text-orange-500 tracking-tight leading-none">GoToEat</p>
            <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-0.5">Restaurant Management</p>
        </div>
    </div>

    {{-- Navegación principal --}}
    <nav class="flex-1 space-y-1">
        @php
        $navItems = [
            ['route' => 'dashboard',       'icon' => 'dashboard',        'label' => 'Dashboard'],
            ['route' => 'admin.inventory', 'icon' => 'inventory_2',      'label' => 'Inventario'],
            ['route' => 'admin.menu',      'icon' => 'restaurant_menu',  'label' => 'Menú'],
            ['route' => 'admin.staff',     'icon' => 'group',            'label' => 'Personal'],
            ['route' => 'admin.tables',    'icon' => 'table_restaurant', 'label' => 'Mesas'],
            ['route' => 'admin.finance',   'icon' => 'bar_chart',        'label' => 'Finanzas'],
            ['route' => 'admin.ai',        'icon' => 'smart_toy',        'label' => 'AI Assistant'],
        ];
        @endphp

        @foreach ($navItems as $item)
            @if (Route::has($item['route']))
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98]
                      {{ $active
                          ? 'text-orange-600 font-bold bg-orange-50 border-r-4 border-orange-500'
                          : 'text-gray-500 hover:text-orange-500 hover:bg-gray-50' }}">
                <span class="material-symbols-outlined text-[22px]">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
            @endif
        @endforeach
    </nav>

    {{-- Parte inferior --}}
    <div class="mt-auto space-y-1 border-t border-gray-100 pt-4">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ request()->routeIs('profile.*') ? 'text-orange-600 font-bold bg-orange-50 border-r-4 border-orange-500' : 'text-gray-500 hover:text-orange-500 hover:bg-gray-50' }}">
            <span class="material-symbols-outlined text-[22px]">settings</span>
            <span>Configuración</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-error hover:bg-red-50 transition-all duration-200">
                <span class="material-symbols-outlined text-[22px]">logout</span>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>

{{-- ══ TOPBAR ═══════════════════════════════════════════════════ --}}
<header class="fixed top-0 z-40 border-b border-gray-100 bg-white/80 backdrop-blur-md flex justify-between items-center h-16 px-8 shadow-sm"
        style="left:16rem;right:0;">
    <div class="flex items-center gap-4">
        <div class="relative group w-72">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px] group-focus-within:text-orange-500 transition-colors">search</span>
            <input class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-full text-sm focus:ring-2 focus:ring-orange-500/20"
                   placeholder="Buscar en el panel..."
                   type="text"/>
        </div>
    </div>
    <div class="flex items-center gap-4">
        {{-- Alerta stock bajo --}}
        @if(isset($stats['lowStockCount']) && $stats['lowStockCount'] > 0)
        @if(Route::has('admin.inventory'))
        <a href="{{ route('admin.inventory') }}"
           class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold hover:bg-amber-100 transition-colors">
            <span class="material-symbols-outlined text-[16px]">warning</span>
            {{ $stats['lowStockCount'] }} con stock bajo
        </a>
        @endif
        @endif

        <div class="h-7 w-px bg-gray-200"></div>

        {{-- Usuario --}}
        <div class="flex items-center gap-3">
            <div class="text-right hidden lg:block">
                <p class="font-semibold text-sm leading-none text-on-background">{{ auth()->user()->name }}</p>
                @if(isset($restaurant))
                <p class="text-[10px] text-gray-400 mt-0.5">{{ $restaurant->name }}</p>
                @endif
            </div>
            <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>

{{-- ══ CONTENIDO PRINCIPAL ══════════════════════════════════════ --}}
<main class="min-h-screen pt-16" style="margin-left:16rem;">
    <div class="p-8 max-w-[1280px] mx-auto">
        {{ $slot }}
    </div>
</main>

@livewireScripts
@stack('scripts')
</body>
</html>
