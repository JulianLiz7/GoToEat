<!DOCTYPE html>
<html class="scroll-smooth" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Gestión y Experiencia Gastronómica</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-variant": "#d3e4fe",
                    "primary-container": "#f97316",
                    "surface-container-low": "#eff4ff",
                    "on-primary-fixed-variant": "#783200",
                    "on-secondary": "#ffffff",
                    "tertiary-container": "#00a2f4",
                    "surface": "#f8f9ff",
                    "surface-container": "#e5eeff",
                    "on-background": "#0b1c30",
                    "on-tertiary-container": "#003554",
                    "on-primary-fixed": "#341100",
                    "on-tertiary": "#ffffff",
                    "inverse-on-surface": "#eaf1ff",
                    "primary-fixed-dim": "#ffb690",
                    "background": "#f8f9ff",
                    "secondary-fixed": "#6ffbbe",
                    "surface-dim": "#cbdbf5",
                    "outline": "#8c7164",
                    "secondary-fixed-dim": "#4edea3",
                    "surface-container-lowest": "#ffffff",
                    "on-primary": "#ffffff",
                    "on-error-container": "#93000a",
                    "on-tertiary-fixed": "#001d32",
                    "on-tertiary-fixed-variant": "#004b74",
                    "on-secondary-container": "#00714d",
                    "on-primary-container": "#582200",
                    "surface-container-high": "#dce9ff",
                    "secondary": "#006c49",
                    "secondary-container": "#6cf8bb",
                    "error": "#ba1a1a",
                    "primary": "#9d4300",
                    "surface-tint": "#9d4300",
                    "tertiary": "#006398",
                    "outline-variant": "#e0c0b1",
                    "on-surface": "#0b1c30",
                    "on-secondary-fixed": "#002113",
                    "error-container": "#ffdad6",
                    "surface-bright": "#f8f9ff",
                    "on-secondary-fixed-variant": "#005236",
                    "inverse-primary": "#ffb690",
                    "surface-container-highest": "#d3e4fe",
                    "tertiary-fixed": "#cde5ff",
                    "on-surface-variant": "#584237",
                    "inverse-surface": "#213145",
                    "on-error": "#ffffff",
                    "primary-fixed": "#ffdbca",
                    "tertiary-fixed-dim": "#93ccff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "lg": "40px",
                    "gutter": "24px",
                    "xs": "8px",
                    "sm": "16px",
                    "xl": "64px",
                    "md": "24px",
                    "base": "4px",
                    "container_max": "1280px"
            },
            "fontFamily": {
                    "body-lg": ["Inter"],
                    "body-md": ["Inter"],
                    "body-sm": ["Inter"],
                    "h1": ["Sora"],
                    "h3": ["Sora"],
                    "label-caps": ["Inter"],
                    "h2": ["Sora"]
            },
            "fontSize": {
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .perspective-3d {
            perspective: 2000px;
        }
        .dashboard-tilt {
            transform: rotateX(10deg) rotateY(-15deg) rotateZ(5deg);
            box-shadow: -40px 40px 80px -20px rgba(0,0,0,0.15);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-slate-200 glass-nav h-20 px-6 md:px-12">
<div class="max-w-7xl mx-auto flex justify-between items-center h-full">
<div class="text-2xl font-h1 font-bold text-slate-900 tracking-tight">
            GoTo<span class="text-primary-container">Eat</span>
</div>
<div class="hidden md:flex items-center gap-xs space-x-8">
<a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="#restaurantes">Para Restaurantes</a>
<a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="#comensales">Para Comensales</a>
</div>
<div class="flex items-center gap-sm">
@auth
    <a href="{{ route('dashboard') }}" class="text-slate-600 font-semibold px-4 py-2 hover:text-primary-container transition-colors">Mi Panel</a>
@else
    <a href="{{ route('login') }}" class="hidden lg:block text-slate-600 font-semibold px-4 py-2 hover:text-primary-container transition-colors">Iniciar Sesión</a>
    <a href="{{ route('register') }}" class="bg-primary-container text-white font-semibold px-6 py-2.5 rounded-lg shadow-lg shadow-orange-500/20 active:scale-95 transition-all inline-block">Empezar Ahora</a>
@endauth
</div>
</div>
</nav>
<!-- Unified Hero Section -->
<header class="relative pt-32 pb-xl px-6 md:px-12 overflow-hidden bg-white">
<div class="max-w-7xl mx-auto text-center mb-xl">
<div class="inline-flex items-center gap-xs px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed text-label-caps uppercase tracking-widest border border-primary-fixed-dim mb-md">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">restaurant</span>
            Conectando el mundo gastronómico
        </div>
<h1 class="font-h1 text-h1 text-on-background max-w-4xl mx-auto mb-sm">
            El puente entre una gestión impecable y la experiencia culinaria perfecta
        </h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">
            GoToEat une a dueños de restaurantes y amantes de la comida en una sola plataforma diseñada para potenciar cada bocado.
        </p>
</div>
<!-- Split Choice Section -->
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-md px-4">
<!-- For Restaurants Card -->
<a class="group relative overflow-hidden rounded-3xl bg-slate-900 aspect-[16/10] flex flex-col justify-end p-lg hover:shadow-2xl transition-all duration-500" href="#restaurantes">
<img alt="Restaurant Management" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFpmet8Glm9oY6a6lQhmDy5UM21O76r3aW4kgSoeUnU6WabiMie04QK91bVQ-8lGQSgbNqDzLq52kVgkaG25cYDK2h5jR477VfcaKIOok-Jv9YG-35m_9HnqFlZxiuKXhP7TuYT_eclxa_8St9NIwqKpwchkpbtFGgeg4wiXHsblnGmm0XN1TQPnK6fok7Tb3cOaZCeKuH6imdf-ufVQAgWw-2yNw71MeLOhLnF21ilCrpET0qg2-ibBa0mINydwb_YVvH7zx7mLY"/>
<div class="relative z-10 text-white space-y-xs">
<div class="w-12 h-12 bg-primary-container rounded-xl flex items-center justify-center mb-sm">
<span class="material-symbols-outlined">analytics</span>
</div>
<h3 class="font-h2 text-h3">Para Restaurantes</h3>
<p class="text-slate-300 max-w-xs">Optimiza tu operación, controla tu stock y lidera tu equipo con IA.</p>
<div class="flex items-center gap-xs text-primary-container font-bold pt-sm">
                    Ver soluciones de gestión <span class="material-symbols-outlined">arrow_forward</span>
</div>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
</a>
<!-- For Diners Card -->
<a class="group relative overflow-hidden rounded-3xl bg-primary-container aspect-[16/10] flex flex-col justify-end p-lg hover:shadow-2xl transition-all duration-500" href="#comensales">
<img alt="Dining Experience" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDKPCRydGe9ylWGgrDp9HTh2ML0CYJAJLSg1z807pMoS72inqUBVwwZhJYVEopq2Q4B-FI-c-hELymaDEFx6lOoLgiUxaEr_Nf-AOrb3uNFF2_Ngnm3x2VArW0hBb8lrCTyEq0eAdGwWLLbvn2yocmJ4qGoh16IM81-N42oGGa-qKez1G-GZffJsGqHyjj2YhOGAYyRiZUET5duL9X2CPWLNBUENoCm5JYi9tkhBujiLQe4ROFS7bHXmoQDaCxDAatnhbkeWjUt3ZY"/>
<div class="relative z-10 text-white space-y-xs">
<div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center mb-sm">
<span class="material-symbols-outlined">explore</span>
</div>
<h3 class="font-h2 text-h3">Para Comensales</h3>
<p class="text-white/90 max-w-xs">Descubre menús digitales, reserva tu mesa y vive experiencias únicas.</p>
<div class="flex items-center gap-xs text-white font-bold pt-sm">
                    Registrarse <span class="material-symbols-outlined">arrow_forward</span>
</div>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent"></div>
</a>
</div>
</header>
<!-- SECTION 1: Para Restaurantes -->
<section class="py-xl bg-surface-container-low px-6 md:px-12" id="restaurantes">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col lg:flex-row gap-xl items-center mb-xl">
<div class="lg:w-1/2 space-y-md">
<span class="text-primary-container font-bold tracking-widest text-label-caps">SOLUCIONES B2B</span>
<h2 class="font-h2 text-h2 text-on-surface">Eficiencia total en tu cocina y salón</h2>
<p class="text-body-lg text-on-surface-variant">Centraliza tu operación con herramientas diseñadas para el mundo real. Olvídate del caos administrativo y enfócate en lo que importa: la comida.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-sm pt-md">
<div class="flex items-start gap-sm p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
<span class="material-symbols-outlined text-primary-container">inventory</span>
<div>
<h4 class="font-bold text-body-md">Inventario Inteligente</h4>
<p class="text-sm text-on-surface-variant">Alertas de stock y mermas.</p>
</div>
</div>
<div class="flex items-start gap-sm p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
<span class="material-symbols-outlined text-primary-container">groups</span>
<div>
<h4 class="font-bold text-body-md">Gestión de Equipo</h4>
<p class="text-sm text-on-surface-variant">Roles y turnos simplificados.</p>
</div>
</div>
</div>
<a href="{{ route('register') }}" class="mt-md bg-primary-container text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:translate-y-[-2px] transition-all inline-block">Potenciar mi Restaurante</a>
</div>
<div class="lg:w-1/2 relative perspective-3d">
<div class="dashboard-tilt bg-white rounded-2xl p-2 border border-slate-200">
<img alt="Dashboard Analytics" class="w-full h-auto rounded-xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC16fIQBa-7rLK3U3nZBEkyttpOC4ImPA75FzRCmMACnqbLvpoMa8j47MGvttHBUkhCzeJ1mV_66LTfBuDtxOmVny2fB1LHXuk0F81eGCBWZrWJhjxyBAyLGKEXPxKsLWRhp_J2BXfNcwZmO52q1xpybCt3Ka2wcZa5WYUstcvh3ynxvjwffPRdCf__g2UW3fXTK-Ko--_KxmJHAa9eNY92DsOqTEfphOLeQz9mxLgSdvkixoKDIdOt0pWZqEiDkNOxZyAU3FDRIFM"/>
</div>
</div>
</div>
</div>
</section>
<!-- SECTION 2: Para Comensales -->
<section class="py-xl px-6 md:px-12 bg-white overflow-hidden" id="comensales">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col-reverse lg:flex-row gap-xl items-center">
<div class="lg:w-1/2 grid grid-cols-2 gap-sm">
<div class="space-y-sm">
<img alt="Restaurant Interior" class="rounded-2xl shadow-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAEQnGfjxf86Zuo9-25ZgojeCe3hbtDNKyzjO4vwNUvLEgTcG8ks-3905cJNlaukgt-cqSI4eIcKG8RWiv8ayJOFqgAz8Ugu7th42fb6k73daAwSa2JWkr6LgIpNsfwRtCk1f6L5AuwNuRW8U3F6vqcKMgLcDwZZCEgntd70Ps6zA2zc_4yVTXfY3UPuNdrHxFP6TgnVisr4EXgbOPKFngU6_DTIXlWaLvwnXcWvw7YTpFIXjGSSKwvtAcjSuqH-Hu0XKgA3h-ww-Y"/>
<img alt="Delicious Food" class="rounded-2xl shadow-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAKidYt3QNvxV-D_o9qsk5vmrAB7s4Y51NAUcpw2kf6mTLcMumUyJtXABnU61U7a_xL4PxHvmDlNR9pUUIKt2Jj4Kbo1D2g2KUSU5k9xKwhQjm0hfnl5bo58zymC_MWxBYwwOcC6N93hsETHKFk7oH-OzKRDhhnvjFqQnmowGxBRsorXZkF8R6D84_CNwkt8ePfqlCukeBUH7Dg1Rau6B-RUo_scQ5JjrpsfdQsBa56IDPHk3hazjlTHP3Y6plTw1TkKaUcXBMsWdQ"/>
</div>
<div class="space-y-sm pt-xl">
<img alt="Dining Table" class="rounded-2xl shadow-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFsrvXcSnSL1ScLloKgunwNKClICVTApR-DUOP9I_LqLSTr1STa_di6TTBIct3wiq-zKZ_1hJWhdJid05_DZy58564DdnT2MyCC50b__D_CBRudQLlWCydbECDELp5nzmYBiyuClVnMB_aj7HDwRGuyag30yEiW_uAwa8Nppb9vfFvQnQF90aUpZIGe-uKlE1kX7g-EXkQ4YtGf0FLZU7jYhQcbFz84ZkmNbM7NKncK8CNbXjtPS5G_HdIx1OG0vxdIHHHThdgejc"/>
<img alt="Chef Cooking" class="rounded-2xl shadow-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCh_GoEcJEJXbXA1wgZ58CBkk_8IGo4JXa93NUj5IAstytUXNgjFRseEDf_hA3cGxYElNMgw0LAYfAoBvXjncG1myDQm_3qn5FIC1qK5Y5c8mrkoczX1wnEYJwGuqHvYPbYMTxvER0pMqEHRkWYnlBh9mkRZ1Q3Z_7S29Znu414x1S2fjOq6hknPknu7kijjBZFqxkNANeffQMh6wC4viBPg9y_QB7VoZk-dDj89E4q_CZwdSjHdqKbyCAnJ_5NBBY5_PdtckS4v48"/>
</div>
</div>
<div class="lg:w-1/2 space-y-md">
<span class="text-tertiary font-bold tracking-widest text-label-caps">SOLUCIONES B2C</span>
<h2 class="font-h2 text-h2 text-on-surface">Tu próximo sabor favorito está a un click</h2>
<p class="text-body-lg text-on-surface-variant">Explora los mejores lugares de tu ciudad, consulta menús digitales actualizados en tiempo real y reserva sin esperas. GoToEat es tu pasaporte a la mejor mesa.</p>
<ul class="space-y-sm">
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined text-tertiary-container">menu_book</span>
<span class="text-body-md font-medium">Menús digitales interactivos con fotos reales.</span>
</li>
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined text-tertiary-container">calendar_month</span>
<span class="text-body-md font-medium">Reservas instantáneas con confirmación.</span>
</li>
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined text-tertiary-container">star</span>
<span class="text-body-md font-medium">Reseñas de la comunidad gastronómica.</span>
</li>
</ul>
<a href="{{ route('register') }}" class="mt-md border-2 border-tertiary text-tertiary font-bold px-8 py-4 rounded-xl hover:bg-tertiary/5 transition-all inline-block">Empezar a Explorar</a>
</div>
</div>
</div>
</section>
<!-- Features Grid -->
<section class="py-xl px-6 md:px-12 bg-surface-container">
<div class="max-w-7xl mx-auto text-center mb-xl">
<h2 class="font-h2 text-h2 mb-xs">Todo lo que necesitas para ganar</h2>
<p class="text-on-surface-variant text-body-lg max-w-2xl mx-auto">Módulos integrados que conectan el front-end con el back-office.</p>
</div>
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-md">
<div class="bg-white p-lg rounded-2xl shadow-sm hover:shadow-xl transition-all group">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md group-hover:bg-primary-container group-hover:text-white transition-colors">
<span class="material-symbols-outlined">table_restaurant</span>
</div>
<h3 class="font-bold text-h3 mb-xs">Mesas &amp; Reservas</h3>
<p class="text-sm text-on-surface-variant">Gestión de salón sincronizada con la app de comensales.</p>
</div>
<div class="bg-white p-lg rounded-2xl shadow-sm hover:shadow-xl transition-all group">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md group-hover:bg-primary-container group-hover:text-white transition-colors">
<span class="material-symbols-outlined">restaurant_menu</span>
</div>
<h3 class="font-bold text-h3 mb-xs">Menú Digital</h3>
<p class="text-sm text-on-surface-variant">Edita precios y platos que se actualizan al instante para todos.</p>
</div>
<div class="bg-white p-lg rounded-2xl shadow-sm hover:shadow-xl transition-all group">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md group-hover:bg-primary-container group-hover:text-white transition-colors">
<span class="material-symbols-outlined">auto_awesome</span>
</div>
<h3 class="font-bold text-h3 mb-xs">Asistente IA</h3>
<p class="text-sm text-on-surface-variant">Predicciones de demanda para optimizar tus compras.</p>
</div>
</div>
</section>
<!-- Final CTA Section -->
<section class="py-xl px-6 md:px-12">
<div class="max-w-7xl mx-auto rounded-3xl bg-gradient-to-br from-primary-container to-primary overflow-hidden relative p-lg md:p-xl text-center text-white shadow-2xl">
<div class="relative z-10">
<h2 class="font-h1 text-h1 mb-md">Únete a la evolución gastronómica</h2>
<p class="text-body-lg opacity-90 max-w-2xl mx-auto mb-xl">Potencia tu negocio o encuentra tu próxima cena inolvidable. El futuro del sabor comienza aquí.</p>
<div class="flex flex-wrap justify-center gap-md">
<a href="{{ route('register') }}" class="bg-white text-primary-container font-bold px-10 py-5 rounded-xl text-body-lg shadow-xl hover:scale-105 active:scale-95 transition-all inline-block">Registrar Restaurante</a>
</div>
</div>
<div class="absolute -bottom-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
<div class="absolute -top-20 -left-20 w-80 h-80 bg-black/10 rounded-full blur-3xl"></div>
</div>
</section>
<!-- Footer -->
<footer class="bg-slate-950 text-white py-xl px-6 md:px-12">
<div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-xl">
<div class="col-span-2 md:col-span-1 space-y-md">
<div class="text-2xl font-black text-white">GoToEat</div>
<p class="text-slate-400 text-sm max-w-xs">El ecosistema que conecta la pasión por cocinar con el placer de comer.</p>
<div class="flex gap-sm">
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">camera_alt</span></a>
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">chat</span></a>
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">video_library</span></a>
</div>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Restaurantes</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">Gestión Inventario</a>
<a class="hover:text-white transition-colors" href="#">Staff Manager</a>
<a class="hover:text-white transition-colors" href="#">POS Cloud</a>
</nav>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Comensales</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">Explorar Menús</a>
<a class="hover:text-white transition-colors" href="#">Reservas</a>
<a class="hover:text-white transition-colors" href="#">Club GoToEat</a>
</nav>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Legal</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">Privacidad</a>
<a class="hover:text-white transition-colors" href="#">Términos</a>
<a class="hover:text-white transition-colors" href="#">Cookies</a>
</nav>
</div>
</div>
<div class="max-w-7xl mx-auto mt-xl pt-lg border-t border-slate-800 text-center text-slate-500 text-sm">
        © 2024 GoToEat. Elevando la gastronomía global.
    </div>
</footer>
</body></html>