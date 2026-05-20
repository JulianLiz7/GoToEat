<!DOCTYPE html>
<html class="scroll-smooth" lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat — Gestión y Experiencia Gastronómica</title>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
    .glass-nav { background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    .hero-gradient { background: linear-gradient(135deg, #fff7ed 0%, #fff 50%, #fff7ed 100%); }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(249,115,22,0.15); }
    .section-tag { background: linear-gradient(135deg, #fff7ed, #ffedd5); }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
    .float { animation: float 4s ease-in-out infinite; }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-body text-on-surface antialiased">

{{-- ══ NAVBAR ══════════════════════════════════════════════════════ --}}
<nav class="glass-nav fixed top-0 w-full z-50 border-b border-orange-100/60 h-18">
    <div class="max-w-7xl mx-auto flex justify-between items-center h-18 px-6 md:px-10 py-4">
        <a href="/" class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center shadow-md shadow-orange-500/30">
                <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <span class="text-xl font-black text-slate-900 tracking-tight">GoTo<span class="text-orange-500">Eat</span></span>
        </a>

        <div class="hidden md:flex items-center gap-1">
            <a href="#restaurantes" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all">Para Restaurantes</a>
            <a href="#comensales" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all">Para Comensales</a>
            <a href="#caracteristicas" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all">Características</a>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-orange-500 hover:bg-orange-50 rounded-lg transition-all">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span> Mi Panel
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-sm font-semibold text-slate-700 hover:text-orange-500 transition-colors">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-orange-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-orange-500/30 active:scale-95 transition-all">
                    Empezar gratis
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ══ HERO ═════════════════════════════════════════════════════════ --}}
<header class="hero-gradient pt-28 pb-20 px-6 md:px-10 overflow-hidden relative">
    {{-- Decoración de fondo --}}
    <div class="absolute top-20 right-10 w-72 h-72 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto">
        {{-- Badge superior --}}
        <div class="flex justify-center mb-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-50 border border-orange-200 text-orange-600 text-xs font-bold uppercase tracking-widest rounded-full">
                <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                La plataforma gastronómica #1 en Latinoamérica
            </span>
        </div>

        {{-- Título principal --}}
        <div class="text-center max-w-4xl mx-auto mb-8">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-6" style="font-family:'Sora',sans-serif">
                Gestión impecable.<br>
                <span class="text-orange-500">Experiencia perfecta.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                GoToEat conecta dueños de restaurantes con comensales en una sola plataforma.
                Controla tu operación, digitaliza tu menú y llena tus mesas cada noche.
            </p>
        </div>

        {{-- Botones CTA --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            <a href="{{ route('register') }}"
               class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-bold text-base rounded-2xl shadow-xl shadow-orange-500/30 hover:bg-orange-600 active:scale-95 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">storefront</span>
                Registrar mi Restaurante
            </a>
            <a href="{{ route('register') }}"
               class="w-full sm:w-auto px-8 py-4 bg-white text-slate-800 font-bold text-base rounded-2xl shadow-lg border border-slate-200 hover:border-orange-300 hover:text-orange-500 active:scale-95 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">explore</span>
                Explorar como Comensal
            </a>
        </div>

        {{-- Cards de elección --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Restaurantes --}}
            <a href="#restaurantes"
               class="group relative overflow-hidden rounded-3xl aspect-[16/9] flex flex-col justify-end p-8 shadow-xl hover:shadow-2xl hover:scale-[1.01] transition-all duration-500 cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFpmet8Glm9oY6a6lQhmDy5UM21O76r3aW4kgSoeUnU6WabiMie04QK91bVQ-8lGQSgbNqDzLq52kVgkaG25cYDK2h5jR477VfcaKIOok-Jv9YG-35m_9HnqFlZxiuKXhP7TuYT_eclxa_8St9NIwqKpwchkpbtFGgeg4wiXHsblnGmm0XN1TQPnK6fok7Tb3cOaZCeKuH6imdf-ufVQAgWw-2yNw71MeLOhLnF21ilCrpET0qg2-ibBa0mINydwb_YVvH7zx7mLY"
                     alt="Cocina de restaurante"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/90 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="material-symbols-outlined text-[14px]">analytics</span>
                        Para Propietarios
                    </div>
                    <h3 class="text-2xl font-black mb-2" style="font-family:'Sora',sans-serif">Para Restaurantes</h3>
                    <p class="text-slate-300 text-sm mb-4">Optimiza tu operación, controla tu inventario y lidera tu equipo con inteligencia artificial.</p>
                    <span class="inline-flex items-center gap-1.5 text-orange-400 font-bold text-sm group-hover:gap-3 transition-all">
                        Ver soluciones <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </div>
            </a>

            {{-- Comensales --}}
            <a href="#comensales"
               class="group relative overflow-hidden rounded-3xl aspect-[16/9] flex flex-col justify-end p-8 shadow-xl hover:shadow-2xl hover:scale-[1.01] transition-all duration-500 cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDKPCRydGe9ylWGgrDp9HTh2ML0CYJAJLSg1z807pMoS72inqUBVwwZhJYVEopq2Q4B-FI-c-hELymaDEFx6lOoLgiUxaEr_Nf-AOrb3uNFF2_Ngnm3x2VArW0hBb8lrCTyEq0eAdGwWLLbvn2yocmJ4qGoh16IM81-N42oGGa-qKez1G-GZffJsGqHyjj2YhOGAYyRiZUET5duL9X2CPWLNBUENoCm5JYi9tkhBujiLQe4ROFS7bHXmoQDaCxDAatnhbkeWjUt3ZY"
                     alt="Experiencia gastronómica"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-orange-950/90 via-orange-900/40 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="material-symbols-outlined text-[14px]">explore</span>
                        Para Amantes de la Comida
                    </div>
                    <h3 class="text-2xl font-black mb-2" style="font-family:'Sora',sans-serif">Para Comensales</h3>
                    <p class="text-orange-100 text-sm mb-4">Descubre menús digitales, reserva tu mesa favorita y vive experiencias culinarias únicas.</p>
                    <span class="inline-flex items-center gap-1.5 text-white font-bold text-sm group-hover:gap-3 transition-all">
                        Explorar restaurantes <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </div>
            </a>
        </div>
    </div>
</header>

{{-- ══ SECCIÓN RESTAURANTES ════════════════════════════════════════ --}}
<section id="restaurantes" class="py-24 px-6 md:px-10 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-16">

            {{-- Texto --}}
            <div class="lg:w-1/2 space-y-8">
                <div>
                    <span class="section-tag inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-orange-600 text-xs font-bold uppercase tracking-widest border border-orange-200 mb-4">
                        <span class="material-symbols-outlined text-[14px]">storefront</span>
                        Gestión de Restaurantes
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-4 leading-tight" style="font-family:'Sora',sans-serif">
                        Eficiencia total en tu cocina y salón
                    </h2>
                    <p class="text-slate-500 mt-4 leading-relaxed">
                        Centraliza toda tu operación en una sola plataforma. Olvídate del caos administrativo y enfócate en lo que realmente importa: la comida y tus clientes.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        ['inventory_2', 'Inventario Inteligente', 'Alertas automáticas de stock mínimo y mermas en tiempo real.'],
                        ['groups', 'Gestión de Equipo', 'Turnos, roles y comunicación interna centralizada.'],
                        ['table_restaurant', 'Mesas & Reservas', 'Plano del salón sincronizado con reservas de comensales.'],
                        ['bar_chart', 'Finanzas y Reportes', 'Control de ingresos, egresos y propinas del equipo.'],
                    ] as [$icon, $title, $desc])
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm card-hover">
                        <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-orange-500" style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $title }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-orange-500 text-white font-bold rounded-2xl shadow-xl shadow-orange-500/30 hover:bg-orange-600 hover:-translate-y-0.5 active:scale-95 transition-all">
                    <span class="material-symbols-outlined">rocket_launch</span>
                    Potenciar mi Restaurante
                </a>
            </div>

            {{-- Imagen / mockup --}}
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-orange-500/10 rounded-3xl blur-xl"></div>
                    <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center gap-3">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-white/30"></span>
                                <span class="w-3 h-3 rounded-full bg-white/30"></span>
                                <span class="w-3 h-3 rounded-full bg-white/30"></span>
                            </div>
                            <span class="text-white/80 text-xs font-medium">Panel Operativo — GoToEat</span>
                        </div>
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC16fIQBa-7rLK3U3nZBEkyttpOC4ImPA75FzRCmMACnqbLvpoMa8j47MGvttHBUkhCzeJ1mV_66LTfBuDtxOmVny2fB1LHXuk0F81eGCBWZrWJhjxyBAyLGKEXPxKsLWRhp_J2BXfNcwZmO52q1xpybCt3Ka2wcZa5WYUstcvh3ynxvjwffPRdCf__g2UW3fXTK-Ko--_KxmJHAa9eNY92DsOqTEfphOLeQz9mxLgSdvkixoKDIdOt0pWZqEiDkNOxZyAU3FDRIFM"
                             alt="Panel de control GoToEat"
                             class="w-full h-auto">
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl border border-slate-100 px-5 py-3 flex items-center gap-3 float">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">trending_up</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Ingresos este mes</p>
                            <p class="font-black text-slate-900 text-sm">+23% vs anterior</p>
                        </div>
                    </div>
                    {{-- Floating badge 2 --}}
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl border border-slate-100 px-5 py-3 flex items-center gap-3 float" style="animation-delay:2s">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">table_restaurant</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Mesas ocupadas</p>
                            <p class="font-black text-slate-900 text-sm">8 de 12 hoy</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ SECCIÓN COMENSALES ══════════════════════════════════════════ --}}
<section id="comensales" class="py-24 px-6 md:px-10 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col-reverse lg:flex-row items-center gap-16">

            {{-- Galería de fotos --}}
            <div class="lg:w-1/2">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAEQnGfjxf86Zuo9-25ZgojeCe3hbtDNKyzjO4vwNUvLEgTcG8ks-3905cJNlaukgt-cqSI4eIcKG8RWiv8ayJOFqgAz8Ugu7th42fb6k73daAwSa2JWkr6LgIpNsfwRtCk1f6L5AuwNuRW8U3F6vqcKMgLcDwZZCEgntd70Ps6zA2zc_4yVTXfY3UPuNdrHxFP6TgnVisr4EXgbOPKFngU6_DTIXlWaLvwnXcWvw7YTpFIXjGSSKwvtAcjSuqH-Hu0XKgA3h-ww-Y"
                             alt="Interior del restaurante"
                             class="rounded-2xl shadow-lg w-full object-cover h-48">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKidYt3QNvxV-D_o9qsk5vmrAB7s4Y51NAUcpw2kf6mTLcMumUyJtXABnU61U7a_xL4PxHvmDlNR9pUUIKt2Jj4Kbo1D2g2KUSU5k9xKwhQjm0hfnl5bo58zymC_MWxBYwwOcC6N93hsETHKFk7oH-OzKRDhhnvjFqQnmowGxBRsorXZkF8R6D84_CNwkt8ePfqlCukeBUH7Dg1Rau6B-RUo_scQ5JjrpsfdQsBa56IDPHk3hazjlTHP3Y6plTw1TkKaUcXBMsWdQ"
                             alt="Plato gourmet"
                             class="rounded-2xl shadow-lg w-full object-cover h-40">
                    </div>
                    <div class="space-y-4 pt-10">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFsrvXcSnSL1ScLloKgunwNKClICVTApR-DUOP9I_LqLSTr1STa_di6TTBIct3wiq-zKZ_1hJWhdJid05_DZy58564DdnT2MyCC50b__D_CBRudQLlWCydbECDELp5nzmYBiyuClVnMB_aj7HDwRGuyag30yEiW_uAwa8Nppb9vfFvQnQF90aUpZIGe-uKlE1kX7g-EXkQ4YtGf0FLZU7jYhQcbFz84ZkmNbM7NKncK8CNbXjtPS5G_HdIx1OG0vxdIHHHThdgejc"
                             alt="Mesa lista"
                             class="rounded-2xl shadow-lg w-full object-cover h-40">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCh_GoEcJEJXbXA1wgZ58CBkk_8IGo4JXa93NUj5IAstytUXNgjFRseEDf_hA3cGxYElNMgw0LAYfAoBvXjncG1myDQm_3qn5FIC1qK5Y5c8mrkoczX1wnEYJwGuqHvYPbYMTxvER0pMqEHRkWYnlBh9mkRZ1Q3Z_7S29Znu414x1S2fjOq6hknPknu7kijjBZFqxkNANeffQMh6wC4viBPg9y_QB7VoZk-dDj89E4q_CZwdSjHdqKbyCAnJ_5NBBY5_PdtckS4v48"
                             alt="Chef cocinando"
                             class="rounded-2xl shadow-lg w-full object-cover h-48">
                    </div>
                </div>
            </div>

            {{-- Texto --}}
            <div class="lg:w-1/2 space-y-8">
                <div>
                    <span class="section-tag inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-orange-600 text-xs font-bold uppercase tracking-widest border border-orange-200 mb-4">
                        <span class="material-symbols-outlined text-[14px]">explore</span>
                        Para Amantes de la Comida
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-4 leading-tight" style="font-family:'Sora',sans-serif">
                        Tu próxima experiencia gastronómica está a un clic
                    </h2>
                    <p class="text-slate-500 mt-4 leading-relaxed">
                        Explora los mejores restaurantes de tu ciudad, consulta menús digitales en tiempo real y reserva sin esperas ni llamadas. GoToEat es tu pasaporte a la mejor mesa.
                    </p>
                </div>

                <ul class="space-y-5">
                    @foreach([
                        ['menu_book', 'Menús digitales con fotos reales', 'Explora platos, ingredientes y precios actualizados al instante.'],
                        ['calendar_month', 'Reservas instantáneas', 'Confirma tu mesa en segundos. Sin llamadas, sin esperas.'],
                        ['star', 'Reseñas de la comunidad', 'Lee opiniones reales y descubre los lugares más valorados.'],
                        ['qr_code_2', 'Ticket QR para tus reservas', 'Llega con tu código QR y vive una experiencia sin fricciones.'],
                    ] as [$icon, $title, $desc])
                    <li class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-md shadow-orange-500/25">
                            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $title }}</p>
                            <p class="text-sm text-slate-500 mt-0.5">{{ $desc }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-white border-2 border-orange-500 text-orange-500 font-bold rounded-2xl hover:bg-orange-500 hover:text-white hover:-translate-y-0.5 active:scale-95 transition-all shadow-lg shadow-orange-500/10">
                    <span class="material-symbols-outlined">explore</span>
                    Empezar a Explorar
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ CARACTERÍSTICAS ═════════════════════════════════════════════ --}}
<section id="caracteristicas" class="py-24 px-6 md:px-10 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="section-tag inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-orange-600 text-xs font-bold uppercase tracking-widest border border-orange-200 mb-4">
                <span class="material-symbols-outlined text-[14px]">auto_awesome</span>
                Todo en uno
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-4 mb-4" style="font-family:'Sora',sans-serif">
                Todo lo que necesitas para destacar
            </h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">
                Módulos integrados que conectan la operación interna con la experiencia del comensal.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['table_restaurant', 'Mesas y Reservas', 'Plano del salón sincronizado en tiempo real con las reservas de comensales. Asigna meseros, controla estados y gestiona llegadas.', 'orange'],
                ['restaurant_menu', 'Menú Digital', 'Edita precios y platos que se actualizan al instante para todos tus comensales. Con fotos, categorías y disponibilidad.', 'orange'],
                ['smart_toy', 'Asistente con IA', 'Predicciones de demanda, alertas de inventario y análisis de ventas impulsados por inteligencia artificial.', 'orange'],
                ['inventory_2', 'Control de Inventario', 'Alertas de stock mínimo, registro de mermas y trazabilidad de ingredientes en tiempo real.', 'orange'],
                ['bar_chart', 'Finanzas Integradas', 'Panel de ingresos, egresos, propinas y cierre de caja. Exporta reportes en PDF y Excel con un clic.', 'orange'],
                ['badge', 'Gestión de Personal', 'Roles, turnos, notificaciones y panel individual para cada empleado. Todo conectado.', 'orange'],
            ] as [$icon, $title, $desc, $color])
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 card-hover group">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                    <span class="material-symbols-outlined text-orange-500 text-[26px] group-hover:text-white transition-colors duration-300" style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                </div>
                <h3 class="font-black text-slate-900 text-lg mb-3" style="font-family:'Sora',sans-serif">{{ $title }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA FINAL ═══════════════════════════════════════════════════ --}}
<section class="py-24 px-6 md:px-10">
    <div class="max-w-5xl mx-auto">
        <div class="relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl overflow-hidden p-12 md:p-20 text-center shadow-2xl shadow-orange-500/25">
            {{-- Decoración --}}
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-black/10 rounded-full blur-3xl"></div>
            <div class="absolute top-6 left-6 w-3 h-3 bg-white/40 rounded-full"></div>
            <div class="absolute top-10 right-10 w-2 h-2 bg-white/30 rounded-full"></div>
            <div class="absolute bottom-8 right-16 w-4 h-4 bg-white/20 rounded-full"></div>

            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/20 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-widest rounded-full mb-8">
                    <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">rocket_launch</span>
                    Únete a la revolución gastronómica
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight" style="font-family:'Sora',sans-serif">
                    El futuro del sabor<br>comienza aquí
                </h2>
                <p class="text-white/80 text-lg max-w-xl mx-auto mb-12 leading-relaxed">
                    Potencia tu restaurante o encuentra tu próxima cena inolvidable. Regístrate gratis hoy.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-8 py-4 bg-white text-orange-500 font-black text-base rounded-2xl shadow-xl hover:bg-orange-50 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">storefront</span>
                        Registrar mi Restaurante
                    </a>
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-black text-base rounded-2xl border border-white/30 hover:bg-white/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">explore</span>
                        Soy Comensal
                    </a>
                </div>

                <p class="text-white/50 text-xs mt-8">Sin tarjeta de crédito. Gratis para empezar.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══════════════════════════════════════════════════════ --}}
<footer class="bg-slate-950 text-white py-16 px-6 md:px-10">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-16">
            {{-- Marca --}}
            <div class="col-span-2 md:col-span-1 space-y-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                    </div>
                    <span class="text-xl font-black">GoTo<span class="text-orange-500">Eat</span></span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                    El ecosistema que conecta la pasión por cocinar con el placer de comer bien.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors" title="Instagram">
                        <span class="material-symbols-outlined text-[18px]">camera_alt</span>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors" title="Twitter/X">
                        <span class="material-symbols-outlined text-[18px]">alternate_email</span>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors" title="YouTube">
                        <span class="material-symbols-outlined text-[18px]">video_library</span>
                    </a>
                </div>
            </div>

            {{-- Restaurantes --}}
            <div>
                <h4 class="font-bold text-sm text-white mb-5 uppercase tracking-widest">Restaurantes</h4>
                <nav class="flex flex-col gap-3 text-slate-400 text-sm">
                    <a href="#restaurantes" class="hover:text-orange-400 transition-colors">Gestión de Inventario</a>
                    <a href="#restaurantes" class="hover:text-orange-400 transition-colors">Gestión de Personal</a>
                    <a href="#restaurantes" class="hover:text-orange-400 transition-colors">Sistema en la Nube</a>
                    <a href="#restaurantes" class="hover:text-orange-400 transition-colors">Asistente IA</a>
                </nav>
            </div>

            {{-- Comensales --}}
            <div>
                <h4 class="font-bold text-sm text-white mb-5 uppercase tracking-widest">Comensales</h4>
                <nav class="flex flex-col gap-3 text-slate-400 text-sm">
                    <a href="#comensales" class="hover:text-orange-400 transition-colors">Explorar Menús</a>
                    <a href="#comensales" class="hover:text-orange-400 transition-colors">Hacer una Reserva</a>
                    <a href="#comensales" class="hover:text-orange-400 transition-colors">Club GoToEat</a>
                    <a href="#comensales" class="hover:text-orange-400 transition-colors">Reseñas</a>
                </nav>
            </div>

            {{-- Legal --}}
            <div>
                <h4 class="font-bold text-sm text-white mb-5 uppercase tracking-widest">Legal</h4>
                <nav class="flex flex-col gap-3 text-slate-400 text-sm">
                    <a href="#" class="hover:text-orange-400 transition-colors">Privacidad</a>
                    <a href="#" class="hover:text-orange-400 transition-colors">Términos de Uso</a>
                    <a href="#" class="hover:text-orange-400 transition-colors">Cookies</a>
                    <a href="#" class="hover:text-orange-400 transition-colors">Contacto</a>
                </nav>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-500 text-sm">© {{ date('Y') }} GoToEat. Todos los derechos reservados. Elevando la gastronomía de Latinoamérica.</p>
            <div class="flex items-center gap-2 text-slate-500 text-xs">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Todos los sistemas operativos
            </div>
        </div>
    </div>
</footer>

</body>
</html>
