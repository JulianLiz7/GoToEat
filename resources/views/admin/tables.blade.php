<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Mesas & Reservas</x-slot>

<div x-data="{ tab: '{{ request()->has('rstatus') ? 'reservas' : 'plano' }}' }">

{{-- ══ ENCABEZADO ══════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-6">
    <div>
        <span class="text-xs font-bold text-orange-600 uppercase tracking-widest">Administración</span>
        <h1 class="text-4xl font-black font-heading text-on-surface mt-0.5">Mesas & Reservas</h1>
        <p class="text-gray-500 mt-1 text-sm">Plano del salón sincronizado con reservas de comensales.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.tables.create') }}"
           class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition-all text-sm shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Nueva mesa
        </a>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ══ KPIs UNIFICADOS ══════════════════════════════════════════════ --}}
@php
$total         = $tables->count();
$disponibles   = $tables->where('status','disponible')->count();
$ocupadas      = $tables->where('status','ocupada')->count();
$reservadas    = $tables->where('status','reservada')->count();
$mant          = $tables->where('status','mantenimiento')->count();
$capacityPct   = $total > 0 ? round(($ocupadas / $total) * 100) : 0;
@endphp

<div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-6">
    {{-- Ocupación --}}
    <div class="col-span-2 md:col-span-1 bg-on-surface text-white rounded-2xl p-5 relative overflow-hidden">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Ocupación</p>
        <p class="text-3xl font-black font-heading">{{ $capacityPct }}%</p>
        <div class="mt-2 flex gap-1">
            @for($i=0; $i<4; $i++)
            <div class="flex-1 h-1.5 rounded-full {{ $i < round($capacityPct/25) ? 'bg-orange-500' : 'bg-white/20' }}"></div>
            @endfor
        </div>
        <p class="text-xs text-gray-400 mt-1.5">{{ $ocupadas }}/{{ $total }} mesas</p>
        <div class="absolute -right-4 -bottom-4 opacity-10"><span class="material-symbols-outlined text-7xl">restaurant</span></div>
    </div>

    {{-- Mesas por estado --}}
    @foreach([
        ['label'=>'Disponibles','val'=>$disponibles,'bg'=>'bg-emerald-50','text'=>'text-emerald-700','dot'=>'bg-emerald-500'],
        ['label'=>'Ocupadas',   'val'=>$ocupadas,   'bg'=>'bg-orange-50', 'text'=>'text-orange-700', 'dot'=>'bg-orange-500 animate-pulse'],
        ['label'=>'Reservadas', 'val'=>$reservadas, 'bg'=>'bg-blue-50',   'text'=>'text-blue-700',   'dot'=>'bg-blue-500'],
        ['label'=>'Mant.',      'val'=>$mant,       'bg'=>'bg-gray-50',   'text'=>'text-gray-600',   'dot'=>'bg-gray-400'],
    ] as $kpi)
    <div class="{{ $kpi['bg'] }} rounded-2xl p-4 border border-white">
        <div class="flex items-center gap-1.5 mb-1">
            <span class="w-2 h-2 rounded-full {{ $kpi['dot'] }}"></span>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ $kpi['label'] }}</p>
        </div>
        <p class="text-2xl font-black font-heading {{ $kpi['text'] }}">{{ $kpi['val'] }}</p>
    </div>
    @endforeach

    {{-- Reservas KPIs --}}
    <div class="bg-amber-50 rounded-2xl p-4 border border-white">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Pendientes</p>
        <p class="text-2xl font-black text-amber-600">{{ $pendingCount }}</p>
        @if($pendingCount > 0)
        <p class="text-xs text-amber-500 mt-0.5">por confirmar</p>
        @endif
    </div>
    <div class="bg-violet-50 rounded-2xl p-4 border border-white">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Confirmadas</p>
        <p class="text-2xl font-black text-violet-600">{{ $confirmedCount }}</p>
    </div>
</div>

{{-- ══ ALERTAS DE RESERVAS SIN MESA ════════════════════════════════ --}}
@if($unassigned->isNotEmpty())
<div class="mb-5 bg-amber-50 border border-amber-200 rounded-2xl p-4">
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-500 text-[22px] shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">warning</span>
        <div class="flex-1">
            <p class="font-bold text-amber-800 text-sm">{{ $unassigned->count() }} reserva{{ $unassigned->count() > 1 ? 's' : '' }} sin mesa asignada</p>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($unassigned->take(5) as $ur)
                <span class="bg-white border border-amber-200 text-amber-700 text-xs px-3 py-1.5 rounded-full font-medium">
                    {{ $ur->user->name ?? '?' }} · {{ \Carbon\Carbon::parse($ur->reservation_date)->format('d/m') }} {{ substr($ur->reservation_time ?? '', 0, 5) }} · {{ $ur->party_size }}p
                </span>
                @endforeach
                @if($unassigned->count() > 5)
                <span class="text-xs text-amber-600 self-center">+{{ $unassigned->count() - 5 }} más</span>
                @endif
            </div>
            <button @click="tab = 'reservas'" class="mt-2 text-xs font-bold text-amber-700 hover:underline">
                → Asignar mesas en la pestaña Reservas
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ TABS ══════════════════════════════════════════════════════════ --}}
<div class="flex gap-1 bg-gray-100 p-1 rounded-2xl mb-6 w-fit">
    <button @click="tab = 'plano'"
            :class="tab === 'plano' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all">
        <span class="material-symbols-outlined text-[18px]">table_restaurant</span>
        Plano del Salón
        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full" :class="tab === 'plano' ? 'bg-orange-100 text-orange-700' : ''">{{ $total }}</span>
    </button>
    <button @click="tab = 'reservas'"
            :class="tab === 'reservas' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all">
        <span class="material-symbols-outlined text-[18px]">event_seat</span>
        Reservas
        @if($pendingCount > 0)
        <span class="text-xs bg-amber-500 text-white px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
        @else
        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $reservations->total() }}</span>
        @endif
    </button>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- TAB 1: PLANO DEL SALÓN ══════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="tab === 'plano'" x-cloak>

    {{-- Filtros de zona --}}
    @if($zonas->isNotEmpty())
    <div class="flex gap-2 mb-5 overflow-x-auto pb-1">
        <a href="{{ route('admin.tables') }}"
           class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all
                  {{ !$zonaFiltro ? 'bg-on-surface text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-400' }}">
            Todas ({{ $total }})
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

    @if($tables->isEmpty())
    <div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border-2 border-dashed border-gray-200">
        <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">table_restaurant</span>
        <p class="text-gray-400 font-medium text-lg">Sin mesas configuradas</p>
        <a href="{{ route('admin.tables.create') }}" class="mt-4 text-orange-500 font-semibold text-sm hover:underline">Agregar la primera mesa</a>
    </div>
    @else

    @php $byZone = $tables->groupBy(fn($t) => $t->zone ?: 'General'); @endphp

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
            $orden       = $activeOrders->get($mesa->id);
            $meseroNombre = null;
            if ($orden && $orden->waiter_id) {
                $meseroNombre = DB::table('users')->where('id', $orden->waiter_id)->value('name');
            }
            $items = $orden ? json_decode($orden->items ?? '[]', true) : [];

            // Reservas vinculadas a esta mesa
            $mesaReservations = $reservationsByTable->get($mesa->id, collect());
            $nextReservation  = $mesaReservations->first();

            $cfg = match($mesa->status) {
                'disponible'    => ['border'=>'border-emerald-200', 'bg'=>'bg-emerald-50/30', 'dotColor'=>'bg-emerald-500 animate-pulse', 'label'=>'Disponible',   'labelColor'=>'text-emerald-700'],
                'ocupada'       => ['border'=>'border-orange-200',  'bg'=>'bg-orange-50/20',  'dotColor'=>'bg-orange-500 animate-pulse',  'label'=>'Ocupada',       'labelColor'=>'text-orange-700'],
                'reservada'     => ['border'=>'border-blue-200',    'bg'=>'bg-blue-50/20',    'dotColor'=>'bg-blue-500',                  'label'=>'Reservada',     'labelColor'=>'text-blue-700'],
                'mantenimiento' => ['border'=>'border-gray-200',    'bg'=>'bg-gray-50/40',    'dotColor'=>'bg-gray-400',                  'label'=>'Mantenimiento', 'labelColor'=>'text-gray-500'],
                default         => ['border'=>'border-gray-200',    'bg'=>'bg-gray-50',       'dotColor'=>'bg-gray-300',                  'label'=>$mesa->status,   'labelColor'=>'text-gray-500'],
            };
            @endphp

            <div class="bg-white rounded-3xl border-2 {{ $cfg['border'] }} overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col"
                 x-data="{ open: false, checkInModal: false, sentarModal: false, assignModal: false, editModal: false }">

                {{-- Cabecera --}}
                <div class="{{ $cfg['bg'] }} px-5 pt-5 pb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            @if($mesa->zone)
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-0.5">{{ $mesa->zone }}</span>
                            @endif
                            <h3 class="text-2xl font-black font-heading text-on-surface leading-none">Mesa {{ $mesa->number }}</h3>
                        </div>
                        <div class="flex items-center gap-1.5">
                            {{-- Indicador de reserva vinculada --}}
                            @if($nextReservation)
                            <span class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center" title="Tiene reserva">
                                <span class="material-symbols-outlined text-white text-[14px]" style="font-variation-settings:'FILL' 1">event_available</span>
                            </span>
                            @endif
                            <div class="flex items-center gap-1 bg-white/80 px-2.5 py-1.5 rounded-full shadow-sm">
                                <span class="material-symbols-outlined text-gray-400 text-[14px]">group</span>
                                <span class="text-sm font-bold text-gray-600">{{ $mesa->capacity }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-2.5">
                        <span class="w-2 h-2 rounded-full {{ $cfg['dotColor'] }}"></span>
                        <span class="text-sm font-bold {{ $cfg['labelColor'] }}">{{ $cfg['label'] }}</span>
                        @if($mesa->status === 'ocupada' && $mesa->party_size)
                        <span class="text-xs text-gray-400">· {{ $mesa->party_size }}p</span>
                        @endif
                    </div>
                </div>

                {{-- Cuerpo --}}
                <div class="px-5 py-4 flex-1 space-y-3">

                    {{-- Reserva vinculada (si existe) --}}
                    @if($nextReservation && $mesa->status !== 'ocupada')
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-3">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="material-symbols-outlined text-blue-500 text-[16px]" style="font-variation-settings:'FILL' 1">event_seat</span>
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-wide">Reserva vinculada</span>
                            @if($nextReservation->qr_token)
                            <a href="{{ route('reserva.verificar', $nextReservation->qr_token) }}" target="_blank"
                               class="ml-auto text-blue-400 hover:text-blue-600 transition-colors" title="Ver QR">
                                <span class="material-symbols-outlined text-[16px]">qr_code_2</span>
                            </a>
                            @endif
                        </div>
                        <p class="font-bold text-gray-800 text-sm">{{ $nextReservation->user->name ?? '—' }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                            <span>{{ \Carbon\Carbon::parse($nextReservation->reservation_date)->format('d/m') }}</span>
                            <span>{{ substr($nextReservation->reservation_time ?? '', 0, 5) }}</span>
                            <span>{{ $nextReservation->party_size }}p</span>
                            <span class="ml-auto px-2 py-0.5 rounded-full font-bold text-[10px] {{ $nextReservation->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $nextReservation->statusLabel() }}
                            </span>
                        </div>
                        @if($nextReservation->selected_items && count($nextReservation->selected_items) > 0)
                        <p class="text-[10px] text-blue-500 mt-1">
                            🍽 Pre-orden: {{ count($nextReservation->selected_items) }} ítem(s) — ${{ number_format(collect($nextReservation->selected_items)->sum(fn($i) => ($i['price']??0)*($i['qty']??1)), 0, ',', '.') }}
                        </p>
                        @endif
                    </div>
                    @endif

                    {{-- Contenido según estado --}}
                    @if($mesa->status === 'ocupada')
                        @if($mesa->customer_name)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-[16px] text-gray-400">person</span>
                            <span class="font-semibold text-on-surface">{{ $mesa->customer_name }}</span>
                        </div>
                        @endif

                        @if($meseroNombre)
                        <div class="flex items-center gap-2 bg-blue-50 rounded-xl px-3 py-2 border border-blue-100">
                            <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ strtoupper(substr($meseroNombre, 0, 1)) }}
                            </div>
                            <span class="text-xs font-semibold text-blue-700">{{ $meseroNombre }}</span>
                        </div>
                        @endif

                        @if(count($items) > 0)
                        <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                            <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                            Orden ({{ count($items) }} ítems)
                            <span class="material-symbols-outlined text-[14px]" x-text="open ? 'expand_less' : 'expand_more'"></span>
                        </button>
                        <div x-show="open" x-cloak class="space-y-1">
                            @foreach(array_slice($items, 0, 4) as $item)
                            <div class="flex justify-between text-xs bg-gray-50 rounded-lg px-2.5 py-1.5">
                                <span class="text-gray-700 truncate">{{ $item['name'] ?? '' }}</span>
                                <span class="text-gray-400 ml-2 shrink-0">×{{ $item['qty'] ?? $item['quantity'] ?? 1 }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    @elseif($mesa->status === 'disponible')
                        <p class="text-xs text-gray-400 text-center py-2">
                            Mesa lista · {{ $mesa->capacity }} asientos
                        </p>
                        @if($unassigned->isNotEmpty())
                        <button @click="assignModal = true"
                                class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-100 rounded-xl py-2 transition-all">
                            <span class="material-symbols-outlined text-[15px]">link</span>
                            Asignar reserva
                        </button>
                        @endif
                    @endif

                    {{-- Múltiples reservas futuras --}}
                    @if($mesaReservations->count() > 1)
                    <p class="text-[10px] text-gray-400">+{{ $mesaReservations->count() - 1 }} reserva(s) futura(s)</p>
                    @endif
                </div>

                {{-- Acciones según estado --}}
                <div class="px-5 pb-5 space-y-2">
                    @if($mesa->status === 'disponible')
                    <button @click="sentarModal = true"
                            class="w-full flex items-center justify-center gap-2 bg-on-surface hover:bg-gray-800 text-white py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        Sentar cliente
                    </button>

                    @elseif($mesa->status === 'reservada')
                    <button @click="checkInModal = true"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">how_to_reg</span>
                        Registrar llegada
                    </button>

                    @elseif($mesa->status === 'ocupada')
                    {{-- Actualizar orden --}}
                    @if($orden)
                    <div class="grid grid-cols-3 gap-1">
                        @foreach(['preparing' => ['label'=>'Preparando','icon'=>'cooking'], 'ready' => ['label'=>'Listo','icon'=>'check_circle'], 'completed' => ['label'=>'Cobrado','icon'=>'payments']] as $st => $info)
                        <form method="POST" action="{{ route('admin.tables.order.status', $mesa->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $st }}">
                            <button type="submit"
                                    class="w-full text-center text-[10px] font-bold py-2 rounded-lg {{ $orden->status === $st ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-all"
                                    title="{{ $info['label'] }}">
                                {{ $info['label'] }}
                            </button>
                        </form>
                        @endforeach
                    </div>
                    @endif
                    <form method="POST" action="{{ route('admin.tables.liberar', $mesa->id) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Liberar Mesa {{ $mesa->number }}?')"
                                class="w-full flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">table_restaurant</span>
                            Liberar mesa
                        </button>
                    </form>
                    @endif

                    {{-- Editar / Eliminar --}}
                    <div class="flex gap-2 pt-1">
                        <button @click="editModal = true"
                                class="flex-1 flex items-center justify-center gap-1 text-xs text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 py-2 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            Editar
                        </button>
                        <form method="POST" action="{{ route('admin.tables.destroy', $mesa->id) }}" class="flex-1"
                              onsubmit="return confirm('¿Eliminar Mesa {{ $mesa->number }}? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-1 text-xs text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 py-2 rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ── MODAL: Registro de llegada ─────────────────── --}}
                <div x-show="checkInModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="checkInModal = false"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                        <div class="bg-blue-600 px-6 py-5 text-white">
                            <h3 class="font-bold text-lg">Registro de llegada — Mesa {{ $mesa->number }}</h3>
                            <p class="text-blue-100 text-sm mt-0.5">{{ $mesa->capacity }} asientos · {{ $cfg['label'] }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.tables.checkin', $mesa->id) }}" class="p-6 space-y-4">
                            @csrf

                            @if($mesaReservations->isNotEmpty())
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Reserva vinculada</label>
                                <select name="reservation_id"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                                    <option value="">Sin reserva específica</option>
                                    @foreach($mesaReservations as $mr)
                                    <option value="{{ $mr->id }}" selected>
                                        {{ $mr->user->name ?? '?' }} — {{ \Carbon\Carbon::parse($mr->reservation_date)->format('d/m') }} {{ substr($mr->reservation_time ?? '', 0, 5) }} ({{ $mr->party_size }}p)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Mesero asignado</label>
                                <select name="waiter_user_id"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                                    <option value="">Sin asignar</option>
                                    @foreach($meseros as $m)
                                    <option value="{{ $m->user_id }}">{{ $m->name }} ({{ $m->position }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="checkInModal = false"
                                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all">
                                    ✓ Registrar llegada
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── MODAL: Sentar cliente ───────────────────────── --}}
                <div x-show="sentarModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="sentarModal = false"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                        <div class="bg-on-surface px-6 py-5 text-white">
                            <h3 class="font-bold text-lg">Sentar cliente — Mesa {{ $mesa->number }}</h3>
                            <p class="text-gray-400 text-sm mt-0.5">Capacidad: {{ $mesa->capacity }} personas</p>
                        </div>
                        <form method="POST" action="{{ route('admin.tables.sentar', $mesa->id) }}" class="p-6 space-y-4">
                            @csrf

                            @if($unassigned->isNotEmpty())
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">¿Viene con reserva?</label>
                                <select name="reservation_id"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                                    <option value="">Sin reserva (cliente directo)</option>
                                    @foreach($unassigned->where('party_size', '<=', $mesa->capacity) as $ur)
                                    <option value="{{ $ur->id }}">
                                        {{ $ur->user->name ?? '?' }} — {{ \Carbon\Carbon::parse($ur->reservation_date)->format('d/m') }} {{ substr($ur->reservation_time ?? '', 0, 5) }} ({{ $ur->party_size }}p)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nombre del cliente</label>
                                <input type="text" name="customer_name" placeholder="Nombre (opcional)"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Personas *</label>
                                <div class="flex gap-1.5 flex-wrap">
                                    @foreach(range(1, min($mesa->capacity, 10)) as $n)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="party_size" value="{{ $n }}" class="sr-only peer" {{ $n === 2 ? 'checked' : '' }}>
                                        <span class="w-9 h-9 border-2 border-gray-200 rounded-xl flex items-center justify-center text-xs font-bold text-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white transition-all block">{{ $n }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Mesero</label>
                                <select name="waiter_user_id"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                                    <option value="">Sin asignar</option>
                                    @foreach($meseros as $m)
                                    <option value="{{ $m->user_id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Notas</label>
                                <input type="text" name="notes" placeholder="Alergias, ocasión especial…"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="sentarModal = false"
                                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-on-surface hover:bg-gray-800 text-white rounded-xl text-sm font-bold transition-all">
                                    ✓ Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── MODAL: Asignar reserva ──────────────────────── --}}
                @if($unassigned->isNotEmpty())
                <div x-show="assignModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="assignModal = false"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                        <div class="bg-blue-600 px-6 py-5 text-white">
                            <h3 class="font-bold text-lg">Asignar reserva — Mesa {{ $mesa->number }}</h3>
                            <p class="text-blue-100 text-sm">{{ $mesa->capacity }} asientos disponibles</p>
                        </div>
                        <form method="POST" action="{{ route('admin.tables.assign.reservation', $mesa->id) }}" class="p-6 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Reserva a asignar *</label>
                                <select name="reservation_id" required
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 outline-none">
                                    <option value="">Selecciona una reserva…</option>
                                    @foreach($unassigned as $ur)
                                    <option value="{{ $ur->id }}" {{ $ur->party_size > $mesa->capacity ? 'disabled' : '' }}>
                                        {{ $ur->user->name ?? '?' }} — {{ \Carbon\Carbon::parse($ur->reservation_date)->format('d M') }} {{ substr($ur->reservation_time ?? '', 0, 5) }} · {{ $ur->party_size }}p
                                        @if($ur->party_size > $mesa->capacity) (excede capacidad) @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-blue-600 bg-blue-50 rounded-xl p-3">
                                Al asignar, la reserva se confirmará automáticamente y la mesa quedará marcada como <strong>Reservada</strong>.
                            </p>
                            <div class="flex gap-3">
                                <button type="button" @click="assignModal = false"
                                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold">
                                    ✓ Asignar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- ── MODAL: Editar mesa ──────────────────────────── --}}
                <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="editModal = false"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900">Editar Mesa {{ $mesa->number }}</h3>
                            <button @click="editModal = false" class="text-gray-400 hover:text-gray-600">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('admin.tables.update', $mesa->id) }}" class="p-6 space-y-4">
                            @csrf @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Número / nombre</label>
                                <input type="text" name="number" value="{{ $mesa->number }}" required
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Capacidad</label>
                                <input type="number" name="capacity" value="{{ $mesa->capacity }}" min="1" max="50" required
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Zona</label>
                                <input type="text" name="zone" value="{{ $mesa->zone }}" placeholder="Ej: Terraza, Salón principal…"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 outline-none">
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="editModal = false"
                                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-on-surface text-white rounded-xl text-sm font-bold">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- /card -->
            @endforeach
        </div>
    </div>
    @endforeach
    @endif

</div>{{-- /tab plano --}}

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- TAB 2: RESERVAS ════════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="tab === 'reservas'" x-cloak>

    {{-- Filtros --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['upcoming' => 'Próximas', 'today' => 'Hoy', 'pending' => 'Pendientes', 'confirmed' => 'Confirmadas', 'completed' => 'Finalizadas', 'cancelled' => 'Canceladas', 'all' => 'Todas'] as $val => $lbl)
        <a href="{{ route('admin.tables', ['rstatus' => $val]) }}"
           @click="tab = 'reservas'"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                  {{ $statusFiltro === $val
                     ? 'bg-primary-container text-white shadow-sm'
                     : 'bg-white border border-gray-200 text-gray-500 hover:border-primary-container hover:text-primary-container' }}">
            {{ $lbl }}
            @if($val === 'pending' && $pendingCount > 0)
            <span class="ml-1 bg-amber-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $pendingCount }}</span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Tabla de reservas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($reservations->isEmpty())
        <div class="py-16 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-3">calendar_today</span>
            <p class="font-semibold text-gray-400">No hay reservas en este filtro.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Fecha & Hora</th>
                        <th class="px-5 py-3 text-center">Pax</th>
                        <th class="px-5 py-3">Mesa asignada</th>
                        <th class="px-5 py-3">Pre-orden</th>
                        <th class="px-5 py-3">Mesero</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($reservations as $res)
                    @php
                        $items   = $res->selected_items ?? [];
                        $estRev  = collect($items)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? $i['quantity'] ?? 1));
                        $stColor = match($res->status) {
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            'cancelled' => 'bg-red-100 text-red-600',
                            default     => 'bg-gray-100 text-gray-500',
                        };
                        // Mesas disponibles con suficiente capacidad para esta reserva
                        $availableTables = $tables->where('status', 'disponible')
                                                  ->where('capacity', '>=', $res->party_size);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group" x-data="{ detalle: false }">

                        {{-- Cliente --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-orange-50 flex items-center justify-center font-bold text-sm text-orange-500 shrink-0">
                                    {{ strtoupper(substr($res->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-on-surface">{{ $res->user->name ?? '—' }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $res->user->phone ?? $res->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Fecha --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-sm">{{ $res->reservation_date->locale('es')->isoFormat('ddd D MMM') }}</p>
                            <p class="text-xs text-gray-400">{{ substr($res->reservation_time, 0, 5) }}</p>
                        </td>

                        {{-- Pax --}}
                        <td class="px-5 py-4 text-center">
                            <span class="font-bold text-sm">{{ $res->party_size }}</span>
                        </td>

                        {{-- Mesa asignada + selector de mesa --}}
                        <td class="px-5 py-4">
                            @if($res->table)
                            <div class="flex items-center gap-2">
                                <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-xl">
                                    Mesa {{ $res->table->number }}
                                    @if($res->table->zone) · {{ $res->table->zone }} @endif
                                </span>
                                {{-- Opción de reasignar --}}
                                @if(in_array($res->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('admin.reservations.status', $res) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $res->status }}">
                                    <select name="table_id" onchange="this.form.submit()"
                                            class="border border-gray-200 rounded-lg text-xs px-2 py-1 focus:ring-1 focus:ring-orange-400 outline-none text-gray-600">
                                        <option value="{{ $res->table_id }}">Mesa {{ $res->table->number }}</option>
                                        <option value="">Sin mesa</option>
                                        @foreach($availableTables as $at)
                                        @if($at->id !== $res->table_id)
                                        <option value="{{ $at->id }}">Mesa {{ $at->number }}{{ $at->zone ? ' · '.$at->zone : '' }} ({{ $at->capacity }}p)</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </form>
                                @endif
                            </div>
                            @else
                            {{-- Sin mesa: mostrar selector --}}
                            @if(in_array($res->status, ['pending', 'confirmed']) && $availableTables->isNotEmpty())
                            <form method="POST" action="{{ route('admin.reservations.status', $res) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <select name="table_id" onchange="this.form.submit()"
                                        class="border border-gray-200 rounded-xl text-xs px-3 py-1.5 focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all bg-amber-50 text-amber-700 font-semibold">
                                    <option value="">⚠ Sin mesa</option>
                                    @foreach($availableTables as $at)
                                    <option value="{{ $at->id }}">Mesa {{ $at->number }}{{ $at->zone ? ' · '.$at->zone : '' }} ({{ $at->capacity }}p)</option>
                                    @endforeach
                                </select>
                            </form>
                            @else
                            <span class="text-xs text-gray-300 italic">Sin asignar</span>
                            @endif
                            @endif
                        </td>

                        {{-- Pre-orden --}}
                        <td class="px-5 py-4 max-w-xs">
                            @if(count($items) > 0)
                            <div class="space-y-0.5">
                                @foreach(array_slice($items, 0, 3) as $item)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-on-surface truncate max-w-[120px]">{{ $item['name'] ?? '' }}</span>
                                    <span class="text-gray-400 ml-1 shrink-0">×{{ $item['qty'] ?? 1 }}</span>
                                </div>
                                @endforeach
                                @if(count($items) > 3)
                                <p class="text-[10px] text-gray-400">+{{ count($items)-3 }} más</p>
                                @endif
                                @if($estRev > 0)
                                <p class="text-xs font-bold text-primary-container">${{ number_format($estRev, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            @elseif($res->notes)
                            <p class="text-xs text-gray-400 italic">{{ Str::limit($res->notes, 40) }}</p>
                            @else
                            <span class="text-xs text-gray-300">Sin pre-orden</span>
                            @endif
                        </td>

                        {{-- Mesero --}}
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.reservations.status', $res) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $res->status }}">
                                <select name="waiter_id" onchange="this.form.submit()"
                                        class="border border-gray-200 rounded-lg text-xs px-2 py-1.5 focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                                    <option value="">Sin asignar</option>
                                    @foreach($waiters as $w)
                                    <option value="{{ $w->id }}" {{ $res->waiter_id == $w->id ? 'selected' : '' }}>
                                        {{ $w->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        {{-- Estado --}}
                        <td class="px-5 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $stColor }}">
                                {{ $res->statusLabel() }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-1">
                                @if($res->status === 'pending')
                                <form method="POST" action="{{ route('admin.reservations.status', $res) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" title="Confirmar"
                                            class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    </button>
                                </form>
                                @endif
                                @if(in_array($res->status, ['pending','confirmed']))
                                <form method="POST" action="{{ route('admin.reservations.status', $res) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" title="Finalizar"
                                            class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.reservations.status', $res) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" title="Cancelar" onclick="return confirm('¿Cancelar esta reserva?')"
                                            class="p-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                                    </button>
                                </form>
                                @endif

                                {{-- QR --}}
                                @if($res->qr_token)
                                <a href="{{ route('reserva.verificar', $res->qr_token) }}" target="_blank" title="Ver QR"
                                   class="p-1.5 rounded-lg bg-orange-50 text-primary hover:bg-orange-100 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                                </a>
                                @endif

                                {{-- Detalle pre-orden --}}
                                @if(count($items) > 0)
                                <button @click="detalle = !detalle" title="Ver pre-orden"
                                        class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Fila expandible de pre-orden --}}
                    @if(count($items) > 0)
                    <tr x-show="detalle" x-cloak>
                        <td colspan="8" class="px-5 py-4 bg-orange-50/50 border-b border-orange-100">
                            <div class="max-w-3xl">
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">
                                    Pre-orden — {{ $res->user->name }} · {{ $res->reservation_date->locale('es')->isoFormat('D MMM') }} {{ substr($res->reservation_time,0,5) }}
                                    @if($res->table) · Mesa {{ $res->table->number }} @endif
                                </p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    @php $subtotal = 0; @endphp
                                    @foreach($items as $item)
                                    @php $lt = ($item['price']??0)*($item['qty']??$item['quantity']??1); $subtotal += $lt; @endphp
                                    <div class="bg-white rounded-xl px-3 py-2 border border-orange-100 flex justify-between items-center">
                                        <div>
                                            <p class="text-xs font-semibold text-on-surface">{{ $item['name'] ?? 'Ítem' }}</p>
                                            <p class="text-[10px] text-gray-400">×{{ $item['qty']??$item['quantity']??1 }}</p>
                                        </div>
                                        <span class="text-xs font-bold text-primary-container">${{ number_format($lt,0,',','.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-sm font-black text-primary-container">
                                    Total estimado: ${{ number_format($subtotal, 0, ',', '.') }}
                                </p>
                                @if($res->notes)
                                <p class="text-xs text-gray-500 italic mt-1">Nota: {{ $res->notes }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($reservations->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $reservations->appends(['rstatus' => $statusFiltro])->links('pagination::simple-tailwind') }}
        </div>
        @endif
        @endif
    </div>
</div>{{-- /tab reservas --}}

</div>{{-- /x-data tab --}}

</x-admin-layout>
