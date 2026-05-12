<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Finanzas</x-slot>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Finanzas</h2>
        <p class="text-gray-500 mt-1">Egresos, propinas y reporte financiero del mes.</p>
    </div>
    <button onclick="document.getElementById('modalAddExpense').classList.remove('hidden')"
            class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Registrar egreso
    </button>
</div>

{{-- KPIs del mes --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Ingresos del mes</p>
        <p class="text-3xl font-bold font-heading text-on-surface">${{ number_format($monthRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Egresos del mes</p>
        <p class="text-3xl font-bold font-heading text-red-600">${{ number_format($monthExpenses, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-1">Profit neto</p>
        <p class="text-3xl font-bold font-heading {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
            ${{ number_format(abs($netProfit), 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- Liquidación de propinas (Ley 1935) --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-purple-500">volunteer_activism</span>
            Liquidación de propinas — Ley 1935/2018
        </h3>
        <span class="text-xs text-gray-400">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</span>
    </div>
    @if($tipLiquidation->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">Sin propinas registradas este mes.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <tr>
                        <th class="px-6 py-3 text-left">Empleado</th>
                        <th class="px-6 py-3 text-left">Cargo</th>
                        <th class="px-6 py-3 text-right">Propinas totales</th>
                        <th class="px-6 py-3 text-right">Efectivo (ya recibido)</th>
                        <th class="px-6 py-3 text-right">Por transferir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($tipLiquidation as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $row->employee_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $row->position ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-bold">${{ number_format($row->total_tips, 0) }}</td>
                        <td class="px-6 py-4 text-right text-emerald-600">${{ number_format($row->cash_tips, 0) }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($row->transfer_pending > 0)
                            <span class="font-semibold text-amber-600">${{ number_format($row->transfer_pending, 0) }}</span>
                            @else
                            <span class="text-gray-300">$0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-purple-50 text-xs text-purple-700">
            <span class="material-symbols-outlined text-[14px] align-middle">info</span>
            Ley 1935/2018: las propinas son voluntarias y pertenecen íntegramente al trabajador. Las pagadas con tarjeta deben ser giradas por el empleador.
        </div>
    @endif
</div>

{{-- Últimos egresos --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-red-500">shopping_cart_checkout</span>
            Egresos registrados
        </h3>
    </div>
    @if($expenses->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">Sin egresos registrados.</div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($expenses as $expense)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <p class="font-medium text-on-surface text-sm">{{ $expense->name }}</p>
                    <p class="text-xs text-gray-400">{{ $expense->expense_date->format('d M Y') }}</p>
                </div>
                <p class="font-bold text-red-600">${{ number_format($expense->amount, 0) }}</p>
            </div>
            @endforeach
        </div>
    @endif
</div>

</x-admin-layout>
