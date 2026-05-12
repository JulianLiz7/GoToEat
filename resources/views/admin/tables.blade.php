<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Mesas</x-slot>

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
    <div>
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.2em]">Management</span>
        <h1 class="text-5xl font-black font-heading text-on-surface mt-1">Floor Plan</h1>
        <p class="text-gray-500 mt-2 text-lg">Real-time table occupancy and layout management.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex bg-white p-1 rounded-xl shadow-sm border border-gray-100">
            <button class="px-5 py-2 bg-orange-50 text-orange-600 rounded-lg font-bold text-sm">Grid View</button>
            <button class="px-5 py-2 text-gray-500 rounded-lg font-medium text-sm hover:bg-gray-50 transition-all">List View</button>
        </div>
        <a href="{{ route('admin.tables.create') }}"
           class="flex items-center gap-2 bg-on-surface text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-gray-200 hover:scale-[0.98] transition-transform">
            <span class="material-symbols-outlined text-lg">add</span>
            Add Table
        </a>
    </div>
</div>

{{-- ══ Filtros de zona + capacidad live ════════════════════════ --}}
@php
$zones = $tables->pluck('zone')->filter()->unique()->values();
$totalTables    = $tables->count();
$occupiedTables = $tables->where('status', 'ocupada')->count();
$capacityPct    = $totalTables > 0 ? round(($occupiedTables / $totalTables) * 100) : 0;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    {{-- Zona filter --}}
    <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg font-heading">Active Zones</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            <button class="px-5 py-2.5 bg-orange-500 text-white rounded-xl font-bold text-sm shadow-md shadow-orange-100">
                All Tables ({{ $totalTables }})
            </button>
            @foreach($zones as $zone)
            <button class="px-5 py-2.5 bg-gray-50 text-gray-600 border border-gray-100 rounded-xl font-medium text-sm hover:border-orange-200 hover:text-orange-500 transition-all">
                {{ $zone }} ({{ $tables->where('zone', $zone)->count() }})
            </button>
            @endforeach
        </div>
    </div>

    {{-- Capacidad live --}}
    <div class="bg-on-surface text-white p-6 rounded-2xl shadow-xl relative overflow-hidden flex flex-col justify-center">
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Live Capacity</p>
            <h4 class="text-4xl font-black font-heading">{{ $capacityPct }}%</h4>
            <div class="mt-4 flex gap-1.5">
                @for ($i = 0; $i < 4; $i++)
                <span class="flex-1 h-1.5 rounded-full {{ $i < round($capacityPct / 25) ? 'bg-orange-500' : 'bg-white/20' }}"></span>
                @endfor
            </div>
            <p class="text-xs text-gray-400 mt-3">{{ $occupiedTables }} of {{ $totalTables }} tables occupied</p>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-9xl">restaurant</span>
        </div>
    </div>
</div>

{{-- ══ Grid de mesas ═════════════════════════════════════════════ --}}
@if($tables->isEmpty())
<button onclick="window.location='{{ route('admin.tables.create') }}'"
        class="w-full border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center p-20 hover:border-orange-300 hover:bg-orange-50/50 transition-all group">
    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-orange-100 transition-colors mb-4">
        <span class="material-symbols-outlined text-3xl text-gray-400 group-hover:text-orange-500">add</span>
    </div>
    <p class="font-bold text-on-surface">Add New Table</p>
    <p class="text-xs text-gray-400 mt-1">Configure layout &amp; capacity</p>
</button>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    @foreach($tables as $table)
    @php
    $statusConfig = match($table->status) {
        'disponible'    => ['dot' => 'bg-emerald-500', 'label' => 'Available',    'color' => 'text-emerald-600', 'zone_bg' => 'bg-emerald-50 text-emerald-700'],
        'ocupada'       => ['dot' => 'bg-orange-500',  'label' => 'Occupied',     'color' => 'text-orange-600',  'zone_bg' => 'bg-gray-100 text-gray-500'],
        'reservada'     => ['dot' => 'bg-tertiary',    'label' => 'Reserved',     'color' => 'text-tertiary',    'zone_bg' => 'bg-gray-100 text-gray-500'],
        'mantenimiento' => ['dot' => 'bg-gray-400',    'label' => 'Maintenance',  'color' => 'text-gray-500',    'zone_bg' => 'bg-gray-100 text-gray-500'],
        default         => ['dot' => 'bg-gray-400',    'label' => $table->status, 'color' => 'text-gray-500',    'zone_bg' => 'bg-gray-100 text-gray-500'],
    };
    @endphp

    {{-- Tarjeta de mesa con hover reveal --}}
    <div class="relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 group overflow-hidden
                hover:-translate-y-1 hover:shadow-lg transition-all duration-300"
         style="perspective:1000px">

        {{-- Zona + Número + Capacidad --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                @if($table->zone)
                <span class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter mb-1 inline-block {{ $statusConfig['zone_bg'] }}">
                    {{ $table->zone }}
                </span>
                @endif
                <h3 class="text-2xl font-black font-heading text-on-surface">Table {{ $table->number }}</h3>
            </div>
            <div class="flex items-center gap-1 {{ $table->status === 'disponible' ? 'bg-gray-50 text-gray-400 border-2 border-dashed border-gray-200' : 'bg-orange-50 text-orange-600' }} p-2.5 rounded-2xl">
                <span class="material-symbols-outlined text-[20px]">group</span>
                <span class="font-bold text-sm">{{ $table->capacity }}</span>
            </div>
        </div>

        {{-- Estado --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full {{ $statusConfig['dot'] }} {{ $table->status === 'disponible' ? 'animate-pulse' : '' }}"></span>
                <span class="text-sm font-bold {{ $statusConfig['color'] }}">{{ $statusConfig['label'] }}</span>
            </div>

            @if($table->status === 'ocupada')
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Current Order</p>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-sm text-on-surface">Mesa activa</span>
                    <span class="text-xs text-gray-500">Ahora</span>
                </div>
            </div>
            @elseif($table->status === 'disponible')
            <div class="bg-emerald-50/30 border border-emerald-100/50 rounded-2xl p-4 flex items-center justify-center min-h-[60px]">
                <p class="text-xs text-emerald-600/70 font-medium italic">Ready for seating</p>
            </div>
            @elseif($table->status === 'reservada' && $table->customer_name)
            <div class="bg-blue-50/30 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-on-surface">{{ $table->customer_name }}</span>
                    @if($table->reservation_time)
                    <span class="text-xs font-medium text-tertiary">{{ $table->reservation_time->format('H:i') }}</span>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-center min-h-[60px]">
                <p class="text-xs text-gray-400 font-medium">{{ $statusConfig['label'] }}</p>
            </div>
            @endif
        </div>

        {{-- Acciones que aparecen en hover --}}
        <div class="absolute inset-x-0 bottom-0 p-4 bg-white/95 backdrop-blur-sm border-t border-gray-50
                    translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex gap-2">
            @if($table->status === 'disponible')
            <button class="flex-1 py-2 bg-orange-500 text-white rounded-xl text-xs font-bold hover:bg-orange-600 transition-colors">
                Quick Seat
            </button>
            <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-sm">edit</span>
            </button>
            @elseif($table->status === 'ocupada')
            <button class="flex-1 py-2 bg-on-surface text-white rounded-xl text-xs font-bold">
                View Ticket
            </button>
            <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl">
                <span class="material-symbols-outlined text-sm">more_horiz</span>
            </button>
            @elseif($table->status === 'reservada')
            <button class="flex-1 py-2 bg-tertiary text-white rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                Check In
            </button>
            <button class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl">
                <span class="material-symbols-outlined text-sm">calendar_today</span>
            </button>
            @else
            <button class="flex-1 py-2 bg-gray-200 text-gray-600 rounded-xl text-xs font-bold">
                Manage
            </button>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Placeholder añadir mesa --}}
    <a href="{{ route('admin.tables.create') }}"
       class="border-2 border-dashed border-gray-200 rounded-3xl p-6 flex flex-col items-center justify-center gap-4 hover:border-orange-300 hover:bg-orange-50/50 transition-all group min-h-[200px]">
        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-orange-100 transition-colors">
            <span class="material-symbols-outlined text-2xl text-gray-400 group-hover:text-orange-500">add</span>
        </div>
        <div class="text-center">
            <p class="font-bold text-on-surface">Add New Table</p>
            <p class="text-xs text-gray-400 mt-1">Configure layout &amp; capacity</p>
        </div>
    </a>
</div>
@endif

{{-- FAB restaurante --}}
<button class="fixed bottom-10 right-10 w-14 h-14 bg-orange-500 text-white rounded-full shadow-2xl shadow-orange-300 flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-50">
    <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">restaurant</span>
</button>

</x-admin-layout>
