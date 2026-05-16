{{--
    Formulario compartido entre modales "Crear" y "Editar" del menú.
    Variable $modo: 'crear' | 'editar'
--}}
@php
$sufijo = $modo === 'editar' ? 'Editar' : 'Crear';
@endphp

<div class="grid grid-cols-12 gap-5">

    {{-- ── Foto del plato (col izquierda) ──────────────────────── --}}
    <div class="col-span-4 space-y-4">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Foto del plato</label>

        <div id="imgZone{{ $sufijo }}"
             class="relative aspect-square rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200
                    flex flex-col items-center justify-center overflow-hidden cursor-pointer
                    hover:border-orange-300 hover:bg-orange-50/20 transition-all group">
            <img id="imgPreview{{ $sufijo }}" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview"/>
            <div id="imgPlaceholder{{ $sufijo }}" class="flex flex-col items-center gap-2 p-4 text-center">
                <span class="material-symbols-outlined text-4xl text-gray-300 group-hover:text-orange-400 transition-colors">cloud_upload</span>
                <p class="text-xs font-semibold text-gray-400 group-hover:text-orange-500 transition-colors leading-tight">
                    Clic o arrastra<br/>para subir imagen
                </p>
                <p class="text-[10px] text-gray-300">PNG, JPG (máx. 2MB)</p>
            </div>
            <input id="imgInput{{ $sufijo }}" type="file" name="image" accept="image/*" class="hidden"/>
        </div>

        {{-- Disponibilidad --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Estado</label>
            <input type="hidden" name="available_radio" id="availableHidden{{ $sufijo }}" value="1">
            <div class="space-y-2">
                <label class="flex items-center justify-between px-4 py-3 rounded-xl border-2 cursor-pointer
                              border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 transition-all">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-semibold text-emerald-800">Disponible</span>
                    </div>
                    <input type="radio" name="available_radio" value="1"
                           data-target="availableHidden{{ $sufijo }}"
                           checked class="text-orange-500 focus:ring-orange-400">
                </label>
                <label class="flex items-center justify-between px-4 py-3 rounded-xl border-2 cursor-pointer
                              border-gray-200 hover:bg-gray-50 transition-all">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        <span class="text-sm font-semibold text-gray-600">No disponible</span>
                    </div>
                    <input type="radio" name="available_radio" value="0"
                           data-target="availableHidden{{ $sufijo }}"
                           class="text-orange-500 focus:ring-orange-400">
                </label>
            </div>
        </div>
    </div>

    {{-- ── Campos del plato (col derecha) ───────────────────────── --}}
    <div class="col-span-8 space-y-4">

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Nombre del plato <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" required
                   placeholder="ej. Lomo al trapo con chimichurri"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm
                          focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Categoría</label>
                <select name="category"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm
                               focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none bg-white">
                    <option value="">Sin categoría</option>
                    <option value="Entradas">Entradas</option>
                    <option value="Sopas">Sopas</option>
                    <option value="Platos principales">Platos principales</option>
                    <option value="Ensaladas">Ensaladas</option>
                    <option value="Postres">Postres</option>
                    <option value="Bebidas">Bebidas</option>
                    <option value="Bebidas alcohólicas">Bebidas alcohólicas</option>
                    <option value="Desayunos">Desayunos</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Especialidades">Especialidades</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                    Precio ($) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                    <input type="number" name="price" step="0.01" min="0" required
                           placeholder="0.00"
                           class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 text-sm
                                  focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Descripción</label>
            <textarea name="description" rows="3"
                      placeholder="Describe los ingredientes, preparación y acompañamientos..."
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm resize-none
                             focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                    Tiempo de preparación (min)
                </label>
                <input type="number" name="prep_time" min="1" max="180"
                       placeholder="15"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm
                              focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                    Etiquetas
                </label>
                <input type="text" name="tags"
                       placeholder="vegano, sin gluten, picante..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm
                              focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
            </div>
        </div>

        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_featured" value="1"
                   class="w-4 h-4 rounded text-orange-500 focus:ring-orange-400">
            <span class="text-sm font-medium text-gray-700">⭐ Marcar como plato destacado</span>
        </label>
    </div>
</div>
