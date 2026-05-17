<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Finanzas' }} — GoToEat</title>
    @include('partials.head-assets')
    @livewireStyles
</head>
<body class="bg-background font-body text-on-background antialiased">

{{-- ══ SIDEBAR FINANZAS ══════════════════════════════════════════ --}}
<aside class="w-64 h-screen fixed left-0 top-0 bg-surface-container-low border-r border-outline-variant/20 flex flex-col py-6 px-4 shadow-lg z-50">

    {{-- Logo --}}
    <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
            <span class="material-symbols-outlined text-white text-[22px]"
                  style="font-variation-settings:'FILL' 1">account_balance_wallet</span>
        </div>
        <div>
            <p class="text-xl font-black text-primary tracking-tight leading-none">GoToEat</p>
            <p class="text-[10px] uppercase tracking-widest text-on-surface-variant mt-0.5">Gestión Financiera</p>
        </div>
    </div>

    {{-- Navegación del módulo --}}
    <nav class="flex-1 space-y-1">
        @php
        $finNav = [
            ['route' => 'admin.finance',          'icon' => 'dashboard',      'label' => 'Resumen'],
            ['route' => 'admin.finance.ingresos',  'icon' => 'trending_up',    'label' => 'Ingresos'],
            ['route' => 'admin.finance.gastos',    'icon' => 'receipt_long',   'label' => 'Gastos'],
            ['route' => 'admin.finance.cierre',    'icon' => 'point_of_sale',  'label' => 'Cierre de Caja'],
        ];
        @endphp

        @foreach ($finNav as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.98]
                  {{ $active
                     ? 'bg-primary-container text-white font-bold shadow-md shadow-orange-200/50'
                     : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined text-[22px]"
                  @if($active) style="font-variation-settings:'FILL' 1" @endif>{{ $item['icon'] }}</span>
            <span class="text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    {{-- Parte inferior --}}
    <div class="mt-auto space-y-1 border-t border-outline-variant/20 pt-4">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant hover:text-primary hover:bg-orange-50 transition-all duration-200">
            <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            <span class="text-sm font-medium">Volver al Panel</span>
        </a>
        <a href="{{ route('admin.finance.ajustes') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ request()->routeIs('admin.finance.ajustes')
                     ? 'text-primary font-bold bg-orange-50'
                     : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined text-[22px]">settings</span>
            <span class="text-sm">Ajustes</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-error hover:bg-red-50 transition-all duration-200">
                <span class="material-symbols-outlined text-[22px]">logout</span>
                <span class="text-sm">Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>

{{-- ══ TOPBAR ═══════════════════════════════════════════════════ --}}
<header class="fixed top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm flex justify-between items-center h-16 px-8"
        style="left:16rem;right:0;">
    <div class="flex items-center gap-4">
        <h1 class="font-semibold text-lg text-primary">{{ $title ?? 'Gestión Financiera' }}</h1>
        @isset($subtitle)
        <span class="hidden lg:block text-sm text-on-surface-variant">{{ $subtitle }}</span>
        @endisset
    </div>

    <div class="flex items-center gap-3"
         x-data="{ exportOpen: false }"
         @click.outside="exportOpen = false">

        {{-- Exportar Datos dropdown --}}
        <div class="relative">
            <button @click="exportOpen = !exportOpen"
                    class="flex items-center gap-2 px-4 py-2 bg-primary-container text-white rounded-xl font-semibold text-sm
                           hover:bg-primary active:scale-[0.97] transition-all shadow-sm shadow-orange-200">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Exportar Datos
                <span class="material-symbols-outlined text-[16px] transition-transform duration-200"
                      :class="exportOpen ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="exportOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-50">
                    Mes actual
                </div>
                <a href="{{ route('admin.finance.export.csv') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface hover:bg-orange-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-secondary">table_chart</span>
                    <div>
                        <p class="font-semibold">Descargar CSV</p>
                        <p class="text-[11px] text-gray-400">Compatible con Excel</p>
                    </div>
                </a>
                <div class="h-px bg-gray-100 mx-4"></div>
                <a href="{{ route('admin.finance.export.pdf') }}" target="_blank"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface hover:bg-orange-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-error">picture_as_pdf</span>
                    <div>
                        <p class="font-semibold">Imprimir / PDF</p>
                        <p class="text-[11px] text-gray-400">Abre en nueva pestaña</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="h-7 w-px bg-gray-200"></div>

        {{-- Avatar + nombre --}}
        <div class="flex items-center gap-3">
            <div class="text-right hidden lg:block">
                <p class="font-semibold text-sm leading-none text-on-background">{{ auth()->user()->name }}</p>
                @isset($restaurant)
                <p class="text-[10px] text-gray-400 mt-0.5">{{ $restaurant->name }}</p>
                @endisset
            </div>
            <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>

{{-- ══ CONTENIDO ════════════════════════════════════════════════ --}}
<main class="min-h-screen pt-16" style="margin-left:16rem;">
    <div class="p-8 max-w-[1280px] mx-auto">

        {{-- Flash --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-semibold text-secondary">{{ session('success') }}</p>
        </div>
        @endif

        {{ $slot }}
    </div>
</main>

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
