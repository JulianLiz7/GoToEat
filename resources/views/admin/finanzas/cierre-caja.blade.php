<x-finance-layout :restaurant="$restaurant">
<x-slot name="title">Cierre de Caja</x-slot>
<x-slot name="subtitle">{{ now()->format('d/m/Y') }} — {{ auth()->user()->name }}</x-slot>

<div x-data="{
    step: 1,
    notes: '',
    depositAmount: 0,
    denominations: [
        { label: '\$100.000', value: 100000, qty: 0, type: 'billete' },
        { label: '\$50.000',  value: 50000,  qty: 0, type: 'billete' },
        { label: '\$20.000',  value: 20000,  qty: 0, type: 'billete' },
        { label: '\$10.000',  value: 10000,  qty: 0, type: 'billete' },
        { label: '\$5.000',   value: 5000,   qty: 0, type: 'billete' },
        { label: '\$2.000',   value: 2000,   qty: 0, type: 'billete' },
        { label: '\$1.000',   value: 1000,   qty: 0, type: 'billete' },
        { label: '\$1.000',   value: 1000,   qty: 0, type: 'moneda'  },
        { label: '\$500',     value: 500,    qty: 0, type: 'moneda'  },
        { label: '\$200',     value: 200,    qty: 0, type: 'moneda'  },
        { label: '\$100',     value: 100,    qty: 0, type: 'moneda'  },
        { label: '\$50',      value: 50,     qty: 0, type: 'moneda'  },
    ],
    get billetes() { return this.denominations.filter(d => d.type === 'billete'); },
    get monedas()  { return this.denominations.filter(d => d.type === 'moneda');  },
    get totalCounted() {
        return this.denominations.reduce((s, d) => s + d.value * (parseInt(d.qty) || 0), 0);
    },
    get difference() { return this.totalCounted - {{ $expectedCash }}; },
    formatCOP(n) { return '\$' + n.toLocaleString('es-CO'); },
    get denominationJson() {
        return JSON.stringify(this.denominations.map(d => ({ label: d.label, value: d.value, qty: d.qty, type: d.type })));
    }
}">

{{-- ── Stepper ─────────────────────────────────────────────── --}}
<div class="flex items-center justify-center max-w-lg mx-auto mb-10 relative">
    <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-200 -z-0"></div>
    <div class="absolute top-5 left-0 h-0.5 bg-primary-container -z-0 transition-all duration-500"
         :style="'width:' + ((step - 1) * 50) + '%'"></div>

    @foreach([1 => 'Conteo', 2 => 'Conciliación', 3 => 'Finalización'] as $s => $label)
    <div class="flex flex-col items-center gap-2 flex-1 relative z-10">
        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm"
             :class="{
                'bg-primary-container text-white shadow-orange-200': step >= {{ $s }},
                'bg-gray-100 text-gray-400 border-2 border-gray-200': step < {{ $s }}
             }">
            <template x-if="step > {{ $s }}">
                <span class="material-symbols-outlined text-[18px]">check</span>
            </template>
            <template x-if="step <= {{ $s }}">
                <span>{{ $s }}</span>
            </template>
        </div>
        <span class="text-xs font-bold uppercase tracking-wide transition-colors"
              :class="step >= {{ $s }} ? 'text-primary' : 'text-gray-400'">{{ $label }}</span>
    </div>
    @endforeach
</div>

{{-- ══ PASO 1: CONTEO ════════════════════════════════════════ --}}
<div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Conteo físico --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-on-surface mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container text-[22px]">payments</span>
                Conteo de Efectivo
                <span class="text-xs text-gray-400 font-normal ml-1">— Introduce las cantidades físicas</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Billetes --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-2 mb-3">Billetes</h4>
                    <div class="space-y-2">
                        <template x-for="(d, i) in billetes" :key="'b'+i">
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-xl hover:bg-orange-50 transition-colors">
                                <div class="w-16 text-right font-bold text-primary text-sm shrink-0" x-text="d.label"></div>
                                <div class="flex-1 flex items-center gap-2">
                                    <button type="button" @click="d.qty = Math.max(0, (parseInt(d.qty)||0) - 1)"
                                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-container hover:text-white hover:border-primary-container transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <input type="number" x-model.number="d.qty" min="0"
                                           class="flex-1 h-8 bg-white border border-gray-200 rounded-lg text-center font-bold text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none"/>
                                    <button type="button" @click="d.qty = (parseInt(d.qty)||0) + 1"
                                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-container hover:text-white hover:border-primary-container transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                                <div class="w-20 text-right font-bold text-sm text-on-surface shrink-0"
                                     x-text="formatCOP(d.value * (parseInt(d.qty)||0))"></div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Monedas --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-2 mb-3">Monedas</h4>
                    <div class="space-y-2">
                        <template x-for="(d, i) in monedas" :key="'m'+i">
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-xl hover:bg-orange-50 transition-colors">
                                <div class="w-16 text-right font-bold text-primary text-sm shrink-0" x-text="d.label"></div>
                                <div class="flex-1 flex items-center gap-2">
                                    <button type="button" @click="d.qty = Math.max(0, (parseInt(d.qty)||0) - 1)"
                                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-container hover:text-white hover:border-primary-container transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <input type="number" x-model.number="d.qty" min="0"
                                           class="flex-1 h-8 bg-white border border-gray-200 rounded-lg text-center font-bold text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none"/>
                                    <button type="button" @click="d.qty = (parseInt(d.qty)||0) + 1"
                                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-container hover:text-white hover:border-primary-container transition-all active:scale-90">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                                <div class="w-20 text-right font-bold text-sm text-on-surface shrink-0"
                                     x-text="formatCOP(d.value * (parseInt(d.qty)||0))"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-on-surface-variant text-sm uppercase tracking-wide">Total Contado</span>
                <span class="text-2xl font-black text-primary" x-text="formatCOP(totalCounted)"></span>
            </div>
        </div>

        {{-- Resumen lateral --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-4">Datos del Día</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Ventas del día (POS)</span>
                        <span class="font-bold text-sm">${{ number_format($expectedCash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Órdenes procesadas</span>
                        <span class="font-bold text-sm">{{ $todayOrdersCount }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Egresos del día</span>
                        <span class="font-bold text-sm text-error">-${{ number_format($todayExpenses, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-semibold text-on-surface">Efectivo esperado</span>
                        <span class="font-black text-base text-primary">${{ number_format($expectedCash, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Ventas por método --}}
            @if($salesByMethod->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-4 text-sm">Ventas por Método</h3>
                <div class="space-y-2">
                    @foreach($salesByMethod as $sm)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 capitalize">{{ $sm->method }}</span>
                        <div class="text-right">
                            <span class="font-bold text-sm text-on-surface">${{ number_format($sm->total, 0, ',', '.') }}</span>
                            <span class="text-[11px] text-gray-400 ml-1">({{ $sm->cnt }})</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <button @click="step = 2"
                    class="w-full py-3.5 bg-primary-container text-white rounded-xl font-bold text-sm
                           hover:bg-primary active:scale-[0.97] transition-all shadow-md shadow-orange-200 flex items-center justify-center gap-2">
                Continuar a Conciliación
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>
    </div>
</div>

{{-- ══ PASO 2: CONCILIACIÓN ══════════════════════════════════ --}}
<div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            {{-- Resumen diferencias --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-5">Resumen de Diferencias</h3>
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Efectivo en POS</p>
                        <p class="text-2xl font-black text-on-surface">${{ number_format($expectedCash, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Contado Físico</p>
                        <p class="text-2xl font-black text-on-surface" x-text="formatCOP(totalCounted)"></p>
                    </div>
                </div>

                {{-- Indicador de diferencia --}}
                <div class="rounded-xl p-4 flex items-center justify-between transition-colors"
                     :class="difference === 0 ? 'bg-secondary/10 border border-secondary/20'
                           : difference > 0 ? 'bg-blue-50 border border-blue-200'
                           : 'bg-error/10 border border-error/20'">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center"
                             :class="difference === 0 ? 'bg-secondary text-white'
                                   : difference > 0 ? 'bg-blue-500 text-white'
                                   : 'bg-error text-white'">
                            <span class="material-symbols-outlined text-[20px]"
                                  x-text="difference === 0 ? 'check_circle' : difference > 0 ? 'trending_up' : 'trending_down'"></span>
                        </div>
                        <div>
                            <p class="font-bold text-sm"
                               :class="difference === 0 ? 'text-secondary'
                                     : difference > 0 ? 'text-blue-700'
                                     : 'text-error'"
                               x-text="difference === 0 ? 'Cuadre perfecto' : difference > 0 ? 'Sobrante detectado' : 'Faltante detectado'"></p>
                            <p class="text-xs text-gray-500">
                                Diferencia entre contado y esperado
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-black"
                           :class="difference === 0 ? 'text-secondary'
                                 : difference > 0 ? 'text-blue-700'
                                 : 'text-error'"
                           x-text="(difference >= 0 ? '+' : '') + formatCOP(difference)"></p>
                        <p class="text-[10px] text-gray-400 uppercase">Diferencia</p>
                    </div>
                </div>
            </div>

            {{-- Ajustes y notas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-4 text-sm">Ajustes y Observaciones</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Observaciones</label>
                        <textarea x-model="notes" rows="3"
                                  placeholder="Ej: Error de cambio mesa 4, propina no registrada, billete deteriorado..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Importe a depositar</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-sm">$</span>
                            <input type="number" x-model.number="depositAmount" min="0" step="1000"
                                   class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Monto físico que saldrá de caja hacia el banco.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar conciliación --}}
        <div class="space-y-4">
            <div class="bg-on-surface text-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-primary px-5 py-4">
                    <h3 class="font-bold">Resumen de Cierre</h3>
                    <p class="text-white/70 text-xs mt-0.5">Conciliación en tiempo real</p>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70 text-sm">Efectivo POS</span>
                        <span class="font-bold">${{ number_format($expectedCash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70 text-sm">Contado</span>
                        <span class="font-bold" x-text="formatCOP(totalCounted)"></span>
                    </div>
                    <div class="rounded-xl p-3 text-center mt-2"
                         :class="difference === 0 ? 'bg-secondary/30' : difference > 0 ? 'bg-blue-500/30' : 'bg-error/30'">
                        <span class="text-[10px] font-bold uppercase tracking-wider block mb-1" x-text="difference >= 0 ? 'SOBRANTE' : 'FALTANTE'"></span>
                        <span class="text-2xl font-black" x-text="(difference >= 0 ? '+' : '') + formatCOP(difference)"></span>
                    </div>
                    <div class="pt-2 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-white/60">Egresos del día</span>
                            <span class="font-semibold text-red-300">-${{ number_format($todayExpenses, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/60">A depositar</span>
                            <span class="font-semibold" x-text="formatCOP(depositAmount)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button @click="step = 3"
                        class="w-full py-3.5 bg-primary-container text-white rounded-xl font-bold text-sm
                               hover:bg-primary active:scale-[0.97] transition-all shadow-md shadow-orange-200 flex items-center justify-center gap-2">
                    Continuar a Finalización
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
                <button @click="step = 1"
                        class="w-full py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Volver al Conteo
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ PASO 3: FINALIZACIÓN ══════════════════════════════════ --}}
<div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Resumen final --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Sincronización exitosa --}}
            <div class="bg-secondary/10 border border-secondary/20 p-4 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white shrink-0">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">cloud_done</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-secondary">Listo para cerrar</p>
                    <p class="text-xs text-secondary/70">Todos los datos serán guardados al confirmar.</p>
                </div>
            </div>

            {{-- Depósito --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 bg-tertiary/10 rounded-xl">
                        <span class="material-symbols-outlined text-tertiary text-[22px]">account_balance</span>
                    </div>
                    <h3 class="font-bold text-on-surface">Depósito Bancario</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Monto retirado de caja para depósito al finalizar la jornada.</p>
                <div class="bg-gray-50 rounded-xl p-6 flex flex-col items-center border-2 border-dashed border-gray-200">
                    <span class="text-xs text-gray-400 font-bold uppercase mb-2">Cantidad a retirar</span>
                    <span class="text-4xl font-black text-primary" x-text="formatCOP(depositAmount)"></span>
                    <div class="flex items-center gap-1 mt-2 text-secondary">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                        <span class="text-xs font-bold">Confirmado para depósito</span>
                    </div>
                </div>
            </div>

            {{-- Resumen del día --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-on-surface mb-4">Resumen del Día</h3>
                <div class="divide-y divide-gray-50">
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm text-gray-500">Ventas del día</span>
                        <span class="font-bold text-secondary">${{ number_format($expectedCash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm text-gray-500">Egresos del día</span>
                        <span class="font-bold text-error">-${{ number_format($todayExpenses, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm text-gray-500">Efectivo contado</span>
                        <span class="font-bold" x-text="formatCOP(totalCounted)"></span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm text-gray-500">Diferencia (descuadre)</span>
                        <span class="font-bold" :class="difference >= 0 ? 'text-secondary' : 'text-error'"
                              x-text="(difference >= 0 ? '+' : '') + formatCOP(difference)"></span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-sm font-semibold text-on-surface">A depositar</span>
                        <span class="font-black text-primary" x-text="formatCOP(depositAmount)"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-gray-400 text-[18px]">info</span>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Al confirmar, se guardará el cierre y se notificará al administrador. Esta acción registra el estado final del turno.
                    </p>
                </div>
            </div>

            {{-- Formulario de envío --}}
            <form method="POST" action="{{ route('admin.finance.cierre.store') }}">
                @csrf
                <input type="hidden" name="cash_expected"       value="{{ $expectedCash }}">
                <input type="hidden" name="cash_counted"        :value="totalCounted">
                <input type="hidden" name="deposit_amount"      :value="depositAmount">
                <input type="hidden" name="notes"               :value="notes">
                <input type="hidden" name="denomination_counts" :value="denominationJson">
                <input type="hidden" name="shift_name"          value="completo">

                <div class="flex flex-col gap-3">
                    <button type="submit"
                            class="w-full py-4 bg-primary text-white rounded-xl font-bold
                                   hover:bg-primary/90 active:scale-[0.97] transition-all shadow-lg shadow-primary-container/30 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">print</span>
                        Confirmar Cierre de Caja
                    </button>
                    <button type="button" @click="step = 2"
                            class="w-full py-3 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Revisar Conciliación
                    </button>
                    <a href="{{ route('admin.finance.export.pdf') }}" target="_blank"
                       class="w-full py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-center">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        Previsualizar Reporte
                    </a>
                </div>
            </form>

            {{-- Estado del último cierre --}}
            @if($existingCierre)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-xs font-bold text-amber-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">info</span>
                    Cierre anterior registrado hoy
                </p>
                <p class="text-xs text-amber-600 mt-1">
                    Efectivo contado: ${{ number_format($existingCierre->cash_counted, 0, ',', '.') }} —
                    Estado: {{ $existingCierre->status }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

</div>{{-- /x-data --}}

</x-finance-layout>
