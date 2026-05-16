<x-admin-layout :restaurant="$restaurant" :stats="['lowStockCount' => $lowStockCount]">
<x-slot name="title">Inventario</x-slot>

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div>
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
            <span>Administración</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-orange-500">Inventario</span>
        </nav>
        <h1 class="text-4xl font-black font-heading text-on-surface">Gestión de Inventario</h1>
        <p class="text-gray-500 mt-1">Controla el stock de ingredientes y materias primas en tiempo real.</p>
    </div>
    <button onclick="abrirModal('modalAgregar')"
            class="flex items-center gap-2 bg-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition-all active:scale-95 shadow-lg shadow-orange-200 shrink-0">
        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">add_circle</span>
        Agregar ítem
    </button>
</div>

{{-- Flash de éxito --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ══ KPIs ══════════════════════════════════════════════════════ --}}
@php $lowCount = $lowStockItems->count(); @endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50">
        <div class="flex justify-between items-start mb-3">
            <div class="bg-orange-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-orange-500">inventory</span>
            </div>
            <span class="text-emerald-600 text-xs font-bold bg-emerald-50 px-2 py-1 rounded-full">Activos</span>
        </div>
        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Total ítems</p>
        <p class="text-3xl font-black font-heading mt-1">{{ $items->total() }}</p>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-l-red-400 border border-gray-50">
        <div class="flex justify-between items-start mb-3">
            <div class="bg-red-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-red-500">warning</span>
            </div>
        </div>
        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Stock bajo</p>
        <p class="text-3xl font-black font-heading mt-1 {{ $lowCount > 0 ? 'text-red-600' : '' }}">
            {{ $lowCount }}
            <span class="text-sm font-normal text-gray-400 ml-1">ítems</span>
        </p>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50">
        <div class="flex justify-between items-start mb-3">
            <div class="bg-blue-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-blue-500">payments</span>
            </div>
        </div>
        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Valor total</p>
        <p class="text-3xl font-black font-heading mt-1">${{ number_format($totalValue, 0) }}</p>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50">
        <div class="flex justify-between items-start mb-3">
            <div class="bg-purple-50 p-2 rounded-xl">
                <span class="material-symbols-outlined text-purple-500">category</span>
            </div>
        </div>
        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Categorías</p>
        <p class="text-3xl font-black font-heading mt-1">{{ $categorias->count() }}</p>
    </div>
</div>

{{-- ══ Alertas de stock bajo ══════════════════════════════════════ --}}
@if($lowStockItems->isNotEmpty())
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
    <span class="material-symbols-outlined text-amber-600 shrink-0 mt-0.5">warning</span>
    <div>
        <p class="font-semibold text-amber-800 text-sm mb-2">{{ $lowCount }} ítem(s) necesitan reabastecimiento</p>
        <div class="flex flex-wrap gap-2">
            @foreach($lowStockItems as $low)
            <span class="text-xs bg-amber-100 text-amber-800 px-3 py-1 rounded-full font-medium">
                {{ $low->name }} — {{ $low->quantity }} {{ $low->unit }} (mín. {{ $low->min_stock }})
            </span>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ══ Tabla ══════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">

    {{-- Barra de filtros --}}
    <form method="GET" action="{{ route('admin.inventory') }}"
          class="p-5 border-b border-gray-100 flex flex-wrap items-center gap-3 bg-gray-50/30">
        {{-- Búsqueda --}}
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar ítem..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
        </div>

        {{-- Filtro categoría --}}
        <select name="categoria"
                class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-orange-500 outline-none bg-white min-w-[160px]">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
            <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        {{-- Solo stock bajo --}}
        <label class="flex items-center gap-2 text-sm font-medium text-gray-600 cursor-pointer select-none">
            <input type="checkbox" name="solo_bajo_stock" value="1"
                   {{ request('solo_bajo_stock') ? 'checked' : '' }}
                   class="rounded text-orange-500 focus:ring-orange-500">
            Solo stock bajo
        </label>

        <button type="submit"
                class="px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-all active:scale-95">
            Filtrar
        </button>

        @if(request()->hasAny(['buscar','categoria','solo_bajo_stock']))
        <a href="{{ route('admin.inventory') }}"
           class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all">
            Limpiar
        </a>
        @endif

        <span class="ml-auto text-xs text-gray-400">{{ $items->total() }} ítem(s)</span>
    </form>

    {{-- Tabla vacía --}}
    @if($items->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">inventory_2</span>
        <p class="text-gray-400 font-medium text-lg">
            {{ request()->hasAny(['buscar','categoria','solo_bajo_stock']) ? 'Sin resultados para ese filtro' : 'Sin ítems en inventario' }}
        </p>
        @if(!request()->hasAny(['buscar','categoria','solo_bajo_stock']))
        <button onclick="abrirModal('modalAgregar')"
                class="mt-4 text-orange-500 font-semibold text-sm hover:underline">
            Agregar el primer ítem
        </button>
        @endif
    </div>
    @else

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/70 text-gray-400 text-[11px] font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">Producto</th>
                    <th class="px-6 py-4">Categoría</th>
                    <th class="px-6 py-4 text-center">Nivel de stock</th>
                    <th class="px-6 py-4">Costo / Unidad</th>
                    <th class="px-6 py-4 text-center">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($items as $item)
                @php
                $maxRef   = $item->min_stock > 0 ? $item->min_stock * 3 : ($item->quantity > 0 ? $item->quantity : 1);
                $stockPct = min(100, round(($item->quantity / $maxRef) * 100));
                $barColor = $stockPct < 20 ? 'bg-red-400' : ($stockPct < 50 ? 'bg-amber-400' : 'bg-emerald-400');
                $isLow    = $item->isLowStock();
                @endphp
                <tr class="hover:bg-orange-50/10 transition-colors group">

                    {{-- Producto --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-orange-400 text-xl">kitchen</span>
                            </div>
                            <div>
                                <p class="font-semibold text-on-surface text-sm">{{ $item->name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->unit ?: '—' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Categoría --}}
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $item->category ?: '—' }}</span>
                    </td>

                    {{-- Nivel de stock --}}
                    <td class="px-6 py-4">
                        <div class="w-36 mx-auto">
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="{{ $isLow ? 'text-red-500' : 'text-gray-700' }}">
                                    {{ number_format($item->quantity, 2) }} {{ $item->unit }}
                                </span>
                                @if($item->min_stock > 0)
                                <span class="text-gray-400">mín. {{ $item->min_stock }}</span>
                                @endif
                            </div>
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                                     style="width:{{ $stockPct }}%"></div>
                            </div>
                        </div>
                    </td>

                    {{-- Costo --}}
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                        {{ $item->cost_price ? '$' . number_format($item->cost_price, 2) . ' / ' . ($item->unit ?: 'u') : '—' }}
                    </td>

                    {{-- Estado --}}
                    <td class="px-6 py-4 text-center">
                        @if($isLow)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            Stock bajo
                        </span>
                        @elseif($item->status === 'active')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            {{ $stockPct > 60 ? 'Óptimo' : 'Estable' }}
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-100">
                            Inactivo
                        </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">

                            {{-- Ajustar stock --}}
                            <button type="button"
                                    title="Ajustar cantidad"
                                    onclick="abrirAjuste({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->quantity }}, '{{ $item->unit }}')"
                                    class="p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-[20px]">tune</span>
                            </button>

                            {{-- Editar --}}
                            <button type="button"
                                    title="Editar ítem"
                                    onclick="abrirEditar(
                                        {{ $item->id }},
                                        '{{ addslashes($item->name) }}',
                                        '{{ addslashes($item->category ?? '') }}',
                                        {{ $item->quantity }},
                                        '{{ $item->unit ?? '' }}',
                                        {{ $item->min_stock ?? 0 }},
                                        {{ $item->cost_price ?? 0 }},
                                        '{{ $item->status }}'
                                    )"
                                    class="p-2 text-amber-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>

                            {{-- Eliminar --}}
                            <form method="POST"
                                  action="{{ route('admin.inventory.destroy', $item->id) }}"
                                  onsubmit="return confirm('¿Eliminar \"{{ addslashes($item->name) }}\"? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Eliminar ítem"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Mostrando {{ $items->firstItem() }}–{{ $items->lastItem() }} de {{ $items->total() }} ítems
        </p>
        {{ $items->links() }}
    </div>
    @endif
</div>

{{-- ══ Banner IA ═══════════════════════════════════════════════════ --}}
<div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl">
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl">
            <div class="flex items-center gap-2 text-orange-200 text-xs font-bold uppercase tracking-widest mb-4">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">auto_awesome</span>
                Motor de Compras IA
            </div>
            <h2 class="text-3xl font-black font-heading leading-tight">Optimiza tus compras con inteligencia artificial</h2>
            <p class="text-orange-100 text-base mt-4 leading-relaxed">
                La IA analiza tu inventario, historial de ventas y mermas para recomendarte cuándo y cuánto comprar.
            </p>
            <a href="{{ route('admin.ai') }}"
               class="inline-flex items-center gap-2 mt-6 bg-white text-orange-600 px-6 py-3 rounded-xl font-bold hover:bg-orange-50 transition-all active:scale-95 shadow-lg text-sm">
                <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                Consultar al asistente IA
            </a>
        </div>
        <div class="hidden lg:block w-64 h-40 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-5 rotate-3 shadow-2xl">
            <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-4">Índice de precios</p>
            <div class="space-y-3">
                @foreach([70, 45, 90] as $w)
                <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full" style="width:{{ $w }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-orange-400/20 rounded-full blur-3xl"></div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODALES
══════════════════════════════════════════════════════════════ --}}

{{-- ── Modal: Agregar ítem ─────────────────────────────────────── --}}
<div id="modalAgregar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">
        <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-orange-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">add_box</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Agregar nuevo ítem</h2>
                    <p class="text-xs text-gray-400">Registra un ingrediente o materia prima</p>
                </div>
            </div>
            <button onclick="cerrarModal('modalAgregar')" class="p-2 hover:bg-gray-100 rounded-full">
                <span class="material-symbols-outlined text-gray-400">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.inventory.store') }}" class="p-7 space-y-4">
            @csrf
            @include('admin.partials.inventory-form', ['modo' => 'agregar'])
            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="cerrarModal('modalAgregar')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-bold hover:bg-orange-600 active:scale-95 shadow-sm shadow-orange-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar ítem
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Editar ítem ───────────────────────────────────────── --}}
<div id="modalEditar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">
        <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-amber-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">edit</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Editar ítem</h2>
                    <p id="editarSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalEditar')" class="p-2 hover:bg-gray-100 rounded-full">
                <span class="material-symbols-outlined text-gray-400">close</span>
            </button>
        </div>
        <form id="formEditar" method="POST" action="" class="p-7 space-y-4">
            @csrf
            @method('PUT')
            @include('admin.partials.inventory-form', ['modo' => 'editar'])
            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="cerrarModal('modalEditar')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-bold hover:bg-amber-600 active:scale-95 shadow-sm shadow-amber-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Ajustar stock ─────────────────────────────────────── --}}
<div id="modalAjuste" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-blue-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">tune</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Ajustar stock</h2>
                    <p id="ajusteSubtitulo" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalAjuste')" class="p-2 hover:bg-gray-100 rounded-full">
                <span class="material-symbols-outlined text-gray-400">close</span>
            </button>
        </div>

        <form id="formAjuste" method="POST" action="" class="p-7 space-y-5">
            @csrf
            @method('PATCH')

            {{-- Stock actual --}}
            <div class="bg-gray-50 rounded-2xl p-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-bold mb-1">Stock actual</p>
                <p class="text-3xl font-black font-heading text-on-surface" id="stockActualTexto">—</p>
            </div>

            {{-- Tipo de ajuste --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipo de ajuste</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="agregar" class="peer hidden" checked>
                        <div class="px-3 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 text-center text-sm font-semibold transition-all text-gray-500 hover:border-gray-300">
                            <span class="material-symbols-outlined text-[18px] block mx-auto mb-0.5">add</span>
                            Agregar
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="retirar" class="peer hidden">
                        <div class="px-3 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 text-center text-sm font-semibold transition-all text-gray-500 hover:border-gray-300">
                            <span class="material-symbols-outlined text-[18px] block mx-auto mb-0.5">remove</span>
                            Retirar
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="fijar" class="peer hidden">
                        <div class="px-3 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 text-center text-sm font-semibold transition-all text-gray-500 hover:border-gray-300">
                            <span class="material-symbols-outlined text-[18px] block mx-auto mb-0.5">edit_note</span>
                            Fijar
                        </div>
                    </label>
                </div>
            </div>

            {{-- Cantidad --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                    Cantidad <span id="ajusteUnidad" class="normal-case font-normal text-gray-400"></span>
                </label>
                <div class="flex items-center gap-3">
                    <button type="button"
                            onclick="cambiarCantidad(-1)"
                            class="w-11 h-11 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xl transition-all active:scale-90">
                        −
                    </button>
                    <input type="number" name="cantidad" id="inputCantidad"
                           step="0.01" min="0.01" value="1" required
                           class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-center text-lg font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none"/>
                    <button type="button"
                            onclick="cambiarCantidad(1)"
                            class="w-11 h-11 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xl transition-all active:scale-90">
                        +
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="cerrarModal('modalAjuste')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-500 text-white text-sm font-bold hover:bg-blue-600 active:scale-95 shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    Confirmar ajuste
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Scripts de los modales ───────────────────────────────────── --}}
@push('scripts')
<script>
function abrirModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }

// Cerrar al hacer clic fuera del modal
['modalAgregar','modalEditar','modalAjuste'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) cerrarModal(id);
    });
});

// ── Editar ítem ─────────────────────────────────────────────────
function abrirEditar(id, nombre, categoria, cantidad, unidad, minStock, costoPrecio, estado) {
    const form = document.getElementById('formEditar');
    form.action = `/admin/inventory/${id}`;
    document.getElementById('editarSubtitulo').textContent = nombre;

    form.querySelector('[name="name"]').value       = nombre;
    form.querySelector('[name="category"]').value   = categoria;
    form.querySelector('[name="quantity"]').value   = cantidad;
    form.querySelector('[name="unit"]').value       = unidad;
    form.querySelector('[name="min_stock"]').value  = minStock;
    form.querySelector('[name="cost_price"]').value = costoPrecio;
    form.querySelector('[name="status"]').value     = estado;

    abrirModal('modalEditar');
}

// ── Ajustar stock ────────────────────────────────────────────────
function abrirAjuste(id, nombre, cantidadActual, unidad) {
    const form = document.getElementById('formAjuste');
    form.action = `/admin/inventory/${id}/stock`;

    document.getElementById('ajusteSubtitulo').textContent = nombre;
    document.getElementById('stockActualTexto').textContent = cantidadActual + ' ' + unidad;
    document.getElementById('ajusteUnidad').textContent     = unidad ? `(${unidad})` : '';
    document.getElementById('inputCantidad').value          = 1;

    abrirModal('modalAjuste');
}

function cambiarCantidad(delta) {
    const input = document.getElementById('inputCantidad');
    const nuevo = Math.max(0.01, parseFloat(input.value || 0) + delta);
    input.value = parseFloat(nuevo.toFixed(2));
}
</script>
@endpush

</x-admin-layout>
