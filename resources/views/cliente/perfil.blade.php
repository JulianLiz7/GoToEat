<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Perfil de Usuario</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-bright": "#f8f9ff",
                    "on-surface": "#0b1c30",
                    "secondary": "#006c49",
                    "primary-container": "#f97316",
                    "on-primary-fixed-variant": "#783200",
                    "error": "#f97316", // Replaced red with orange
                    "surface-container-highest": "#d3e4fe",
                    "outline": "#f97316", // Replaced brown/muted with orange
                    "inverse-surface": "#213145",
                    "secondary-fixed-dim": "#4edea3",
                    "on-error": "#ffffff",
                    "on-tertiary-container": "#003554",
                    "inverse-primary": "#ffb690",
                    "on-error-container": "#f97316", // Replaced red with orange
                    "error-container": "#ffdbca", // Replaced red container with light orange
                    "on-background": "#0b1c30",
                    "tertiary-fixed-dim": "#93ccff",
                    "on-primary-container": "#ffffff",
                    "on-secondary-fixed-variant": "#005236",
                    "surface-container-low": "#eff4ff",
                    "surface": "#f8f9ff",
                    "on-secondary-fixed": "#002113",
                    "on-primary": "#ffffff",
                    "secondary-fixed": "#6ffbbe",
                    "primary-fixed-dim": "#ffb690",
                    "surface-tint": "#f97316",
                    "surface-container-high": "#dce9ff",
                    "primary-fixed": "#ffdbca",
                    "tertiary-fixed": "#cde5ff",
                    "outline-variant": "#ffdbca", // Replaced muted variant with light orange
                    "on-primary-fixed": "#341100",
                    "background": "#f8f9ff",
                    "surface-dim": "#cbdbf5",
                    "inverse-on-surface": "#eaf1ff",
                    "on-tertiary": "#ffffff",
                    "on-secondary": "#ffffff",
                    "primary": "#f97316", // Vibrant Orange
                    "on-secondary-container": "#00714d",
                    "tertiary": "#006398",
                    "secondary-container": "#6cf8bb",
                    "on-tertiary-fixed": "#001d32",
                    "tertiary-container": "#00a2f4",
                    "on-tertiary-fixed-variant": "#004b74",
                    "surface-variant": "#d3e4fe",
                    "surface-container": "#e5eeff",
                    "surface-container-lowest": "#ffffff",
                    "on-surface-variant": "#584237"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "xl": "64px",
                    "gutter": "24px",
                    "base": "4px",
                    "container_max": "1280px",
                    "md": "24px",
                    "sm": "16px",
                    "xs": "8px",
                    "lg": "40px"
            },
            "fontFamily": {
                    "body-lg": ["Inter"],
                    "h1": ["Sora"],
                    "body-md": ["Inter"],
                    "label-caps": ["Inter"],
                    "h3": ["Sora"],
                    "body-sm": ["Inter"],
                    "h2": ["Sora"]
            },
            "fontSize": {
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}]
            }
          }
        }
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen">
<!-- TopNavBar -->
<header class="bg-surface/80 dark:bg-on-surface/80 backdrop-blur-md font-h3 text-h3 tracking-tight font-body-md text-body-md docked full-width top-0 sticky border-b border-primary/20 dark:border-primary/20 shadow-sm z-50">
<div class="flex justify-between items-center w-full px-gutter py-4 max-w-container_max mx-auto">
<div class="flex items-center gap-lg">
<span class="font-h3 text-h3 font-bold text-primary">GoToEat</span>
<nav class="hidden md:flex gap-md items-center">
<a class="text-on-surface-variant dark:text-surface-variant hover:text-primary transition-colors" href="{{ route('dashboard') }}">Explorar</a>
<a class="text-on-surface-variant dark:text-surface-variant hover:text-primary transition-colors" href="{{ route('reservas') }}">Reservas</a>

</nav>
</div>
<div class="flex items-center gap-sm">
<button class="bg-primary text-on-primary px-sm py-2 rounded-lg font-bold hover:scale-95 active:scale-90 transition-transform">Nueva Reserva</button>
<div class="flex items-center gap-xs ml-4">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-xs hover:bg-surface-container-low rounded-full transition-all">notifications</span>
<span class="material-symbols-outlined text-primary font-bold cursor-pointer p-xs bg-surface-container-low rounded-full transition-all">account_circle</span>
</div>
</div>
</div>
</header>
<div class="max-w-container_max mx-auto flex">
<!-- SideNavBar -->
<aside class="hidden md:flex flex-col h-[calc(100vh-80px)] w-64 sticky top-20 bg-surface-container-low p-sm gap-xs shadow-md">
<div class="mb-lg p-xs">
<div class="flex items-center gap-sm mb-xs">
<div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary">
<img alt="Avatar del usuario" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfUPVitbrCMR1p3w1TKXks4ay9r_3iHeQaynHVPvvr5dqTxy5_LhnkURU700pzpsCn_zgAA_5AFH9qbjsOOxa8kiRiOZ69r3bbzMY29L91Ia286wDnd5X0kndDQjvXv38S0dJdwsYiGpjsGtEh-1zp-EIfyGCdaJ0f0zswIGQVnUrg3VFK7U0D7zDYO2np1hNCEpanL-jEcBCKP2td3apgBDxHe2OfxICuYe80ogxZ9jW2ivBCrSVTexMB-abFImAe4w14yJ6FqbM"/>
</div>
<div>
<h4 class="font-h3 text-body-md font-bold text-on-surface">Panel de Usuario</h4>
<p class="text-body-sm text-on-surface-variant">Gastronomía Premium</p>
</div>
</div>
</div>
<nav class="flex-grow flex flex-col gap-xs">
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined">search</span>
<span class="font-body-sm">Explorar</span>
</a>
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="{{ route('reservas') }}">
<span class="material-symbols-outlined">calendar_today</span>
<span class="font-body-sm">Mis Reservas</span>
</a>
<a class="flex items-center gap-sm p-sm bg-primary text-on-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="{{ route('perfil') }}">
<span class="material-symbols-outlined">person</span>
<span class="font-body-sm">Perfil</span>
</a>
<a class="flex items-center gap-sm p-sm text-primary hover:bg-primary/10 rounded-lg transition-transform hover:translate-x-1 mt-auto font-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
<span class="material-symbols-outlined">logout</span>
<span class="font-body-sm">Cerrar Sesión</span>
</a>
</nav>
<div class="mt-xl border-t border-primary/20 pt-sm flex flex-col gap-xs">
<a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="font-body-sm">Ajustes</span>
</a>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-grow p-gutter md:p-xl">
<div class="max-w-3xl mx-auto space-y-lg">
<!-- Header Section -->
<section class="space-y-xs">
<h1 class="font-h1 text-h2 text-on-surface">Configuración de Perfil</h1>
<p class="text-body-lg text-on-surface-variant">Gestiona tu información personal y la seguridad de tu cuenta GoToEat.</p>
</section>
<!-- Update Personal Data Card -->
<div class="bg-surface-container-lowest rounded-xl p-lg shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)] border border-primary/10">
<div class="flex items-center gap-sm mb-lg border-b border-primary/10 pb-sm">
<span class="material-symbols-outlined text-primary">edit_note</span>
<h2 class="font-h2 text-h3 text-on-surface">Actualizar Datos Personales</h2>
</div>
<form class="space-y-md">
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="space-y-xs md:col-span-2">
<label class="font-label-caps text-on-surface-variant uppercase">Nombre Completo</label>
<input class="w-full bg-surface border border-primary/20 rounded-lg p-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Ingresa tu nombre" type="text" value="{{ auth()->user()->name }}"/>
<p class="text-body-sm text-primary/70 italic">Este es el único campo editable en esta sección.</p>
</div>
<div class="space-y-xs opacity-60">
<label class="font-label-caps text-on-surface-variant uppercase">Correo Electrónico</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-sm cursor-not-allowed" disabled="" placeholder="email@ejemplo.com" type="email" value="{{ auth()->user()->email }}"/>
</div>
<div class="space-y-xs opacity-60">
<label class="font-label-caps text-on-surface-variant uppercase">ID de Usuario</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-sm cursor-not-allowed" disabled="" type="text" value="GE-882910"/>
</div>
</div>
<div class="pt-md flex justify-end">
<button class="bg-primary text-on-primary font-bold px-lg py-sm rounded-lg shadow-lg shadow-primary/20 hover:scale-95 active:scale-90 transition-all duration-200" type="submit">
                                Guardar Cambios
                            </button>
</div>
</form>
</div>
<!-- Security Section Card -->
<div class="bg-surface-container-lowest rounded-xl p-lg shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)] border border-primary/10">
<div class="flex items-center gap-sm mb-lg border-b border-primary/10 pb-sm">
<span class="material-symbols-outlined text-primary">security</span>
<h2 class="font-h2 text-h3 text-on-surface">Seguridad de la Cuenta</h2>
</div>
<div class="space-y-md">
<h3 class="font-h3 text-body-md font-bold text-on-surface-variant mb-sm">Cambiar Contraseña</h3>
<div class="space-y-md">
<div class="space-y-xs">
<label class="font-label-caps text-on-surface-variant uppercase">Contraseña Actual</label>
<div class="relative">
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-sm cursor-not-allowed outline-none" disabled="" placeholder="••••••••" type="password"/>
<span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant cursor-not-allowed">visibility</span>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="space-y-xs">
<label class="font-label-caps text-on-surface-variant uppercase">Nueva Contraseña</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-sm cursor-not-allowed outline-none" disabled="" placeholder="Solo lectura" type="password"/>
</div>
<div class="space-y-xs">
<label class="font-label-caps text-on-surface-variant uppercase">Confirmar Nueva Contraseña</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-sm cursor-not-allowed outline-none" disabled="" placeholder="Solo lectura" type="password"/>
</div>
</div>
</div>
<div class="pt-md flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant flex items-center gap-xs">
<span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                Último cambio hace 3 meses
                            </span>
<button class="bg-primary/20 text-primary font-bold px-lg py-sm rounded-lg cursor-not-allowed" disabled="" type="button">
                                Actualizar Contraseña
                            </button>
</div>
</div>
</div>
<!-- Removed Sessions Section as requested -->
<!-- Removed Banners as requested -->
</div>
</main>
</div>
<!-- Mobile Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-primary/10 px-gutter py-xs flex justify-around items-center z-50 glass-nav">
<a class="flex flex-col items-center gap-xs text-on-surface-variant p-xs" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined">search</span>
<span class="text-[10px]">Explorar</span>
</a>
<a class="flex flex-col items-center gap-xs text-on-surface-variant p-xs" href="{{ route('reservas') }}">
<span class="material-symbols-outlined">calendar_today</span>
<span class="text-[10px]">Reservas</span>
</a>
<a class="flex flex-col items-center gap-xs text-primary font-bold p-xs" href="{{ route('perfil') }}">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
<span class="text-[10px]">Perfil</span>
</a>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>