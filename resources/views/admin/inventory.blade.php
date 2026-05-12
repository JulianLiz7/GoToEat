<x-admin-layout :restaurant="$restaurant" :stats="['lowStockCount' => $lowStockCount]">
<x-slot name="title">Inventario</x-slot>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Inventario</h2>
        <p class="text-gray-500 mt-1">Gestiona los ingredientes y materias primas del restaurante.</p>
    </div>
    <button onclick="document.getElementById('modalAddItem').classList.remove('hidden')"
            class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Agregar ítem
    </button>
</div>

{{-- Alertas de stock bajo --}}
@if($lowStockItems->isNotEmpty())
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-amber-600">warning</span>
        <p class="font-semibold text-amber-800">{{ $lowStockItems->count() }} ítems con stock bajo</p>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($lowStockItems as $item)
        <span class="text-xs bg-amber-100 text-amber-800 px-3 py-1 rounded-full font-medium">
            {{ $item->name }} ({{ $item->quantity }} {{ $item->unit }})
        </span>
        @endforeach
    </div>
</div>
@endif

{{-- Tabla de inventario --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-on-background flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-primary-container">inventory_2</span>
            Todos los ítems
        </h3>
        <span class="text-xs text-gray-400">{{ $items->total() }} ítems totales</span>
    </div>

    @if($items->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">inventory_2</span>
            <p class="text-gray-400 font-medium">Sin ítems en inventario</p>
            <p class="text-gray-300 text-sm mt-1">Agrega ingredientes para comenzar</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Categoría</th>
                        <th class="px-6 py-3 text-right">Cantidad</th>
                        <th class="px-6 py-3 text-right">Mínimo</th>
                        <th class="px-6 py-3 text-right">Costo/U</th>
                        <th class="px-6 py-3 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-on-surface">
                            {{ $item->name }}
                            @if($item->isLowStock())
                            <span class="ml-2 text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold">Stock bajo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $item->category ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-semibold {{ $item->isLowStock() ? 'text-amber-600' : 'text-on-surface' }}">
                            {{ $item->quantity }} {{ $item->unit }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500">{{ $item->min_stock }} {{ $item->unit }}</td>
                        <td class="px-6 py-4 text-right text-gray-500">
                            {{ $item->cost_price ? '$' . number_format($item->cost_price, 0) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full
                                {{ $item->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item->status === 'active' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $items->links() }}
        </div>
    @endif
</div>

</x-admin-layout>
