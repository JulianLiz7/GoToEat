<x-admin-layout :restaurant="$restaurant" :stats="['lowStockCount' => $lowStockCount]">
<x-slot name="title">Inventario</x-slot>

{{-- ══ Breadcrumb + Header ════════════════════════════════════════ --}}
<div class="flex justify-between items-end mb-8">
    <div>
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
            <span>Management</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-orange-500">Inventory</span>
        </nav>
        <h1 class="text-5xl font-black font-heading text-on-surface">Inventory Management</h1>
        <p class="text-gray-500 mt-2 text-lg">Track your kitchen stock and optimize procurement with real-time analytics.</p>
    </div>
    <button onclick="document.getElementById('modalAddItem').classList.remove('hidden')"
            class="flex items-center gap-2 bg-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition-all active:scale-[0.98] shadow-lg shadow-orange-200">
        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">add_circle</span>
        Add New Item
    </button>
</div>

{{-- ══ KPI Bento ══════════════════════════════════════════════════ --}}
@php
$totalValue = $items->sum(fn($i) => ($i->quantity ?? 0) * ($i->cost_price ?? 0));
$lowCount   = $lowStockItems->count();
@endphp
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-orange-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-orange-500">inventory</span>
            </div>
            <span class="text-emerald-500 text-xs font-bold bg-emerald-50 px-2 py-1 rounded-full">Activos</span>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Items</p>
            <p class="text-3xl font-black font-heading mt-1">{{ $items->count() }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 border-l-4 border-l-red-400">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-red-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-red-500">warning</span>
            </div>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Low Stock Alerts</p>
            <p class="text-3xl font-black font-heading mt-1">
                {{ $lowCount }}
                <span class="text-sm font-normal text-gray-400 ml-1">Items</span>
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-blue-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-blue-500">payments</span>
            </div>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Inventory Value</p>
            <p class="text-3xl font-black font-heading mt-1">${{ number_format($totalValue, 0) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-purple-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-purple-500">category</span>
            </div>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Categorías</p>
            <p class="text-3xl font-black font-heading mt-1">
                {{ $items->pluck('category')->filter()->unique()->count() }}
            </p>
        </div>
    </div>
</div>

{{-- ══ Tabla de inventario ══════════════════════════════════════ --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden mb-8">

    {{-- Filtros --}}
    <div class="p-6 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4 bg-white/50">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-sm font-semibold text-gray-600 cursor-pointer hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_list</span>
                Filter by Category
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-sm font-semibold text-gray-600 cursor-pointer hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-sm">sort</span>
                Sort: Stock Level
            </div>
        </div>
        <span class="text-sm text-gray-400">{{ $items->count() }} items totales</span>
    </div>

    @if($items->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">inventory_2</span>
        <p class="text-gray-400 font-medium">Sin ítems en inventario</p>
        <button onclick="document.getElementById('modalAddItem').classList.remove('hidden')"
                class="mt-4 text-orange-500 font-semibold text-sm hover:underline">Agregar el primero</button>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">Item Details</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-center">Stock Level</th>
                    <th class="px-6 py-4">Cost / Unit</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($items as $item)
                @php
                $stockPct = $item->min_stock > 0
                    ? min(100, round(($item->quantity / ($item->min_stock * 3)) * 100))
                    : ($item->quantity > 0 ? 75 : 0);
                $barColor = $stockPct < 20 ? 'bg-red-400' : ($stockPct < 50 ? 'bg-orange-400' : 'bg-emerald-400');
                $isLow    = $item->isLowStock();
                @endphp
                <tr class="hover:bg-orange-50/10 transition-colors group">
                    {{-- Item details --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-orange-400 text-2xl">kitchen</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface">{{ $item->name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->unit ? $item->unit : '—' }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Categoría --}}
                    <td class="px-6 py-5">
                        <span class="text-sm text-gray-600">{{ $item->category ?? '—' }}</span>
                    </td>
                    {{-- Stock con barra de progreso --}}
                    <td class="px-6 py-5">
                        <div class="w-full max-w-[140px] mx-auto">
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="{{ $isLow ? 'text-red-500' : 'text-on-surface' }}">{{ $stockPct }}%</span>
                                <span class="text-gray-400">{{ $item->quantity }} {{ $item->unit }}</span>
                            </div>
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                                     style="width:{{ $stockPct }}%"></div>
                            </div>
                        </div>
                    </td>
                    {{-- Precio --}}
                    <td class="px-6 py-5">
                        <span class="font-semibold text-on-surface">
                            {{ $item->cost_price ? '$' . number_format($item->cost_price, 2) . ' / ' . ($item->unit ?? 'u') : '—' }}
                        </span>
                    </td>
                    {{-- Estado --}}
                    <td class="px-6 py-5">
                        @if($isLow)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            Low Stock
                        </span>
                        @elseif($item->status === 'active')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            {{ $stockPct > 60 ? 'Optimal' : 'Stable' }}
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-100">
                            Inactive
                        </span>
                        @endif
                    </td>
                    {{-- Acciones --}}
                    <td class="px-6 py-5 text-right">
                        <button class="text-gray-400 hover:text-orange-500 transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="p-6 border-t border-gray-50 flex items-center justify-between">
        {{ $items->links() }}
    </div>
    @endif
</div>

{{-- ══ Banner IA Procurement ══════════════════════════════════════ --}}
<div class="mt-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl">
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl">
            <div class="flex items-center gap-2 text-orange-200 text-xs font-bold uppercase tracking-widest mb-4">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">auto_awesome</span>
                AI Procurement Engine
            </div>
            <h2 class="text-3xl font-black font-heading leading-tight">
                Optimiza tus compras con IA
            </h2>
            <p class="text-orange-100 text-base mt-4 font-medium opacity-90">
                Basado en tu inventario actual, la IA recomienda cuándo y cuánto comprar para reducir mermas y maximizar márgenes.
            </p>
            <div class="flex gap-4 mt-8">
                <button class="bg-white text-orange-600 px-8 py-3 rounded-xl font-bold hover:bg-orange-50 transition-all active:scale-95 shadow-lg text-sm">
                    View Recommendations
                </button>
            </div>
        </div>
        <div class="hidden lg:block w-64 h-44 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 rotate-3 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xs font-bold uppercase text-white/70">Price Index</span>
                <span class="material-symbols-outlined text-emerald-400 text-sm">trending_down</span>
            </div>
            <div class="space-y-3">
                @foreach([70, 45, 90] as $w)
                <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full" style="width:{{ $w }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-orange-400/20 rounded-full blur-3xl"></div>
</div>

{{-- ══ Modal: Agregar ítem ════════════════════════════════════════ --}}
<div id="modalAddItem" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-orange-50/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined text-2xl">add_box</span>
                </div>
                <div>
                    <h2 class="font-bold text-xl font-heading">Add New Item</h2>
                    <p class="text-sm text-gray-400">Register a new product to your inventory system</p>
                </div>
            </div>
            <button onclick="document.getElementById('modalAddItem').classList.add('hidden')"
                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.inventory.store') }}" class="p-8 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Item Name</label>
                    <input type="text" name="name" required placeholder="ej. Extra Virgin Olive Oil"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
                    <select name="category" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                        <option value="">Select Category</option>
                        <option>Produce</option><option>Dairy</option><option>Meat</option>
                        <option>Seafood</option><option>Bakery</option><option>Beverages</option><option>Cleaning</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Unit</label>
                    <select name="unit" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                        <option value="kg">kg</option><option value="L">Litros</option>
                        <option value="units">Unidades</option><option value="boxes">Cajas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Initial Stock</label>
                    <input type="number" name="quantity" step="0.01" min="0" placeholder="0.00" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Low Stock Alert</label>
                    <input type="number" name="min_stock" step="0.01" min="0" placeholder="Mínimo"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Cost per Unit</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="cost_price" step="0.01" min="0" placeholder="0.00"
                               class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                    </div>
                </div>
            </div>

            {{-- Guías --}}
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex gap-3">
                    <span class="material-symbols-outlined text-blue-500 text-[20px] shrink-0">info</span>
                    <p class="text-xs text-blue-700">El stock mínimo activa alertas y el pipeline de IA para recomendaciones de compra.</p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex gap-3">
                    <span class="material-symbols-outlined text-emerald-500 text-[20px] shrink-0">verified_user</span>
                    <p class="text-xs text-emerald-700">El escandallo descuenta ingredientes automáticamente al completar órdenes.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-50">
                <button type="button"
                        onclick="document.getElementById('modalAddItem').classList.add('hidden')"
                        class="px-6 py-3 rounded-xl font-semibold text-gray-500 hover:bg-gray-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-orange-500 text-white font-bold shadow-lg shadow-orange-200 hover:bg-orange-600 active:scale-95 transition-all flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Confirm &amp; Add Item
                </button>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
