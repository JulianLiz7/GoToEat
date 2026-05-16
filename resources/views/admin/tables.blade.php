<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Mesas</x-slot>

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-8">
    <div>
        <span class="text-xs font-bold text-orange-600 uppercase tracking-widest">Administración</span>
        <h1 class="text-4xl font-black font-heading text-on-surface mt-0.5">Plano del Salón</h1>
        <p class="text-gray-500 mt-1">Control de ocupación y órdenes en tiempo real.</p>
    </div>
    <a href="{{ route('admin.tables.create') }}"
       class="flex items-center gap-2 bg-on-surface text-white px-6 py-3 rounded-2xl font-bold
              hover:bg-gray-800 active:scale-95 transition-all shadow-lg shrink-0">
        <span class="material-symbols-outlined text-lg">add</span>
        Nueva mesa
    </a>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ══ KPI rápidos ═════════════════════════════════════════════════ --}}
@php
$total       = $tables->count();
$disponibles = $tables->where('status','disponible')->count();
$ocupadas    = $tables->where('status','ocupada')->count();
$reservadas  = $tables->where('status','reservada')->count();
$mantenimiento = $tables->where('status','mantenimiento')->count();
$capacityPct = $total > 0 ? round(($ocupadas / $total) * 100) : 0;
@endphp

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="col-span-2 md:col-span-1 bg-on-surface text-white rounded-2xl p-5 relative overflow-hidden">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Ocupación</p>
        <p class="text-4xl font-black font-heading">{{ $capacityPct }}%</p>
        <div class="mt-3 flex gap-1">
            @for($i = 0; $i < 4; $i++)
            <div class="flex-1 h-1.5 rounded-full {{ $i < round($capacityPct/25) ? 'bg-orange-500' : 'bg-white/20' }}"></div>
            @endfor
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ $ocupadas }} de {{ $total }} mesas</p>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-8xl">restaurant</span>
        </div>
    </div>

    @foreach([
        ['label'=>'Disponibles', 'val'=>$disponibles, 'bg'=>'bg-emerald-50', 'text'=>'text-emerald-700', 'dot'=>'bg-emerald-500'],
        ['label'=>'Ocupadas',    'val'=>$ocupadas,    'bg'=>'bg-orange-50',  'text'=>'text-orange-700',  'dot'=>'bg-orange-500'],
        ['label'=>'Reservadas',  'val'=>$reservadas,  'bg'=>'bg-blue-50',    'text'=>'text-blue-700',    'dot'=>'bg-blue-500'],
        ['label'=>'Mant.',       'val'=>$mantenimiento,'bg'=>'bg-gray-50',   'text'=>'text-gray-600',    'dot'=>'bg-gray-400'],
    ] as $kpi)
    <div class="{{ $kpi['bg'] }} rounded-2xl p-5 border border-white">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $kpi['dot'] }} {{ $kpi['val'] > 0 && $kpi['dot'] === 'bg-orange-500' ? 'animate-pulse' : '' }}"></span>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ $kpi['label'] }}</p>
        </div>
        <p class="text-3xl font-black font-heading {{ $kpi['text'] }}">{{ $kpi['val'] }}</p>
    </div>
    @endforeach
</div>

{{-- ══ Filtros de zona ═════════════════════════════════════════════ --}}
@if($zonas->isNotEmpty())
<div class="flex gap-3 mb-6 overflow-x-auto pb-2">
    <a href="{{ route('admin.tables') }}"
       class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all
              {{ !$zonaFiltro ? 'bg-on-surface text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-400' }}">
        Todas las zonas ({{ $total }})
    </a>
    @foreach($zonas as $zona)
    <a href="{{ route('admin.tables', ['zona' => $zona]) }}"
       class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all
              {{ $zonaFiltro === $zona ? 'bg-on-surface text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-400' }}">
        {{ $zona }} ({{ $tables->where('zone', $zona)->count() }})
    </a>
    @endforeach
</div>
@endif

{{-- ══ Grid de mesas ═══════════════════════════════════════════════ --}}
@if($tables->isEmpty())
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border-2 border-dashed border-gray-200">
    <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">table_restaurant</span>
    <p class="text-gray-400 font-medium text-lg">Sin mesas configuradas</p>
    <a href="{{ route('admin.tables.create') }}"
       class="mt-4 text-orange-500 font-semibold text-sm hover:underline">Agregar la primera mesa</a>
</div>
@else

@php
$byZone = $tables->groupBy(fn($t) => $t->zone ?: 'General');
@endphp

@foreach($byZone as $zoneName => $zoneTables)
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-gray-400 text-[18px]">location_on</span>
        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500">Zona: {{ $zoneName }}</h3>
        <div class="flex-1 h-px bg-gray-100"></div>
        <span class="text-xs text-gray-400">{{ $zoneTables->count() }} mesa(s)</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($zoneTables as $mesa)
        @php
        $orden = $activeOrders->get($mesa->id);
        $meseroNombre = null;
        if ($orden && $orden->waiter_id) {
            $meseroNombre = DB::table('users')->where('id', $orden->waiter_id)->value('name');
        }
        $items = $orden ? json_decode($orden->items ?? '[]', true) : [];

        $cfg = match($mesa->status) {
            'disponible'   => ['border'=>'border-emerald-200', 'bg'=>'bg-emerald-50/30', 'dotColor'=>'bg-emerald-500 animate-pulse', 'label'=>'Disponible',    'labelColor'=>'text-emerald-700'],
            'ocupada'      => ['border'=>'border-orange-200',  'bg'=>'bg-orange-50/20',  'dotColor'=>'bg-orange-500 animate-pulse',  'label'=>'Ocupada',        'labelColor'=>'text-orange-700'],
            'reservada'    => ['border'=>'border-blue-200',    'bg'=>'bg-blue-50/20',    'dotColor'=>'bg-blue-500',                  'label'=>'Reservada',      'labelColor'=>'text-blue-700'],
            'mantenimiento'=> ['border'=>'border-gray-200',    'bg'=>'bg-gray-50/40',    'dotColor'=>'bg-gray-400',                  'label'=>'Mantenimiento',  'labelColor'=>'text-gray-500'],
            default        => ['border'=>'border-gray-200',    'bg'=>'bg-gray-50',       'dotColor'=>'bg-gray-300',                  'label'=>$mesa->status,    'labelColor'=>'text-gray-500'],
        };
        @endphp

        <div class="bg-white rounded-3xl border-2 {{ $cfg['border'] }} overflow-hidden
                    hover:shadow-lg transition-all duration-300 flex flex-col">

            {{-- ── Cabecera de la tarjeta ───────── --}}
            <div class="{{ $cfg['bg'] }} px-5 pt-5 pb-4">
                <div class="flex justify-between items-start">
                    <div>
                        @if($mesa->zone)
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">
                            {{ $mesa->zone }}
                        </span>
                        @endif
                        <h3 class="text-2xl font-black font-heading text-on-surface leading-none">
                            Mesa {{ $mesa->number }}
                        </h3>
                    </div>
                    {{-- Capacidad --}}
                    <div class="flex items-center gap-1 bg-white/80 px-3 py-1.5 rounded-full shadow-sm">
                        <span class="material-symbols-outlined text-gray-400 text-[16px]">group</span>
                        <span class="text-sm font-bold text-gray-600">{{ $mesa->capacity }}</span>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="flex items-center gap-2 mt-3">
                    <span class="w-2 h-2 rounded-full {{ $cfg['dotColor'] }}"></span>
                    <span class="text-sm font-bold {{ $cfg['labelColor'] }}">{{ $cfg['label'] }}</span>
                    @if($mesa->status === 'ocupada' && $mesa->party_size)
                    <span class="text-xs text-gray-400 ml-1">· {{ $mesa->party_size }} personas</span>
                    @endif
                </div>
            </div>

            {{-- ── Cuerpo según estado ─────────── --}}
            <div class="px-5 py-4 flex-1 space-y-3">

                {{-- OCUPADA: Mesero + Orden --}}
                @if($mesa->status === 'ocupada')

                    @if($mesa->customer_name)
                    <div class="flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">person</span>
                        <span class="font-semibold text-on-surface">{{ $mesa->customer_name }}</span>
                    </div>
                    @endif

                    {{-- Mesero asignado --}}
                    @if($meseroNombre)
                    <div class="flex items-center gap-2 bg-blue-50 rounded-xl px-3 py-2 border border-blue-100">
                        <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                            {{ strtoupper(substr($meseroNombre, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wide">Mesero</p>
                            <p class="text-sm font-semibold text-blue-900 truncate">{{ $meseroNombre }}</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-2 bg-amber-50 rounded-xl px-3 py-2 border border-amber-100">
                        <span class="material-symbols-outlined text-amber-500 text-[18px]">warning</span>
                        <p class="text-xs text-amber-700 font-medium">Sin mesero asignado</p>
                    </div>
                    @endif

                    {{-- Resumen de orden --}}
                    @if($orden && count($items) > 0)
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Orden activa</p>
                        <div class="space-y-1.5 max-h-24 overflow-y-auto">
                            @foreach(array_slice($items, 0, 4) as $it)
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-700 truncate max-w-[120px]">
                                    {{ $it['quantity'] ?? 1 }}× {{ $it['name'] ?? ('Plato #' . ($it['menu_item_id'] ?? '?')) }}
                                </span>
                                <span class="font-semibold text-gray-600 shrink-0">
                                    ${{ number_format(($it['unit_price'] ?? 0) * ($it['quantity'] ?? 1), 0) }}
                                </span>
                            </div>
                            @endforeach
                            @if(count($items) > 4)
                            <p class="text-[10px] text-gray-400 text-center">+{{ count($items)-4 }} más...</p>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between">
                            <span class="text-xs font-bold text-gray-500">Total</span>
                            <span class="text-sm font-black text-orange-600">
                                ${{ number_format($orden->total, 0) }}
                            </span>
                        </div>
                    </div>
                    @elseif($orden)
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                        <span class="material-symbols-outlined text-gray-300 text-2xl">receipt_long</span>
                        <p class="text-xs text-gray-400 mt-1">Orden abierta — sin platos aún</p>
                    </div>
                    @endif

                {{-- RESERVADA: Datos de la reserva --}}
                @elseif($mesa->status === 'reservada')
                    @if($mesa->customer_name)
                    <div class="flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-blue-400 text-[18px]">person</span>
                        <span class="font-bold text-on-surface">{{ $mesa->customer_name }}</span>
                    </div>
                    @endif
                    @if($mesa->reservation_time)
                    <div class="flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-blue-400 text-[18px]">schedule</span>
                        <span class="text-blue-700 font-semibold">{{ \Carbon\Carbon::parse($mesa->reservation_time)->format('H:i — d/m') }}</span>
                    </div>
                    @endif
                    @if($mesa->party_size)
                    <div class="flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-blue-400 text-[18px]">group</span>
                        <span class="text-gray-600">{{ $mesa->party_size }} personas</span>
                    </div>
                    @endif
                    @if($mesa->reservation_notes)
                    <p class="text-xs text-gray-400 italic bg-gray-50 rounded-xl px-3 py-2 border border-gray-100">
                        "{{ $mesa->reservation_notes }}"
                    </p>
                    @endif

                {{-- DISPONIBLE --}}
                @elseif($mesa->status === 'disponible')
                <div class="flex flex-col items-center justify-center py-4 text-center">
                    <span class="material-symbols-outlined text-3xl text-emerald-300 mb-2">event_seat</span>
                    <p class="text-sm text-emerald-600 font-semibold">Lista para sentar</p>
                    <p class="text-xs text-gray-400 mt-0.5">Capacidad: {{ $mesa->capacity }} personas</p>
                </div>

                {{-- MANTENIMIENTO --}}
                @else
                <div class="flex flex-col items-center justify-center py-4 text-center">
                    <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">build</span>
                    <p class="text-sm text-gray-500 font-semibold">En mantenimiento</p>
                </div>
                @endif
            </div>

            {{-- ── Acciones ───────────────────── --}}
            <div class="px-5 pb-5 space-y-2">

                @if($mesa->status === 'disponible')
                {{-- Sentar cliente --}}
                <button type="button"
                        onclick="abrirSentar({{ $mesa->id }}, '{{ $mesa->number }}', {{ $mesa->capacity }})"
                        class="w-full py-3 rounded-2xl bg-emerald-500 text-white font-bold text-sm
                               hover:bg-emerald-600 active:scale-95 transition-all shadow-sm shadow-emerald-200
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">restaurant</span>
                    Sentar clientes
                </button>
                <div class="flex gap-2">
                    <button type="button"
                            onclick="abrirReservar({{ $mesa->id }}, '{{ $mesa->number }}')"
                            class="flex-1 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold
                                   hover:bg-blue-100 transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">bookmark_add</span>
                        Reservar
                    </button>
                    <button type="button"
                            onclick="abrirEditar({{ $mesa->id }}, '{{ $mesa->number }}', {{ $mesa->capacity }}, '{{ $mesa->zone ?? '' }}')"
                            class="px-3 py-2 rounded-xl bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition-all">
                        <span class="material-symbols-outlined text-[15px]">edit</span>
                    </button>
                    <form method="POST" action="{{ route('admin.tables.destroy', $mesa->id) }}"
                          onsubmit="return confirm('¿Eliminar mesa {{ $mesa->number }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-2 rounded-xl bg-red-50 text-red-500 text-xs font-bold hover:bg-red-100 transition-all">
                            <span class="material-symbols-outlined text-[15px]">delete</span>
                        </button>
                    </form>
                </div>

                @elseif($mesa->status === 'ocupada')

                {{-- Ver orden (siempre visible) --}}
                <button type="button"
                        onclick="abrirOrden(
                            '{{ $mesa->number }}',
                            '{{ addslashes($mesa->customer_name ?? '') }}',
                            '{{ addslashes($meseroNombre ?? 'Sin asignar') }}',
                            {{ $mesa->party_size ?? 0 }},
                            {{ $orden->total ?? 0 }},
                            {{ json_encode($items) }}
                        )"
                        class="w-full py-3 rounded-2xl bg-orange-500 text-white font-bold text-sm
                               hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Ver orden completa
                </button>

                {{-- Estado rápido de la orden --}}
                @if($orden)
                @php $orderStatus = $orden->status ?? 'pending'; @endphp
                <div class="grid grid-cols-3 gap-1.5">
                    @foreach([
                        ['status'=>'pending',   'label'=>'Pendiente',  'color'=>'text-amber-700 bg-amber-50 border-amber-200',   'active'=>'bg-amber-500 text-white border-amber-500'],
                        ['status'=>'preparing', 'label'=>'Preparando', 'color'=>'text-blue-700 bg-blue-50 border-blue-200',       'active'=>'bg-blue-500 text-white border-blue-500'],
                        ['status'=>'ready',     'label'=>'Listo',      'color'=>'text-emerald-700 bg-emerald-50 border-emerald-200','active'=>'bg-emerald-500 text-white border-emerald-500'],
                    ] as $st)
                    <form method="POST" action="{{ route('admin.tables.order.status', $mesa->id) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $st['status'] }}">
                        <button type="submit"
                                class="w-full py-2 rounded-xl border font-bold text-xs transition-all active:scale-95
                                       {{ $orderStatus === $st['status'] ? $st['active'] : $st['color'] }}">
                            {{ $st['label'] }}
                        </button>
                    </form>
                    @endforeach
                </div>

                {{-- Finalizar pedido --}}
                <form method="POST" action="{{ route('admin.tables.order.status', $mesa->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit"
                            onclick="return confirm('¿Finalizar el pedido de mesa {{ $mesa->number }}? La mesa permanecerá ocupada hasta que uses Liberar.')"
                            class="w-full py-2.5 rounded-2xl bg-emerald-500 text-white font-bold text-sm
                                   hover:bg-emerald-600 active:scale-95 transition-all
                                   flex items-center justify-center gap-2 shadow-sm shadow-emerald-200">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        Finalizar / Cobrar
                    </button>
                </form>
                @endif

                {{-- Liberar mesa --}}
                <form method="POST" action="{{ route('admin.tables.liberar', $mesa->id) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('¿Liberar mesa {{ $mesa->number }}? Se cerrará la orden activa.')"
                            class="w-full py-2.5 rounded-2xl bg-gray-100 text-gray-600 font-semibold text-sm
                                   hover:bg-gray-200 active:scale-95 transition-all flex items-center justify-center gap-2 border border-gray-200">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Liberar mesa
                    </button>
                </form>

                @elseif($mesa->status === 'reservada')
                {{-- Check In --}}
                <button type="button"
                        onclick="abrirCheckIn({{ $mesa->id }}, '{{ $mesa->number }}', '{{ addslashes($mesa->customer_name ?? '') }}')"
                        class="w-full py-3 rounded-2xl bg-blue-500 text-white font-bold text-sm
                               hover:bg-blue-600 active:scale-95 transition-all shadow-sm shadow-blue-200
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                    Check In
                </button>
                <form method="POST" action="{{ route('admin.tables.status', $mesa->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="disponible">
                    <button type="submit"
                            class="w-full py-2 rounded-xl bg-gray-100 text-gray-600 font-semibold text-xs
                                   hover:bg-gray-200 transition-all">
                        Cancelar reserva
                    </button>
                </form>

                @else {{-- Mantenimiento --}}
                <form method="POST" action="{{ route('admin.tables.status', $mesa->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="disponible">
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-emerald-500 text-white font-bold text-sm
                                   hover:bg-emerald-600 active:scale-95 transition-all
                                   flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Marcar disponible
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.tables.destroy', $mesa->id) }}"
                      onsubmit="return confirm('¿Eliminar mesa {{ $mesa->number }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2 rounded-xl bg-red-50 text-red-500 font-semibold text-xs hover:bg-red-100 transition-all">
                        Eliminar
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif

{{-- ══════════════════════════════════════════════════════════════════
     MODALES
══════════════════════════════════════════════════════════════════ --}}

{{-- ── Modal: Sentar clientes ──────────────────────────────────────── --}}
{{-- ══ MODAL: Sentar clientes ════════════════════════════════════════
     Estructura: header fijo + body scroll + footer fijo (botones siempre visibles) --}}
<div id="modalSentar" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col" style="max-height:90vh">
        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-emerald-50/40 rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">restaurant</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Sentar clientes</h2>
                    <p id="sentarSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalSentar')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400 shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="formSentar" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto px-7 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                            N° de personas <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="party_size" id="sentarParty"
                               min="1" max="50" required value="2"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-center text-xl font-black
                                      focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mesero asignado</label>
                        <select name="waiter_user_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-emerald-500 outline-none bg-white">
                            <option value="">Sin asignar</option>
                            @foreach($meseros as $m)
                            <option value="{{ $m->user_id }}">{{ $m->name }} ({{ $m->position ?: 'Staff' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nombre del cliente (opcional)</label>
                    <input type="text" name="customer_name" placeholder="ej. Familia Rodríguez"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-emerald-500 outline-none"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Notas</label>
                    <input type="text" name="notes" placeholder="ej. Celebración de cumpleaños, silla para bebé..."
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-emerald-500 outline-none"/>
                </div>
            </div>
            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalSentar')"
                        class="flex-1 py-3 rounded-2xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-emerald-500 text-white font-bold text-sm hover:bg-emerald-600 active:scale-95 shadow-sm shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Reservar mesa ══════════════════════════════════════════ --}}
<div id="modalReservar" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col" style="max-height:90vh">
        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-blue-50/40 rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">bookmark_add</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Registrar reserva</h2>
                    <p id="reservarSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalReservar')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400 shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="formReservar" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="reservada">
            <div class="flex-1 overflow-y-auto px-7 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nombre del cliente <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required placeholder="Nombre o apellido"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Personas</label>
                        <input type="number" name="party_size" min="1" max="50" value="2"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Hora de llegada</label>
                        <input type="datetime-local" name="reservation_time"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 outline-none"/>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Teléfono</label>
                        <input type="text" name="customer_phone" placeholder="+57 300 000 0000"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 outline-none"/>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Notas</label>
                        <input type="text" name="reservation_notes" placeholder="Ocasión especial, alergias, preferencias..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 outline-none"/>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalReservar')"
                        class="flex-1 py-3 rounded-2xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">Cancelar</button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-blue-500 text-white font-bold text-sm hover:bg-blue-600 active:scale-95 shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">bookmark_added</span>
                    Reservar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Check In ══════════════════════════════════════════════ --}}
<div id="modalCheckIn" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm flex flex-col" style="max-height:90vh">
        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">how_to_reg</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Check In</h2>
                    <p id="checkInSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalCheckIn')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400 shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="formCheckIn" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto px-7 py-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mesero asignado</label>
                <select name="waiter_user_id"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-blue-500 outline-none bg-white">
                    <option value="">Sin asignar</option>
                    @foreach($meseros as $m)
                    <option value="{{ $m->user_id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalCheckIn')"
                        class="flex-1 py-3 rounded-2xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">Cancelar</button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-blue-500 text-white font-bold text-sm hover:bg-blue-600 active:scale-95 shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Ver orden completa ════════════════════════════════════ --}}
<div id="modalOrden" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col" style="max-height:90vh">
        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-orange-50/40 rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">receipt_long</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Orden — Mesa <span id="ordenMesaNum"></span></h2>
                    <p id="ordenCliente" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalOrden')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400 shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-7 py-6 space-y-5">
            {{-- Mesero + personas --}}
            <div class="flex items-center gap-3 bg-blue-50 rounded-2xl px-4 py-3 border border-blue-100">
                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm shrink-0" id="ordenMeseroAvatar"></div>
                <div>
                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide">Mesero</p>
                    <p class="text-sm font-bold text-blue-900" id="ordenMeseroNombre"></p>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Personas</p>
                    <p class="text-sm font-bold text-gray-700" id="ordenPersonas"></p>
                </div>
            </div>
            {{-- Items --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Platos pedidos</p>
                <div id="ordenItems" class="space-y-2"></div>
            </div>
            {{-- Total --}}
            <div class="border-t-2 border-dashed border-gray-200 pt-4 flex justify-between items-center">
                <span class="font-bold text-gray-600 text-base">Total</span>
                <span class="text-2xl font-black text-orange-600" id="ordenTotal"></span>
            </div>
        </div>
        <div class="shrink-0 px-7 py-4 border-t border-gray-100 bg-white rounded-b-3xl">
            <button onclick="cerrarModal('modalOrden')"
                    class="w-full py-3 rounded-2xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 transition-all">
                Cerrar
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL: Editar mesa ════════════════════════════════════════════ --}}
<div id="modalEditar" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm flex flex-col" style="max-height:90vh">
        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">edit</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Editar mesa</h2>
                    <p id="editarSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalEditar')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400 shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="formEditar" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PUT')
            <div class="flex-1 overflow-y-auto px-7 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Número *</label>
                        <input type="text" name="number" id="editarNumero" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Capacidad *</label>
                        <input type="number" name="capacity" id="editarCapacidad" min="1" max="50" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none"/>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Zona</label>
                        <input type="text" name="zone" id="editarZona" placeholder="ej. Terraza, Salón principal"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none"/>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalEditar')"
                        class="flex-1 py-3 rounded-2xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">Cancelar</button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-amber-500 text-white font-bold text-sm hover:bg-amber-600 active:scale-95 shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Scripts ─────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }
function abrirModal(id)  { document.getElementById(id).classList.remove('hidden'); }

// Cerrar al clic fuera
['modalSentar','modalReservar','modalCheckIn','modalOrden','modalEditar'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) cerrarModal(id);
    });
});

// ── Sentar ───────────────────────────────────────────────────────
function abrirSentar(id, numero, capacidad) {
    document.getElementById('sentarSubtitulo').textContent = `Mesa ${numero} · Capacidad ${capacidad}`;
    document.getElementById('sentarParty').max = capacidad;
    document.getElementById('formSentar').action = `/admin/tables/${id}/sentar`;
    abrirModal('modalSentar');
}

// ── Reservar ─────────────────────────────────────────────────────
function abrirReservar(id, numero) {
    document.getElementById('reservarSubtitulo').textContent = `Mesa ${numero}`;
    document.getElementById('formReservar').action = `/admin/tables/${id}/estado`;
    abrirModal('modalReservar');
}

// ── Check In ─────────────────────────────────────────────────────
function abrirCheckIn(id, numero, cliente) {
    document.getElementById('checkInSubtitulo').textContent = cliente ? `${cliente} · Mesa ${numero}` : `Mesa ${numero}`;
    document.getElementById('formCheckIn').action = `/admin/tables/${id}/checkin`;
    abrirModal('modalCheckIn');
}

// ── Editar mesa ──────────────────────────────────────────────────
function abrirEditar(id, numero, capacidad, zona) {
    document.getElementById('editarSubtitulo').textContent = `Mesa ${numero}`;
    document.getElementById('formEditar').action = `/admin/tables/${id}`;
    document.getElementById('editarNumero').value    = numero;
    document.getElementById('editarCapacidad').value = capacidad;
    document.getElementById('editarZona').value      = zona;
    abrirModal('modalEditar');
}

// ── Ver orden completa ───────────────────────────────────────────
function abrirOrden(numero, cliente, mesero, personas, total, items) {
    document.getElementById('ordenMesaNum').textContent   = numero;
    document.getElementById('ordenCliente').textContent   = cliente || 'Sin nombre registrado';
    document.getElementById('ordenMeseroNombre').textContent = mesero;
    document.getElementById('ordenMeseroAvatar').textContent = mesero.charAt(0).toUpperCase();
    document.getElementById('ordenPersonas').textContent  = personas > 0 ? `${personas} personas` : '—';
    document.getElementById('ordenTotal').textContent     = '$' + Number(total).toLocaleString('es-CO');

    const container = document.getElementById('ordenItems');
    container.innerHTML = '';

    if (!items || items.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-400 text-sm py-6">Sin platos registrados en la orden</p>';
    } else {
        items.forEach(it => {
            const qty    = it.quantity ?? 1;
            const name   = it.name ?? `Plato #${it.menu_item_id ?? '?'}`;
            const price  = (it.unit_price ?? 0) * qty;
            const div    = document.createElement('div');
            div.className = 'flex justify-between items-center py-2 border-b border-gray-100 last:border-0';
            div.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-bold shrink-0">${qty}</span>
                    <span class="text-sm text-on-surface truncate">${name}</span>
                </div>
                <span class="text-sm font-bold text-gray-700 shrink-0 ml-3">$${Number(price).toLocaleString('es-CO')}</span>
            `;
            container.appendChild(div);
        });
    }

    abrirModal('modalOrden');
}
</script>
@endpush

</x-admin-layout>
