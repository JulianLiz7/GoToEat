<!DOCTYPE html>

<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Registro de Equipo</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "primary-fixed-dim": "#ffb690",
                      "secondary-fixed": "#6ffbbe",
                      "background": "#f8f9ff",
                      "on-primary-fixed": "#341100",
                      "on-tertiary": "#ffffff",
                      "inverse-on-surface": "#eaf1ff",
                      "on-primary": "#ffffff",
                      "on-error-container": "#93000a",
                      "secondary-fixed-dim": "#4edea3",
                      "surface-dim": "#cbdbf5",
                      "outline": "#8c7164",
                      "surface-container-lowest": "#ffffff",
                      "on-secondary": "#ffffff",
                      "surface-container-low": "#eff4ff",
                      "on-primary-fixed-variant": "#783200",
                      "surface-variant": "#d3e4fe",
                      "primary-container": "#f97316",
                      "on-tertiary-container": "#003554",
                      "surface-container": "#e5eeff",
                      "surface": "#f8f9ff",
                      "tertiary-container": "#00a2f4",
                      "on-background": "#0b1c30",
                      "outline-variant": "#e0c0b1",
                      "on-surface": "#0b1c30",
                      "on-secondary-fixed": "#002113",
                      "error-container": "#ffdad6",
                      "primary": "#9d4300",
                      "tertiary": "#006398",
                      "surface-tint": "#9d4300",
                      "tertiary-fixed": "#cde5ff",
                      "tertiary-fixed-dim": "#93ccff",
                      "primary-fixed": "#ffdbca",
                      "on-surface-variant": "#584237",
                      "on-error": "#ffffff",
                      "inverse-surface": "#213145",
                      "surface-bright": "#f8f9ff",
                      "on-secondary-fixed-variant": "#005236",
                      "surface-container-highest": "#d3e4fe",
                      "inverse-primary": "#ffb690",
                      "on-primary-container": "#582200",
                      "on-secondary-container": "#00714d",
                      "on-tertiary-fixed-variant": "#004b74",
                      "on-tertiary-fixed": "#001d32",
                      "error": "#ba1a1a",
                      "secondary": "#006c49",
                      "surface-container-high": "#dce9ff",
                      "secondary-container": "#6cf8bb"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "md": "24px",
                      "container_max": "1280px",
                      "base": "4px",
                      "xl": "64px",
                      "xs": "8px",
                      "sm": "16px",
                      "lg": "40px",
                      "gutter": "24px"
              },
              "fontFamily": {
                      "h2": ["Sora"],
                      "label-caps": ["Inter"],
                      "h3": ["Sora"],
                      "h1": ["Sora"],
                      "body-sm": ["Inter"],
                      "body-md": ["Inter"],
                      "body-lg": ["Inter"]
              },
              "fontSize": {
                      "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                      "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}]
              }
            },
          },
        }
      </script>
</head>
<body class="bg-background text-on-background font-body-md antialiased">
<!-- TopNavBar -->
<nav class="sticky top-0 w-full z-50 border-b border-gray-100 bg-white/80 backdrop-blur-md shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)]">
<div class="flex justify-between items-center px-8 py-4 max-w-[1280px] mx-auto">
<div class="text-2xl font-black tracking-tight text-orange-600 font-h1">GoToEat</div>
</div>
</nav>
<main class="max-w-[1280px] mx-auto px-8 py-xl">
<!-- Progress Indicator -->
<div class="mb-lg max-w-4xl mx-auto">
<div class="flex items-center justify-between relative">
<div class="absolute top-1/2 left-0 w-full h-0.5 bg-primary-fixed -z-10 transform -translate-y-1/2"></div>
<div class="flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold mb-2">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<span class="text-label-caps font-label-caps text-primary-container font-bold">DETALLES</span>
</div>
<div class="flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold mb-2">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<span class="text-label-caps font-label-caps text-primary-container font-bold">MENÚ</span>
</div>
<div class="flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold mb-2">
                        3
                    </div>
<span class="text-label-caps font-label-caps text-primary-container font-bold">EQUIPO</span>
</div>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
<!-- Left Side: Visual/Descriptive Area -->
<div class="lg:col-span-5 space-y-md sticky top-32">
<div class="relative rounded-3xl overflow-hidden aspect-[4/5] shadow-xl group">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="A professional restaurant staff meeting in a bright, modern dining room. The team consists of diverse waiters and chefs in clean uniforms discussing service strategy. High-key natural lighting illuminates the scene, reinforcing a sense of teamwork and professional excellence in the hospitality industry. The color palette is dominated by warm wood tones and clean white surfaces." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBH-MJQGlLoVU4Ht7pozoBTpPxGY5897YYxUOFnMv7mTnpgxfSfnL73gI6INvjuZ4w3iipa_PwtqTT0fi7cyvERjvsvFxFuZt502DENYLLOuiE45B1j9NOfBN4Z43v2PxlNP5gF6tM-QJdQp23ikCXBl-yGZcjn-hArSooEVV8-E2Pi3Y7cz5MZaScDOjvunY7dozoK7XvPS6vx4fh_KlJW6d9RyolfigzGVi62zts4WolsC7f0-LTRucaDT2cTIqlItjWFw3Gg9Sk"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-lg">
<h2 class="text-white font-h2 text-h2 mb-sm">Construye tu equipo de alto rendimiento</h2>
<p class="text-white/80 font-body-lg">Asigna roles específicos y gestiona permisos para que cada miembro brinde su mejor servicio desde el primer día.</p>
</div>
</div>
<div class="bg-surface-container-low p-md rounded-2xl border border-surface-container">
<div class="flex gap-sm">
<div class="w-10 h-10 bg-tertiary-container/20 text-tertiary rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined">security</span>
</div>
<div>
<h4 class="font-h3 text-body-md font-bold mb-1">Roles y Permisos</h4>
<p class="text-on-surface-variant text-body-sm">Controla quién accede a las finanzas, el inventario o simplemente la toma de pedidos.</p>
</div>
</div>
</div>
</div>
<!-- Right Side: Form Card -->
<div class="lg:col-span-7 bg-white p-lg rounded-3xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)] border border-gray-100">
<div class="mb-lg">
<h1 class="font-h1 text-h2 text-slate-900 mb-2">Gestiona tu Equipo</h1>
<p class="text-slate-500 font-body-md">Envía invitaciones a tus colaboradores para que se unan a la plataforma.</p>
</div>
<!-- Invite Collaborator Form -->
<section class="mb-xl">
<h3 class="font-h3 text-h3 mb-md flex items-center gap-2">
<span class="material-symbols-outlined text-primary-container">person_add</span>
                        Invitar Colaborador
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="space-y-2">
<label class="block text-label-caps font-label-caps text-slate-600">Nombre</label>
<input class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all" placeholder="Ej. Juan Pérez" type="text"/>
</div>
<div class="space-y-2">
<label class="block text-label-caps font-label-caps text-slate-600">Correo Electrónico</label>
<input class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all" placeholder="juan@ejemplo.com" type="email"/>
</div>
<div class="md:col-span-2 space-y-2">
<label class="block text-label-caps font-label-caps text-slate-600">Rol del Equipo</label>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-sm">
<label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-orange-50 transition-colors has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
<input class="hidden peer" name="role" type="radio"/>
<div class="flex flex-col">
<span class="font-bold text-slate-900">Mesero</span>
<span class="text-xs text-slate-500">Pedidos y mesas</span>
</div>
<span class="absolute top-2 right-2 material-symbols-outlined text-orange-500 opacity-0 peer-checked:opacity-100">check_circle</span>
</label>
<label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-orange-50 transition-colors has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
<input class="hidden peer" name="role" type="radio"/>
<div class="flex flex-col">
<span class="font-bold text-slate-900">Cocinero</span>
<span class="text-xs text-slate-500">Gestión de cocina</span>
</div>
<span class="absolute top-2 right-2 material-symbols-outlined text-orange-500 opacity-0 peer-checked:opacity-100">check_circle</span>
</label>
<label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-orange-50 transition-colors has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
<input class="hidden peer" name="role" type="radio"/>
<div class="flex flex-col">
<span class="font-bold text-slate-900">Administrador</span>
<span class="text-xs text-slate-500">Control total</span>
</div>
<span class="absolute top-2 right-2 material-symbols-outlined text-orange-500 opacity-0 peer-checked:opacity-100">check_circle</span>
</label>
</div>
</div>
</div>
<button class="mt-lg w-full md:w-auto bg-primary-container text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-orange-500/20 hover:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
<span class="material-symbols-outlined">send</span>
                        Enviar Invitación
                    </button>
</section>
<!-- Invitations Sent List -->
<div class="mt-xl">
<div class="flex items-center justify-between mb-md">
<h3 class="font-h3 text-h3">Invitaciones Enviadas</h3>
<span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">0 PENDIENTES</span>
</div>
<div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-lg flex flex-col items-center justify-center text-center">
<div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-sm">
<span class="material-symbols-outlined text-slate-300">mail</span>
</div>
<p class="text-slate-500 font-body-md">Aún no has enviado invitaciones</p>
</div>
</div><div class="mt-xl flex justify-between items-center pt-lg border-t border-gray-100">
<a href="{{ route('register.step2') }}" class="text-slate-500 font-bold flex items-center gap-2 hover:text-slate-700 transition-colors">
<span class="material-symbols-outlined">arrow_back</span>
                        Atrás
                    </a>
<a href="{{ route('register.welcome') }}" class="bg-primary-container text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-orange-500/20 hover:scale-[0.98] transition-all duration-200 font-h2 inline-block">Finalizar</a>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="w-full border-t border-gray-200 bg-slate-50 font-body-sm">
<div class="flex flex-col md:flex-row justify-between items-center py-12 px-8 max-w-[1280px] mx-auto">
<div class="mb-8 md:mb-0">
<div class="font-bold text-slate-900 mb-2">GoToEat</div>
<p class="text-slate-400">© 2024 GoToEat. Potenciando la gastronomía moderna.</p>
</div>
<div class="flex gap-8">
<a class="text-slate-400 hover:text-orange-500 transition-colors" href="#">Privacidad</a>
<a class="text-slate-400 hover:text-orange-500 transition-colors" href="#">Soporte</a>
<a class="text-slate-400 hover:text-orange-500 transition-colors" href="#">Términos</a>
</div>
</div>
</footer>
</body></html>