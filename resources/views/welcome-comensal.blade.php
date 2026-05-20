<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bienvenido a GoToEat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
    .hero-bg {
        background: linear-gradient(to bottom, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.65) 100%),
                    url('https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    }
    @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
    .fade-up { animation: fadeUp 0.7s ease both; }
    .delay-1 { animation-delay: 0.15s; }
    .delay-2 { animation-delay: 0.30s; }
    .delay-3 { animation-delay: 0.45s; }
    .float-badge { animation: float 4s ease-in-out infinite; }
</style>
</head>
<body class="bg-white font-body text-on-surface antialiased">

{{-- ══ NAVBAR ══════════════════════════════════════════════════════ --}}
<nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-orange-100/60 h-16">
    <div class="max-w-7xl mx-auto flex justify-between items-center h-full px-6 md:px-10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center shadow-md shadow-orange-500/30">
                <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <span class="text-lg font-black text-slate-900" style="font-family:'Sora',sans-serif">
                GoTo<span class="text-orange-500">Eat</span>
            </span>
        </a>
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 active:scale-95 transition-all shadow-md shadow-orange-500/25">
            <span class="material-symbols-outlined text-[18px]">explore</span>
            Explorar Restaurantes
        </a>
    </div>
</nav>

{{-- ══ HERO ═════════════════════════════════════════════════════════ --}}
<section class="hero-bg relative min-h-screen flex items-center justify-center overflow-hidden pt-16">

    {{-- Badge superior --}}
    <div class="absolute top-24 left-1/2 -translate-x-1/2 fade-up z-20">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-500/90 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-widest rounded-full border border-white/20 shadow-lg">
            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">celebration</span>
            Ya eres parte de la comunidad
        </span>
    </div>

    {{-- Contenido central --}}
    <div class="relative z-10 max-w-3xl px-6 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6 drop-shadow-xl fade-up"
            style="font-family:'Sora',sans-serif">
            ¡Tu viaje culinario<br>comienza aquí,<br>
            <span class="text-orange-400">{{ explode(' ', auth()->user()->name)[0] }}!</span>
        </h1>

        <p class="text-lg md:text-xl text-white/85 mb-10 max-w-xl mx-auto leading-relaxed drop-shadow-md fade-up delay-1">
            Bienvenido a GoToEat. Ahora formas parte de una comunidad apasionada por la gastronomía
            que celebra cada bocado y cada momento compartido en la mesa.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 fade-up delay-2">
            <a href="{{ route('dashboard') }}"
               class="px-8 py-4 bg-orange-500 text-white font-bold text-base rounded-2xl shadow-2xl shadow-orange-500/40 hover:bg-orange-600 hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">explore</span>
                Explorar Restaurantes
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
            <a href="{{ route('reservas') }}"
               class="px-8 py-4 bg-white/15 backdrop-blur-sm text-white font-bold text-base rounded-2xl border border-white/30 hover:bg-white/25 active:scale-95 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                Mis Reservas
            </a>
        </div>
    </div>

    {{-- Degradado inferior --}}
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
</section>

{{-- ══ SECCIÓN: ¿QUÉ SIGUE? ═══════════════════════════════════════ --}}
<section class="py-20 px-6 md:px-10 bg-white">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-50 border border-orange-200 text-orange-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">auto_awesome</span>
                Tu aventura gastronómica
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-3" style="font-family:'Sora',sans-serif">
                ¿Qué puedes hacer ahora?
            </h2>
            <div class="w-16 h-1 bg-orange-500 mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'icon'  => 'explore',
                    'color' => 'orange',
                    'title' => 'Explora Restaurantes',
                    'desc'  => 'Descubre los mejores lugares de tu ciudad. Filtra por tipo de cocina, calificación y precio.',
                    'route' => 'dashboard',
                    'cta'   => 'Explorar ahora',
                ],
                [
                    'icon'  => 'calendar_month',
                    'color' => 'emerald',
                    'title' => 'Reserva al Instante',
                    'desc'  => 'Sin llamadas ni esperas. Reserva tu mesa en segundos y recibe confirmación inmediata.',
                    'route' => 'dashboard',
                    'cta'   => 'Ver disponibilidad',
                ],
                [
                    'icon'  => 'receipt_long',
                    'color' => 'blue',
                    'title' => 'Gestiona tus Reservas',
                    'desc'  => 'Revisa, cancela o modifica tus reservaciones desde tu panel personal en cualquier momento.',
                    'route' => 'reservas',
                    'cta'   => 'Mis reservas',
                ],
            ] as $card)
            @php $c = $card['color']; @endphp
            <div class="group bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-{{ $c }}-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-{{ $c }}-500 transition-colors duration-300">
                    <span class="material-symbols-outlined text-{{ $c }}-500 text-[28px] group-hover:text-white transition-colors duration-300" style="font-variation-settings:'FILL' 1">{{ $card['icon'] }}</span>
                </div>
                <h3 class="font-black text-slate-900 text-xl mb-3" style="font-family:'Sora',sans-serif">{{ $card['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ $card['desc'] }}</p>
                <a href="{{ route($card['route']) }}"
                   class="inline-flex items-center gap-2 text-{{ $c }}-500 font-bold text-sm hover:gap-3 transition-all">
                    {{ $card['cta'] }}
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA FINAL ═══════════════════════════════════════════════════ --}}
<section class="py-16 px-6 md:px-10 bg-slate-50">
    <div class="max-w-3xl mx-auto text-center">
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-12 shadow-2xl shadow-orange-500/20 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="material-symbols-outlined text-white text-[48px] mb-4 block" style="font-variation-settings:'FILL' 1">restaurant</span>
                <h3 class="text-2xl md:text-3xl font-black text-white mb-4" style="font-family:'Sora',sans-serif">
                    ¡Todo listo, {{ explode(' ', auth()->user()->name)[0] }}!
                </h3>
                <p class="text-white/80 mb-8 text-base leading-relaxed">
                    Tu cuenta está activa y lista para usar. Empieza a descubrir los mejores restaurantes cerca de ti.
                </p>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-white text-orange-500 font-black text-base rounded-2xl shadow-xl hover:bg-orange-50 hover:scale-105 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">explore</span>
                    Comenzar a Explorar
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══════════════════════════════════════════════════════ --}}
<footer class="bg-white border-t border-slate-100 py-10 px-6 md:px-10">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-orange-500 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <span class="font-black text-slate-900 text-sm" style="font-family:'Sora',sans-serif">GoTo<span class="text-orange-500">Eat</span></span>
            <span class="text-slate-400 text-xs ml-2">© {{ date('Y') }}. Buen provecho.</span>
        </div>
        <div class="flex gap-6 text-sm">
            <a href="#" class="text-slate-400 hover:text-orange-500 transition-colors">Privacidad</a>
            <a href="#" class="text-slate-400 hover:text-orange-500 transition-colors">Términos</a>
            <a href="#" class="text-slate-400 hover:text-orange-500 transition-colors">Soporte</a>
            <a href="#" class="text-slate-400 hover:text-orange-500 transition-colors">Contacto</a>
        </div>
    </div>
</footer>

</body>
</html>
