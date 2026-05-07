<x-app-layout>

{{-- ── Hero de búsqueda ──────────────────────────────────────────── --}}
<div class="bg-gradient-to-br from-secondary to-green-800 -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 px-4 sm:px-6 lg:px-8 py-12 mb-8">
    <div class="max-w-3xl mx-auto text-center">
        <p class="text-green-200 text-sm font-semibold uppercase tracking-widest mb-2">Explora restaurantes</p>
        <h1 class="text-white font-heading text-3xl sm:text-4xl font-bold mb-6">
            ¿Qué quieres comer hoy?
        </h1>

        {{-- Buscador --}}
        <form method="GET" action="{{ route('dashboard') }}" class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none">search</span>
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Buscar por nombre, cocina o especialidad…"
                class="w-full pl-12 pr-36 py-4 rounded-2xl border-0 text-on-surface text-base shadow-xl outline-none focus:ring-2 focus:ring-white/50 transition-all"/>
            <button type="submit"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary-container text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-all">
                Buscar
            </button>
        </form>
    </div>
</div>

{{-- ── Filtro por tipo de cocina ────────────────────────────────── --}}
@if($cuisines->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('dashboard', ['search' => $search]) }}"
           class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold transition-all border
                  {{ !$cuisine ? 'bg-primary-container text-white border-primary-container' : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary-container hover:text-primary-container' }}">
            Todos
        </a>
        @foreach($cuisines as $c)
        <a href="{{ route('dashboard', ['search' => $search, 'cuisine' => $c]) }}"
           class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold transition-all border
                  {{ $cuisine === $c ? 'bg-primary-container text-white border-primary-container' : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary-container hover:text-primary-container' }}">
            {{ $c }}
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ── Resultados ───────────────────────────────────────────────── --}}
@if($search || $cuisine)
<div class="mb-4 flex items-center gap-2">
    <p class="text-sm text-on-surface-variant">
        {{ $restaurants->total() }} {{ $restaurants->total() === 1 ? 'resultado' : 'resultados' }}
        @if($search) para "<strong class="text-on-surface">{{ $search }}</strong>" @endif
        @if($cuisine) en <strong class="text-on-surface">{{ $cuisine }}</strong> @endif
    </p>
    <a href="{{ route('dashboard') }}" class="text-xs text-primary-container hover:underline ml-auto">
        Limpiar filtros
    </a>
</div>
@endif

{{-- ── Grid de restaurantes ─────────────────────────────────────── --}}
@if($restaurants->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 bg-surface-container rounded-2xl flex items-center justify-center mb-5">
            <span class="material-symbols-outlined text-4xl text-outline">storefront</span>
        </div>
        <h3 class="text-lg font-semibold text-on-surface mb-2">
            {{ $search || $cuisine ? 'Sin resultados para tu búsqueda' : 'Aún no hay restaurantes registrados' }}
        </h3>
        <p class="text-sm text-on-surface-variant max-w-sm">
            {{ $search || $cuisine
                ? 'Intenta con otros términos o explora todos los restaurantes disponibles.'
                : 'Pronto habrá restaurantes disponibles en tu área. ¡Vuelve más tarde!' }}
        </p>
        @if($search || $cuisine)
            <a href="{{ route('dashboard') }}" class="mt-4 px-5 py-2.5 bg-primary-container text-white rounded-xl text-sm font-semibold hover:opacity-90 transition-all">
                Ver todos
            </a>
        @endif
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($restaurants as $rest)
        <div class="group bg-white rounded-2xl border border-outline-variant hover:border-primary-container hover:shadow-lg hover:shadow-orange-500/10 transition-all duration-300 overflow-hidden cursor-pointer">

            {{-- Logo / Banner --}}
            <div class="h-36 bg-gradient-to-br from-surface-container to-surface-container-high flex items-center justify-center relative overflow-hidden">
                @if($rest->logo)
                    <img src="{{ $rest->logo }}" alt="{{ $rest->name }}" class="w-full h-full object-cover"/>
                @else
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-primary-container">restaurant</span>
                    </div>
                @endif
                {{-- Badge de estado --}}
                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold
                             {{ $rest->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $rest->status === 'active' ? '● Abierto' : '● Cerrado' }}
                </span>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-on-surface text-base leading-tight mb-1 group-hover:text-primary-container transition-colors truncate">
                    {{ $rest->name }}
                </h3>

                @if($rest->cuisine_type)
                <div class="flex items-center gap-1 text-xs text-on-surface-variant mb-1">
                    <span class="material-symbols-outlined text-sm">skillet</span>
                    {{ $rest->cuisine_type }}
                </div>
                @endif

                @if($rest->category)
                <div class="flex items-center gap-1 text-xs text-on-surface-variant mb-1">
                    <span class="material-symbols-outlined text-sm">category</span>
                    {{ $rest->category }}
                </div>
                @endif

                @if($rest->address)
                <div class="flex items-center gap-1 text-xs text-on-surface-variant truncate">
                    <span class="material-symbols-outlined text-sm">location_on</span>
                    <span class="truncate">{{ $rest->address }}</span>
                </div>
                @endif

                @if($rest->description)
                <p class="text-xs text-on-surface-variant mt-2 line-clamp-2">{{ $rest->description }}</p>
                @endif
            </div>

            {{-- Footer de la card --}}
            <div class="px-4 pb-4">
                <div class="flex gap-2">
                    <button class="flex-1 py-2 rounded-xl border border-outline-variant text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                        Escanear QR
                    </button>
                    <button class="flex-1 py-2 rounded-xl bg-primary-container/10 text-primary-container text-xs font-semibold hover:bg-primary-container hover:text-white transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">restaurant_menu</span>
                        Ver menú
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    @if($restaurants->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $restaurants->appends(request()->query())->links() }}
    </div>
    @endif
@endif

{{-- ── Banner de IA (próximamente) ─────────────────────────────── --}}
<div class="mt-12 bg-gradient-to-r from-tertiary to-blue-700 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">
    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-3xl text-white">psychology</span>
    </div>
    <div class="flex-1 text-center sm:text-left">
        <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-1">Próximamente</p>
        <h3 class="text-white font-heading font-bold text-xl mb-1">Sugerencias personalizadas con IA</h3>
        <p class="text-white/80 text-sm">
            Nuestra IA aprenderá tus gustos y te sugerirá el restaurante perfecto para cada ocasión.
        </p>
    </div>
    <span class="shrink-0 px-5 py-2.5 bg-white/20 border border-white/30 text-white rounded-xl text-sm font-semibold cursor-not-allowed opacity-70">
        Disponible pronto
    </span>
</div>

</x-app-layout>
