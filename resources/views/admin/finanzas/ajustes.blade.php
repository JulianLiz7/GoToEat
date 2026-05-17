<x-finance-layout :restaurant="$restaurant">
<x-slot name="title">Ajustes Financieros</x-slot>

<div class="mb-8">
    <h2 class="text-3xl font-black text-on-surface">Ajustes</h2>
    <p class="text-on-surface-variant text-sm mt-1">Configura parámetros del módulo financiero.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Moneda y formato --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-on-surface mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-container text-[22px]">payments</span>
            Moneda y Formato
        </h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Moneda del restaurante</label>
                <select class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none" disabled>
                    <option>COP — Peso Colombiano ($)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Zona horaria</label>
                <select class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none" disabled>
                    <option>America/Bogota (UTC-5)</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Exportación --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-on-surface mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-container text-[22px]">download</span>
            Exportación de Datos
        </h3>
        <div class="space-y-3">
            <a href="{{ route('admin.finance.export.csv') }}"
               class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-orange-50 hover:border-primary-container/30 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-secondary/10 rounded-lg group-hover:bg-secondary/20 transition-colors">
                        <span class="material-symbols-outlined text-secondary text-[20px]">table_chart</span>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-on-surface">Reporte CSV</p>
                        <p class="text-xs text-gray-400">Compatible con Excel y Google Sheets</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">download</span>
            </a>
            <a href="{{ route('admin.finance.export.pdf') }}" target="_blank"
               class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-orange-50 hover:border-primary-container/30 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-error/10 rounded-lg group-hover:bg-error/20 transition-colors">
                        <span class="material-symbols-outlined text-error text-[20px]">picture_as_pdf</span>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-on-surface">Reporte PDF</p>
                        <p class="text-xs text-gray-400">Imprimible en formato A4</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">open_in_new</span>
            </a>
        </div>
    </div>

    {{-- Info Ley 1935 --}}
    <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 lg:col-span-2">
        <h3 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-500 text-[22px]">volunteer_activism</span>
            Propinas — Ley 1935 de 2018
        </h3>
        <p class="text-sm text-purple-700 leading-relaxed">
            Las propinas son voluntarias y pertenecen <strong>íntegramente al trabajador</strong>.
            GoToEat registra automáticamente las propinas por método de pago y genera reportes de liquidación.
            Las propinas pagadas con tarjeta o transferencia deben ser giradas por el empleador al trabajador.
        </p>
        <a href="{{ route('admin.finance') }}"
           class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-purple-700 hover:underline">
            Ver liquidación del mes
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>
</div>

</x-finance-layout>
