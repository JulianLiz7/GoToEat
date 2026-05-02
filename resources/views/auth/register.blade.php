<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Registro GoToEat - Gestión de Restaurantes</title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-step-shadow {
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen flex flex-col">
<!-- Top Navigation (Shell Implementation) -->
<header class="sticky top-0 w-full z-40 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm flex justify-between items-center h-16 px-8">
<div class="flex items-center gap-2">
<span class="text-2xl font-black text-primary-container tracking-tight font-h2">GoToEat</span>
</div>
</header>
<main class="flex-grow flex items-center justify-center py-xl px-sm">
<div class="max-w-container_max w-full grid grid-cols-1 lg:grid-cols-12 gap-xl items-start">
<!-- Left Branding/Intro Column -->
<div class="lg:col-span-5 space-y-lg hidden lg:block">
<div class="bg-white p-lg rounded-xl shadow-lg border border-gray-50">
<h1 class="font-h1 text-h1 text-on-background mb-md">Bienvenido al futuro de la gestión gastronómica.</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-lg">Únete a más de 5.000 propietarios de restaurantes que han optimizado sus operaciones, aumentado sus ingresos y deleitado a sus clientes con GoToEat.</p>
<div class="relative rounded-xl overflow-hidden aspect-video shadow-2xl">
<img alt="Restaurant Interior" class="w-full h-full object-cover" data-alt="A high-end modern restaurant interior during golden hour, featuring sleek wooden tables and designer lighting. The atmosphere is warm and professional, with a clean architectural aesthetic. Soft bokeh highlights the premium dining setting with accents of orange and deep earth tones, reflecting the GoToEat brand identity." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWcnLASfKpwzTjigDLVfUl9vNiKBS-cWbGXktmYkjnT2b9zpStKqik-OywAQQG0hdM9hiirk0KK9VzE5Lr50PMf7tpvCOI748DkDsZjZqRnXA4apCnsevIC1dKo7jgulhT1Cod9Q1GemYsn8TgpiTBUlYI-IFKzwg5zuh17eDrF0gHS4jGQb-Rx6VcTcqBOGJO0bOeTKR6xb8RNmm-MCGul1zqgIkHLmx8B_9vlfyClsGpXJK35ofb79jotgD5O1o1DvNtfPDv5vE"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-md">
<span class="text-white font-h3 text-h3">Gestión Digital, Conexión Humana.</span>
</div>
</div>
<div class="mt-lg flex items-center gap-md">
<div class="flex -space-x-4">
<img alt="User" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a smiling professional restaurant manager in a modern kitchen setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK2psOlMJY90O6CpTuDwgE_gk9abaw3XHjiEM0l73xdUMxU8jvcHkIKmJrrCswieWgLROOrPnwrLxrsNH8W_uYbXCIShO5rjBEO2hUYkBRF227qvkyKprnOOkKNmMZ8mum7UOxSDbRWXsKjPR4IO_hwl8zykPCNbCBpN1decUUORuZL09YPElTXpQPtUplM36UIlcqHtlt-Dir5iSwtWCNVAWuuK7LhIw7zHAh_GkMRq4WIl2-iKSULZpZtRo8oJT68f3CljXRj6w"/>
<img alt="User" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a confident female chef in a professional chef's coat." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG1nVoBbxK-1KewW008LWkdyjToQ3b7ObjPnm6ZFATCGglJujhuzLecJ9IIvWxU0eDOS20Akc2XKI3Mon9PwjN_rVKY2W2293vs6oyHhwTC-ESjg9sGcybjsPsll_0SXl2GRWymYWvX43tnw0Jk_QMCvvw7YOnhFq9knodWzVNLtvkPVNxMiRY-JgxHsbJYovqlgHYJs2v_nv2e2LgsJWIuwrrhiz2ppNm5esuqISs4mVeifj2_JrZRDjUeKa1erRgQFE_FoKzMSc"/>
<img alt="User" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a friendly waiter in a high-end restaurant." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAg5haTRnpQuxr80D0FlpTfI9z1G9jbjHG2fmHMknPz4c6fYz0LLEtRbaietV9iJ2arGvugODvDyrZ7nLxeLeyl1VkjLXM0mpTb51CBZZsqsg3JI17ypUfG9AeIbqZyfZ9q5RAVOVE3padj5YstSlVOsFzzS4Jb4kzHEwOK4SkzRq3T_Ec1aLyS3cMUXcQ7_3TDKZfprDw1x5rYQkzub1qqmMEjhYSiDaqBQjd0ZHMB9e5p-TGqbc-bIZ_DgyzLrgOidzrvpNlJUNg"/>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant">Confiado por líderes culinarios a nivel mundial.</p>
</div>
</div>
</div>
<!-- Right Form Column -->
<div class="lg:col-span-7 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
<!-- Stepper -->
<div class="bg-surface-container-low px-lg py-md border-b border-gray-100">
<div class="flex items-center justify-between max-w-md mx-auto">
<div class="flex flex-col items-center gap-2">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold active-step-shadow">1</div>
<span class="text-label-caps font-label-caps text-primary-container">DETALLES</span>
</div>
<div class="h-px bg-gray-200 flex-grow mx-4 mt-[-20px]"></div>
<div class="flex flex-col items-center gap-2">
<div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">2</div>
<span class="text-label-caps font-label-caps text-gray-400">MENÚ</span>
</div>
<div class="h-px bg-gray-200 flex-grow mx-4 mt-[-20px]"></div>
<div class="flex flex-col items-center gap-2">
<div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">3</div>
<span class="text-label-caps font-label-caps text-gray-400">EQUIPO</span>
</div>
</div>
</div>
<!-- Form Content -->
<div class="p-lg md:p-xl">
<div class="mb-lg">
<h2 class="font-h2 text-h3 text-on-background mb-xs">Detalles del Restaurante</h2>
<p class="font-body-md text-on-surface-variant">Cuéntanos sobre tu establecimiento para personalizar tu experiencia.</p>
</div>
<form action="{{ route('register.step2') }}" method="GET" class="space-y-md">
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="col-span-2">
<label class="block text-label-caps font-label-caps text-on-surface-variant mb-xs">NOMBRE DEL RESTAURANTE</label>
<div class="relative">
<input class="w-full px-md py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all font-body-md text-on-surface" placeholder="ej. El Tenedor de Oro" type="text"/>
<span class="material-symbols-outlined absolute right-3 top-3 text-gray-400" data-icon="restaurant">restaurant</span>
</div>
</div>
<div>
<label class="block text-label-caps font-label-caps text-on-surface-variant mb-xs">CATEGORÍA</label>
<select class="w-full px-md py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all font-body-md text-on-surface appearance-none bg-white">
<option>Fine Dining</option>
<option>Casual Dining</option>
<option>Fast Food</option>
<option>Cafe / Bakery</option>
<option>Bar / Pub</option>
</select>
</div>
<div>
<label class="block text-label-caps font-label-caps text-on-surface-variant mb-xs">TIPO DE COCINA</label>
<select class="w-full px-md py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all font-body-md text-on-surface appearance-none bg-white">
<option>Italian</option>
<option>Mexican</option>
<option>Japanese</option>
<option>American</option>
<option>Mediterranean</option>
</select>
</div>
</div>
<!-- Map Preview (Asymmetric Design Element) -->
<div class="pt-lg flex items-center justify-between">
<a href="{{ route('login') }}" class="text-on-surface-variant font-semibold text-body-sm hover:text-on-surface transition-colors flex items-center gap-xs">
<span class="material-symbols-outlined text-sm" data-icon="arrow_back">arrow_back</span>
                                Volver
                            </a>
<button class="bg-primary-container text-white px-xl py-3 rounded-lg font-bold text-body-md hover:opacity-90 active:scale-[0.98] transition-all shadow-lg flex items-center gap-sm" type="submit">Continuar a Configuración del Menú <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span></button>
</div>
</form>
</div>
<!-- Trust Footer -->
<div class="px-lg py-md bg-gray-50 flex items-center justify-center gap-lg border-t border-gray-100">
<div class="flex items-center gap-xs grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all">
<span class="material-symbols-outlined" data-icon="verified_user">verified_user</span>
<span class="text-label-caps font-label-caps">DATOS SEGUROS</span>
</div>
<div class="flex items-center gap-xs grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all">
<span class="material-symbols-outlined" data-icon="support_agent">support_agent</span>
<span class="text-label-caps font-label-caps">SOPORTE 24/7</span>
</div>
</div>
</div>
</div>
</main>
<footer class="py-lg px-8 bg-white border-t border-gray-100 mt-xl">
<div class="max-w-container_max mx-auto flex flex-col md:flex-row justify-between items-center gap-md">
<div class="text-gray-400 text-body-sm font-body-sm">© 2024 GoToEat. Todos los derechos reservados. Gestión precisa para hospitalidad de primer nivel.</div>
<div class="flex gap-md">
<a class="text-gray-500 hover:text-primary-container text-body-sm font-body-sm" href="#">Política de Privacidad</a>
<a class="text-gray-500 hover:text-primary-container text-body-sm font-body-sm" href="#">Términos de Servicio</a>
<a class="text-gray-500 hover:text-primary-container text-body-sm font-body-sm" href="#">Cookies</a>
</div>
</div>
</footer>
</body></html>