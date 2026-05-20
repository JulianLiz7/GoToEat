<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Explorar — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f0f2f7; }
        .sidebar { background: linear-gradient(180deg, #111827 0%, #1a2332 100%); }
        .sidebar-item { color: rgba(255,255,255,0.6); transition: all .2s; }
        .sidebar-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar-item.active { background: rgba(249,115,22,0.15); color: #f97316; font-weight: 600; }
        .card-lift { transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s; }
        .card-lift:hover { transform: translateY(-6px); box-shadow: 0 20px 40px -8px rgba(0,0,0,.18); }
        .hero-gradient { background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%); }
        .bento-featured { background-size: cover; background-position: center; }
        .bento-overlay { background: linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,.2) 60%, transparent 100%); }
        .tag-chip { background: rgba(249,115,22,.1); color: #ea580c; border: 1px solid rgba(249,115,22,.2); }
        .tag-chip.active { background: #f97316; color: white; border-color: #f97316; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-body antialiased" x-data="{ sideOpen: false, searchOpen: false }">

{{-- ══ SIDEBAR ────────────────────────────────────────────────── --}}
<aside class="sidebar hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 z-50 shadow-2xl">
    {{-- Logo --}}
    <div class="px-6 pt-8 pb-6">
        <a href="{{ route('explorar') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-orange-900/30">
                <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1,'wght' 600">restaurant</span>
            </div>
            <div>
                <p class="font-black text-xl text-white tracking-tight leading-none">GoTo<span class="text-primary">Eat</span></p>
                <p class="text-[10px] text-white/40 uppercase tracking-widest mt-0.5">Gastronomía</p>
            </div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 space-y-1">
        <a href="{{ route('explorar') }}" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">explore</span>
            <span class="text-sm font-medium">Explorar</span>
        </a>
        <a href="{{ route('reservas') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
            <span class="text-sm font-medium">Mis Reservas</span>
        </a>
        <a href="{{ route('perfil') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]">person</span>
            <span class="text-sm font-medium">Mi Perfil</span>
        </a>
    </nav>

    {{-- User Footer --}}
    <div class="px-4 py-6 border-t border-white/10">
        <div class="flex items-center gap-3 mb-4">
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-primary/30">
            @else
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-orange-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-white/40 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-white/50 hover:text-red-400 hover:bg-red-500/10 transition-all text-sm">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN ────────────────────────────────────────────────────── --}}
<main class="md:ml-64 min-h-screen">

    {{-- TOP BAR --}}
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-4 px-6 py-4">
            {{-- Mobile menu --}}
            <button @click="sideOpen = true" class="md:hidden p-2 rounded-xl hover:bg-gray-100 transition">
                <span class="material-symbols-outlined text-gray-600">menu</span>
            </button>

            {{-- Search --}}
            <form method="GET" action="{{ route('explorar') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Buscar restaurantes, cocinas…"
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-0 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary/30 transition-all">
                </div>
            </form>

            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('reservas') }}"
                   class="hidden sm:flex items-center gap-2 bg-primary hover:bg-orange-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md shadow-orange-200 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Nueva Reserva
                </a>
                <a href="{{ route('perfil') }}" class="w-9 h-9 rounded-full overflow-hidden border-2 border-primary/20 hover:border-primary transition-all">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="px-6 py-8 max-w-7xl mx-auto space-y-10">

        {{-- ── HERO WELCOME ─────────────────────────────────────────── --}}
        <section class="hero-gradient rounded-3xl p-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-4 right-16 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-yellow-300 rounded-full blur-3xl"></div>
            </div>
            <div class="relative">
                <p class="text-orange-200 text-sm font-medium mb-1">
                    {{ now()->hour < 12 ? 'Buenos días' : (now()->hour < 19 ? 'Buenas tardes' : 'Buenas noches') }} 👋
                </p>
                <h1 class="font-heading text-3xl font-bold text-white mb-2">
                    {{ explode(' ', auth()->user()->name)[0] }}
                </h1>
                <p class="text-orange-100 text-base max-w-sm">¿Dónde quieres comer hoy? Encuentra el restaurante perfecto para cada momento.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <div class="bg-white/20 backdrop-blur rounded-2xl px-5 py-3 text-center">
                        <p class="text-2xl font-bold">{{ $restaurants->total() }}</p>
                        <p class="text-xs text-orange-100">Restaurantes</p>
                    </div>
                    @php $myReservations = \App\Domains\Reservations\Models\Reservation::where('user_id', auth()->id())->whereMonth('reservation_date', now()->month)->count(); @endphp
                    <div class="bg-white/20 backdrop-blur rounded-2xl px-5 py-3 text-center">
                        <p class="text-2xl font-bold">{{ $myReservations }}</p>
                        <p class="text-xs text-orange-100">Reservas este mes</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur rounded-2xl px-5 py-3 text-center">
                        <p class="text-2xl font-bold">{{ $cuisines->count() }}</p>
                        <p class="text-xs text-orange-100">Tipos de cocina</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── FEATURED RESTAURANTS (BENTO GRID) ───────────────────── --}}
        @if($featuredRestaurants->isNotEmpty())
        <section>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-heading text-xl font-bold text-gray-900">Restaurantes Destacados</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Los más populares de la semana</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Big featured card --}}
                @php $big = $featuredRestaurants->first(); @endphp
                <a href="{{ route('restaurante.show', $big) }}" class="md:col-span-2 group relative rounded-3xl overflow-hidden h-64 md:h-80 bento-featured card-lift block"
                   style="background-image: url('{{ $big->cover_path ? Storage::url($big->cover_path) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800' }}')">
                    <div class="bento-overlay absolute inset-0"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <div class="flex items-center gap-2 mb-2">
                            @if($big->cuisine_type)
                            <span class="bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $big->cuisine_type }}</span>
                            @endif
                            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Destacado</span>
                        </div>
                        <h3 class="font-heading text-2xl font-bold text-white">{{ $big->name }}</h3>
                        <div class="flex items-center gap-3 mt-1 text-white/80 text-sm">
                            @if($big->avg_rating)
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-yellow-400 text-[16px]" style="font-variation-settings:'FILL' 1">star</span>
                                {{ number_format($big->avg_rating, 1) }}
                            </span>
                            @endif
                            @if($big->price_range)
                            <span>{{ $big->price_range }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                        <span class="material-symbols-outlined text-white text-[18px]">arrow_forward</span>
                    </div>
                </a>

                {{-- Two smaller featured cards --}}
                <div class="flex flex-col gap-4">
                    @foreach($featuredRestaurants->skip(1)->take(2) as $feat)
                    <a href="{{ route('restaurante.show', $feat) }}" class="group relative rounded-2xl overflow-hidden h-36 bento-featured card-lift block flex-1"
                       style="background-image: url('{{ $feat->cover_path ? Storage::url($feat->cover_path) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600' }}')">
                        <div class="bento-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="font-heading text-base font-bold text-white">{{ $feat->name }}</h3>
                            <p class="text-xs text-white/70">{{ $feat->cuisine_type ?? 'Restaurante' }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- ── FILTERS ────────────────────────────────────────────────── --}}
        <section>
            <form method="GET" action="{{ route('explorar') }}" id="filterForm">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="sort" value="{{ $sortBy }}" id="sortInput">

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Cuisine chips --}}
                    <button type="button" onclick="setCuisine('')"
                            class="tag-chip {{ !$cuisine ? 'active' : '' }} px-4 py-2 rounded-full text-sm font-medium transition-all">
                        Todos
                    </button>
                    @foreach($cuisines as $c)
                    <button type="button" onclick="setCuisine('{{ $c }}')"
                            class="tag-chip {{ $cuisine === $c ? 'active' : '' }} px-4 py-2 rounded-full text-sm font-medium transition-all">
                        {{ $c }}
                    </button>
                    @endforeach

                    <input type="hidden" name="cuisine" value="{{ $cuisine }}" id="cuisineInput">

                    <div class="ml-auto flex items-center gap-2">
                        {{-- Sort --}}
                        <select onchange="document.getElementById('sortInput').value=this.value; document.getElementById('filterForm').submit()"
                                class="bg-white border border-gray-200 rounded-xl text-sm px-3 py-2 text-gray-600 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            <option value="latest"     {{ $sortBy==='latest'     ? 'selected':'' }}>Más recientes</option>
                            <option value="rating"     {{ $sortBy==='rating'     ? 'selected':'' }}>Mejor valorados</option>
                            <option value="price_asc"  {{ $sortBy==='price_asc'  ? 'selected':'' }}>Precio ↑</option>
                            <option value="price_desc" {{ $sortBy==='price_desc' ? 'selected':'' }}>Precio ↓</option>
                        </select>
                        {{-- Price filter --}}
                        <select name="price" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl text-sm px-3 py-2 text-gray-600 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            <option value="">Todos los precios</option>
                            @foreach(['$'=>'Económico','$$'=>'Moderado','$$$'=>'Caro','$$$$'=>'Premium'] as $val=>$label)
                            <option value="{{ $val }}" {{ $priceRange===$val ? 'selected':'' }}>{{ $val }} — {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </section>

        {{-- ── RESTAURANT GRID ───────────────────────────────────────── --}}
        <section>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-heading text-xl font-bold text-gray-900">
                        @if($search) Resultados para "{{ $search }}"
                        @elseif($cuisine) Cocina {{ $cuisine }}
                        @else Todos los Restaurantes
                        @endif
                    </h2>
                    <p class="text-sm text-gray-500">{{ $restaurants->total() }} restaurante{{ $restaurants->total() !== 1 ? 's' : '' }} disponible{{ $restaurants->total() !== 1 ? 's' : '' }}</p>
                </div>
            </div>

            @if($restaurants->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-100">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-gray-400 text-[32px]">search_off</span>
                </div>
                <h3 class="font-heading text-lg font-semibold text-gray-700 mb-2">Sin resultados</h3>
                <p class="text-gray-500 text-sm">Prueba con otros filtros o términos de búsqueda.</p>
                <a href="{{ route('explorar') }}" class="mt-4 inline-block text-primary text-sm font-medium hover:underline">Ver todos</a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($restaurants as $restaurant)
                <div class="card-lift bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group">
                    {{-- Cover --}}
                    <div class="relative h-44 overflow-hidden bento-featured"
                         style="background-image: url('{{ $restaurant->cover_path ? Storage::url($restaurant->cover_path) : 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500' }}')">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        {{-- Price badge --}}
                        @if($restaurant->price_range)
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $restaurant->price_range }}
                        </span>
                        @endif

                        {{-- Rating --}}
                        @if($restaurant->avg_rating)
                        <div class="absolute bottom-3 left-3 flex items-center gap-1 bg-black/40 backdrop-blur text-white text-xs px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined text-yellow-400 text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                            {{ number_format($restaurant->avg_rating, 1) }}
                            @if($restaurant->reviews_count)
                            <span class="text-white/60">({{ $restaurant->reviews_count }})</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        {{-- Logo + name --}}
                        <div class="flex items-center gap-3 mb-2">
                            @if($restaurant->logo_path)
                            <img src="{{ Storage::url($restaurant->logo_path) }}" class="w-10 h-10 rounded-xl object-cover border border-gray-100 shadow-sm">
                            @else
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-sm"
                                 style="background: {{ $restaurant->primary_color ?? '#f97316' }}">
                                {{ strtoupper(substr($restaurant->name, 0, 2)) }}
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-heading text-base font-bold text-gray-900 truncate">{{ $restaurant->name }}</h3>
                                @if($restaurant->cuisine_type)
                                <p class="text-xs text-gray-400">{{ $restaurant->cuisine_type }}</p>
                                @endif
                            </div>
                        </div>

                        @if($restaurant->description)
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $restaurant->description }}</p>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('restaurante.show', $restaurant) }}"
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl text-center transition-all">
                                Ver Menú
                            </a>
                            <a href="{{ route('restaurante.show', $restaurant) }}#reservar"
                               class="flex-1 bg-primary hover:bg-orange-600 text-white text-xs font-semibold py-2.5 rounded-xl text-center transition-all shadow-sm shadow-orange-100">
                                Reservar
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($restaurants->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $restaurants->links('pagination::simple-tailwind') }}
            </div>
            @endif
            @endif
        </section>
    </div>
</main>

{{-- ══ MOBILE SIDEBAR OVERLAY ──────────────────────────────────── --}}
<div x-show="sideOpen" x-cloak class="fixed inset-0 z-50 md:hidden" @click.self="sideOpen = false">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <aside class="sidebar absolute left-0 top-0 h-full w-64 shadow-2xl z-10 flex flex-col">
        <div class="flex items-center justify-between px-6 pt-8 pb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                </div>
                <p class="font-black text-xl text-white tracking-tight">GoTo<span class="text-primary">Eat</span></p>
            </div>
            <button @click="sideOpen = false" class="text-white/50 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <nav class="flex-1 px-3 space-y-1">
            <a href="{{ route('explorar') }}" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">explore</span>
                <span class="text-sm font-medium">Explorar</span>
            </a>
            <a href="{{ route('reservas') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                <span class="text-sm font-medium">Mis Reservas</span>
            </a>
            <a href="{{ route('perfil') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <span class="material-symbols-outlined text-[20px]">person</span>
                <span class="text-sm font-medium">Mi Perfil</span>
            </a>
        </nav>
    </aside>
</div>

<script>
function setCuisine(val) {
    document.getElementById('cuisineInput').value = val;
    document.getElementById('filterForm').submit();
}
</script>
</body>
</html>
