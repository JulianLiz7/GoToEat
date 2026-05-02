<!DOCTYPE html>

<html class="scroll-smooth" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Gestión Inteligente para Restaurantes</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<div class="hidden md:flex items-center gap-xs space-x-8"><a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="#features">Funcionalidades</a><a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="#how-it-works">Cómo Funciona</a></div>
<div class="flex items-center gap-sm">
<button class="hidden lg:block text-slate-600 font-semibold px-4 py-2">Iniciar Sesión</button>
<button class="bg-primary-container text-white font-semibold px-6 py-2.5 rounded-lg shadow-lg shadow-orange-500/20 active:scale-95 transition-all">Registrarse Gratis</button>
</div>
</div>
</nav>
<!-- Hero Section -->
<header class="relative pt-32 pb-xl px-6 md:px-12 overflow-hidden">
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-xl items-center">
<div class="space-y-md">
<div class="inline-flex items-center gap-xs px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed text-label-caps uppercase tracking-widest border border-primary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                    Plataforma impulsada por IA
                </div>
<h1 class="font-h1 text-h1 text-on-background max-w-xl">
                    Tu restaurante merece una gestión a la altura
                </h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg">
                    GoToEat es la herramienta administrativa gratuita que centraliza tu operación, optimiza tus costos y potencia tus ventas.
                </p>
<div class="flex flex-wrap gap-sm pt-xs">
<button class="bg-primary-container text-on-primary font-semibold px-8 py-4 rounded-xl text-body-lg shadow-xl shadow-primary-container/30 hover:translate-y-[-2px] active:scale-95 transition-all">Empieza Gratis</button>
</div>
</div>
<div class="relative perspective-3d flex justify-center items-center">
<div class="dashboard-tilt w-full max-w-xl bg-white rounded-2xl overflow-hidden border border-slate-200 p-base">
<img alt="Dashboard Analytics" class="w-full h-auto rounded-xl" data-alt="A sophisticated digital dashboard interface for a restaurant management software showing vibrant analytic charts and data cards. The screen features clean glassmorphism design with cards in orange, emerald green, and deep purple against a pristine white background. The lighting is crisp and modern, reflecting a premium SaaS platform aesthetic for professional hospitality business owners." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC16fIQBa-7rLK3U3nZBEkyttpOC4ImPA75FzRCmMACnqbLvpoMa8j47MGvttHBUkhCzeJ1mV_66LTfBuDtxOmVny2fB1LHXuk0F81eGCBWZrWJhjxyBAyLGKEXPxKsLWRhp_J2BXfNcwZmO52q1xpybCt3Ka2wcZa5WYUstcvh3ynxvjwffPRdCf__g2UW3fXTK-Ko--_KxmJHAa9eNY92DsOqTEfphOLeQz9mxLgSdvkixoKDIdOt0pWZqEiDkNOxZyAU3FDRIFM"/>
</div>
<!-- Decorative elements -->
<div class="absolute -top-12 -right-12 w-48 h-48 bg-primary-container/10 rounded-full blur-3xl -z-10"></div>
<div class="absolute -bottom-12 -left-12 w-64 h-64 bg-tertiary-container/10 rounded-full blur-3xl -z-10"></div>
</div>
</div>
</header>
<!-- Problem/Solution Section -->
<section class="py-xl bg-surface-container-low px-6 md:px-12">
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-xl items-stretch">
<div class="flex flex-col justify-center space-y-lg">
<h2 class="font-h2 text-h2 text-on-surface">¿El caos administrativo frena tu crecimiento?</h2>
<div class="space-y-md">
<div class="flex items-start gap-sm">
<div class="p-2 bg-error-container text-on-error-container rounded-lg">
<span class="material-symbols-outlined">inventory_2</span>
</div>
<div>
<h4 class="font-bold text-body-lg">Stock agotado inesperadamente</h4>
<p class="text-on-surface-variant">Pierde ventas por falta de ingredientes críticos en plena jornada.</p>
</div>
</div>
<div class="flex items-start gap-sm">
<div class="p-2 bg-error-container text-on-error-container rounded-lg">
<span class="material-symbols-outlined">group_off</span>
</div>
<div>
<h4 class="font-bold text-body-lg">Caos en horas pico</h4>
<p class="text-on-surface-variant">Falta de comunicación entre cocina y salón que retrasa los pedidos.</p>
</div>
</div>
<div class="flex items-start gap-sm">
<div class="p-2 bg-error-container text-on-error-container rounded-lg">
<span class="material-symbols-outlined">monitoring</span>
</div>
<div>
<h4 class="font-bold text-body-lg">Falta de visibilidad financiera</h4>
<p class="text-on-surface-variant">No saber cuánto ganas realmente después de gastos y mermas.</p>
</div>
</div>
</div>
</div>
<div class="bg-primary-container p-lg md:p-xl rounded-3xl shadow-2xl shadow-primary-container/20 text-on-primary flex flex-col justify-between">
<div class="space-y-md">
<div class="text-label-caps opacity-80">La Solución GoToEat</div>
<h3 class="text-h2 font-h2">Control total sin costo alguno.</h3>
<ul class="space-y-sm">
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-body-md">Inventario inteligente con alertas automáticas.</span>
</li>
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-body-md">Gestión de personal y turnos simplificada.</span>
</li>
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-body-md">Análisis de rentabilidad por plato en tiempo real.</span>
</li>
<li class="flex items-center gap-xs">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-body-md">Integración directa con sistemas de pago.</span>
</li>
</ul>
</div>
<button class="mt-xl bg-white text-primary-container font-bold px-8 py-4 rounded-xl text-body-lg hover:scale-105 active:scale-95 transition-all">Empieza Gratis Ahora</button>
</div>
</div>
</section>
<!-- Features Grid -->
<section class="py-xl px-6 md:px-12 bg-surface" id="features">
<div class="max-w-7xl mx-auto text-center mb-xl">
<h2 class="font-h2 text-h2 mb-xs">Todo lo que necesitas para ganar</h2>
<p class="text-on-surface-variant text-body-lg max-w-2xl mx-auto">Módulos diseñados por expertos en restauración para maximizar tu eficiencia.</p>
</div>
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
<!-- Feature 1 -->
<div class="group bg-white p-lg rounded-2xl border border-slate-200 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md">
<span class="material-symbols-outlined">dashboard</span>
</div>
<h3 class="font-h3 text-h3 mb-xs">Dashboard Real-time</h3>
<p class="text-on-surface-variant text-body-md">Visualiza tus ventas y métricas clave en tiempo real desde cualquier dispositivo.</p>
</div>
<!-- Feature 2 -->
<div class="group bg-white p-lg rounded-2xl border border-slate-200 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md">
<span class="material-symbols-outlined">inventory</span>
</div>
<h3 class="font-h3 text-h3 mb-xs">Inventario</h3>
<p class="text-on-surface-variant text-body-md">Control riguroso de existencias y pedidos automáticos a proveedores.</p>
</div>
<!-- Feature 3 -->
<div class="group bg-white p-lg rounded-2xl border border-slate-200 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md">
<span class="material-symbols-outlined">restaurant_menu</span>
</div>
<h3 class="font-h3 text-h3 mb-xs">Menú</h3>
<p class="text-on-surface-variant text-body-md">Escandallos precisos y gestión dinámica de tu carta digital.</p>
</div>
<!-- Feature 4 -->
<div class="group bg-white p-lg rounded-2xl border border-slate-200 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md">
<span class="material-symbols-outlined">badge</span>
</div>
<h3 class="font-h3 text-h3 mb-xs">Equipo</h3>
<p class="text-on-surface-variant text-body-md">Roles, permisos y control de asistencia integrado para tus colaboradores.</p>
</div>
<!-- Feature 5 -->
<div class="group bg-white p-lg rounded-2xl border border-slate-200 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary mb-md">
<span class="material-symbols-outlined">table_restaurant</span>
</div>
<h3 class="font-h3 text-h3 mb-xs">Mesas</h3>
<p class="text-on-surface-variant text-body-md">Mapa de salón interactivo y gestión de reservas en tiempo real.</p>
</div>
<!-- Feature 6 (AI Special) -->
<div class="group bg-orange-50 p-lg rounded-2xl border border-primary-container/30 hover:border-t-primary-container hover:border-t-4 hover:shadow-2xl transition-all duration-300">
<div class="flex justify-between items-start mb-md">
<div class="w-12 h-12 bg-primary-container rounded-xl flex items-center justify-center text-white">
<span class="material-symbols-outlined">auto_awesome</span>
</div>
<span class="bg-primary-fixed text-on-primary-fixed text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">Premium Free</span>
</div>
<h3 class="font-h3 text-h3 mb-xs text-primary-container">Asistente IA</h3>
<p class="text-on-surface-variant text-body-md">Predicciones de demanda basadas en datos históricos para evitar mermas.</p>
</div>
</div>
</section>
<!-- How It Works Section -->
<section class="py-xl bg-slate-950 text-white px-6 md:px-12" id="how-it-works">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-xl">
<h2 class="font-h2 text-h2 mb-xs">Tres pasos hacia el éxito</h2>
<p class="text-slate-400 text-body-lg">Sin procesos complejos ni instalaciones tardías.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-xl relative">
<!-- Connector lines (hidden on mobile) -->
<div class="hidden md:block absolute top-12 left-[20%] right-[20%] h-0.5 bg-slate-800 -z-0"></div>
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-20 h-20 bg-primary-container text-white rounded-full flex items-center justify-center text-h2 font-h1 mb-md shadow-2xl shadow-primary-container/30">1</div>
<h3 class="text-h3 font-h3 mb-xs">Registra tu restaurante</h3>
<p class="text-slate-400">Crea tu cuenta en 30 segundos de forma gratuita.</p>
</div>
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-20 h-20 bg-primary-container text-white rounded-full flex items-center justify-center text-h2 font-h1 mb-md shadow-2xl shadow-primary-container/30">2</div>
<h3 class="text-h3 font-h3 mb-xs">Carga tu información</h3>
<p class="text-slate-400">Añade tu menú, stock inicial y equipo de trabajo.</p>
</div>
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-20 h-20 bg-primary-container text-white rounded-full flex items-center justify-center text-h2 font-h1 mb-md shadow-2xl shadow-primary-container/30">3</div>
<h3 class="text-h3 font-h3 mb-xs">Toma el control</h3>
<p class="text-slate-400">Comienza a operar con datos reales y optimización constante.</p>
</div>
</div>
</div>
</section>
<!-- Testimonials Section -->
<!-- FAQ Section -->
<!-- Final CTA Section -->
<section class="py-xl px-6 md:px-12">
<div class="max-w-7xl mx-auto rounded-3xl bg-gradient-to-br from-primary-container to-primary overflow-hidden relative p-lg md:p-xl text-center text-white">
<div class="relative z-10">
<h2 class="font-h1 text-h1 mb-md">Únete a la revolución gastronómica</h2>
<p class="text-body-lg opacity-90 max-w-2xl mx-auto mb-xl">Empieza hoy mismo a profesionalizar tu restaurante sin gastar un solo centavo. El futuro de tu negocio comienza aquí.</p>
<div class="flex flex-wrap justify-center gap-md">
<button class="bg-white text-primary-container font-bold px-10 py-5 rounded-xl text-body-lg shadow-2xl hover:scale-105 active:scale-95 transition-all">Empieza Gratis</button>
<button class="border-2 border-white text-white font-bold px-10 py-5 rounded-xl text-body-lg hover:bg-white/10 transition-colors">Saber más</button>
</div>
</div>
<!-- Decorative background elements -->
<div class="absolute -bottom-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
<div class="absolute -top-20 -left-20 w-80 h-80 bg-black/10 rounded-full blur-3xl"></div>
</div>
</section>
<!-- Footer -->
<footer class="bg-slate-950 text-white py-xl px-6 md:px-12">
<div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-xl">
<div class="col-span-2 md:col-span-1 space-y-md">
<div class="text-2xl font-black text-white">GoToEat</div>
<p class="text-slate-400 text-sm max-w-xs">Elevando el estándar de la gestión gastronómica global mediante tecnología gratuita y accesible.</p>
<div class="flex gap-sm">
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">camera_alt</span></a>
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">chat</span></a>
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">video_library</span></a>
<a class="p-2 bg-slate-800 rounded-lg hover:text-primary-container transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
</div>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Product</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">Inventory</a>
<a class="hover:text-white transition-colors" href="#">Menu Builder</a>
<a class="hover:text-white transition-colors" href="#">Staff Manager</a>
<a class="hover:text-white transition-colors" href="#">POS Integration</a>
</nav>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Company</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">About</a>
<a class="hover:text-white transition-colors" href="#">Careers</a>
<a class="hover:text-white transition-colors" href="#">Contact</a>
<a class="hover:text-white transition-colors" href="#">Press Kit</a>
</nav>
</div>
<div class="space-y-sm">
<h4 class="font-bold text-body-md">Legal</h4>
<nav class="flex flex-col gap-xs text-slate-400 text-sm">
<a class="hover:text-white transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-white transition-colors" href="#">Terms of Service</a>
<a class="hover:text-white transition-colors" href="#">Cookies</a>
<a class="hover:text-white transition-colors" href="#">Security</a>
</nav>
</div>
</div>
<div class="max-w-7xl mx-auto mt-xl pt-lg border-t border-slate-800 text-center text-slate-500 text-sm">
            © 2024 GoToEat. All rights reserved. Built for modern hospitality.
        </div>
</footer>
</body></html>