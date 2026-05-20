<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Reservas</x-slot>

<div class="mb-8 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Gestión de Reservas</h2>
        <p class="text-gray-500 mt-1 text-sm">Reservas solicitadas por comensales desde la app.</p>
    </div>
</div>

{{-- KPIs ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl shadow-sm border-b-2 border-amber-400">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Hoy</p>
        <p class="text-2xl font-black text-on-surface">{{ $todayReservations }}</p>
        <p class="text-xs text-gray-400 mt-1">reservas</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border-b-2 border-orange-400">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Pendientes</p>
        <p class="text-2xl font-black text-amber-500">{{ $pendingCount }}</p>
        <p class="text-xs text-gray-400 mt-1">por confirmar</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border-b-2 border-emerald-400">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Confirmadas</p>
        <p class="text-2xl font-black text-emerald-600">{{ $confirmedCount }}</p>
        <p class="text-xs text-gray-400 mt-1">próximas</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border-b-2 border-primary-container">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ingreso estimado</p>
        <p class="text-2xl font-black text-primary-container">${{ number_format($estimatedRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">de pre-órdenes (mes)</p>
    </div>
</div>

{{-- Filtros ──────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach(['upcoming' => 'Próximas', 'today' => 'Hoy', 'pending' => 'Pendientes', 'confirmed' => 'Confirmadas', 'completed' => 'Finalizadas', 'cancelled' => 'Canceladas', 'all' => 'Todas'] as $val => $lbl)
    <a href="{{ route('admin.reservations', ['status' => $val]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
              {{ $status === $val
                 ? 'bg-primary-container text-white shadow-sm'
                 : 'bg-white border border-gray-200 text-gray-500 hover:border-primary-container hover:text-primary-container' }}">
        {{ $lbl }}
    </a>
    @endforeach
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
</div>
@endif

{{-- Tabla de reservas ────────────────────────────────── --}}
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
                    <th class="px-5 py-3">Pre-orden / Notas</th>
                    <th class="px-5 py-3 text-right">$ Estimado</th>
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
                    $stLabel = match($res->status) {
                        'pending'   => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'completed' => 'Finalizada',
                        'cancelled' => 'Cancelada',
                        default     => $res->status,
                    };
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    {{-- Cliente --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-container/20 flex items-center justify-center font-bold text-sm text-primary-container shrink-0">
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
                        <p class="font-semibold text-sm text-on-surface">
                            {{ $res->reservation_date->locale('es')->isoFormat('ddd D MMM') }}
                        </p>
                        <p class="text-xs text-gray-400">{{ substr($res->reservation_time, 0, 5) }}</p>
                    </td>

                    {{-- Pax --}}
                    <td class="px-5 py-4 text-center">
                        <span class="flex items-center justify-center gap-1 font-bold text-sm text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-gray-400">group</span>
                            {{ $res->party_size }}
                        </span>
                    </td>

                    {{-- Pre-orden --}}
                    <td class="px-5 py-4 max-w-xs">
                        @if(count($items) > 0)
                        <div class="space-y-0.5">
                            @foreach(array_slice($items, 0, 3) as $item)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-on-surface truncate max-w-[140px]">{{ $item['name'] ?? '' }}</span>
                                <span class="text-gray-400 ml-2 shrink-0">×{{ $item['qty'] ?? $item['quantity'] ?? 1 }}</span>
                            </div>
                            @endforeach
                            @if(count($items) > 3)
                            <p class="text-[11px] text-gray-400">+{{ count($items) - 3 }} más ítems</p>
                            @endif
                        </div>
                        @elseif($res->notes)
                        <p class="text-xs text-gray-400 italic">{{ Str::limit($res->notes, 50) }}</p>
                        @else
                        <span class="text-xs text-gray-300">Sin pre-orden</span>
                        @endif
                    </td>

                    {{-- $ Estimado --}}
                    <td class="px-5 py-4 text-right">
                        @if($estRev > 0)
                        <span class="font-black text-sm text-primary-container">${{ number_format($estRev, 0, ',', '.') }}</span>
                        @else
                        <span class="text-xs text-gray-300">—</span>
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
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $stColor }}">{{ $stLabel }}</span>
                    </td>

                    {{-- Acciones rápidas --}}
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
                                <button type="submit" title="Marcar finalizada"
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
                            {{-- Detalle de pre-orden completa --}}
                            @if(count($items) > 0)
                            <button onclick="toggleDetalle('detalle-{{ $res->id }}')"
                                    class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Detalle expandible de pre-orden --}}
                @if(count($items) > 0)
                <tr id="detalle-{{ $res->id }}" class="hidden">
                    <td colspan="8" class="px-5 py-4 bg-orange-50/50 border-b border-orange-100">
                        <div class="max-w-2xl">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">
                                Pre-orden completa — {{ $res->user->name }} · {{ $res->reservation_date->locale('es')->isoFormat('D MMM') }} {{ substr($res->reservation_time,0,5) }}
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @php $subtotal = 0; @endphp
                                @foreach($items as $item)
                                @php $lineTotal = ($item['price'] ?? 0) * ($item['qty'] ?? $item['quantity'] ?? 1); $subtotal += $lineTotal; @endphp
                                <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-gray-100">
                                    <div>
                                        <p class="text-xs font-semibold text-on-surface">{{ $item['name'] ?? 'Ítem' }}</p>
                                        @if(isset($item['category']))
                                        <p class="text-[10px] text-gray-400">{{ $item['category'] }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-3">
                                        <p class="text-xs font-bold text-primary-container">${{ number_format($lineTotal, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-gray-400">×{{ $item['qty'] ?? $item['quantity'] ?? 1 }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                @if($res->notes)
                                <p class="text-xs text-gray-500 italic">📝 {{ $res->notes }}</p>
                                @else
                                <div></div>
                                @endif
                                <p class="font-black text-sm text-primary-container">
                                    TOTAL ESTIMADO: ${{ number_format($subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($reservations->hasPages())
    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Mostrando {{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} de {{ $reservations->total() }}
        </p>
        {{ $reservations->links() }}
    </div>
    @endif
    @endif
</div>

<script>
function toggleDetalle(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>

</x-admin-layout>
