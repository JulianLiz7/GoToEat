<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Nueva Mesa</x-slot>

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-xs text-gray-400 mb-6 font-medium">
    <a href="{{ route('admin.tables') }}" class="hover:text-orange-500 transition-colors">Administración</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <a href="{{ route('admin.tables') }}" class="hover:text-orange-500 transition-colors">Mesas & Reservas</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-orange-500 font-bold">Nueva Mesa</span>
</nav>

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="text-4xl font-black font-heading text-on-surface">Agregar Nueva Mesa</h1>
        <p class="text-gray-500 mt-1">Configura la distribución y capacidad de la nueva mesa.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-gray-50 bg-gradient-to-r from-white to-orange-50/30 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                <span class="material-symbols-outlined text-2xl">table_restaurant</span>
            </div>
            <div>
                <h2 class="font-bold text-lg font-heading">Nueva Mesa</h2>
                <p class="text-sm text-gray-400">Define el número, zona y capacidad</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.tables.store') }}" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Número de mesa --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Número / Nombre de mesa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="number" required
                           placeholder="ej. 1, A-01, VIP-3"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                    <p class="text-[11px] text-gray-400 mt-1">Puede ser número o código alfanumérico.</p>
                </div>

                {{-- Capacidad --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Capacidad (personas) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="capacity" required min="1" max="50"
                           value="2"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>

                {{-- Zona --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Zona
                    </label>
                    <input type="text" name="zone"
                           placeholder="ej. Salón Principal, Terraza, Bar, Salón VIP"
                           list="zonasSugeridas"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                    <datalist id="zonasSugeridas">
                        @foreach($existingZones as $z)
                        <option value="{{ $z }}">
                        @endforeach
                        <option value="Salón Principal">
                        <option value="Terraza">
                        <option value="Bar">
                        <option value="Salón Privado">
                        <option value="Exterior">
                        <option value="Planta Alta">
                    </datalist>
                    <p class="text-[11px] text-gray-400 mt-1">Agrupa las mesas por zona del salón.</p>
                </div>

                {{-- Estado inicial --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Estado inicial
                    </label>
                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                        <option value="disponible" selected>Disponible</option>
                        <option value="mantenimiento">Mantenimiento</option>
                        <option value="reservada">Reservada</option>
                    </select>
                </div>
            </div>

            {{-- Preview visual de la mesa --}}
            <div class="pt-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Vista previa</label>
                <div class="bg-gray-50 rounded-2xl p-6 flex items-center justify-center">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 w-48 text-center">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-orange-500 text-2xl">table_restaurant</span>
                        </div>
                        <p class="font-black text-lg font-heading text-on-surface" id="previewNumber">Mesa —</p>
                        <p class="text-xs text-gray-400 mt-1" id="previewCapacity">— personas</p>
                        <span class="inline-flex items-center gap-1 mt-2 text-[11px] font-semibold text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Disponible
                        </span>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <a href="{{ route('admin.tables') }}"
                   class="px-6 py-3 rounded-xl font-semibold text-gray-500 hover:bg-gray-50 transition-colors text-sm">
                    ← Volver
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-on-surface text-white font-bold shadow-lg shadow-gray-200 hover:bg-gray-800 active:scale-95 transition-all flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Crear mesa
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Preview en tiempo real del número y capacidad
document.querySelector('input[name="number"]').addEventListener('input', function() {
    document.getElementById('previewNumber').textContent = this.value ? 'Mesa ' + this.value : 'Mesa —';
});
document.querySelector('input[name="capacity"]').addEventListener('input', function() {
    document.getElementById('previewCapacity').textContent = this.value ? this.value + ' personas' : '— personas';
});
</script>
@endpush

</x-admin-layout>
