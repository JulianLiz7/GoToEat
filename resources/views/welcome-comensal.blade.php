<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bienvenido a GoToEat</title>
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body-md text-on-surface">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md shadow-sm border-b border-outline-variant/30">
<div class="flex justify-between items-center h-20 px-6 md:px-16 max-w-container_max mx-auto">
<div class="text-h3 font-h1 font-bold text-primary dark:text-primary-fixed-dim">GoToEat</div>
</div>
</nav>
<!-- Main Content -->
<main class="pt-20">
<!-- Hero Section -->
<section class="relative min-h-[870px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 hero-gradient" data-alt="A cinematic, high-angle shot of a vibrant restaurant interior filled with diverse people enjoying exquisite, colorful Mediterranean dishes. The lighting is warm and golden, casting soft glows on polished wooden tables and sparkling crystal glassware. The atmosphere is energetic and celebratory, with a shallow depth of field focusing on a beautifully plated pasta dish in the foreground, using a palette of rich oranges, deep greens, and creamy whites."></div>
<div class="absolute inset-0 celebration-sparkle pointer-events-none"></div>
<div class="relative z-10 max-w-4xl px-6 text-center text-white">
<div class="inline-flex items-center gap-2 bg-primary-container/90 px-4 py-1 rounded-full mb-6 backdrop-blur-sm border border-white/20">
<span class="material-symbols-outlined text-[18px]">celebration</span>
<span class="font-label-caps text-label-caps uppercase tracking-widest">Ya eres parte de la comunidad</span>
</div>
<h1 class="font-h1 text-h1 mb-4 drop-shadow-lg">
                    ¡Tu viaje culinario comienza aquí, {{ auth()->user()->name }}!
                </h1>
<p class="font-body-lg text-body-lg mb-16 opacity-90 max-w-2xl mx-auto drop-shadow-md">
                    Bienvenido a la familia GoToEat. Ahora formas parte de una comunidad global de apasionados por la gastronomía que celebra cada bocado, cada aroma y cada momento compartido en la mesa.
                </p>
<div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
<a href="{{ route('dashboard') }}" class="inline-block bg-primary-container text-on-primary font-h3 text-h3 px-16 py-6 rounded-xl shadow-2xl hover:shadow-primary/40 active:scale-[0.98] transition-all duration-300 group">
                        ¡Estás a punto de encontrar tu restaurante favorito!
                        <span class="material-symbols-outlined align-middle ml-2 group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
</div>
<!-- Floating Decoration Cards (Bento Style Accents) -->
<div class="absolute bottom-xl left-xl hidden lg:block">
<div class="bg-surface-container-lowest/10 backdrop-blur-xl border border-white/20 p-6 rounded-xl flex items-center gap-4 shadow-2xl">
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
<section class="py-16 px-6 bg-surface">
<div class="max-w-container_max mx-auto">
<div class="text-center mb-16">
<h2 class="font-h2 text-h2 text-on-surface mb-2">¿Qué sigue en tu aventura?</h2>
<div class="w-20 h-1.5 bg-primary-container mx-auto rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Feature Card 1 -->
<div class="bg-surface-container-lowest p-16 rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-primary/10 rounded-lg flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-primary text-[32px]">restaurant_menu</span>
</div>
<h3 class="font-h3 text-h3 mb-4">Explora el Mapa</h3>
<p class="text-on-surface-variant font-body-md">Descubre joyas ocultas y los restaurantes más premiados cerca de tu ubicación actual.</p>
</div>
<!-- Feature Card 2 -->
<div class="bg-surface-container-lowest p-16 rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-secondary/10 rounded-lg flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-secondary text-[32px]">favorite</span>
</div>
<h3 class="font-h3 text-h3 mb-4">Guarda Favoritos</h3>
<p class="text-on-surface-variant font-body-md">Crea tu propia lista de deseos culinarios y no pierdas nunca ese lugar que quieres visitar.</p>
</div>
<!-- Feature Card 3 -->
<div class="bg-surface-container-lowest p-16 rounded-xl shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-colors duration-300">
<div class="w-14 h-14 bg-tertiary/10 rounded-lg flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-tertiary text-[32px]">confirmation_number</span>
</div>
<h3 class="font-h3 text-h3 mb-4">Reserva al Instante</h3>
<p class="text-on-surface-variant font-body-md">Sin llamadas, sin esperas. Reserva tu mesa en segundos directamente desde la app.</p>
</div>
</div>
</div>
</section>
<!-- Stats / Trust Social Proof -->
</main>
<!-- Footer -->
<footer class="w-full py-16 bg-surface-container-lowest dark:bg-inverse-surface mt-auto">
<div class="flex flex-col md:flex-row justify-between items-center gap-6 px-6 max-w-container_max mx-auto">
<div class="flex flex-col gap-2 items-center md:items-start">
<div class="font-h3 text-h3 font-bold text-primary dark:text-primary-fixed-dim">GoToEat</div>
<p class="font-body-sm text-body-sm text-on-surface-variant dark:text-surface-variant">© 2024 GoToEat. Buen provecho.</p>
</div>
<div class="flex gap-6">
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Privacidad</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Términos</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Soporte</a>
<a class="text-on-surface-variant dark:text-surface-variant font-body-sm text-body-sm hover:text-primary-container dark:hover:text-secondary-fixed-dim transition-colors hover:underline decoration-primary/50" href="#">Contacto</a>
</div>
</div>
</footer>
</body></html>
