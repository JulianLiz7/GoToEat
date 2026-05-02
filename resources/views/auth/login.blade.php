<!DOCTYPE html>

<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Iniciar Sesión | GoToEat</title>
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
                        "tertiary-fixed": "#cde5ff",
                        "primary-fixed": "#ffdbca",
                        "surface-dim": "#cbdbf5",
                        "on-primary-container": "#582200",
                        "surface-bright": "#f8f9ff",
                        "secondary-fixed-dim": "#4edea3",
                        "on-background": "#0b1c30",
                        "inverse-on-surface": "#eaf1ff",
                        "error-container": "#ffdad6",
                        "primary": "#9d4300",
                        "tertiary-container": "#00a2f4",
                        "on-surface-variant": "#584237",
                        "on-secondary-fixed": "#002113",
                        "on-surface": "#0b1c30",
                        "on-tertiary-fixed": "#001d32",
                        "inverse-primary": "#ffb690",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#dce9ff",
                        "surface-container-low": "#eff4ff",
                        "on-primary": "#ffffff",
                        "on-secondary-container": "#00714d",
                        "secondary": "#006c49",
                        "on-error-container": "#93000a",
                        "outline": "#8c7164",
                        "surface-container": "#e5eeff",
                        "on-tertiary": "#ffffff",
                        "outline-variant": "#e0c0b1",
                        "on-secondary-fixed-variant": "#005236",
                        "surface-tint": "#9d4300",
                        "on-primary-fixed": "#341100",
                        "tertiary": "#006398",
                        "surface-container-highest": "#d3e4fe",
                        "secondary-container": "#6cf8bb",
                        "inverse-surface": "#213145",
                        "primary-container": "#f97316",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#783200",
                        "on-tertiary-container": "#003554",
                        "background": "#f8f9ff",
                        "surface": "#f8f9ff",
                        "tertiary-fixed-dim": "#93ccff",
                        "secondary-fixed": "#6ffbbe",
                        "error": "#ba1a1a",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed-variant": "#004b74",
                        "surface-variant": "#d3e4fe",
                        "primary-fixed-dim": "#ffb690"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "xs": "8px",
                        "md": "24px",
                        "lg": "40px",
                        "xl": "64px",
                        "gutter": "24px",
                        "container_max": "1280px",
                        "base": "4px",
                        "sm": "16px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "h2": ["Sora"],
                        "body-sm": ["Inter"],
                        "label-caps": ["Inter"],
                        "h3": ["Sora"],
                        "h1": ["Sora"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                        "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
<style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .perspective-container {
            perspective: 1000px;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-surface-variant font-body-md text-on-surface selection:bg-primary-fixed selection:text-on-primary-container">
<main class="min-h-screen flex items-center justify-center p-sm md:p-lg relative overflow-hidden">
<!-- Background decorative elements -->
<div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden z-0">
<div class="absolute -top-1/4 -right-1/4 w-[600px] h-[600px] bg-secondary-container opacity-20 rounded-full blur-[100px]"></div>
<div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-primary-container opacity-10 rounded-full blur-[100px]"></div>
</div>
<div class="w-full max-w-[480px] z-10 perspective-container">
<!-- Login Card -->
<div class="glass-card rounded-xl p-lg md:p-xl shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-white/40">
<!-- Logo & Header -->
<div class="text-center mb-xl">
<div class="inline-flex items-center gap-xs mb-md">
<div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center shadow-lg shadow-primary-container/20">
<span class="material-symbols-outlined text-white text-2xl">restaurant_menu</span>
</div>
<span class="font-h2 text-h3 text-primary-container tracking-tight font-black">GoToEat</span>
</div>
<h1 class="font-h2 text-h3 text-on-background mb-base">Bienvenido de nuevo</h1>
<p class="font-body-sm text-on-surface-variant">Accede a tu panel de gestión de restaurante</p>
</div>
<!-- Login Form -->
<form action="{{ route('dashboard') }}" method="GET" class="space-y-md">
<!-- Email Input -->
<div class="space-y-xs">
<label class="font-label-caps text-on-surface-variant block uppercase" for="email">Correo Electrónico</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">mail</span>
<input class="w-full pl-[48px] pr-sm py-md bg-surface-bright border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container transition-all outline-none text-body-md" id="email" placeholder="nombre@restaurante.com" type="email"/>
</div>
</div>
<!-- Password Input -->
<div class="space-y-xs">
<label class="font-label-caps text-on-surface-variant block uppercase" for="password">Contraseña</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">lock</span>
<input class="w-full pl-[48px] pr-sm py-md bg-surface-bright border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container transition-all outline-none text-body-md" id="password" placeholder="••••••••" type="password"/>
</div>
</div>
<!-- Options -->
<div class="flex items-center justify-between py-xs">
<label class="flex items-center gap-xs cursor-pointer group">
<div class="relative flex items-center">
<input class="peer appearance-none w-5 h-5 border border-outline-variant rounded bg-surface-bright checked:bg-primary-container checked:border-primary-container transition-all cursor-pointer" type="checkbox"/>
<span class="material-symbols-outlined absolute text-white text-sm scale-0 peer-checked:scale-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transition-transform pointer-events-none">check</span>
</div>
<span class="font-body-sm text-on-surface-variant group-hover:text-primary transition-colors">Recordarme</span>
</label>
<a class="font-body-sm font-semibold text-primary hover:text-primary-container transition-colors" href="#">¿Olvidaste tu contraseña?</a>
</div>
<!-- Submit Button -->
<button class="w-full py-md bg-primary-container text-white font-h3 text-body-md rounded-lg shadow-lg shadow-primary-container/20 hover:shadow-xl hover:shadow-primary-container/30 active:scale-[0.98] transition-all flex items-center justify-center gap-xs" type="submit">
                        Iniciar Sesión
                        <span class="material-symbols-outlined text-xl">login</span>
</button>
</form>
<!-- Footer Links -->
<div class="mt-xl pt-lg border-t border-outline-variant/30 text-center">
<p class="font-body-sm text-on-surface-variant">
                        ¿No tienes una cuenta? 
                        <a class="font-semibold text-secondary hover:text-on-secondary-container transition-colors" href="{{ route('register') }}">Registrarse Gratis</a>
</p>
</div>
</div>
<!-- Trust Badge or Minimal Graphic -->
<div class="mt-lg flex items-center justify-center gap-md grayscale opacity-50">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-body-sm">verified_user</span>
<span class="font-label-caps text-[10px]">Secure 256-bit SSL</span>
</div>
<div class="h-1 w-1 bg-outline-variant rounded-full"></div>
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-body-sm">shield</span>
<span class="font-label-caps text-[10px]">GDPR Compliant</span>
</div>
</div>
</div>
<!-- Right Side Graphic (Subtle) -->
<div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/3 w-[600px] h-[400px] rotate-12">
<div class="w-full h-full bg-white rounded-[40px] shadow-2xl border border-white/50 overflow-hidden relative">
<img class="w-full h-full object-cover" data-alt="A clean and professional digital management interface displayed on a high-resolution screen with soft, bright light-mode styling. The screen features organized data charts and restaurant analytics using a palette of vibrant oranges and deep emerald greens. The setting is a blurred, high-end modern office environment with natural daylight, conveying a sense of premium administrative speed and reliability for the hospitality industry." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2kwzjb_YA8VWEICrQh0ZZu0qTWXJbJUxxudcA4-G-hzwRs8lSbRqMftkyRMwknkXuuLhPnFYloNAbX5xNy5LmwU8y5jqsV7UKgtKiUeRdU_EUHYs8Ev1Kn-a5w5WLoEmow3eGjmizarGjtUtU4iJjxnJI53bHIstxkZyYj8w16wCD8x1zoEqjvDQlyR-7Fpl-5zVrIKk-29LZEhCYtOvb_rrOTm8LHDwwTYANiLGYzLnkhPSzCX5JGAaa7ZweB0y2qzBBE69kIpQ"/>
<div class="absolute inset-0 bg-gradient-to-tr from-primary-container/20 to-transparent"></div>
</div>
</div>
</main>
</body></html>