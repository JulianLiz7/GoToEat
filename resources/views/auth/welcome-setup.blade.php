<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>¡Bienvenido a GoToEat!</title>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .bg-pattern {
            background-image: radial-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-lowest font-body-md text-on-surface min-h-screen flex flex-col items-center justify-center bg-pattern">
<!-- Top Navigation (Simplified for Transactional Success Screen) -->
<header class="fixed top-0 w-full z-50 flex justify-center items-center px-6 py-4 bg-white/80 backdrop-blur-md">
<div class="flex items-center gap-2">
<span class="text-2xl font-black text-primary-container font-h1">GoToEat</span>
</div>
</header>
<main class="w-full max-w-container_max px-gutter py-xl flex flex-col items-center text-center mt-16">
<!-- Festive Hero Section -->
<div class="relative mb-lg group">
<div class="absolute -inset-4 bg-primary-container/10 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all duration-500"></div>
<div class="relative w-48 h-48 md:w-64 md:h-64 rounded-full overflow-hidden shadow-2xl border-4 border-white">
<img alt="Chef feliz celebrando" class="w-full h-full object-cover" data-alt="A professional, smiling chef wearing a clean white uniform and tall toque, gesturing a welcoming 'perfect' sign in a modern, brightly lit restaurant kitchen. The scene is bathed in warm, soft sunlight, emphasizing a celebratory and successful atmosphere. The color palette features warm whites, light grays, and vibrant orange accents that match the GoToEat brand identity. The mood is high-energy, motivating, and incredibly welcoming for a new business owner." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBI2MAmkSRqXcXMobfb_V0CQfcsU2amEpexVA1yap5hvREb4ZMZMMzwUVnFNi4-vn2vAlTTGIzEYj5FRMHS_dYEl6TS7cH7Ebsmf1gRIy3CFu2dvbBhNSzNupG22I2iclcqDsfmYQFLo43NTp60lr1UMh5Jm7VjKU1Ack4GahCpAiY94tOdAXBbh2LxgGSpBo_Ypd0bZMGTJkuXtE1h-6gZztRjdTmBFD6BW__OdtgpksL148Zgu2FgzMpIoZOqnpzQRDUFp_rC24s"/>
</div>
<!-- Decorative Elements -->
<div class="absolute -top-4 -right-4 bg-secondary-container text-on-secondary-container p-3 rounded-full shadow-lg">
<span class="material-symbols-outlined text-3xl" data-icon="celebration">celebration</span>
</div>
<div class="absolute -bottom-4 -left-4 bg-primary-container text-white p-3 rounded-full shadow-lg">
<span class="material-symbols-outlined text-3xl" data-icon="check_circle" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
</div>
<!-- Success Message -->
<h1 class="font-h1 text-h1 text-on-surface mb-sm max-w-2xl">
            ¡Todo listo! Bienvenido a <span class="text-primary-container">GoToEat</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mb-lg">
            Tu restaurante ya está configurado y listo para dar el siguiente paso. Hemos preparado tu panel de control para que empieces a gestionar inteligentemente.
        </p>
<!-- Bento-style Benefit Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter w-full max-w-4xl mb-lg">
<!-- Card 1 -->
<div class="bg-white p-md rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg mb-sm group-hover:bg-primary-container/10 transition-colors">
<span class="material-symbols-outlined text-primary-container" data-icon="monitoring">monitoring</span>
</div>
<h3 class="font-h3 text-body-md font-bold text-on-surface mb-xs">Panel en tiempo real</h3>
<p class="text-body-sm text-on-surface-variant">Visualiza tus ventas y métricas clave al instante.</p>
</div>
<!-- Card 2 -->
<div class="bg-white p-md rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg mb-sm group-hover:bg-primary-container/10 transition-colors">
<span class="material-symbols-outlined text-primary-container" data-icon="restaurant_menu">restaurant_menu</span>
</div>
<h3 class="font-h3 text-body-md font-bold text-on-surface mb-xs">Gestión de pedidos</h3>
<p class="text-body-sm text-on-surface-variant">Control total sobre tu cocina y tiempos de entrega.</p>
</div>
<!-- Card 3 -->
<div class="bg-white p-md rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg mb-sm group-hover:bg-primary-container/10 transition-colors">
<span class="material-symbols-outlined text-primary-container" data-icon="smart_toy">smart_toy</span>
</div>
<h3 class="font-h3 text-body-md font-bold text-on-surface mb-xs">Asistente IA activado</h3>
<p class="text-body-sm text-on-surface-variant">Sugerencias inteligentes para optimizar tu inventario.</p>
</div>
</div>
<!-- Action Buttons -->
<div class="flex flex-col sm:flex-row gap-md items-center justify-center w-full">
<button class="bg-primary-container text-white px-lg py-sm rounded-lg font-h3 text-body-md font-bold shadow-lg shadow-primary-container/20 hover:scale-105 active:scale-95 transition-all duration-200 w-full sm:w-auto">
                Ir a mi Panel de Control
            </button>
</div>
</main>
<!-- Footer Decoration -->
<footer class="mt-auto py-lg w-full flex justify-center opacity-50">
<div class="flex items-center gap-8 grayscale">
<span class="material-symbols-outlined text-4xl" data-icon="bakery_dining">bakery_dining</span>
<span class="material-symbols-outlined text-4xl" data-icon="skillet">skillet</span>
<span class="material-symbols-outlined text-4xl" data-icon="dinner_dining">dinner_dining</span>
<span class="material-symbols-outlined text-4xl" data-icon="local_bar">local_bar</span>
</div>
</footer>
</body></html>