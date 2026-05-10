<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bienvenido a GoToEat</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#93ccff",
                        "inverse-on-surface": "#eaf1ff",
                        "tertiary-fixed": "#cde5ff",
                        "secondary-container": "#6cf8bb",
                        "on-background": "#0b1c30",
                        "on-primary-fixed-variant": "#783200",
                        "error": "#ba1a1a",
                        "surface-container-low": "#eff4ff",
                        "on-secondary-container": "#00714d",
                        "on-surface": "#0b1c30",
                        "on-secondary-fixed-variant": "#005236",
                        "primary": "#9d4300",
                        "background": "#f8f9ff",
                        "surface-container-high": "#dce9ff",
                        "inverse-surface": "#213145",
                        "on-error-container": "#93000a",
                        "primary-fixed-dim": "#ffb690",
                        "on-secondary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-surface-variant": "#584237",
                        "on-tertiary-fixed-variant": "#004b74",
                        "on-secondary-fixed": "#002113",
                        "on-primary-fixed": "#341100",
                        "on-primary-container": "#582200",
                        "surface-container-highest": "#d3e4fe",
                        "inverse-primary": "#ffb690",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-container": "#003554",
                        "tertiary-container": "#00a2f4",
                        "surface": "#f8f9ff",
                        "on-primary": "#ffffff",
                        "primary-fixed": "#ffdbca",
                        "surface-tint": "#9d4300",
                        "on-tertiary-fixed": "#001d32",
                        "surface-bright": "#f8f9ff",
                        "tertiary": "#006398",
                        "surface-dim": "#cbdbf5",
                        "secondary": "#006c49",
                        "surface-container": "#e5eeff",
                        "primary-container": "#f97316",
                        "outline-variant": "#e0c0b1",
                        "outline": "#8c7164",
                        "surface-variant": "#d3e4fe",
                        "secondary-fixed-dim": "#4edea3",
                        "on-error": "#ffffff",
                        "secondary-fixed": "#6ffbbe",
                        "on-tertiary": "#ffffff"
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
                        "container_max": "1280px",
                        "base": "4px",
                        "xl": "64px",
                        "sm": "16px",
                        "xs": "8px",
                        "md": "24px"
                    },
                    "fontFamily": {
                        "body-sm": ["Inter"],
                        "h3": ["Sora"],
                        "h1": ["Sora"],
                        "h2": ["Sora"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "label-caps": ["Inter"]
                    },
                    "fontSize": {
                        "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                        "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }
        .celebration-sparkle {
            background-image: radial-gradient(circle, #f97316 10%, transparent 10.5%);
            background-size: 40px 40px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-surface">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md shadow-sm border-b border-outline-variant/30">
<div class="flex justify-between items-center h-20 px-md md:px-xl max-w-container_max mx-auto">
<div class="text-h3 font-h1 font-bold text-primary dark:text-primary-fixed-dim">GoToEat</div>
</div>
</nav>
<!-- Main Content -->
<main class="pt-20">
<!-- Hero Section -->
<section class="relative min-h-[870px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 hero-gradient" data-alt="A cinematic, high-angle shot of a vibrant restaurant interior filled with diverse people enjoying exquisite, colorful Mediterranean dishes. The lighting is warm and golden, casting soft glows on polished wooden tables and sparkling crystal glassware. The atmosphere is energetic and celebratory, with a shallow depth of field focusing on a beautifully plated pasta dish in the foreground, using a palette of rich oranges, deep greens, and creamy whites."></div>
<div class="absolute inset-0 celebration-sparkle pointer-events-none"></div>
<div class="relative z-10 max-w-4xl px-md text-center text-white">
<div class="inline-flex items-center gap-xs bg-primary-container/90 px-sm py-1 rounded-full mb-md backdrop-blur-sm border border-white/20">
<span class="material-symbols-outlined text-[18px]">celebration</span>
<span class="font-label-caps text-label-caps uppercase tracking-widest">Ya eres parte de la comunidad</span>
</div>
<h1 class="font-h1 text-h1 mb-sm drop-shadow-lg">
                    ¡Tu viaje culinario comienza aquí, {{ auth()->user()->name }}!
                </h1>
<p class="font-body-lg text-body-lg mb-xl opacity-90 max-w-2xl mx-auto drop-shadow-md">
                    Bienvenido a la familia GoToEat. Ahora formas parte de una comunidad global de apasionados por la gastronomía que celebra cada bocado, cada aroma y cada momento compartido en la mesa.
                </p>
<div class="flex flex-col sm:flex-row gap-md justify-center items-center">
<a href="{{ route('dashboard') }}" class="inline-block bg-primary-container text-on-primary font-h3 text-h3 px-xl py-md rounded-xl shadow-2xl hover:shadow-primary/40 active:scale-[0.98] transition-all duration-300 group">
                        ¡Estás a punto de encontrar tu restaurante favorito!
                        <span class="material-symbols-outlined align-middle ml-xs group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
</div>
<!-- Floating Decoration Cards (Bento Style Accents) -->
<div class="absolute bottom-xl left-xl hidden lg:block">
<div class="bg-surface-container-lowest/10 backdrop-blur-xl border border-white/20 p-md rounded-xl flex items-center gap-sm shadow-2xl">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div>
<p class="text-white font-bold text-body-md">Sabor Premium</p>
<p class="text-white/70 text-body-sm">Seleccionado para ti</p>
</div>
</div>
</div>
</section>
<!-- Welcome Journey / Features -->
<section class="py-xl px-md bg-surface">
<div class="max-w-container_max mx-auto">
<div class="text-center mb-xl">
<h2 class="font-h2 text-h2 text-on-surface mb-xs">¿Qué sigue en tu aventura?</h2>
<div class="w-20 h-1.5 bg-primary-container mx-auto rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<!-- Feature Card 1 -->
<div class="bg-surface-container-lowest p-xl rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-primary/10 rounded-lg flex items-center justify-center mb-md">
<span class="material-symbols-outlined text-primary text-[32px]">restaurant_menu</span>
</div>
<h3 class="font-h3 text-h3 mb-sm">Explora el Mapa</h3>
<p class="text-on-surface-variant font-body-md">Descubre joyas ocultas y los restaurantes más premiados cerca de tu ubicación actual.</p>
</div>
<!-- Feature Card 2 -->
<div class="bg-surface-container-lowest p-xl rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-secondary/10 rounded-lg flex items-center justify-center mb-md">
<span class="material-symbols-outlined text-secondary text-[32px]">favorite</span>
</div>
<h3 class="font-h3 text-h3 mb-sm">Guarda Favoritos</h3>
<p class="text-on-surface-variant font-body-md">Crea tu propia lista de deseos culinarios y no pierdas nunca ese lugar que quieres visitar.</p>
</div>
<!-- Feature Card 3 -->
<div class="bg-surface-container-lowest p-xl rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-tertiary/10 rounded-lg flex items-center justify-center mb-md">
<span class="material-symbols-outlined text-tertiary text-[32px]">confirmation_number</span>
</div>
<h3 class="font-h3 text-h3 mb-sm">Reserva al Instante</h3>
<p class="text-on-surface-variant font-body-md">Sin llamadas, sin esperas. Reserva tu mesa en segundos directamente desde la app.</p>
</div>
</div>
</div>
</section>
<!-- Stats / Trust Social Proof -->
</main>
<!-- Footer -->
<footer class="w-full py-xl bg-surface-container-lowest dark:bg-inverse-surface mt-auto">
<div class="flex flex-col md:flex-row justify-between items-center gap-md px-md max-w-container_max mx-auto">
<div class="flex flex-col gap-xs items-center md:items-start">
<div class="font-h3 text-h3 font-bold text-primary dark:text-primary-fixed-dim">GoToEat</div>
<p class="font-body-sm text-body-sm text-on-surface-variant dark:text-surface-variant">© 2024 GoToEat. Buen provecho.</p>
</div>
<div class="flex gap-md">
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Privacidad</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Términos</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Soporte</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Contacto</a>
</div>
</div>
</footer>
</body></html>
