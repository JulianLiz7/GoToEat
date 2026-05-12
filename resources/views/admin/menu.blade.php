<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Menú</x-slot>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Menú</h2>
        <p class="text-gray-500 mt-1">Administra los platos y categorías del menú.</p>
    </div>
    <button onclick="document.getElementById('modalAddMenuItem').classList.remove('hidden')"
            class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Nuevo plato
    </button>
</div>

{{-- Cards de platos por categoría --}}
@forelse($itemsByCategory as $category => $items)
<div class="mb-8">
    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
        <span class="w-4 h-px bg-gray-200 inline-block"></span>
        {{ $category ?: 'Sin categoría' }}
        <span class="text-gray-300 font-normal normal-case tracking-normal">({{ $items->count() }})</span>
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($items as $item)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
            <div class="h-36 bg-gradient-to-br from-orange-50 to-amber-50 flex items-center justify-center">
                @if($item->image)
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                @else
                    <span class="material-symbols-outlined text-5xl text-orange-200">restaurant_menu</span>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-semibold text-on-surface text-sm leading-snug">{{ $item->name }}</p>
                    <span class="text-xs font-bold text-orange-600 whitespace-nowrap">${{ number_format($item->price, 0) }}</span>
                </div>
                @if($item->description)
                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $item->description }}</p>
                @endif
                <div class="flex items-center justify-between mt-3">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                        {{ $item->available ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $item->available ? 'Disponible' : 'No disponible' }}
                    </span>
                    @if($item->prep_time)
                    <span class="text-[11px] text-gray-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        {{ $item->prep_time }}min
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl shadow-sm">
    <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">restaurant_menu</span>
    <p class="text-gray-400 font-medium text-lg">El menú está vacío</p>
    <p class="text-gray-300 text-sm mt-1">Agrega el primer plato para comenzar</p>
</div>
@endforelse

</x-admin-layout>
