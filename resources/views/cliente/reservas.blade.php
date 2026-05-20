<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mis Reservas — GoToEat</title>
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
        .featured-bg { background-size: cover; background-position: center; }
        .featured-overlay { background: linear-gradient(to right, rgba(0,0,0,.85) 0%, rgba(0,0,0,.45) 60%, transparent 100%); }
        .status-pending   { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .status-confirmed { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .status-completed { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .status-cancelled { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .card-lift { transition: transform .2s, box-shadow .2s; }
        .card-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 24px -4px rgba(0,0,0,.12); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-body antialiased" x-data="{ sideOpen: false, newResModal: false }">

{{-- ══ SIDEBAR ────────────────────────────────────────────────── --}}
<aside class="sidebar hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 z-50 shadow-2xl">
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
    <nav class="flex-1 px-3 space-y-1">
        <a href="{{ route('explorar') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]">explore</span>
            <span class="text-sm font-medium">Explorar</span>
        </a>
        <a href="{{ route('reservas') }}" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">calendar_month</span>
            <span class="text-sm font-medium">Mis Reservas</span>
        </a>
        <a href="{{ route('perfil') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
            <span class="material-symbols-outlined text-[20px]">person</span>
            <span class="text-sm font-medium">Mi Perfil</span>
        </a>
    </nav>
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
                <p class="text-xs text-white/40 truncate">Comensal</p>
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
            <button @click="sideOpen = true" class="md:hidden p-2 rounded-xl hover:bg-gray-100 transition">
                <span class="material-symbols-outlined text-gray-600">menu</span>
            </button>
            <div>
                <h1 class="font-heading text-lg font-bold text-gray-900">Mis Reservas</h1>
                <p class="text-xs text-gray-500">Gestiona todas tus reservaciones</p>
            </div>
            <div class="ml-auto">
                <button @click="newResModal = true"
                        class="flex items-center gap-2 bg-primary hover:bg-orange-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md shadow-orange-200 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Nueva Reserva
                </button>
            </div>
        </div>
    </header>

    <div class="px-6 py-8 max-w-6xl mx-auto space-y-8">

        {{-- Alerts --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        {{-- ── STATS ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
                <p class="text-3xl font-bold font-heading text-gray-900">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Total reservas</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
                <p class="text-3xl font-bold font-heading text-primary">{{ $stats['month'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
                <p class="text-3xl font-bold font-heading text-green-600">{{ $stats['confirmed'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Confirmadas</p>
            </div>
        </div>

        {{-- ── PRÓXIMA RESERVA (FEATURED) ───────────────────────────── --}}
        @if($upcoming->isNotEmpty())
        @php $next = $upcoming->first(); @endphp
        <section>
            <h2 class="font-heading text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Próxima Reserva</h2>
            <a href="{{ route('reservas.show', $next) }}"
               class="block relative rounded-3xl overflow-hidden h-64 featured-bg card-lift group"
               style="background-image: url('{{ $next->restaurant->cover_path ? Storage::url($next->restaurant->cover_path) : 'https://images.unsplash.com/photo-1424847651672-bf20a4b0982b?w=1200' }}')">
                <div class="featured-overlay absolute inset-0"></div>
                <div class="absolute inset-0 p-7 flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <span class="status-{{ $next->status }} text-xs font-bold px-3 py-1.5 rounded-full">
                            {{ $next->statusLabel() }}
                        </span>
                        <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">qr_code_2</span>
                            Ver QR
                        </span>
                    </div>
                    <div>
                        @if($next->restaurant->logo_path)
                        <img src="{{ Storage::url($next->restaurant->logo_path) }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/30 mb-3">
                        @endif
                        <h3 class="font-heading text-2xl font-bold text-white">{{ $next->restaurant->name }}</h3>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-white/80 text-sm">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                {{ \Carbon\Carbon::parse($next->reservation_date)->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                {{ \Carbon\Carbon::parse($next->reservation_time)->format('h:i A') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">group</span>
                                {{ $next->party_size }} personas
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </section>

        {{-- Remaining upcoming --}}
        @if($upcoming->count() > 1)
        <section>
            <h2 class="font-heading text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Próximas</h2>
            <div class="space-y-3">
                @foreach($upcoming->skip(1) as $res)
                <a href="{{ route('reservas.show', $res) }}"
                   class="card-lift flex items-center gap-4 bg-white rounded-2xl p-4 border border-gray-100 shadow-sm group hover:border-primary/30">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 featured-bg"
                         style="background-image: url('{{ $res->restaurant->cover_path ? Storage::url($res->restaurant->cover_path) : 'https://images.unsplash.com/photo-1424847651672-bf20a4b0982b?w=200' }}')">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $res->restaurant->name }}</h3>
                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                            <span>{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M') }}</span>
                            <span>{{ \Carbon\Carbon::parse($res->reservation_time)->format('h:i A') }}</span>
                            <span>{{ $res->party_size }} personas</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="status-{{ $res->status }} text-xs font-bold px-3 py-1 rounded-full">{{ $res->statusLabel() }}</span>
                        <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">chevron_right</span>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        @else
        {{-- Empty state --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-primary text-[40px]">calendar_today</span>
            </div>
            <h3 class="font-heading text-xl font-bold text-gray-800 mb-2">No tienes reservas próximas</h3>
            <p class="text-gray-500 text-sm mb-6">¡Explora nuestros restaurantes y haz tu primera reserva!</p>
            <a href="{{ route('explorar') }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all shadow-md shadow-orange-200">
                <span class="material-symbols-outlined text-[18px]">explore</span>
                Explorar restaurantes
            </a>
        </div>
        @endif

        {{-- ── HISTORIAL ────────────────────────────────────────────── --}}
        @if($past->isNotEmpty())
        <section>
            <h2 class="font-heading text-base font-bold text-gray-500 uppercase tracking-wider mb-3">Historial Reciente</h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @foreach($past as $res)
                <a href="{{ route('reservas.show', $res) }}"
                   class="flex items-center gap-4 p-4 border-b border-gray-50 last:border-0 hover:bg-gray-50/70 transition-colors group">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 featured-bg opacity-70"
                         style="background-image: url('{{ $res->restaurant->cover_path ? Storage::url($res->restaurant->cover_path) : 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=200' }}')">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-700 truncate">{{ $res->restaurant->name }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }} · {{ $res->party_size }} personas</p>
                    </div>
                    <span class="status-{{ $res->status }} text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0">
                        {{ $res->statusLabel() }}
                    </span>
                    <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors text-[20px]">chevron_right</span>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── EXPLORE CTA ──────────────────────────────────────────── --}}
        <section class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl p-7 flex items-center gap-6 overflow-hidden relative">
            <div class="absolute right-0 top-0 bottom-0 w-48 opacity-10">
                <div class="absolute inset-0 bg-primary rounded-full blur-3xl"></div>
            </div>
            <div class="relative flex-1">
                <h3 class="font-heading text-xl font-bold text-white mb-1">¿Listo para más?</h3>
                <p class="text-gray-400 text-sm">Descubre nuevos restaurantes y sabores increíbles.</p>
            </div>
            <a href="{{ route('explorar') }}"
               class="relative flex-shrink-0 bg-primary hover:bg-orange-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-orange-900/20 active:scale-95">
                Explorar ahora
            </a>
        </section>
    </div>
</main>

{{-- ══ NUEVA RESERVA MODAL ──────────────────────────────────────── --}}
<div x-show="newResModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="newResModal = false"></div>
    <div class="relative bg-white w-full max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-heading text-lg font-bold text-gray-900">Nueva Reserva</h3>
                <p class="text-sm text-gray-500">Elige restaurante, fecha y hora</p>
            </div>
            <button @click="newResModal = false" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-gray-500 text-[20px]">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('reservas.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Restaurante</label>
                <select name="restaurant_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                    <option value="">Selecciona un restaurante…</option>
                    @foreach($restaurants as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fecha</label>
                    <input type="date" name="reservation_date" required min="{{ now()->format('Y-m-d') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Hora</label>
                    <input type="time" name="reservation_time" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Personas</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(range(1,10) as $n)
                    <label class="cursor-pointer">
                        <input type="radio" name="party_size" value="{{ $n }}" class="sr-only peer" {{ $n===2 ? 'checked' : '' }}>
                        <span class="w-10 h-10 rounded-xl border-2 border-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white transition-all block">{{ $n }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notas <span class="text-gray-400 font-normal">(opcional)</span></label>
                <textarea name="notes" rows="2" placeholder="Alergias, preferencias, ocasión especial…"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50 resize-none transition-all"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-orange-200 active:scale-95">
                Solicitar Reserva
            </button>
        </form>
    </div>
</div>

{{-- Mobile sidebar --}}
<div x-show="sideOpen" x-cloak class="fixed inset-0 z-50 md:hidden" @click.self="sideOpen = false">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <aside class="sidebar absolute left-0 top-0 h-full w-64 shadow-2xl z-10 flex flex-col">
        <div class="flex items-center justify-between px-6 pt-8 pb-6">
            <p class="font-black text-xl text-white tracking-tight">GoTo<span class="text-primary">Eat</span></p>
            <button @click="sideOpen = false" class="text-white/50 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <nav class="flex-1 px-3 space-y-1">
            <a href="{{ route('explorar') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]">explore</span><span class="text-sm font-medium">Explorar</span></a>
            <a href="{{ route('reservas') }}" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">calendar_month</span><span class="text-sm font-medium">Mis Reservas</span></a>
            <a href="{{ route('perfil') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]">person</span><span class="text-sm font-medium">Mi Perfil</span></a>
        </nav>
    </aside>
</div>
</body>
</html>
