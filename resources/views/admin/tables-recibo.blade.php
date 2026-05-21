<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Recibo — Mesa {{ $table->number }}</x-slot>

<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6 font-medium">
        <a href="{{ route('admin.tables') }}" class="hover:text-orange-500 transition-colors">Mesas & Reservas</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-orange-500 font-bold">Recibo — Mesa {{ $table->number }}</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-on-surface text-white px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Factura / Recibo</p>
                    <h1 class="text-3xl font-black font-heading">Mesa {{ $table->number }}</h1>
                    @if($table->zone)
                    <p class="text-gray-400 text-sm mt-0.5">Zona: {{ $table->zone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-xs">Fecha</p>
                    <p class="font-bold text-white">{{ now()->format('d/m/Y') }}</p>
                    <p class="text-gray-400 text-xs mt-1">Hora</p>
                    <p class="font-bold text-white">{{ now()->format('h:i A') }}</p>
                </div>
            </div>

            {{-- Info del cliente y mesero --}}
            <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-white/10">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Cliente</p>
                    <p class="font-semibold text-white">{{ $table->customer_name ?? 'Cliente general' }}</p>
                    @if($table->party_size)
                    <p class="text-gray-400 text-xs">{{ $table->party_size }} personas</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Mesero</p>
                    @if($waiter)
                    <p class="font-semibold text-white">{{ $waiter->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $waiter->position }}</p>
                    @else
                    <p class="text-gray-400 text-sm italic">Sin asignar</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ítems del pedido --}}
        <div class="px-8 py-6">
            <h3 class="font-bold text-on-surface text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">restaurant_menu</span>
                Detalle del Pedido
            </h3>

            @if(count($items) > 0)
            <div class="space-y-3 mb-6">
                {{-- Encabezado de tabla --}}
                <div class="grid grid-cols-12 text-xs font-bold text-gray-400 uppercase tracking-wide pb-2 border-b border-gray-100">
                    <div class="col-span-6">Ítem</div>
                    <div class="col-span-2 text-center">Cant.</div>
                    <div class="col-span-2 text-right">P. Unit.</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                </div>

                @php $subtotalCalc = 0; @endphp
                @foreach($items as $item)
                @php
                    $qty      = $item['qty'] ?? $item['quantity'] ?? 1;
                    $price    = $item['price'] ?? 0;
                    $lineTotal = $price * $qty;
                    $subtotalCalc += $lineTotal;
                @endphp
                <div class="grid grid-cols-12 items-center py-2.5 border-b border-gray-50 hover:bg-gray-50/50 rounded-lg transition-colors">
                    <div class="col-span-6">
                        <p class="font-semibold text-on-surface text-sm">{{ $item['name'] ?? 'Ítem sin nombre' }}</p>
                        @if(!empty($item['category']))
                        <p class="text-xs text-gray-400">{{ $item['category'] }}</p>
                        @endif
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-sm font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full">×{{ $qty }}</span>
                    </div>
                    <div class="col-span-2 text-right text-sm text-gray-500">
                        ${{ number_format($price, 0, ',', '.') }}
                    </div>
                    <div class="col-span-2 text-right font-bold text-on-surface text-sm">
                        ${{ number_format($lineTotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <span class="material-symbols-outlined text-4xl mb-2">receipt_long</span>
                <p class="text-sm">No hay ítems registrados en esta orden.</p>
            </div>
            @endif

            {{-- Totales --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold text-on-surface">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if($tipRecord)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Propina registrada</span>
                    <span class="font-semibold text-emerald-600">+${{ number_format($tipRecord->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base font-black border-t border-gray-200 pt-2 mt-2">
                    <span class="text-on-surface">TOTAL</span>
                    <span class="text-primary">${{ number_format($subtotal + $tipRecord->amount, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="flex justify-between text-base font-black border-t border-gray-200 pt-2 mt-2">
                    <span class="text-on-surface">TOTAL (sin propina)</span>
                    <span class="text-primary">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            {{-- Propina ya registrada --}}
            @if($tipRecord)
            <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
                <div>
                    <p class="font-semibold text-emerald-800 text-sm">Propina ya registrada en nómina</p>
                    <p class="text-emerald-600 text-xs">
                        ${{ number_format($tipRecord->amount, 0, ',', '.') }} — {{ ucfirst($tipRecord->payment_method) }}
                        @php
                            $emp = DB::table('employees')->join('users','employees.user_id','=','users.id')
                                ->where('employees.id', $tipRecord->employee_id)->select('users.name')->first();
                        @endphp
                        @if($emp) — Asignada a {{ $emp->name }} @endif
                    </p>
                </div>
            </div>
            @endif
        </div>

        {{-- Formulario de cierre con propina --}}
        @if(in_array($order->status, ['pending', 'preparing', 'ready']))
        <div class="px-8 pb-8">
            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-bold text-on-surface text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-primary">payments</span>
                    Cerrar Cuenta y Registrar Propina
                </h3>

                <form method="POST" action="{{ route('admin.tables.cerrar', $table->id) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Método de pago --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                Método de pago *
                            </label>
                            <select name="payment_method" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary outline-none transition-all bg-white">
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                                <option value="transferencia">📲 Transferencia</option>
                            </select>
                        </div>

                        {{-- Propina --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                Propina (opcional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">$</span>
                                <input type="number" name="tip_amount" min="0" step="1000" value="0"
                                       class="w-full pl-7 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary outline-none transition-all"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>

                    {{-- Mesero para propina --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                            Asignar propina a mesero
                        </label>
                        <select name="tip_employee_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary outline-none transition-all bg-white">
                            <option value="">Sin propina / sin asignar</option>
                            @foreach($meseros as $m)
                            <option value="{{ $m->employee_id }}"
                                {{ $waiter && $waiter->employee_id == $m->employee_id ? 'selected' : '' }}>
                                {{ $m->name }} — {{ $m->position }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">info</span>
                            La propina se registra en la nómina del mesero (Ley 1935 de 2018)
                        </p>
                    </div>

                    {{-- Preview total --}}
                    <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4" id="previewTotal">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Total a cobrar</p>
                                <p class="text-2xl font-black text-primary" id="totalDisplay">
                                    ${{ number_format($subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Subtotal: ${{ number_format($subtotal, 0, ',', '.') }}</p>
                                <p class="text-xs text-emerald-600" id="tipDisplay">+ Propina: $0</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.tables') }}"
                           class="flex-1 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 text-center transition-all">
                            ← Volver
                        </a>
                        <button type="submit"
                                class="flex-1 py-3 bg-primary hover:bg-orange-600 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-orange-200 active:scale-95">
                            ✓ Cerrar Cuenta y Liberar Mesa
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        {{-- Orden ya completada --}}
        <div class="px-8 pb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1">task_alt</span>
                <div>
                    <p class="font-semibold text-blue-800 text-sm">Orden completada</p>
                    <p class="text-blue-600 text-xs">Total cobrado: ${{ number_format($order->total ?? $subtotal, 0, ',', '.') }}</p>
                </div>
            </div>
            <a href="{{ route('admin.tables') }}"
               class="mt-4 flex items-center justify-center gap-2 w-full py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                ← Volver al plano
            </a>
        </div>
        @endif
    </div>

    {{-- Nota legal --}}
    <p class="text-center text-xs text-gray-400 mt-4">
        GoToEat · {{ $restaurant->name }} · {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<script>
const subtotal = {{ $subtotal }};
const tipInput = document.querySelector('input[name="tip_amount"]');
const totalDisplay = document.getElementById('totalDisplay');
const tipDisplay = document.getElementById('tipDisplay');

if (tipInput) {
    tipInput.addEventListener('input', function() {
        const tip = parseFloat(this.value) || 0;
        const total = subtotal + tip;
        totalDisplay.textContent = '$' + total.toLocaleString('es-CO');
        tipDisplay.textContent = '+ Propina: $' + tip.toLocaleString('es-CO');
    });
}
</script>

</x-admin-layout>
