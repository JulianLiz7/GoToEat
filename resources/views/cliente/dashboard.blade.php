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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-nav { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px -8px rgba(0,0,0,.12); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased font-body-md">

{{-- ══ SIDEBAR ─────────────────────────────────────────────────── --}}
<aside class="hidden md:flex flex-col h-screen w-64 bg-surface-container-low border-r border-outline-variant/20 fixed left-0 top-0 z-50 shadow-md">
    <div class="px-6 py-6 border-b border-outline-variant/20">
        <a href="{{ route('explorar') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <div>
                <p class="font-black text-xl text-primary tracking-tight leading-none">GoToEat</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-widest mt-0.5">Gastronomía</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        @php
        $nav = [
            ['route' => 'explorar',  'icon' => 'explore',         'label' => 'Explorar'],
            ['route' => 'reservas',  'icon' => 'calendar_month',  'label' => 'Mis Reservas'],
            ['route' => 'perfil',    'icon' => 'person',          'label' => 'Mi Perfil'],
        ];
        @endphp
        @foreach($nav as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ $active
                     ? 'bg-primary-container text-white font-bold shadow-sm shadow-orange-200'
                     : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                  @if($active) style="font-variation-settings:'FILL' 1" @endif>{{ $item['icon'] }}</span>
            <span class="text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    <div class="px-3 py-4 border-t border-outline-variant/20 space-y-1">
        <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-surface-container-high transition-all">
            <img src="{{ auth()->user()->avatarUrl() }}"
                 class="w-8 h-8 rounded-full object-cover border border-outline-variant/30" alt="Avatar"/>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-error hover:bg-red-50 transition-all text-sm">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- ══ CONTENIDO PRINCIPAL ─────────────────────────────────────── --}}
<main class="md:ml-64 min-h-screen flex flex-col">

    {{-- TopBar ─────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-40 glass-nav bg-surface/80 border-b border-outline-variant/20 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between gap-4">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('explorar') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input name="search" type="text"
                           value="{{ $search }}"
                           placeholder="Buscar restaurantes, cocinas..."
                           class="w-full pl-10 pr-4 py-2 bg-surface-container-low rounded-full border border-outline-variant/30
                                  focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-sm"/>
                </div>
            </form>

            {{-- Acciones --}}
            <div class="flex items-center gap-3">
                {{-- Nueva Reserva --}}
                <button onclick="document.getElementById('modalReserva').classList.remove('hidden')"
                        class="hidden sm:flex items-center gap-2 px-4 py-2 bg-primary-container text-white rounded-xl
                               font-semibold text-sm hover:bg-primary active:scale-[0.97] transition-all shadow-sm shadow-orange-200">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Nueva Reserva
                </button>

                {{-- Notificaciones --}}
                <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all relative">
                    <span class="material-symbols-outlined">notifications</span>
                </button>

                {{-- Avatar --}}
                <a href="{{ route('perfil') }}" class="block">
                    <img src="{{ auth()->user()->avatarUrl() }}"
                         class="w-9 h-9 rounded-full object-cover border-2 border-primary-container/30 hover:border-primary-container transition-all"
                         alt="{{ auth()->user()->name }}"/>
                </a>
            </div>
        </div>
    </header>

    {{-- Flash --}}
    @if(session('success'))
    <div class="max-w-6xl mx-auto px-4 md:px-8 pt-4">
        <div class="p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-semibold text-secondary">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 md:px-8 py-8 w-full flex-1">

        {{-- Hero Bienvenida ──────────────────────────────────────── --}}
        <section class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                 style="background-image:radial-gradient(circle at 1px 1px,white 1px,transparent 0);background-size:28px 28px"></div>
            <div class="relative z-10">
                <p class="text-white/70 text-sm font-semibold mb-1">¡Hola, {{ auth()->user()->name }}!</p>
                <h1 class="font-h2 text-3xl font-black mb-2">¿Qué te apetece hoy?</h1>
                <p class="text-white/80 text-sm max-w-lg">
                    Descubre los mejores restaurantes cerca de ti y reserva tu mesa en segundos.
                </p>
                <div class="flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('reservas') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-xl font-semibold text-sm
                              hover:bg-white/30 transition-all border border-white/30">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        Mis Reservas
                    </a>
                    <button onclick="document.getElementById('modalReserva').classList.remove('hidden')"
                            class="flex items-center gap-2 px-4 py-2 bg-white text-primary rounded-xl font-bold text-sm
                                   hover:bg-white/90 active:scale-[0.97] transition-all shadow-md">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Nueva Reserva
                    </button>
                </div>
            </div>
        </section>

        {{-- Filtros ─────────────────────────────────────────────── --}}
        <div class="flex flex-col gap-3 mb-6">
            {{-- Tipo de cocina --}}
            @if($cuisines->isNotEmpty())
            <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                <a href="{{ route('explorar', array_filter(['search'=>$search,'price'=>$priceRange,'sort'=>$sortBy])) }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shrink-0
                          {{ !$cuisine ? 'bg-primary-container text-white shadow-sm' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                    Todos
                </a>
                @foreach($cuisines as $c)
                <a href="{{ route('explorar', array_filter(['cuisine'=>$c,'search'=>$search,'price'=>$priceRange,'sort'=>$sortBy])) }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shrink-0
                          {{ $cuisine===$c ? 'bg-primary-container text-white shadow-sm' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                    {{ $c }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Filtros adicionales: Precio, Calificación, Orden --}}
            <div class="flex flex-wrap gap-2 items-center">
                {{-- Precio --}}
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wide mr-1">Precio:</span>
                @foreach(['$'=>'Económico','$$'=>'Moderado','$$$'=>'Gourmet','$$$$'=>'Premium'] as $range => $label)
                <a href="{{ route('explorar', array_filter(['cuisine'=>$cuisine,'search'=>$search,'price'=>($priceRange===$range?'':$range),'sort'=>$sortBy])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all
                          {{ $priceRange===$range ? 'bg-primary-container text-white' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                    {{ $range }} · {{ $label }}
                </a>
                @endforeach

                <div class="w-px h-5 bg-outline-variant/30 mx-1"></div>

                {{-- Ordenar --}}
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wide mr-1">Ordenar:</span>
                @foreach(['latest'=>'Recientes','rating'=>'Mejor valorados','price_asc'=>'Precio ↑','price_desc'=>'Precio ↓'] as $val => $lbl)
                <a href="{{ route('explorar', array_filter(['cuisine'=>$cuisine,'search'=>$search,'price'=>$priceRange,'sort'=>$val])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all
                          {{ $sortBy===$val ? 'bg-secondary text-white' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                    {{ $lbl }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Restaurantes Destacados ──────────────────────────────── --}}
        @if($featuredRestaurants->isNotEmpty() && !$search && !$cuisine)
        <div class="mb-8">
            <h2 class="font-h3 text-xl font-bold text-on-surface mb-4">Destacados</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($featuredRestaurants as $rest)
                @php
                    $primary   = $rest->primary_color   ?? '#f97316';
                    $secondary = $rest->secondary_color ?? '#006c49';
                    $coverUrl  = $rest->cover_path ? Storage::url($rest->cover_path) : null;
                    $logoUrl   = $rest->logo_path  ? Storage::url($rest->logo_path)  : null;
                @endphp
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-outline-variant/20 card-hover">
                    <div class="h-40 relative overflow-hidden"
                         style="{{ $coverUrl ? "background:url('{$coverUrl}') center/cover" : "background-color:{$primary}30" }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl border-2 border-white flex items-center justify-center overflow-hidden shadow-sm"
                                 style="background:{{ $primary }}">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" class="w-full h-full object-contain p-1" alt="{{ $rest->name }}"/>
                                @else
                                    <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm leading-tight">{{ $rest->name }}</p>
                                <p class="text-white/70 text-[11px]">{{ $rest->cuisine_type }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            @if($rest->address)
                            <p class="text-xs text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                {{ Str::limit($rest->address, 35) }}
                            </p>
                            @endif
                            @if($rest->opening_hours)
                            <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                {{ $rest->opening_hours }}
                            </p>
                            @endif
                        </div>
                        <button onclick="abrirReservaConRestaurante({{ $rest->id }}, '{{ $rest->name }}')"
                                class="px-3 py-1.5 text-white rounded-lg font-bold text-xs transition-all hover:opacity-90 active:scale-95 shrink-0 ml-3"
                                style="background:{{ $primary }}">
                            Reservar
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Todos los Restaurantes ───────────────────────────────── --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-h3 text-xl font-bold text-on-surface">
                {{ $search || $cuisine ? 'Resultados' : 'Todos los Restaurantes' }}
                <span class="text-sm font-normal text-on-surface-variant ml-2">({{ $restaurants->total() }})</span>
            </h2>
        </div>

        @if($restaurants->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3">search_off</span>
            <p class="font-semibold text-on-surface">Sin resultados para "{{ $search }}"</p>
            <p class="text-sm text-on-surface-variant mt-1">Intenta con otro término o explora todas las categorías.</p>
            <a href="{{ route('explorar') }}" class="mt-4 text-primary font-semibold text-sm hover:underline">Ver todos</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-8">
            @foreach($restaurants as $rest)
            @php
                $primary   = $rest->primary_color   ?? '#f97316';
                $secondary = $rest->secondary_color ?? '#006c49';
                $coverUrl  = $rest->cover_path ? Storage::url($rest->cover_path) : null;
                $logoUrl   = $rest->logo_path  ? Storage::url($rest->logo_path)  : null;
            @endphp
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-outline-variant/20 card-hover flex flex-col">
                {{-- Cover --}}
                <div class="h-28 relative overflow-hidden"
                     style="{{ $coverUrl ? "background:url('{$coverUrl}') center/cover" : "background-color:{$primary}20" }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    <div class="absolute bottom-2.5 left-3">
                        <div class="w-10 h-10 rounded-xl border-2 border-white flex items-center justify-center overflow-hidden shadow-sm"
                             style="background:{{ $primary }}">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" class="w-full h-full object-contain p-1" alt="{{ $rest->name }}"/>
                            @else
                                <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                            @endif
                        </div>
                    </div>
                    <div class="absolute top-2 right-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white"
                              style="background:{{ $secondary }}">Abierto</span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-1 mb-0.5">
                        <h3 class="font-bold text-sm text-on-surface leading-tight truncate">{{ $rest->name }}</h3>
                        @if($rest->price_range ?? false)
                        <span class="text-[11px] font-bold text-gray-400 shrink-0 ml-1">{{ $rest->price_range }}</span>
                        @endif
                    </div>
                    <p class="text-xs font-semibold mb-1.5" style="color:{{ $primary }}">
                        {{ $rest->cuisine_type ?? $rest->category ?? 'Restaurante' }}
                    </p>
                    {{-- Estrellas --}}
                    @if(($rest->avg_rating ?? 0) > 0)
                    <div class="flex items-center gap-1 mb-1.5">
                        @for($s = 1; $s <= 5; $s++)
                        <span class="material-symbols-outlined text-[14px] {{ $s <= round($rest->avg_rating) ? 'text-amber-400' : 'text-gray-200' }}"
                              style="font-variation-settings:'FILL' {{ $s <= round($rest->avg_rating) ? 1 : 0 }}">star</span>
                        @endfor
                        <span class="text-[11px] font-bold text-on-surface ml-0.5">{{ number_format($rest->avg_rating, 1) }}</span>
                        <span class="text-[11px] text-gray-400">({{ $rest->reviews_count }})</span>
                    </div>
                    @else
                    <p class="text-[11px] text-gray-300 mb-1.5">Sin reseñas aún</p>
                    @endif
                    @if($rest->description)
                    <p class="text-xs text-on-surface-variant line-clamp-2 flex-1 mb-3">{{ $rest->description }}</p>
                    @else
                    <div class="flex-1"></div>
                    @endif
                    @if($rest->address)
                    <p class="text-[11px] text-on-surface-variant flex items-center gap-1 mb-2 truncate">
                        <span class="material-symbols-outlined text-[13px]">location_on</span>
                        {{ Str::limit($rest->address, 30) }}
                    </p>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="px-4 pb-4 flex gap-2">
                    <button onclick="verMenu({{ $rest->id }}, '{{ addslashes($rest->name) }}', '{{ $primary }}')"
                            class="flex-1 py-2 rounded-xl border font-semibold text-xs transition-all hover:opacity-80 active:scale-[0.97]"
                            style="border-color:{{ $primary }};color:{{ $primary }}">
                        Ver Menú
                    </button>
                    <button onclick="abrirReservaConRestaurante({{ $rest->id }}, '{{ addslashes($rest->name) }}')"
                            class="flex-1 py-2 rounded-xl text-white font-bold text-xs transition-all hover:opacity-90 active:scale-[0.97]"
                            style="background:{{ $primary }}">
                        Reservar
                    </button>
                    <button onclick="verResenas({{ $rest->id }}, '{{ addslashes($rest->name) }}', '{{ $primary }}')"
                            class="py-2 px-2.5 rounded-xl border border-amber-200 text-amber-500 font-bold text-xs transition-all hover:bg-amber-50 active:scale-[0.97]"
                            title="Ver reseñas">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">star</span>
                    </button>
                </div>

                <div class="h-1 w-0 group-hover:w-full transition-all duration-300"
                     style="background:linear-gradient(to right,{{ $primary }},{{ $secondary }})"></div>
            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($restaurants->hasPages())
        <div class="flex justify-center pb-8">
            {{ $restaurants->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- Footer --}}
    <footer class="border-t border-outline-variant/20 py-8 px-8 bg-surface-container-low mt-auto">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="font-bold text-primary">GoToEat</p>
            <p class="text-xs text-on-surface-variant">© {{ now()->year }} GoToEat — Todos los derechos reservados.</p>
        </div>
    </footer>
</main>

{{-- BottomNav Mobile ──────────────────────────────────────────── --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 glass-nav bg-surface/90 border-t border-outline-variant/20 flex justify-around items-center py-2 z-50">
    @foreach([['explorar','explore','Explorar'],['reservas','calendar_month','Reservas'],['perfil','person','Perfil']] as [$route,$icon,$label])
    @php $a = request()->routeIs($route); @endphp
    <a href="{{ route($route) }}" class="flex flex-col items-center gap-0.5 px-4 py-1
       {{ $a ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined text-[22px]" @if($a) style="font-variation-settings:'FILL' 1" @endif>{{ $icon }}</span>
        <span class="text-[10px] font-semibold">{{ $label }}</span>
    </a>
    @endforeach
</nav>

{{-- ══ MODAL: Nueva Reserva ════════════════════════════════════ --}}
<div id="modalReserva"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)cerrarModalReserva()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:90vh;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-primary-container">calendar_add_on</span>
                Nueva Reserva
            </h3>
            <button onclick="cerrarModalReserva()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('reservas.store') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">

                {{-- Restaurante --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Restaurante *</label>
                    <select id="reservaRestauranteId" name="restaurant_id" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                        <option value="">Seleccionar restaurante...</option>
                        @foreach($restaurants as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha y Hora --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha *</label>
                        <input name="reservation_date" type="date" required
                               min="{{ now()->toDateString() }}"
                               value="{{ old('reservation_date', now()->addDay()->toDateString()) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Hora *</label>
                        <select name="reservation_time" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                            @foreach(['12:00','12:30','13:00','13:30','14:00','14:30','15:00','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00'] as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Personas --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Número de personas *</label>
                    <div class="flex items-center gap-3">
                        <button type="button" id="btnMenos"
                                onclick="cambiarPersonas(-1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">−</button>
                        <input id="partySize" name="party_size" type="number" min="1" max="20" value="2" required
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-center font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none"/>
                        <button type="button" id="btnMas"
                                onclick="cambiarPersonas(1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">+</button>
                    </div>
                </div>

                {{-- Notas --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Notas especiales</label>
                    <textarea name="notes" rows="2"
                              placeholder="Alergias, ocasión especial, preferencias de mesa..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                </div>

                @if($errors->any())
                <div class="p-3 bg-error/10 border border-error/20 rounded-xl">
                    @foreach($errors->all() as $e)
                    <p class="text-xs text-error">{{ $e }}</p>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
                <button type="button" onclick="cerrarModalReserva()"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold hover:bg-primary active:scale-[0.97] transition-all shadow-sm">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Reseñas ══════════════════════════════════════════ --}}
<div id="modalResenas"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)document.getElementById('modalResenas').classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:85vh;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 id="resenaTitle" class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
                Reseñas
            </h3>
            <button onclick="document.getElementById('modalResenas').classList.add('hidden')" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>
        <div id="resenasContent" class="overflow-y-auto flex-1 px-6 py-4 text-sm"></div>

        {{-- Escribir reseña --}}
        @auth
        <div class="px-6 py-4 border-t border-gray-100 shrink-0">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Tu reseña</p>
            <form id="resenaAction" method="POST" action="" class="space-y-3">
                @csrf
                <input id="resenaRestaurantId" type="hidden" name="_restaurant_id" value=""/>
                {{-- Estrellas interactivas --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Calificación:</span>
                    <div class="flex gap-1" id="starPicker">
                        @for($s = 1; $s <= 5; $s++)
                        <button type="button" onclick="setRating({{ $s }})"
                                class="star-btn text-gray-200 hover:text-amber-400 transition-colors"
                                data-star="{{ $s }}">
                            <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 0">star</span>
                        </button>
                        @endfor
                    </div>
                    <input id="ratingInput" name="rating" type="hidden" value="" required/>
                </div>
                <input name="title" type="text" placeholder="Título (opcional)" maxlength="120"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300/30 focus:border-amber-400 outline-none transition-all"/>
                <textarea name="body" rows="2" placeholder="Comparte tu experiencia..." maxlength="1000"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300/30 focus:border-amber-400 outline-none transition-all resize-none"></textarea>
                <button type="submit"
                        class="w-full py-2.5 bg-amber-400 text-white rounded-xl font-bold text-sm hover:bg-amber-500 active:scale-[0.97] transition-all">
                    Publicar Reseña
                </button>
            </form>
        </div>
        @endauth
    </div>
</div>

{{-- ══ MODAL: Ver Menú ════════════════════════════════════════ --}}
<div id="modalMenu"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)cerrarModalMenu()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:85vh;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 id="menuTitle" class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-primary-container" style="font-variation-settings:'FILL' 1">restaurant_menu</span>
                Menú
            </h3>
            <button onclick="cerrarModalMenu()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>
        <div id="menuContent" class="overflow-y-auto flex-1 px-6 py-4">
            <div class="flex items-center justify-center py-12">
                <span class="material-symbols-outlined text-4xl text-gray-300 animate-spin">refresh</span>
            </div>
        </div>
    </div>
</div>

<script>
function setRating(val) {
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('.star-btn').forEach((btn, idx) => {
        const filled = idx < val;
        btn.querySelector('span').style.fontVariationSettings = filled ? "'FILL' 1" : "'FILL' 0";
        btn.querySelector('span').className = 'material-symbols-outlined text-[22px] ' + (filled ? 'text-amber-400' : 'text-gray-200');
    });
}
function cerrarModalReserva() {
    document.getElementById('modalReserva').classList.add('hidden');
}
function cerrarModalMenu() {
    document.getElementById('modalMenu').classList.add('hidden');
}
function abrirReservaConRestaurante(id, nombre) {
    const sel = document.getElementById('reservaRestauranteId');
    if (sel) sel.value = id;
    document.getElementById('modalReserva').classList.remove('hidden');
}
function cambiarPersonas(delta) {
    const input = document.getElementById('partySize');
    const val = Math.max(1, Math.min(20, (parseInt(input.value) || 2) + delta));
    input.value = val;
}
function verMenu(restaurantId, nombre, color) {
    document.getElementById('menuTitle').innerHTML =
        '<span class="material-symbols-outlined text-[20px] mr-2" style="font-variation-settings:\'FILL\' 1;color:' + color + '">restaurant_menu</span>' + nombre;
    document.getElementById('modalMenu').classList.remove('hidden');
    document.getElementById('menuContent').innerHTML =
        '<div class="flex items-center justify-center py-12"><span class="material-symbols-outlined text-4xl text-gray-300">cached</span></div>';

    fetch('/restaurante/' + restaurantId + '/menu', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const menu = data.menu;
        if (!menu || menu.length === 0) {
            document.getElementById('menuContent').innerHTML =
                '<div class="py-12 text-center text-gray-400"><span class="material-symbols-outlined text-4xl block mb-2">no_meals</span>Sin ítems en el menú aún.</div>';
            return;
        }
        const byCategory = {};
        menu.forEach(item => {
            const cat = item.category || 'General';
            if (!byCategory[cat]) byCategory[cat] = [];
            byCategory[cat].push(item);
        });
        let html = '';
        Object.entries(byCategory).forEach(([cat, items]) => {
            html += '<h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3 mt-4 first:mt-0">' + cat + '</h4>';
            items.forEach(item => {
                html += '<div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">';
                html += '<div class="flex-1 pr-3"><p class="font-semibold text-sm text-on-surface">' + item.name + '</p>';
                if (item.description) html += '<p class="text-xs text-gray-400 mt-0.5 line-clamp-2">' + item.description + '</p>';
                html += '</div>';
                html += '<p class="font-black text-sm shrink-0" style="color:' + color + '">$' + parseInt(item.price).toLocaleString('es-CO') + '</p>';
                html += '</div>';
            });
        });
        document.getElementById('menuContent').innerHTML = html;
    })
    .catch(() => {
        document.getElementById('menuContent').innerHTML =
            '<div class="py-12 text-center text-gray-400">Error al cargar el menú.</div>';
    });
}

function verResenas(restaurantId, nombre, color) {
    document.getElementById('resenaTitle').textContent = nombre + ' — Reseñas';
    document.getElementById('resenaRestaurantId').value = restaurantId;
    document.getElementById('resenaAction').action = '/restaurante/' + restaurantId + '/resena';
    document.getElementById('modalResenas').classList.remove('hidden');
    document.getElementById('resenasContent').innerHTML = '<div class="py-8 text-center text-gray-400">Cargando reseñas...</div>';
    fetch('/restaurante/' + restaurantId + '/resenas', {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
    .then(r=>r.json())
    .then(data=>{
        let html = '';
        if(data.restaurant.avg_rating > 0){
            html += '<div class="flex items-center gap-3 mb-4 p-4 bg-amber-50 rounded-xl border border-amber-100">';
            html += '<div class="text-3xl font-black text-amber-500">' + data.restaurant.avg_rating + '</div>';
            html += '<div>';
            let stars = '';
            for(let i=1;i<=5;i++){stars+='<span class="material-symbols-outlined text-[18px] ' + (i<=Math.round(data.restaurant.avg_rating)?'text-amber-400':'text-gray-200') + '" style="font-variation-settings:\'FILL\' '+(i<=Math.round(data.restaurant.avg_rating)?1:0)+'">star</span>';}
            html += '<div class="flex">' + stars + '</div>';
            html += '<p class="text-xs text-gray-500 mt-0.5">Basado en ' + data.total + ' reseñas</p>';
            html += '</div></div>';
        }
        if(data.reviews.length === 0){
            html += '<p class="text-center text-gray-400 text-sm py-6">Sé el primero en dejar una reseña.</p>';
        } else {
            data.reviews.forEach(r => {
                let s='';
                for(let i=1;i<=5;i++){s+='<span class="material-symbols-outlined text-[14px] '+(i<=r.rating?'text-amber-400':'text-gray-200')+'" style="font-variation-settings:\'FILL\' '+(i<=r.rating?1:0)+'">star</span>';}
                html += '<div class="py-3 border-b border-gray-50 last:border-0">';
                html += '<div class="flex items-start justify-between gap-2 mb-1">';
                html += '<div><p class="font-semibold text-sm">'+(r.user?.name||'Usuario')+'</p><div class="flex">' + s + '</div></div>';
                html += '<p class="text-xs text-gray-400">' + new Date(r.created_at).toLocaleDateString('es-CO') + '</p>';
                html += '</div>';
                if(r.title) html += '<p class="font-semibold text-sm text-on-surface mb-0.5">' + r.title + '</p>';
                if(r.body) html += '<p class="text-xs text-gray-500">' + r.body + '</p>';
                html += '</div>';
            });
        }
        document.getElementById('resenasContent').innerHTML = html;
    });
}

// Abrir modal si hay errores de validación en la reserva
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modalReserva').classList.remove('hidden');
});
@endif
</script>

</body>
</html>
