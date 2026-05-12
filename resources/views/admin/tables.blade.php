<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Mesas</x-slot>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Mesas</h2>
        <p class="text-gray-500 mt-1">Estado en tiempo real del salón.</p>
    </div>
    <a href="{{ route('admin.tables.create') }}"
       class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Nueva mesa
    </a>
</div>

{{-- Leyenda de estados --}}
<div class="flex flex-wrap gap-3 mb-6">
    @foreach([
        ['color' => 'bg-emerald-500', 'label' => 'Disponible'],
        ['color' => 'bg-red-500',     'label' => 'Ocupada'],
        ['color' => 'bg-blue-500',    'label' => 'Reservada'],
        ['color' => 'bg-gray-400',    'label' => 'Mantenimiento'],
    ] as $s)
    <span class="flex items-center gap-2 text-xs text-gray-600 font-medium bg-white px-3 py-1.5 rounded-full shadow-sm">
        <span class="w-2.5 h-2.5 rounded-full {{ $s['color'] }} inline-block"></span>
        {{ $s['label'] }}
    </span>
    @endforeach
</div>

{{-- Mapa de mesas --}}
@if($tables->isEmpty())
    <div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl shadow-sm">
        <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">table_restaurant</span>
        <p class="text-gray-400 font-medium text-lg">Sin mesas configuradas</p>
        <p class="text-gray-300 text-sm mt-1">Agrega las mesas de tu restaurante</p>
    </div>
@else
    @php $byZone = $tables->groupBy(fn($t) => $t->zone ?? 'General'); @endphp
    @foreach($byZone as $zone => $zoneTables)
    <div class="mb-8">
        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">location_on</span>
            Zona: {{ $zone }}
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($zoneTables as $table)
            @php
            $statusConfig = match($table->status) {
                'disponible'   => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                'ocupada'      => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'dot' => 'bg-red-500',     'text' => 'text-red-700'],
                'reservada'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500',    'text' => 'text-blue-700'],
                default        => ['bg' => 'bg-gray-50',    'border' => 'border-gray-200',    'dot' => 'bg-gray-400',    'text' => 'text-gray-600'],
            };
            @endphp
            <div class="relative {{ $statusConfig['bg'] }} border-2 {{ $statusConfig['border'] }} rounded-2xl p-4 text-center hover:shadow-md transition-all cursor-pointer group">
                <div class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full {{ $statusConfig['dot'] }} {{ $table->status === 'disponible' ? 'animate-pulse' : '' }}"></div>
                <span class="material-symbols-outlined text-3xl text-gray-400 group-hover:scale-110 transition-transform">table_restaurant</span>
                <p class="font-bold text-on-surface mt-2">Mesa {{ $table->number }}</p>
                <p class="text-[11px] {{ $statusConfig['text'] }} font-semibold mt-1">
                    <span class="material-symbols-outlined text-[13px] align-middle">people</span>
                    {{ $table->capacity }} personas
                </p>
                @if($table->status === 'reservada' && $table->customer_name)
                <p class="text-[10px] text-blue-600 mt-1 truncate font-medium">{{ $table->customer_name }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endif

</x-admin-layout>
