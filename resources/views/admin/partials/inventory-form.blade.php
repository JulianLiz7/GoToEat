{{--
    Formulario compartido entre los modales "Agregar" y "Editar" del inventario.
    Variable $modo: 'agregar' | 'editar'
--}}
@php $es = $modo === 'editar'; @endphp

<div class="grid grid-cols-2 gap-4">

    {{-- Nombre --}}
    <div class="col-span-2">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
            Nombre del producto <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" required
               placeholder="ej. Aceite de oliva extra virgen"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
    </div>

    {{-- Categoría --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Categoría</label>
        <select name="category"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
            <option value="">Sin categoría</option>
            <option value="Verduras y frutas">Verduras y frutas</option>
            <option value="Lácteos">Lácteos</option>
            <option value="Carnes">Carnes</option>
            <option value="Mariscos">Mariscos</option>
            <option value="Panadería">Panadería</option>
            <option value="Bebidas">Bebidas</option>
            <option value="Condimentos">Condimentos</option>
            <option value="Aceites">Aceites</option>
            <option value="Limpieza">Limpieza</option>
            <option value="Otros">Otros</option>
        </select>
    </div>

    {{-- Unidad --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Unidad de medida</label>
        <select name="unit"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
            <option value="kg">Kilogramos (kg)</option>
            <option value="g">Gramos (g)</option>
            <option value="L">Litros (L)</option>
            <option value="mL">Mililitros (mL)</option>
            <option value="und">Unidades (und)</option>
            <option value="caj">Cajas (caj)</option>
            <option value="paq">Paquetes (paq)</option>
            <option value="doc">Docenas (doc)</option>
        </select>
    </div>

    {{-- Cantidad --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
            {{ $es ? 'Cantidad en stock' : 'Stock inicial' }} <span class="text-red-500">*</span>
        </label>
        <input type="number" name="quantity" step="0.01" min="0" required
               placeholder="0.00"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
    </div>

    {{-- Mínimo --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
            Stock mínimo (alerta)
        </label>
        <input type="number" name="min_stock" step="0.01" min="0"
               placeholder="0.00"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
    </div>

    {{-- Costo por unidad --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Costo por unidad ($)</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
            <input type="number" name="cost_price" step="0.01" min="0"
                   placeholder="0.00"
                   class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
        </div>
    </div>

    {{-- Estado (solo en edición) --}}
    @if($es)
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Estado</label>
        <select name="status"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
        </select>
    </div>
    @endif
</div>
