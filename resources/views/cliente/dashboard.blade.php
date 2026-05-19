<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Dashboard del Comensal</title>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .perspective-card {
            perspective: 1000px;
        }
        .perspective-content {
            transform: rotateY(-5deg) rotateX(2deg);
            transition: transform 0.3s ease-out;
        }
        .perspective-card:hover .perspective-content {
            transform: rotateY(0deg) rotateX(0deg);
        }
        .glass-nav {
            backdrop-filter: blur(12px);
        }
    </style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Sora:wght@400;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body-md text-on-background min-h-screen">
<!-- SideNavBar (Desktop Only) -->
<aside class="hidden md:flex flex-col h-full p-4 gap-2 bg-surface-container-low fixed left-0 top-0 w-64 z-[60] shadow-md">
<div class="mb-10 px-2 py-4">
<h1 class="font-h3 text-h3 font-bold text-primary">GoToEat</h1>
<p class="text-body-sm text-on-surface-variant">Gastronomía Premium</p>
</div>
<nav class="flex-1 flex flex-col gap-2">
<a class="flex items-center gap-4 px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined" data-icon="search">search</span>
<span class="font-body-sm">Explorar</span>
</a>
<a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="{{ route('reservas') }}">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span class="font-body-sm">Mis Reservas</span>
</a>
<a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="{{ route('perfil') }}">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-body-sm">Perfil</span>
</a>
</nav>
<div class="mt-auto flex flex-col gap-2 pt-6 border-t border-outline-variant/30">
<a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-body-sm">Ajustes</span>
</a>
<a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1 text-error" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-body-sm">Cerrar Sesión</span>
</a>
</div>
</aside>
<!-- Main Content Shell -->
<main class="md:ml-64 flex flex-col min-h-screen">
<!-- TopAppBar -->
<header class="docked full-width top-0 sticky z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4 max-w-container_max mx-auto">
<div class="flex items-center gap-6">
<div class="relative group">
<span class="absolute inset-y-0 left-3 flex items-center text-on-surface-variant group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="search">search</span>
</span>
<input class="bg-surface-container-low border-transparent focus:border-primary focus:ring-1 focus:ring-primary rounded-full pl-10 pr-4 py-2 w-64 md:w-80 transition-all font-body-sm" placeholder="Buscar restaurantes..." type="text"/>
</div>
<nav class="hidden lg:flex items-center gap-10">
<a class="font-body-md text-primary font-bold border-b-2 border-primary py-1" href="{{ route('dashboard') }}">Explorar</a>
<a class="font-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('reservas') }}">Reservas</a>

</nav>
</div>
<div class="flex items-center gap-6">
<button class="hidden sm:flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-body-sm shadow-md hover:bg-primary/90 active:scale-95 transition-all">
                    Nueva Reserva
                </button>
<div class="flex items-center gap-4">
<button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all active:scale-90">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
</button>
<a href="{{ route('perfil') }}" class="block p-1 rounded-full border-2 border-outline-variant hover:border-primary transition-all active:scale-90 overflow-hidden w-10 h-10">
<img alt="Avatar" data-alt="A professional close-up headshot of a person with a friendly expression, set against a soft blurred background of a modern office. The lighting is bright and natural, reflecting a clean corporate aesthetic with warm tones that complement the GoToEat primary orange brand colors." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxKjRUCAeEkPz3bH2RNhKdOJsn10_JCh8G8S8T5a3wqNInrjMuncueRnEajm7FDoga7C94MHCjAtFyW3uxK7d038walda1udnisQv8Pe0OeFnS_NYySm4ygFwKjY1agulFovPvFYLX_LoouaHTA--1UiYqq_FrYEtknFdXbJaHblEW-hbDmtx4h6F1T6mYNswUwmtbYIrwr--0PvrcPHaDJN7cLNqg4VlSlwga3NT6U8Wg1Il-msI9bOB0-jtLLxXjj7o2O4MvCEk"/>
</a>
</div>
</div>
</header>
<!-- Dashboard Canvas -->
<div class="px-6 py-16 max-w-container_max mx-auto w-full space-y-16">
<!-- Hero Section / Welcome -->
<section class="relative overflow-hidden bg-primary/10 rounded-xl p-10 border border-primary/20">
<div class="relative z-10 max-w-2xl">
<h2 class="font-h1 text-h2 text-primary mb-4">Bienvenido de nuevo, {{ auth()->user()->name }}</h2>
<p class="text-body-lg text-on-primary-fixed-variant mb-6">Explora las mesas disponibles hoy y disfruta de una experiencia gastronómica sin precedentes.</p>
<div class="flex gap-4">
<span class="px-4 py-2 bg-white/50 backdrop-blur-sm rounded-full text-label-caps border border-primary/20 flex items-center gap-2 text-primary">
<span class="material-symbols-outlined text-[16px]" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            Nivel: Élite
                        </span>
<span class="px-4 py-2 bg-white/50 backdrop-blur-sm rounded-full text-label-caps border border-primary/20 flex items-center gap-2 text-primary">
<span class="material-symbols-outlined text-[16px]" data-icon="loyalty">loyalty</span>
                            120 Puntos
                        </span>
</div>
</div>
<!-- 3D Perspective Graphic Placeholder -->
<div class="absolute right-0 top-1/2 -translate-y-1/2 hidden xl:block w-96 perspective-card">
<div class="perspective-content bg-white p-4 rounded-xl shadow-2xl border border-outline-variant/30">
<img class="rounded-lg shadow-inner" data-alt="A stunning high-angle perspective shot of a gourmet seafood dish being served in a minimalist, upscale restaurant. The table is made of light wood, and the surrounding decor is sleek and professional. Warm ambient lighting creates an inviting atmosphere, with soft orange accents reflecting the brand's identity." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfmvXMsorgxvtT7zSao3Lqq9NqRQz8Nsrdnqy9yXIab9vlX0uZIrNLYrja7BddPuRMBLnCUxYVybIBWAn2wfRmpOF6DHfJdKhJpme15Sha4f_7_CFIRIopOwAO_psHt8fGzUFpNwjNOGzORQXr-n-qmbooHaGCQ92E6cDnAngG-ERUku8OetIAmdI4vC5PZPfnNqYA9mOdmPpTrf_PLx78fSG97xAJhsQUNv1ohIenBOas1uXvTjrcfn41jLq5lZqsLZyNBXmp2Kc"/>
<div class="mt-4 p-2 flex justify-between items-center">
<span class="font-h3 text-body-md text-primary">Reserva Confirmada</span>
<span class="material-symbols-outlined text-primary" data-icon="check_circle">check_circle</span>
</div>
</div>
</div>
</section>
<!-- Recent Reservations -->
<section>
<div class="flex justify-between items-end mb-6">
<div>
<h3 class="font-h2 text-h3 text-on-surface">Reservas Recientes</h3>
<p class="text-body-sm text-on-surface-variant">Tus próximas experiencias gastronómicas</p>
</div>
<a class="text-primary font-bold text-body-sm hover:underline" href="#">Ver todas</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Reservation Card 1 -->
<div class="bg-white p-6 rounded-xl border border-outline-variant/20 shadow-sm hover:shadow-md transition-all group flex items-start gap-6">
<div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined" data-icon="restaurant">restaurant</span>
</div>
<div class="flex-1">
<div class="flex justify-between items-start">
<p class="font-h3 text-body-md text-on-surface font-bold">L'Atelier de Luxe</p>
<span class="bg-primary/10 text-primary text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">Confirmada</span>
</div>
<p class="text-body-sm text-on-surface-variant flex items-center gap-2 mt-1">
<span class="material-symbols-outlined text-[14px]" data-icon="event">event</span>
                                Hoy, 20:30
                            </p>
</div>
</div>
<!-- Reservation Card 2 -->
<div class="bg-white p-6 rounded-xl border border-outline-variant/20 shadow-sm hover:shadow-md transition-all group flex items-start gap-6">
<div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined" data-icon="local_pizza">local_pizza</span>
</div>
<div class="flex-1">
<div class="flex justify-between items-start">
<p class="font-h3 text-body-md text-on-surface font-bold">Pizza Craft Co.</p>
<span class="bg-on-surface-variant/10 text-on-surface-variant text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">Finalizada</span>
</div>
<p class="text-body-sm text-on-surface-variant flex items-center gap-2 mt-1">
<span class="material-symbols-outlined text-[14px]" data-icon="event">event</span>
                                14 Oct, 21:00
                            </p>
</div>
</div>
<!-- Empty/CTA State -->
<div class="bg-surface-container border border-dashed border-outline-variant/50 rounded-xl flex items-center justify-center p-6 group cursor-pointer hover:bg-surface-container-high transition-colors">
<div class="text-center">
<span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors mb-1" data-icon="add_circle">add_circle</span>
<p class="text-label-caps text-on-surface-variant">Nueva Reserva</p>
</div>
</div>
</div>
</section>
<!-- Available Restaurants (Bento Grid Style) -->
<section>
<div class="flex justify-between items-end mb-6">
<div>
<h3 class="font-h2 text-h3 text-on-surface">Restaurantes Disponibles</h3>
<p class="text-body-sm text-on-surface-variant">Descubre los mejores sabores cerca de ti</p>
</div>
<div class="flex gap-2">
<button class="p-2 border border-outline-variant/30 rounded-lg hover:bg-surface-container-high transition-all">
<span class="material-symbols-outlined" data-icon="filter_list">filter_list</span>
</button>
<button class="p-2 border border-outline-variant/30 rounded-lg hover:bg-surface-container-high transition-all">
<span class="material-symbols-outlined" data-icon="grid_view">grid_view</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto">
<!-- Feature Card 1 (Large) -->
<div class="md:col-span-8 group relative overflow-hidden rounded-xl bg-white border border-outline-variant/20 shadow-sm hover:shadow-lg transition-all">
<div class="h-64 md:h-80 w-full overflow-hidden">
<img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" data-alt="A professional top-down photograph of an elegantly set marble restaurant table. It features a diverse spread of Mediterranean tapas. The lighting is soft and golden. The palette emphasizes earthy tones with pops of deep orange, maintaining the GoToEat aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCSDyFnVi9vckkGU665o_Hn1w8TkGX-8B08tpjKnkgYG5zEuI1_iwj_2onmRNHTQ96URzICB3rYD8k5YJxJ5YcAr5JqSC6G1J9p1WmZUfI9ikZFgIQ989kSJs9OdkKN0bs5krTlmSRv78xtK17Gr-Ejk0ljX90pfr_EHIKEQSQZgohI5BJDT8Avw1nnAZO_tlFxKgGLgnK-cwD9pG_x4EBqPXTgDvvTQV1fvuwUbvRHxBKxkBRUPEYKv11wdd95gWSCtfAWhz8alKI"/>
</div>
<div class="absolute top-4 left-4 flex gap-2">
<span class="bg-primary/90 backdrop-blur-md text-on-primary px-4 py-1 rounded-full text-label-caps flex items-center gap-2">
<span class="material-symbols-outlined text-[14px]" data-icon="bolt">bolt</span> Reservar Ahora
                            </span>
</div>
<div class="p-6 flex justify-between items-center">
<div>
<h4 class="font-h3 text-h3 text-on-surface">Mar y Tierra Fusion</h4>
<div class="flex items-center gap-4 mt-1">
<span class="flex items-center gap-2 text-primary font-bold text-body-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span> 4.9
                                    </span>
<span class="text-on-surface-variant text-body-sm">•</span>
<span class="text-on-surface-variant text-body-sm">Cocina de Autor</span>
<span class="text-on-surface-variant text-body-sm">•</span>
<span class="text-on-surface-variant text-body-sm">$$$</span>
</div>
</div>
<button class="bg-primary text-on-primary px-10 py-3 rounded-lg font-bold text-body-md hover:shadow-lg hover:shadow-primary/20 active:scale-95 transition-all">
                                Ver Menú
                            </button>
</div>
<div class="absolute bottom-0 left-0 h-1 w-0 bg-primary group-hover:w-full transition-all duration-300"></div>
</div>
<!-- Side Card 1 -->
<div class="md:col-span-4 group relative overflow-hidden rounded-xl bg-white border border-outline-variant/20 shadow-sm hover:shadow-lg transition-all flex flex-col">
<div class="h-40 w-full overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A close-up of a beautifully plated fine-dining dessert in a modern, brightly lit restaurant. The focus is sharp on the intricate textures of the dish." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxJBXCnTOo4bitThHvXrD7BUBO8BoRgc5S5cDC5IdgXH3nfPxcJrwmQBecWJtOkOb1xiQyjZAHgLMweh3dEqitqsvJaD_YV1Jae9TYDD3urabNpI22Xt1FJ_5r-t5ToCl5RbUCsrY3jFYPQ-62a42-Xden-WZ5dAmprQkj5BB4Y5Ve6-Aw9yA63et1edEFnO7INCsBIKOtZ1wonAZKJSMVtjvdr5AT-7mxtlUUTjm112PGSG4NoulIWS1Y3Wb6gF74725D_ap7k9k"/>
</div>
<div class="p-6 flex-1">
<div class="flex justify-between items-start mb-2">
<h4 class="font-h3 text-body-lg text-on-surface font-bold">The Green Table</h4>
<span class="flex items-center gap-2 text-primary font-bold text-body-sm">
<span class="material-symbols-outlined text-[16px]" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span> 4.7
                                </span>
</div>
<p class="text-body-sm text-on-surface-variant line-clamp-2">Ingredientes orgánicos y sostenibilidad en cada plato.</p>
<div class="mt-6 pt-6 border-t border-outline-variant/20 flex justify-between items-center">
<span class="text-label-caps text-on-surface-variant">Sants, Barcelona</span>
<button class="text-primary font-bold text-body-sm hover:translate-x-1 transition-transform flex items-center gap-2">
                                    Ver Ofertas <span class="material-symbols-outlined text-[18px]" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
</div>
<!-- Side Card 2 -->
<div class="md:col-span-4 group relative overflow-hidden rounded-xl bg-white border border-outline-variant/20 shadow-sm hover:shadow-lg transition-all flex flex-col">
<div class="h-40 w-full overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="The interior of a trendy, urban industrial bistro at twilight. Warm glowing pendant lights hang over a polished concrete floor." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBRNM1zLeRghBXRCt1Y3mgoFMlQafVc9-xDj1M3K6PFRL8Ju9FYtCUy35IYer5SEOndhwsRBT8BY5ay1vTW6pwKYh0yuDYkoA_mM_pOU1HlHa2WUdSDDTS2qcazlyFbTnuB4swj4G1c5c12KGD33aWyPO8bV1EpSQgsuTBua301WEC71waOAt1Id2Pq8goFA_QNA88TwmfNr0jxr4cHk6xzDwuU2fe5BRe3u9uv6pE2WGG_gRwrDfmWWkgzjLf4wRdEI2Dqo0hZ4qE"/>
</div>
<div class="p-6 flex-1">
<div class="flex justify-between items-start mb-2">
<h4 class="font-h3 text-body-lg text-on-surface font-bold">Sushi Lab</h4>
<span class="flex items-center gap-2 text-primary font-bold text-body-sm">
<span class="material-symbols-outlined text-[16px]" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span> 4.8
                                </span>
</div>
<p class="text-body-sm text-on-surface-variant line-clamp-2">Innovación japonesa con técnicas moleculares.</p>
<div class="mt-6 pt-6 border-t border-outline-variant/20 flex justify-between items-center">
<span class="text-label-caps text-on-surface-variant">Salamanca, Madrid</span>
<button class="text-primary font-bold text-body-sm hover:translate-x-1 transition-transform flex items-center gap-2">
                                    Reservar <span class="material-symbols-outlined text-[18px]" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
</div>
<!-- Bottom Wide Card (Asymmetric) -->
<div class="md:col-span-8 group relative overflow-hidden rounded-xl bg-on-surface border border-outline-variant/10 shadow-lg transition-all flex items-center">
<div class="p-10 md:w-1/2 z-10">
<span class="text-primary text-label-caps mb-2 block">Destacado de la Semana</span>
<h4 class="font-h1 text-h2 text-surface mb-4">Bistro 22: Noche de Jazz &amp; Vinos</h4>
<p class="text-body-md text-surface-variant mb-6">Únete a nosotros para una experiencia sensorial única con maridaje exclusivo.</p>
<button class="bg-primary text-on-primary px-10 py-3 rounded-lg font-bold text-body-md hover:bg-primary/90 transition-all active:scale-95">
                                Ver Evento
                            </button>
</div>
<div class="absolute right-0 top-0 h-full w-full md:w-2/3 overflow-hidden pointer-events-none">
<div class="absolute inset-0 bg-gradient-to-r from-on-surface via-on-surface/40 to-transparent z-[1]"></div>
<img class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700" data-alt="A mood-focused shot of a live jazz band performing in the background. Dark mode aesthetic with vibrant orange light highlights." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDPBOSvALVQnb5UYNZsIm0sSD_QEPhcQim376Vn7S8OcXhr34JrifYarRvXr-Y5XN3LgsP_fkC07DDK5c0-0fS9426ZC5rVFK587yrC_z-EhAf_UiS3TNsyBqUoPqysJJhsY4hKOdPzOGzf5Na4eAAve6c97itzB9ZsYEX2KwL_-ysgaYTIAHzOEHY03ZMPIBHw6eGOgqJrpVkCWO2QuZKwSNxzHpc9N2GY8MNEuiFlqzFM7f7XAfYdKTUJJ20vN33Pbt8VeZXd8uc"/>
</div>
</div>
</div>
</section>
</div>
{{-- ══ Restaurantes Reales desde la BD ═══════════════════════ --}}
@if($restaurants->isNotEmpty())
<section class="mt-4">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="font-h2 text-h3 text-on-surface">Restaurantes en GoToEat</h3>
            <p class="text-body-sm text-on-surface-variant">Todos los locales registrados en la plataforma</p>
        </div>
        <span class="text-label-caps text-on-surface-variant">{{ $restaurants->total() }} disponibles</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($restaurants as $rest)
        @php
            $primary   = $rest->primary_color   ?? '#f97316';
            $secondary = $rest->secondary_color ?? '#006c49';
            $logoUrl   = $rest->logo_path ? Storage::url($rest->logo_path) : null;
            $coverUrl  = $rest->cover_path ? Storage::url($rest->cover_path) : null;
        @endphp
        <div class="group bg-white rounded-2xl border border-outline-variant/20 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
            {{-- Cover / Header del color de marca --}}
            <div class="h-28 relative overflow-hidden"
                 style="{{ $coverUrl ? "background-image:url('{$coverUrl}');background-size:cover;background-position:center" : "background-color:{$primary}20" }}">
                @if(!$coverUrl)
                <div class="absolute inset-0 opacity-30"
                     style="background: linear-gradient(135deg, {{ $primary }}40, {{ $secondary }}40)"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                {{-- Logo --}}
                <div class="absolute bottom-3 left-3">
                    <div class="w-12 h-12 rounded-xl border-2 border-white shadow-md overflow-hidden flex items-center justify-center"
                         style="background-color:{{ $primary }}">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo {{ $rest->name }}" class="w-full h-full object-contain p-1"/>
                        @else
                            <span class="material-symbols-outlined text-white text-[24px]" style="font-variation-settings:'FILL' 1">restaurant</span>
                        @endif
                    </div>
                </div>
                {{-- Badge estado --}}
                <div class="absolute top-2 right-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white"
                          style="background-color:{{ $secondary }}">
                        Abierto
                    </span>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-4 flex-1 flex flex-col">
                <h4 class="font-bold text-sm text-on-surface mb-0.5 truncate">{{ $rest->name }}</h4>
                <p class="text-xs font-semibold mb-1" style="color:{{ $primary }}">{{ $rest->cuisine_type ?? $rest->category ?? 'Restaurante' }}</p>

                @if($rest->description)
                <p class="text-xs text-gray-400 line-clamp-2 flex-1 mb-3">{{ $rest->description }}</p>
                @else
                <div class="flex-1"></div>
                @endif

                <div class="flex items-center gap-2 mt-auto">
                    @if($rest->address)
                    <p class="text-[11px] text-gray-400 flex items-center gap-1 flex-1 truncate">
                        <span class="material-symbols-outlined text-[13px]">location_on</span>
                        {{ Str::limit($rest->address, 30) }}
                    </p>
                    @endif
                    @if($rest->opening_hours)
                    <p class="text-[11px] text-gray-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                        {{ Str::limit($rest->opening_hours, 20) }}
                    </p>
                    @endif
                </div>
            </div>

            {{-- Footer acción --}}
            <div class="px-4 pb-4">
                <button class="w-full py-2.5 rounded-xl text-white font-bold text-sm transition-all active:scale-[0.97] hover:opacity-90"
                        style="background-color:{{ $primary }}">
                    Ver Menú
                </button>
            </div>

            {{-- Barra de color de marca (hover) --}}
            <div class="h-1 w-0 group-hover:w-full transition-all duration-300 mt-0"
                 style="background:linear-gradient(to right, {{ $primary }}, {{ $secondary }})"></div>
        </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    @if($restaurants->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $restaurants->links() }}
    </div>
    @endif
</section>
@endif

<!-- Footer (Simple) -->
<footer class="mt-auto border-t border-outline-variant/30 py-10 px-6 bg-surface-container-low">
<div class="max-w-container_max mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
<div class="flex flex-col items-center md:items-start">
<span class="font-h3 text-body-md font-bold text-primary">GoToEat</span>
<p class="text-body-sm text-on-surface-variant">© 2024 GoToEat Technologies Inc. Todos los derechos reservados.</p>
</div>
<div class="flex gap-10">
<a class="text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Privacidad</a>
<a class="text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Términos</a>
<a class="text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Contacto</a>
</div>
</div>
</footer>
</main>
<!-- BottomNavBar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 glass-nav bg-surface/90 border-t border-outline-variant/30 px-4 py-2 flex justify-around items-center z-[60]">
<a class="flex flex-col items-center gap-2 p-2 text-primary font-bold" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined" data-icon="search" data-weight="fill" style="font-variation-settings: 'FILL' 1;">search</span>
<span class="text-[10px] font-body-sm">Explorar</span>
</a>
<a class="flex flex-col items-center gap-2 p-2 text-on-surface-variant" href="{{ route('reservas') }}">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span class="text-[10px] font-body-sm">Reservas</span>
</a>
<a class="flex flex-col items-center gap-2 p-2 text-on-surface-variant" href="{{ route('perfil') }}">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="text-[10px] font-body-sm">Perfil</span>
</a>
</nav>
<!-- Floating Action Button (Mobile context) -->
<button class="md:hidden fixed bottom-20 right-gutter w-14 h-14 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center z-[55] active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="add">add</span>
</button>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>