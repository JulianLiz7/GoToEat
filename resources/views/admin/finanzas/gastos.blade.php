<x-finance-layout :restaurant="$restaurant">
<x-slot name="title">Gastos</x-slot>
<x-slot name="subtitle">Egresos y presupuesto mensual</x-slot>

{{-- ── Encabezado ─────────────────────────────────────────── --}}
<div class="mb-8 flex items-end justify-between gap-4">
    <div>
        <h2 class="text-3xl font-black text-on-surface">Gestión de Gastos</h2>
        <p class="text-on-surface-variant text-sm mt-1">Registra y categoriza todos los egresos del restaurante.</p>
    </div>
    <button onclick="document.getElementById('modalGasto').classList.remove('hidden')"
            class="flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white rounded-xl font-semibold text-sm
                   hover:bg-primary active:scale-[0.97] transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Registrar Gasto
    </button>
</div>

{{-- ── Presupuesto vs Real + Alertas ─────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    {{-- Barra de presupuesto --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-on-surface mb-1">Presupuesto Mensual vs Real</h3>
        <p class="text-xs text-gray-400 mb-5">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>

        @php $pct = $budget > 0 ? min(round(($monthExpenses / $budget) * 100), 100) : 0; @endphp

        <div class="mb-4">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-semibold text-on-surface">Ejecutado ({{ $pct }}%)</span>
                <span class="text-gray-400">${{ number_format($monthExpenses,0,',','.') }} / ${{ number_format($budget,0,',','.') }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700
                            {{ $pct >= 90 ? 'bg-error' : ($pct >= 70 ? 'bg-amber-500' : 'bg-primary-container') }}"
                     style="width:{{ $pct }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Disponible</p>
                <p class="font-black text-lg {{ ($budget - $monthExpenses) >= 0 ? 'text-secondary' : 'text-error' }}">
                    ${{ number_format(max(0, $budget - $monthExpenses), 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Promedio/día</p>
                <p class="font-black text-lg text-on-surface">
                    ${{ number_format($monthExpenses / max(1, now()->day), 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Proyección</p>
                <p class="font-black text-lg text-primary">
                    ${{ number_format(($monthExpenses / max(1, now()->day)) * now()->daysInMonth, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Alertas de vencimiento --}}
    <div class="bg-red-50 border border-red-100 rounded-2xl p-6 flex flex-col">
        <div class="flex items-center gap-2 text-error mb-4">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">warning</span>
            <h4 class="font-bold text-sm">Facturas Próximas a Vencer</h4>
        </div>
        @if($invoiceAlerts->isEmpty())
        <div class="flex-1 flex flex-col items-center justify-center text-gray-400 text-sm py-4">
            <span class="material-symbols-outlined text-3xl mb-2 text-secondary">check_circle</span>
            Sin vencimientos próximos
        </div>
        @else
        <div class="space-y-3 flex-1">
            @foreach($invoiceAlerts as $alert)
            <div class="bg-white rounded-xl p-3 border border-red-100 hover:border-error/30 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-sm text-on-surface">{{ $alert->name }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            Vence {{ \Carbon\Carbon::parse($alert->due_date)->diffForHumans() }}
                        </p>
                    </div>
                    <p class="font-bold text-sm text-error">${{ number_format($alert->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        @endif
        <button onclick="document.getElementById('modalGasto').classList.remove('hidden')"
                class="mt-4 w-full text-error text-xs font-bold hover:underline flex items-center justify-center gap-1">
            Registrar pago
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </button>
    </div>
</div>

{{-- ── Distribución + Registrar ───────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-8">
    {{-- Donut --}}
    <div class="md:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-on-surface">Distribución de Gastos</h3>
            <span class="text-xs text-gray-400">{{ now()->locale('es')->isoFormat('MMMM') }}</span>
        </div>
        @if($expenseChart->isNotEmpty())
        <div class="flex items-center gap-6">
            <div class="relative w-40 h-40 shrink-0">
                <canvas id="donutGastos"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[10px] text-gray-400 uppercase font-bold">Total</span>
                    <span class="text-lg font-black text-on-surface">${{ number_format($monthExpenses/1000,0) }}k</span>
                </div>
            </div>
            <div class="flex-1 space-y-3">
                @php $donutColors = ['#f97316','#006398','#006c49','#8c7164','#9d4300']; @endphp
                @foreach($expenseChart as $i => $cat)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0"
                                  style="background:{{ $donutColors[$i % count($donutColors)] }}"></span>
                            <span class="font-medium text-on-surface truncate max-w-[130px]">{{ $cat['label'] }}</span>
                        </span>
                        <span class="font-bold text-on-surface">${{ number_format($cat['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-full rounded-full" style="width:{{ $cat['pct'] }}%;background:{{ $donutColors[$i % count($donutColors)] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-32 text-gray-400 text-sm">
            <span class="material-symbols-outlined text-3xl mb-2">pie_chart</span>
            Sin egresos este mes
        </div>
        @endif
    </div>

    {{-- Card acción rápida --}}
    <div class="md:col-span-2 bg-primary rounded-2xl p-6 shadow-lg text-white flex flex-col justify-between">
        <div>
            <span class="material-symbols-outlined text-4xl mb-3 block" style="font-variation-settings:'FILL' 1">receipt</span>
            <h3 class="font-bold text-xl mb-2">Registrar Nuevo Gasto</h3>
            <p class="text-white/80 text-sm leading-relaxed">
                Mantén tu contabilidad al día registrando cada egreso con su categoría.
            </p>
        </div>
        <div class="space-y-3 mt-6">
            <button onclick="document.getElementById('modalGasto').classList.remove('hidden')"
                    class="w-full bg-white text-primary py-3 rounded-xl font-bold text-sm
                           hover:bg-white/90 active:scale-[0.97] transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Ingreso Manual
            </button>
            <button onclick="document.getElementById('modalGasto').classList.remove('hidden')"
                    class="w-full bg-white/20 text-white border border-white/30 py-3 rounded-xl font-bold text-sm
                           hover:bg-white/30 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                Subir Comprobante
            </button>
        </div>
    </div>
</div>

{{-- ── Historial de Egresos ────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-error">shopping_cart_checkout</span>
            Historial de Egresos
        </h3>
        <a href="{{ route('admin.finance.export.csv') }}"
           class="flex items-center gap-2 px-3 py-1.5 bg-surface-container text-on-surface rounded-lg text-xs font-semibold hover:bg-gray-200 transition-colors">
            <span class="material-symbols-outlined text-[16px]">download</span>
            Exportar
        </a>
    </div>

    @if($expenses->isEmpty())
    <div class="py-12 text-center text-gray-400 text-sm">Sin egresos registrados.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-400">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Concepto</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3 text-right">Monto</th>
                    <th class="px-6 py-3 text-center">Comprobante</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($expenses as $expense)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-sm text-on-surface">{{ $expense->name }}</p>
                        @isset($expense->notes)
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ Str::limit($expense->notes, 40) }}</p>
                        @endisset
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant">
                            {{ $expense->category ?? 'Operativos' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-sm text-error">
                        ${{ number_format($expense->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if(!empty($expense->comprobante_path))
                            <a href="{{ Storage::url($expense->comprobante_path) }}" target="_blank"
                               class="inline-flex items-center gap-1 p-1.5 rounded-lg bg-secondary/10 text-secondary hover:bg-secondary/20 transition-colors"
                               title="Ver comprobante">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                        @else
                            <span class="p-1.5 rounded-lg text-gray-300 cursor-default" title="Sin comprobante">
                                <span class="material-symbols-outlined text-[18px]">attach_file</span>
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">Mostrando {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} de {{ $expenses->total() }}</p>
        {{ $expenses->links('vendor.pagination.simple-tailwind') }}
    </div>
    @endif
</div>

{{-- ══ MODAL: Registrar Gasto ════════════════════════════════ --}}
<div id="modalGasto"
     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col"
         style="max-height:90vh;">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-error">add_shopping_cart</span>
                Registrar Gasto
            </h3>
            <button onclick="document.getElementById('modalGasto').classList.add('hidden')"
                    class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>

        {{-- Body --}}
        <form id="formGasto" method="POST" action="{{ route('admin.finance.gastos.store') }}"
              enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Concepto *</label>
                    <input name="name" type="text" required placeholder="Ej: Proveedor Carnes S.A."
                           value="{{ old('name') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Monto *</label>
                        <input name="amount" type="number" step="1" min="0" required placeholder="0"
                               value="{{ old('amount') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha *</label>
                        <input name="expense_date" type="date" required value="{{ old('expense_date', now()->toDateString()) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Categoría</label>
                    <select name="category"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                        <option value="">Seleccionar...</option>
                        @foreach(['Proveedores','Nómina','Servicios','Arriendo','Mantenimiento','Marketing','Operativos','Otros'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha de vencimiento</label>
                    <input name="due_date" type="date" value="{{ old('due_date') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Notas</label>
                    <textarea name="notes" rows="2" placeholder="Detalle adicional..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                </div>

                {{-- Comprobante de pago --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">
                        Comprobante de Pago
                        <span class="text-gray-300 font-normal normal-case ml-1">(JPG, PNG o PDF — máx. 10 MB)</span>
                    </label>
                    <div id="dropZone"
                         class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer transition-all duration-200
                                hover:border-primary-container hover:bg-orange-50/40"
                         onclick="document.getElementById('comprobanteInput').click()"
                         ondragover="event.preventDefault(); this.classList.add('border-primary-container','bg-orange-50/40')"
                         ondragleave="this.classList.remove('border-primary-container','bg-orange-50/40')"
                         ondrop="handleDrop(event)">

                        <div id="dropPlaceholder">
                            <span class="material-symbols-outlined text-3xl text-gray-300 block mb-1"
                                  style="font-variation-settings:'FILL' 0">upload_file</span>
                            <p class="text-sm text-gray-500">Arrastra aquí o <span class="text-primary font-semibold">selecciona un archivo</span></p>
                        </div>

                        <div id="filePreview" class="hidden items-center gap-3 text-left">
                            <div id="previewThumb" class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 overflow-hidden"></div>
                            <div class="flex-1 min-w-0">
                                <p id="fileName" class="text-sm font-semibold text-on-surface truncate"></p>
                                <p id="fileSize" class="text-xs text-gray-400"></p>
                            </div>
                            <button type="button" onclick="clearFile(event)"
                                    class="p-1 rounded-lg hover:bg-red-50 hover:text-error transition-colors text-gray-400 shrink-0">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>

                        <input id="comprobanteInput" name="comprobante" type="file"
                               accept=".jpg,.jpeg,.png,.pdf,.webp"
                               class="hidden" onchange="handleFileSelect(this)"/>
                    </div>
                    @error('comprobante')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('modalGasto').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold hover:bg-primary active:scale-[0.97] transition-all shadow-sm">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const expenseChart = @json($expenseChart);
const COLORS = ['#f97316','#006398','#006c49','#8c7164','#9d4300'];

const donutCtx = document.getElementById('donutGastos')?.getContext('2d');
if (donutCtx && expenseChart.length) {
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: expenseChart.map(c => c.label),
            datasets: [{
                data: expenseChart.map(c => c.amount),
                backgroundColor: expenseChart.map((_, i) => COLORS[i % COLORS.length]),
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderColor: '#fff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: { legend: { display: false } },
            animation: { duration: 900, easing: 'easeOutQuart' },
        },
    });
}

// Mostrar modal si hay errores de validación
@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('modalGasto').classList.remove('hidden');
});
@endif

// ── Comprobante upload: preview ────────────────────────────────────
function handleFileSelect(input) {
    if (input.files && input.files[0]) showFilePreview(input.files[0]);
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('border-primary-container','bg-orange-50/40');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const allowed = ['image/jpeg','image/png','image/webp','application/pdf'];
    if (!allowed.includes(file.type)) { alert('Solo se permiten JPG, PNG, WEBP o PDF'); return; }
    if (file.size > 10 * 1024 * 1024) { alert('El archivo supera los 10 MB'); return; }
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('comprobanteInput').files = dt.files;
    showFilePreview(file);
}

function showFilePreview(file) {
    const placeholder = document.getElementById('dropPlaceholder');
    const preview     = document.getElementById('filePreview');
    const thumb       = document.getElementById('previewThumb');
    const nameEl      = document.getElementById('fileName');
    const sizeEl      = document.getElementById('fileSize');

    nameEl.textContent = file.name;
    sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

    thumb.innerHTML = '';
    if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover';
        img.src = URL.createObjectURL(file);
        thumb.appendChild(img);
    } else {
        thumb.innerHTML = '<span class="material-symbols-outlined text-2xl text-error">picture_as_pdf</span>';
    }

    placeholder.classList.add('hidden');
    preview.classList.remove('hidden');
    preview.classList.add('flex');
}

function clearFile(e) {
    e.stopPropagation();
    document.getElementById('comprobanteInput').value = '';
    document.getElementById('dropPlaceholder').classList.remove('hidden');
    const preview = document.getElementById('filePreview');
    preview.classList.add('hidden');
    preview.classList.remove('flex');
}
</script>
@endpush

</x-finance-layout>
