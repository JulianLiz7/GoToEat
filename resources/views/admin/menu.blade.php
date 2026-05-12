<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Menú</x-slot>
@php use Illuminate\Support\Facades\Storage; @endphp

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <h1 class="text-5xl font-black font-heading text-on-background">Menu Management</h1>
        <p class="text-gray-500 mt-2 text-lg">Curate your restaurant's culinary offerings and seasonal specials.</p>
    </div>
    <button onclick="document.getElementById('modalAddMenuItem').classList.remove('hidden')"
            class="flex items-center justify-center gap-2 bg-primary-container text-white px-8 py-4 rounded-2xl font-bold text-base shadow-xl shadow-orange-200 hover:-translate-y-0.5 transition-all active:scale-95">
        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">add_circle</span>
        Create New Dish
    </button>
</div>

{{-- ══ Tabs de categorías ═════════════════════════════════════════ --}}
@php
$allCategories = $itemsByCategory->keys()->filter()->values();
@endphp
<div class="flex gap-3 mb-10 overflow-x-auto pb-2">
    <button class="px-6 py-2 bg-primary-container text-white rounded-full font-bold shadow-md whitespace-nowrap text-sm">
        All Items
    </button>
    @foreach($allCategories as $cat)
    <button class="px-6 py-2 bg-white text-gray-600 rounded-full font-semibold border border-gray-100 hover:border-orange-500 hover:text-orange-500 transition-all whitespace-nowrap text-sm">
        {{ $cat }}
    </button>
    @endforeach
</div>

{{-- ══ Secciones por categoría ═══════════════════════════════════ --}}
@forelse($itemsByCategory as $category => $items)
<section class="mb-16">
    {{-- Título de categoría + línea --}}
    <div class="flex items-center gap-4 mb-8">
        <h2 class="text-4xl font-bold font-heading text-on-surface whitespace-nowrap">{{ $category ?: 'Sin categoría' }}</h2>
        <div class="h-0.5 flex-1 bg-gradient-to-r from-orange-100 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($items as $item)
        {{-- ── Tarjeta de plato ─────────────────────────────── --}}
        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-50">
            {{-- Imagen --}}
            <div class="relative h-56 overflow-hidden bg-gradient-to-br from-orange-50 to-amber-50">
                @if($item->image)
                <img src="{{ str_starts_with($item->image, 'http') ? $item->image : Storage::url($item->image) }}"
                     alt="{{ $item->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-orange-200">restaurant_menu</span>
                </div>
                @endif

                {{-- Badge de estado --}}
                <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1
                            bg-white/90 backdrop-blur-sm
                            {{ $item->available ? 'text-emerald-700' : 'text-gray-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $item->available ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                    {{ $item->available ? 'Active' : 'Draft' }}
                </div>

                @if($item->is_featured)
                <div class="absolute top-4 left-4 px-2 py-0.5 bg-orange-500 text-white rounded-full text-[10px] font-bold uppercase tracking-wide">
                    ⭐ Featured
                </div>
                @endif
            </div>

            {{-- Contenido --}}
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold font-heading text-on-surface leading-snug">{{ $item->name }}</h3>
                    <span class="text-xl font-bold text-primary-container whitespace-nowrap ml-2">${{ number_format($item->price, 0) }}</span>
                </div>

                @if($item->description)
                <p class="text-gray-500 text-sm line-clamp-2 mb-5">{{ $item->description }}</p>
                @else
                <div class="mb-5"></div>
                @endif

                <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                    <div class="flex items-center gap-2 text-gray-400">
                        @if($item->prep_time)
                        <span class="flex items-center gap-1 text-xs">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            {{ $item->prep_time }}min
                        </span>
                        @endif
                        @if($item->tags)
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $item->tags }}</span>
                        @endif
                    </div>
                    <div class="flex gap-1">
                        <button class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all"
                                title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                title="Eliminar">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- ── Placeholder "Agregar nuevo" ─────────────────── --}}
        <button onclick="document.getElementById('modalAddMenuItem').classList.remove('hidden')"
                class="border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center p-12 hover:border-orange-300 hover:bg-orange-50/30 transition-all cursor-pointer group min-h-[200px]">
            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4 group-hover:bg-orange-100 transition-colors">
                <span class="material-symbols-outlined text-gray-300 text-4xl group-hover:text-orange-500">add</span>
            </div>
            <p class="font-bold text-gray-400 group-hover:text-orange-600">Add New {{ $category ?: 'Dish' }}</p>
        </button>
    </div>
</section>
@empty
{{-- Estado vacío --}}
<div class="border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center p-24 hover:border-orange-300 hover:bg-orange-50/30 transition-all cursor-pointer group"
     onclick="document.getElementById('modalAddMenuItem').classList.remove('hidden')">
    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-6 group-hover:bg-orange-100 transition-colors">
        <span class="material-symbols-outlined text-gray-300 text-5xl group-hover:text-orange-500">restaurant_menu</span>
    </div>
    <p class="font-bold text-xl text-gray-400 group-hover:text-orange-600">El menú está vacío</p>
    <p class="text-gray-300 text-sm mt-2">Haz clic para agregar el primer plato</p>
</div>
@endforelse

{{-- ══ Modal: Crear plato ════════════════════════════════════════
     Script de imagen: preview + drag & drop
     Se define aquí arriba para que @push('scripts') lo capture --}}

@push('scripts')
<script>
(function () {
    const zone     = document.getElementById('imageDropZone');
    const input    = document.getElementById('imageFileInput');
    const preview  = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    const availHidden = document.getElementById('availableHidden');

    // Sinc radio → hidden available
    document.querySelectorAll('input[name="available_radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            availHidden.value = radio.value;
        });
    });

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    input.addEventListener('change', () => showPreview(input.files[0]));

    // Drag & drop
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-orange-400', 'bg-orange-50'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('border-orange-400', 'bg-orange-50'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('border-orange-400', 'bg-orange-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Asignar al input para que se envíe con el form
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showPreview(file);
        }
    });
})();
</script>
@endpush

{{-- ══ Modal: Crear plato ════════════════════════════════════════ --}}
<div id="modalAddMenuItem" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-orange-50/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined text-2xl">restaurant_menu</span>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-on-surface font-heading">Create New Dish</h2>
                    <p class="text-sm text-gray-400">Agrega un nuevo plato al menú</p>
                </div>
            </div>
            <button onclick="document.getElementById('modalAddMenuItem').classList.add('hidden')"
                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="p-8 space-y-5 max-h-[80vh] overflow-y-auto">
            @csrf

            {{-- ── Layout dos columnas: foto + campos ── --}}
            <div class="grid grid-cols-12 gap-5">

                {{-- Foto del plato --}}
                <div class="col-span-4 flex flex-col gap-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Dish Photo</label>
                    {{-- Zona drag & drop --}}
                    <div class="relative aspect-square rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200
                                flex flex-col items-center justify-center gap-2 overflow-hidden
                                hover:border-orange-300 hover:bg-orange-50/30 transition-all cursor-pointer group"
                         id="imageDropZone"
                         onclick="document.getElementById('imageFileInput').click()">
                        {{-- Preview de imagen --}}
                        <img id="imagePreview"
                             class="absolute inset-0 w-full h-full object-cover rounded-2xl hidden"
                             alt="Preview"/>
                        {{-- Placeholder --}}
                        <div id="imagePlaceholder" class="flex flex-col items-center gap-2 p-4 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-300 group-hover:text-orange-400 transition-colors">cloud_upload</span>
                            <p class="text-xs font-semibold text-gray-400 group-hover:text-orange-500 transition-colors">
                                Click to upload<br/>or drag &amp; drop
                            </p>
                            <p class="text-[10px] text-gray-300">PNG, JPG (máx. 2MB)</p>
                        </div>
                        <input id="imageFileInput"
                               type="file"
                               name="image"
                               accept="image/*"
                               class="hidden"/>
                    </div>

                    {{-- Disponibilidad --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Availability</label>
                        <label class="flex items-center justify-between p-3 rounded-xl border border-emerald-100 bg-emerald-50/30 cursor-pointer">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-sm font-medium text-emerald-800">Active</span>
                            </div>
                            <input type="radio" name="available_radio" value="1" checked
                                   class="text-orange-500 focus:ring-orange-500 border-gray-300"/>
                        </label>
                        <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                <span class="text-sm font-medium text-gray-600">Draft</span>
                            </div>
                            <input type="radio" name="available_radio" value="0"
                                   class="text-orange-500 focus:ring-orange-500 border-gray-300"/>
                        </label>
                        {{-- Hidden para enviar el valor real --}}
                        <input type="hidden" name="available" id="availableHidden" value="1"/>
                    </div>
                </div>

                {{-- Campos del plato --}}
                <div class="col-span-8 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nombre del plato</label>
                        <input type="text" name="name" required placeholder="ej. Ribeye a las brasas"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Categoría</label>
                            <select name="category" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                                <option value="">Seleccionar...</option>
                                <option>Starters</option><option>Mains</option><option>Desserts</option><option>Beverages</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Precio ($)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                <input type="number" name="price" step="0.01" min="0" placeholder="0.00" required
                                       class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Descripción</label>
                        <textarea name="description" rows="3" placeholder="Describe los ingredientes y método de preparación..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tiempo de prep. (min)</label>
                            <input type="number" name="prep_time" min="1" placeholder="15"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tags / Etiquetas</label>
                            <input type="text" name="tags" placeholder="ej. vegano, sin gluten"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                        </div>
                    </div>

                    {{-- Dietary tags --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dietary Info</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach([
                                ['icon' => 'eco',        'label' => 'Gluten Free'],
                                ['icon' => 'egg_alt',    'label' => 'Vegetarian'],
                                ['icon' => 'star',       'label' => "Chef's Special"],
                                ['icon' => 'local_fire_department', 'label' => 'Spicy'],
                            ] as $tag)
                            <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 text-xs font-semibold text-gray-600 cursor-pointer hover:border-orange-400 hover:text-orange-500 transition-all has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 has-[:checked]:text-orange-600">
                                <input type="checkbox" name="dietary[]" value="{{ $tag['label'] }}" class="hidden"/>
                                <span class="material-symbols-outlined text-[14px]">{{ $tag['icon'] }}</span>
                                {{ $tag['label'] }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-orange-500 focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-700">⭐ Marcar como destacado</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-50">
                <button type="button"
                        onclick="document.getElementById('modalAddMenuItem').classList.add('hidden')"
                        class="px-6 py-3 rounded-xl font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-primary-container text-white font-bold shadow-lg shadow-orange-200 hover:brightness-110 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar plato
                </button>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
