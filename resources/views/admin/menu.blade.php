<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Menú</x-slot>

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
            <span>Administración</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-orange-500">Menú</span>
        </nav>
        <h1 class="text-4xl font-black font-heading text-on-surface">Gestión de Menú</h1>
        <p class="text-gray-500 mt-1">Administra los platos y categorías de tu restaurante.</p>
    </div>
    <button onclick="abrirModalMenu('modalCrearPlato')"
            class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-2xl font-bold
                   hover:brightness-110 transition-all active:scale-95 shadow-lg shadow-orange-200 shrink-0">
        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">add_circle</span>
        Crear nuevo plato
    </button>
</div>

{{-- Flash de éxito --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ══ Tabs de filtro ══════════════════════════════════════════════ --}}
<div class="flex gap-3 mb-8 overflow-x-auto pb-2">
    <a href="{{ route('admin.menu') }}"
       class="px-5 py-2 rounded-full font-bold text-sm whitespace-nowrap transition-all
              {{ !$categoriaFiltro ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-400 hover:text-orange-500' }}">
        Todos los platos
    </a>
    @foreach($todasCategorias as $cat)
    <a href="{{ route('admin.menu', ['categoria' => $cat]) }}"
       class="px-5 py-2 rounded-full font-semibold text-sm whitespace-nowrap transition-all
              {{ $categoriaFiltro === $cat ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-400 hover:text-orange-500' }}">
        {{ $cat }}
    </a>
    @endforeach
</div>

{{-- ══ Secciones por categoría ═══════════════════════════════════ --}}
@forelse($itemsByCategory as $categoria => $platos)
<section class="mb-14">
    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-3xl font-bold font-heading text-on-surface whitespace-nowrap">{{ $categoria }}</h2>
        <span class="text-sm text-gray-400 font-medium">({{ $platos->count() }})</span>
        <div class="h-0.5 flex-1 bg-gradient-to-r from-orange-100 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($platos as $plato)
        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl
                    transition-all duration-300 border border-gray-100 flex flex-col">

            {{-- Imagen --}}
            <div class="relative h-44 overflow-hidden bg-gradient-to-br from-orange-50 to-amber-50 shrink-0">
                @if($plato->image)
                <img src="{{ str_starts_with($plato->image,'http') ? $plato->image : \Illuminate\Support\Facades\Storage::url($plato->image) }}"
                     alt="{{ $plato->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-orange-200">restaurant_menu</span>
                </div>
                @endif

                {{-- Badge disponibilidad --}}
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold
                            flex items-center gap-1 bg-white/90 backdrop-blur-sm shadow-sm
                            {{ $plato->available ? 'text-emerald-700' : 'text-gray-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $plato->available ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                    {{ $plato->available ? 'Disponible' : 'No disponible' }}
                </div>

                @if($plato->is_featured)
                <div class="absolute top-3 left-3 px-2 py-0.5 bg-orange-500 text-white
                            rounded-full text-[10px] font-bold uppercase tracking-wide">
                    ⭐ Destacado
                </div>
                @endif
            </div>

            {{-- Contenido --}}
            <div class="p-5 flex flex-col flex-1">
                <div class="flex justify-between items-start mb-1 gap-2">
                    <h3 class="font-bold text-on-surface leading-snug text-base">{{ $plato->name }}</h3>
                    <span class="font-bold text-primary-container whitespace-nowrap text-base shrink-0">
                        ${{ number_format($plato->price, 0) }}
                    </span>
                </div>

                @if($plato->description)
                <p class="text-gray-400 text-xs line-clamp-2 mb-3 flex-1">{{ $plato->description }}</p>
                @else
                <div class="flex-1"></div>
                @endif

                @if($plato->prep_time || $plato->tags)
                <div class="flex flex-wrap gap-1.5 mb-3">
                    @if($plato->prep_time)
                    <span class="flex items-center gap-1 text-[11px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">
                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                        {{ $plato->prep_time }} min
                    </span>
                    @endif
                    @if($plato->tags)
                    <span class="text-[11px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full truncate max-w-[120px]">
                        {{ $plato->tags }}
                    </span>
                    @endif
                </div>
                @endif

                {{-- Acciones --}}
                <div class="flex items-center gap-1.5 border-t border-gray-100 pt-3 mt-auto">
                    {{-- Toggle disponibilidad --}}
                    <form method="POST" action="{{ route('admin.menu.toggle', $plato->id) }}" class="shrink-0">
                        @csrf @method('PATCH')
                        <button type="submit"
                                title="{{ $plato->available ? 'Marcar no disponible' : 'Marcar disponible' }}"
                                class="p-1.5 rounded-lg transition-all
                                       {{ $plato->available
                                          ? 'text-emerald-500 hover:bg-emerald-50 hover:text-emerald-700'
                                          : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }}">
                            <span class="material-symbols-outlined text-[18px]"
                                  style="font-variation-settings:'FILL' {{ $plato->available ? '1' : '0' }}">
                                visibility
                            </span>
                        </button>
                    </form>

                    {{-- Editar --}}
                    <button type="button"
                            title="Editar plato"
                            onclick="abrirEditarPlato(
                                {{ $plato->id }},
                                '{{ addslashes($plato->name) }}',
                                '{{ addslashes($plato->category ?? '') }}',
                                '{{ addslashes($plato->description ?? '') }}',
                                {{ $plato->price }},
                                {{ $plato->prep_time ?? 'null' }},
                                '{{ addslashes($plato->tags ?? '') }}',
                                {{ $plato->available ? 'true' : 'false' }},
                                {{ $plato->is_featured ? 'true' : 'false' }}
                            )"
                            class="p-1.5 text-orange-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>

                    {{-- Eliminar --}}
                    <form method="POST"
                          action="{{ route('admin.menu.destroy', $plato->id) }}"
                          onsubmit="return confirm('¿Eliminar \"{{ addslashes($plato->name) }}\"? Esta acción no se puede deshacer.')"
                          class="shrink-0">
                        @csrf @method('DELETE')
                        <button type="submit"
                                title="Eliminar plato"
                                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>

                    {{-- Precio editable inline --}}
                    <span class="ml-auto text-[11px] text-gray-300 font-medium">
                        ID #{{ $plato->id }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Placeholder agregar nuevo --}}
        <button onclick="abrirModalMenu('modalCrearPlato')"
                class="border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center
                       justify-center p-8 hover:border-orange-300 hover:bg-orange-50/30
                       transition-all cursor-pointer group min-h-[200px]">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3
                        group-hover:bg-orange-100 transition-colors">
                <span class="material-symbols-outlined text-gray-300 text-2xl group-hover:text-orange-500">add</span>
            </div>
            <p class="text-sm font-bold text-gray-400 group-hover:text-orange-600 text-center">
                Agregar {{ strtolower($categoria) !== 'sin categoría' ? $categoria : 'plato' }}
            </p>
        </button>
    </div>
</section>
@empty
<div class="border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center
            justify-center py-24 hover:border-orange-300 hover:bg-orange-50/20 transition-all
            cursor-pointer group"
     onclick="abrirModalMenu('modalCrearPlato')">
    <span class="material-symbols-outlined text-6xl text-gray-200 mb-4 group-hover:text-orange-300 transition-colors">
        restaurant_menu
    </span>
    <p class="text-xl font-bold text-gray-400 group-hover:text-orange-600">El menú está vacío</p>
    <p class="text-gray-300 text-sm mt-2">Haz clic para agregar el primer plato</p>
</div>
@endforelse

{{-- ══════════════════════════════════════════════════════════════════
     MODALES
══════════════════════════════════════════════════════════════════ --}}

{{-- ── Modal Crear plato ──────────────────────────────────────────── --}}
<div id="modalCrearPlato" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-orange-50/40 shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">restaurant_menu</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Crear nuevo plato</h2>
                    <p class="text-xs text-gray-400">Añade un plato a tu menú</p>
                </div>
            </div>
            <button onclick="cerrarModalMenu('modalCrearPlato')"
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="formCrear"
              method="POST"
              action="{{ route('admin.menu.store') }}"
              enctype="multipart/form-data"
              class="overflow-y-auto flex-1">
            @csrf
            <div class="p-8">
                @include('admin.partials.menu-form', ['modo' => 'crear'])
            </div>
            <div class="px-8 pb-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" onclick="cerrarModalMenu('modalCrearPlato')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-100 transition-all">
                    Cancelar
                </button>
                <button type="submit" id="btnCrear"
                        class="px-7 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-bold
                               hover:bg-orange-600 active:scale-95 shadow-sm shadow-orange-200
                               flex items-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar plato
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal Editar plato ──────────────────────────────────────────── --}}
<div id="modalEditarPlato" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-amber-50/40 shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">edit</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Editar plato</h2>
                    <p id="editarPlatoSubtitulo" class="text-xs text-gray-400 truncate max-w-[200px]"></p>
                </div>
            </div>
            <button onclick="cerrarModalMenu('modalEditarPlato')"
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="formEditar"
              method="POST"
              action=""
              enctype="multipart/form-data"
              class="overflow-y-auto flex-1">
            @csrf @method('PUT')
            <div class="p-8">
                @include('admin.partials.menu-form', ['modo' => 'editar'])
            </div>
            <div class="px-8 pb-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" onclick="cerrarModalMenu('modalEditarPlato')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-100 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-7 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-bold
                               hover:bg-amber-600 active:scale-95 shadow-sm shadow-amber-200
                               flex items-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Scripts ─────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
// ── Abrir / cerrar modales ────────────────────────────────────────
function abrirModalMenu(id)  { document.getElementById(id).classList.remove('hidden'); }
function cerrarModalMenu(id) { document.getElementById(id).classList.add('hidden'); }

// Cerrar al hacer clic fuera
['modalCrearPlato','modalEditarPlato'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) cerrarModalMenu(id);
    });
});

// ── Prevenir doble envío ──────────────────────────────────────────
['formCrear','formEditar'].forEach(id => {
    const form = document.getElementById(id);
    if (!form) return;
    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Guardando...';
        }
    });
});

// ── Drag & drop imagen ───────────────────────────────────────────
function configurarImagenUpload(zoneId, inputId, previewId, placeholderId) {
    const zone        = document.getElementById(zoneId);
    const input       = document.getElementById(inputId);
    const preview     = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    if (!zone || !input) return;

    function mostrarPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    zone.addEventListener('click', () => input.click());
    input.addEventListener('change', () => mostrarPreview(input.files[0]));

    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('border-orange-400','bg-orange-50'); });
    zone.addEventListener('dragleave', ()  => zone.classList.remove('border-orange-400','bg-orange-50'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('border-orange-400','bg-orange-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            mostrarPreview(file);
        }
    });
}

// Inicializar upload para ambos modales
document.addEventListener('DOMContentLoaded', () => {
    configurarImagenUpload('imgZoneCrear',  'imgInputCrear',  'imgPreviewCrear',  'imgPlaceholderCrear');
    configurarImagenUpload('imgZoneEditar', 'imgInputEditar', 'imgPreviewEditar', 'imgPlaceholderEditar');

    // Sync radios de disponibilidad → hidden input
    document.querySelectorAll('input[name="available_radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const hidden = document.getElementById(radio.dataset.target);
            if (hidden) hidden.value = radio.value;
        });
    });
});

// ── Abrir modal editar con datos del plato ────────────────────────
function abrirEditarPlato(id, nombre, categoria, descripcion, precio, prepTime, tags, disponible, destacado) {
    const form = document.getElementById('formEditar');
    form.action = `/admin/menu/${id}`;
    document.getElementById('editarPlatoSubtitulo').textContent = nombre;

    const set = (name, val) => {
        const el = form.querySelector(`[name="${name}"]`);
        if (el) el.value = val ?? '';
    };
    set('name',        nombre);
    set('category',    categoria);
    set('description', descripcion);
    set('price',       precio);
    set('prep_time',   prepTime ?? '');
    set('tags',        tags);

    // Radio disponibilidad
    const radios = form.querySelectorAll('input[name="available_radio"]');
    radios.forEach(r => r.checked = (r.value === (disponible ? '1' : '0')));
    const hidden = form.querySelector('#availableHiddenEditar');
    if (hidden) hidden.value = disponible ? '1' : '0';

    // Checkbox destacado
    const featCheck = form.querySelector('[name="is_featured"]');
    if (featCheck) featCheck.checked = destacado;

    // Reset preview
    document.getElementById('imgPreviewEditar').classList.add('hidden');
    document.getElementById('imgPlaceholderEditar').classList.remove('hidden');

    abrirModalMenu('modalEditarPlato');
}
</script>
@endpush

</x-admin-layout>
