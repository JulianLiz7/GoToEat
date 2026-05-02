<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Configuración del Menú</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                "primary": "#f97316",
                "tertiary": "#006398",
                "surface-tint": "#f97316",
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
            }
          }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .perspective-card {
            perspective: 1000px;
        }
        .tilted-mockup {
            transform: rotateY(-15deg) rotateX(5deg);
            box-shadow: 40px 40px 60px -20px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased overflow-x-hidden">
<!-- Header / Navigation Shell -->
<nav class="sticky top-0 w-full z-50 border-b border-gray-100 dark:border-gray-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md font-sora antialiased shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)]">
<div class="flex justify-between items-center px-8 py-4 max-w-[1280px] mx-auto">
<div class="text-2xl font-black tracking-tight text-primary">GoToEat</div>
</div>
</nav>
<!-- Main Content Area -->
<main class="max-w-[1280px] mx-auto px-8 py-16 min-h-[calc(100vh-80px)] grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
<!-- Left Side: Descriptive Area -->
<section class="lg:col-span-5 flex flex-col gap-lg">
<div class="space-y-sm">
<span class="text-primary font-label-caps uppercase tracking-widest bg-primary/10 px-3 py-1 rounded-full">Paso 2: Configuración</span>
<h1 class="font-h1 text-h1 text-on-surface">Carga tus platos y categorías</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">
                    La presentación es la mitad del sabor. Crea un menú irresistible cargando tus especialidades con descripciones detalladas y fotografías de alta calidad.
                </p>
</div>
<div class="perspective-card relative mt-md">
<div class="tilted-mockup bg-white rounded-2xl p-xs border border-surface-variant overflow-hidden">
<img alt="Gourmet Plating" class="w-full h-[320px] object-cover rounded-xl" data-alt="A top-down professional food photography shot of a gourmet salmon dish with vibrant seasonal vegetables and artisan sauce swirls. The lighting is soft and natural, emphasizing textures and fresh colors within a high-end minimalist restaurant setting. The overall aesthetic is clean, bright, and inviting, perfectly aligning with a modern gastronomy SaaS brand identity." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8AF7CYv2y7ry41IG3JjuY5cOyfpTtSicDutbLoG0lRJfR3ehRx6vm5yYMEkGOqgvSztkf7tSlbgE8y1Rnw6UGhS7oIsiQ11BP2ZjxDPyCDLPVMsujl9Sw5jaZfwTFdeQc2fXzc7QPsp-8uvFKc7HqqAVS4q1rsLFK0EhSbpTq-1uuYvPU_SGeWJ-PJ-AGnFtMOUDOishQPigOGF863WmBYlDCAUDVxyqEBn5qso0fUoG3wRoTkzAsZKqfd5homaNcdmfTGA8NfMA"/>
<div class="p-md bg-white">
<div class="flex justify-between items-center">
<span class="font-h3 text-h3">Salmón Glaseado</span>
<span class="font-bold text-primary">$24.00</span>
</div>
<p class="text-body-sm text-on-surface-variant mt-xs">Categoría: Platos Principales</p>
</div>
</div>
<!-- Decorative element -->
<div class="absolute -bottom-6 -left-6 bg-secondary-container p-sm rounded-2xl shadow-xl flex items-center gap-sm">
<div class="bg-white p-2 rounded-full">
<span class="material-symbols-outlined text-secondary" data-icon="restaurant_menu">restaurant_menu</span>
</div>
<div>
<p class="font-bold text-on-secondary-container">Optimización de Menú</p>
<p class="text-xs text-on-secondary-fixed-variant">Aumenta tus ventas un 20%</p>
</div>
</div>
</div>
</section>
<!-- Right Side: Form Card -->
<section class="lg:col-span-7 flex flex flex-col gap-lg">
<div class="w-full bg-white rounded-3xl p-lg shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08)] border border-surface-container-low">
<div class="flex items-center gap-md mb-lg">
<div class="flex w-full items-center">
<!-- Step 1 -->
<div class="flex flex-col items-center flex-1">
<div class="flex items-center w-full">
<div class="h-[2px] w-full bg-primary"></div>
<div class="w-8 h-8 flex-shrink-0 rounded-full bg-primary text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined text-sm">check</span>
</div>
<div class="h-[2px] w-full bg-primary"></div>
</div>
<span class="mt-2 font-label-caps text-[10px] text-primary">DETALLES</span>
</div>
<!-- Step 2 (Active) -->
<div class="flex flex-col items-center flex-1">
<div class="flex items-center w-full">
<div class="h-[2px] w-full bg-primary"></div>
<div class="w-8 h-8 flex-shrink-0 rounded-full ring-2 ring-primary bg-primary text-on-primary flex items-center justify-center font-bold text-sm">
                                    2
                                </div>
<div class="h-[2px] w-full bg-surface-container-highest"></div>
</div>
<span class="mt-2 font-label-caps text-[10px] text-primary font-bold">MENÚ</span>
</div>
<!-- Step 3 -->
<div class="flex flex-col items-center flex-1">
<div class="flex items-center w-full">
<div class="h-[2px] w-full bg-surface-container-highest"></div>
<div class="w-8 h-8 flex-shrink-0 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-bold text-sm">
                                    3
                                </div>
<div class="h-[2px] w-full bg-surface-container-highest"></div>
</div>
<span class="mt-2 font-label-caps text-[10px] text-on-surface-variant">EQUIPO</span>
</div>
</div>
</div>
<form class="space-y-md">
<!-- Plato Name -->
<div class="space-y-xs">
<label class="block font-label-caps text-on-surface-variant">NOMBRE DEL PLATO</label>
<input class="w-full px-md py-4 rounded-xl border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none text-body-md" placeholder="Ej: Pizza Napolitana Artesanal" type="text"/>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<!-- Category -->
<div class="space-y-xs">
<label class="block font-label-caps text-on-surface-variant">CATEGORÍA</label>
<div class="relative">
<select class="w-full px-md py-4 rounded-xl border border-outline-variant bg-surface appearance-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none text-body-md">
<option>Seleccionar...</option>
<option>Entrantes</option>
<option>Platos Principales</option>
<option>Postres</option>
<option>Bebidas</option>
</select>
<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Price -->
<div class="space-y-xs">
<label class="block font-label-caps text-on-surface-variant">PRECIO ($)</label>
<input class="w-full px-md py-4 rounded-xl border border-outline-variant bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none text-body-md" placeholder="0.00" step="0.01" type="number"/>
</div>
</div>
<!-- Photo Upload -->
<div class="space-y-xs">
<label class="block font-label-caps text-on-surface-variant">SUBIR FOTO DEL PLATO</label>
<div class="border-2 border-dashed border-outline-variant rounded-2xl p-lg flex flex-col items-center justify-center gap-sm bg-surface-container-lowest hover:bg-primary/5 transition-colors cursor-pointer group">
<div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl" data-icon="cloud_upload">cloud_upload</span>
</div>
<div class="text-center">
<p class="font-bold text-on-surface">Haz clic para subir o arrastra una imagen</p>
<p class="text-body-sm text-on-surface-variant">Formatos aceptados: JPG, PNG (Máx. 5MB)</p>
</div>
</div>
</div>
<!-- Action Buttons -->
<div class="pt-md flex flex-col md:flex-row gap-sm">
<button class="flex-1 bg-primary text-on-primary font-bold py-4 rounded-xl shadow-lg shadow-primary/20" type="button" disabled style="opacity: 0.5; cursor: not-allowed;" title="Se habilitará al configurar la base de datos">
                            Guardar Plato
                        </button>
<button class="flex-1 bg-surface-container text-on-surface-variant font-bold py-4 rounded-xl" type="button" disabled style="opacity: 0.5; cursor: not-allowed;" title="Se habilitará al configurar la base de datos">
                            Añadir otro plato
                        </button>
</div>
<div class="flex items-center justify-between pt-sm">
<a href="{{ route('register') }}" class="flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors font-medium">
<span class="material-symbols-outlined text-sm" data-icon="arrow_back">arrow_back</span>
                            Anterior
                        </a>
<a href="{{ route('register.step3') }}" class="px-8 py-3 rounded-xl bg-primary text-white font-bold hover:opacity-90 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-primary/20 inline-block text-center">
                            Continuar a configuración de equipo
                        </a>
</div>
</form>
</div>
<div class="w-full bg-surface-container-low rounded-3xl p-lg border border-surface-container-high">
<div class="flex items-center justify-between mb-lg">
<h2 class="font-h2 text-h3 text-on-surface">Platos Guardados</h2>
<span class="bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full text-xs font-bold font-label-caps uppercase">0 PLATOS</span>
</div>
<div class="space-y-sm">
<div class="flex flex-col items-center justify-center py-12 px-4 text-center">
<div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-4">
<span class="material-symbols-outlined text-on-surface-variant/40 text-3xl">restaurant</span>
</div>
<p class="text-on-surface-variant font-medium">Aún no has agregado platos a tu menú</p>
<p class="text-body-sm text-on-surface-variant/60 mt-1">Completa el formulario de arriba para comenzar a construir tu carta</p>
</div>
</div>
</div>
</section>
</main>
<!-- Footer Shell -->
<footer class="w-full border-t border-gray-200 dark:border-gray-800 bg-slate-50 dark:bg-slate-950 font-sora text-sm mt-xl">
<div class="flex flex-col md:flex-row justify-between items-center py-12 px-8 max-w-[1280px] mx-auto">
<div class="font-bold text-primary mb-md md:mb-0">GoToEat</div>
<p class="text-slate-400 dark:text-slate-500 order-3 md:order-2">© 2024 GoToEat. Potenciando la gastronomía moderna.</p>
<div class="flex gap-md order-2 md:order-3 mb-md md:mb-0">
<a class="text-slate-400 dark:text-slate-500 hover:text-primary transition-colors" href="#">Privacidad</a>
<a class="text-slate-400 dark:text-slate-500 hover:text-primary transition-colors" href="#">Soporte</a>
<a class="text-slate-400 dark:text-slate-500 hover:text-primary transition-colors" href="#">Términos</a>
</div>
</div>
</footer>
</body></html>