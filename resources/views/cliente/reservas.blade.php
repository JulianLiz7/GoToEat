<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
        }
        .perspective-1000 {
            perspective: 1000px;
        }
        .rotate-y-12 {
            transform: rotateY(-12deg) rotateX(5deg);
        }
    </style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body-md text-on-background overflow-x-hidden">
<!-- TopNavBar -->
<header class="bg-surface/80 backdrop-blur-md font-h3 text-h3 tracking-tight font-body-md text-body-md docked full-width top-0 sticky border-b border-outline-variant/30 shadow-sm z-50">
<div class="flex justify-between items-center w-full px-gutter py-4 max-w-container_max mx-auto">
<div class="flex items-center gap-md">
<span class="font-h3 text-h3 font-bold text-primary">GoToEat</span>
<nav class="hidden md:flex gap-md ml-lg">
<a class="text-on-surface-variant hover:text-primary transition-colors" href="{{ route('dashboard') }}">Explorar</a>
<a class="text-primary font-bold border-b-2 border-primary" href="#">GoToEat</a>

</nav>
</div>
<div class="flex items-center gap-sm">
<button class="hidden md:flex bg-primary text-on-primary px-sm py-base rounded-lg font-bold hover:bg-on-primary-fixed-variant transition-all duration-200 active:scale-95">Nueva Reserva</button>
<div class="flex gap-xs">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-base rounded-full hover:bg-surface-container-low" data-icon="notifications">notifications</span>
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-base rounded-full hover:bg-surface-container-low" data-icon="account_circle">account_circle</span>
</div>
</div>
</div>
</header>
<div class="flex min-h-screen">
<!-- SideNavBar -->
<aside class="hidden lg:flex flex-col h-screen w-64 fixed left-0 top-0 bg-surface-container-low shadow-md p-sm gap-xs pt-24">
<div class="mb-lg px-sm">
<h2 class="font-h3 text-h3 text-primary font-bold">GoToEat</h2>
<p class="text-body-sm text-on-surface-variant">Gastronomía Premium</p>
</div>
<nav class="flex flex-col gap-xs flex-1">
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
<span class="material-symbols-outlined" data-icon="search">search</span>
                    Explorar
                </a>
<a class="flex items-center gap-sm p-sm rounded-lg font-bold transition-transform hover:translate-x-1 bg-primary/10 text-primary" href="#">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                    Mis Reservas
                </a>
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
<span class="material-symbols-outlined" data-icon="person">person</span>
                    Perfil
                </a>
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1 mt-auto" href="#">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
                    Cerrar Sesión
                </a>
</nav>
<div class="border-t border-outline-variant/30 pt-sm mt-sm">
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high rounded-lg" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
                    Ajustes
                </a>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 lg:ml-64 p-gutter max-w-container_max mx-auto w-full">
<div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-md">
<div>
<h1 class="font-h1 text-h1 text-on-surface mb-xs">Mis Reservas</h1>
<p class="text-body-lg text-on-surface-variant max-w-2xl">Gestiona tus próximas experiencias gastronómicas y revive tus momentos favoritos.</p>
</div>
<div class="flex bg-surface-container p-xs rounded-xl">
<button class="px-md py-sm bg-surface-container-lowest shadow-sm rounded-lg text-primary font-bold">Próximas</button>
<button class="px-md py-sm text-on-surface-variant hover:text-primary transition-colors">Historial</button>
</div>
</div>
<!-- Dashboard Bento Grid Style -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-md">
<!-- Active/Featured Reservation -->
<section class="md:col-span-8 flex flex-col gap-md">
<h3 class="font-label-caps text-label-caps text-primary tracking-widest uppercase mb-base">Reserva Destacada</h3>
<div class="glass-card border border-outline-variant/30 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
<div class="relative h-64 overflow-hidden">
<img alt="Restaurante Moderno" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" data-alt="A high-end modern restaurant interior with warm ambient lighting reflecting off polished wooden tables and orange velvet chairs. Large floor-to-ceiling windows overlook a sunset cityscape, creating a sophisticated and inviting atmosphere. The scene is shot with a shallow depth of field, highlighting a perfectly set table in the foreground with pristine white porcelain and gleaming silverware." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCbwMnDnknjfXgzbtJrv9R3f2eGX1yUyoMjMWGld3Cff7O6fpO-8n8JLgauvE3TLAtdkOX-EJNKtDEyR14nt3Pph3CPLgtjHknK62X5UuTLmV6XTqzLqwDi8j-H7cr0x3HfFySBdlZosyVNz3LfD0rvXYiiIm6VNkUUBtNeTiuQqByhRoClaY8OzHTF4ougVbMSorUA0Si91k67gyqQJgrsJmP21C5_aZcbex-KSNTNMj451Sjg7d_0qLFw2HTNNX41Zhy5L24M5T8"/>
<div class="absolute top-sm right-sm text-white px-md py-xs rounded-full font-bold shadow-md bg-primary">Confirmada</div>
</div>
<div class="p-md flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
<div>
<h4 class="font-h2 text-h2 text-on-surface">L'Oasis Gastronómico</h4>
<div class="flex flex-wrap gap-md mt-sm text-on-surface-variant">
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" data-icon="calendar_month">calendar_month</span> 24 de Octubre, 2024</span>
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" data-icon="schedule">schedule</span> 21:00</span>
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-primary" data-icon="groups">groups</span> 4 Personas</span>
</div>
</div>
<div class="flex gap-sm w-full md:w-auto">
<button class="flex-1 md:flex-none border-2 border-primary text-primary font-bold px-md py-sm rounded-lg hover:bg-primary/10 transition-all active:scale-95">Cancelar</button>
<button class="flex-1 md:flex-none bg-primary text-on-primary font-bold px-md py-sm rounded-lg shadow-md hover:shadow-lg transition-all active:scale-95">Ver Detalles</button>
</div>
</div>
</div>
<!-- Upcoming List -->
<h3 class="font-label-caps text-label-caps text-primary tracking-widest uppercase mt-md mb-base">Otras Reservas Próximas</h3>
<div class="space-y-sm">
<!-- Reservation Row 1 -->
<div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant/20 flex flex-col md:flex-row md:items-center justify-between gap-md hover:border-primary/40 transition-all group">
<div class="flex items-center gap-md">
<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
<img alt="Bistró" class="w-full h-full object-cover" data-alt="A close-up of a vibrant bistro plate featuring gourmet appetizers with colorful edible flowers and artistic sauce drizzles. The lighting is bright and natural, emphasizing the textures and vivid oranges and greens of the food. The background shows a soft-focus wooden bistro table, enhancing the artisanal and fresh quality of the cuisine." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCVZXmOD-Keei_VjLGY4YRbMhE9v_-DH6RFANpqZS6ItSTDnG3nK1lopzb-gEGYf23v40I7KfU9ORzb4QOVpJtgBBWob6TE5izxVlpncVq3CYpVwoIreH63eW-BEkcjp_P4T0cpli6bb_c0IGIZjWHBxRwekhy_Wsids1UL_v08MtdvGd8GrDre7KP-lA_KxEfwURdW_u5X-3LwV5WhMzIXW2DjQn6cDiQlFvxZzRL4aB8GlcbToak0KdAli5akSkLw4H7VV4C4Cx4"/>
</div>
<div>
<h5 class="font-h3 text-h3 text-on-surface group-hover:text-primary transition-colors">La Parrilla de Oro</h5>
<p class="text-body-sm text-on-surface-variant">Sábado 28 Oct • 14:30 • 2 pax</p>
</div>
</div>
<div class="flex items-center justify-between md:justify-end gap-lg">
<span class="bg-emerald-100 text-secondary px-sm py-base rounded-lg text-body-sm font-bold">Confirmada</span>
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary" data-icon="chevron_right">chevron_right</span>
</div>
</div>
<!-- Reservation Row 2 -->
<div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant/20 flex flex-col md:flex-row md:items-center justify-between gap-md hover:border-primary/40 transition-all group">
<div class="flex items-center gap-md">
<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
<img alt="Sushi" class="w-full h-full object-cover" data-alt="Exquisite sushi platter arranged on a black slate board, showcasing vibrant orange salmon and deep red tuna rolls. Soft, warm spotlighting creates elegant reflections on the surface, emphasizing the freshness and premium quality. The setting is a minimal, dark-themed Japanese restaurant with clean lines and a professional, high-end culinary atmosphere." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDeM0xcKo67Zg6qN848r4y5CbH-tgaeJKuQ_XEpHsAuoIlrHAHYlmn-14PBfWbu-KxZHgPQt0QFRBNEFFvOhL3eS0SETUBMccNGkceoKr1YZHJ15E4rBVEC8zmO42GGWjpFpbX-XqszrbcV7MQi-VOF4BDXGmEg3txFNgYkpub7HVQBolqwdmiK5CNW2l9GHoYqVRP9yyoPcSYId5J-X7amxK2YAawmB1Nxw92Nb8cHSGeBqKTsO6QVu1QCNuNvvLQ7Ev-4EJ8H9x0"/>
</div>
<div>
<h5 class="font-h3 text-h3 text-on-surface group-hover:text-primary transition-colors">Nikkei Zen</h5>
<p class="text-body-sm text-on-surface-variant">Lunes 30 Oct • 20:00 • 6 pax</p>
</div>
</div>
<div class="flex items-center justify-between md:justify-end gap-lg">
<span class="bg-surface-container-high text-on-surface-variant px-sm py-base rounded-lg text-body-sm font-bold">Pendiente</span>
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary" data-icon="chevron_right">chevron_right</span>
</div>
</div>
</div>
</section>
<!-- Sidebar Content / History Summary -->
<aside class="md:col-span-4 flex flex-col gap-md">
<h3 class="font-label-caps text-label-caps text-primary tracking-widest uppercase mb-base">Estadísticas</h3>
<div class="bg-primary text-on-primary p-md rounded-xl shadow-lg relative overflow-hidden perspective-1000">
<div class="relative z-10">
<p class="text-body-sm opacity-80 mb-base">Reservas este mes</p>
<h4 class="text-h1 font-h1">12</h4>
</div>
<div class="absolute -right-8 -bottom-8 opacity-10 rotate-y-12">
<span class="material-symbols-outlined text-[120px]" data-icon="restaurant">restaurant</span>
</div>
</div>
<h3 class="font-label-caps text-label-caps text-primary tracking-widest uppercase mt-md mb-base">Historial Reciente</h3>
<div class="space-y-sm">
<!-- History Item 1 -->
<div class="bg-surface-container p-sm rounded-lg opacity-70 hover:opacity-100 transition-opacity">
<h6 class="font-bold text-on-surface">Terraza Mediterránea</h6>
<p class="text-body-sm text-on-surface-variant">15 Oct • 4 pax</p>
<div class="flex justify-between items-center mt-xs">
<span class="text-[10px] uppercase font-bold tracking-tighter text-on-surface-variant">Finalizada</span>
<span class="flex text-primary">
<span class="material-symbols-outlined text-xs" data-icon="star" data-weight="fill">star</span>
<span class="material-symbols-outlined text-xs" data-icon="star" data-weight="fill">star</span>
<span class="material-symbols-outlined text-xs" data-icon="star" data-weight="fill">star</span>
<span class="material-symbols-outlined text-xs" data-icon="star" data-weight="fill">star</span>
<span class="material-symbols-outlined text-xs" data-icon="star">star</span>
</span>
</div>
</div>
<!-- History Item 2 -->
<div class="bg-surface-container p-sm rounded-lg opacity-70 hover:opacity-100 transition-opacity">
<h6 class="font-bold text-on-surface">Steak House Prime</h6>
<p class="text-body-sm text-on-surface-variant">08 Oct • 2 pax</p>
<div class="flex justify-between items-center mt-xs">
<span class="text-[10px] uppercase font-bold tracking-tighter text-error">Cancelada</span>
<span class="text-[10px] text-on-surface-variant">No asistió</span>
</div>
</div>
<button class="w-full py-sm text-primary font-bold text-body-sm hover:underline">Ver historial completo</button>
</div>
<!-- Promotional 3D Card -->
<div class="mt-lg p-md rounded-xl bg-inverse-surface text-inverse-on-surface relative overflow-hidden group shadow-2xl">
<h5 class="font-h3 text-h3 mb-xs">¿Buscas algo nuevo?</h5>
<p class="text-body-sm opacity-80 mb-md">Descubre restaurantes premiados cerca de ti con menús exclusivos.</p>
<button class="bg-white text-primary px-md py-xs rounded-full text-body-sm font-bold group-hover:scale-105 transition-transform">Explorar ahora</button>
<div class="absolute -right-4 -top-4 opacity-20 group-hover:rotate-12 transition-transform">
<span class="material-symbols-outlined text-[80px]" data-icon="explore">explore</span>
</div>
</div>
</aside>
</div>
</main>
</div>
<!-- Floating Action Button -->
<div class="fixed bottom-gutter right-gutter z-40 lg:hidden">
<button class="w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-transform">
<span class="material-symbols-outlined text-3xl" data-icon="add">add</span>
</button>
</div>
<!-- Bottom Navigation for Mobile -->
<nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface/90 backdrop-blur-lg border-t border-outline-variant/20 flex justify-around items-center py-sm z-50">
<button class="flex flex-col items-center gap-xs text-on-surface-variant">
<span class="material-symbols-outlined" data-icon="search">search</span>
<span class="text-[10px] font-bold">Explorar</span>
</button>
<button class="flex flex-col items-center gap-xs text-primary">
<span class="material-symbols-outlined" data-icon="calendar_today" data-weight="fill">calendar_today</span>
<span class="text-[10px] font-bold">Reservas</span>
</button>
<button class="flex flex-col items-center gap-xs text-on-surface-variant">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="text-[10px] font-bold">Avisos</span>
</button>
<button class="flex flex-col items-center gap-xs text-on-surface-variant">
<span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
<span class="text-[10px] font-bold">Perfil</span>
</button>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form></body></html>