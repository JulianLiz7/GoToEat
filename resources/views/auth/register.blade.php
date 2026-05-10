<!DOCTYPE html>
<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Registro | GoToEat - Gestión Gastronómica Moderna</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&amp;family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "on-error-container": "#93000a",
                      "surface-container-low": "#eff4ff",
                      "on-tertiary-fixed-variant": "#004b74",
                      "inverse-on-surface": "#eaf1ff",
                      "surface-container": "#e5eeff",
                      "secondary-fixed-dim": "#4edea3",
                      "on-primary-fixed-variant": "#783200",
                      "on-secondary-fixed-variant": "#005236",
                      "tertiary-fixed-dim": "#93ccff",
                      "on-secondary-fixed": "#002113",
                      "surface-container-lowest": "#ffffff",
                      "surface-variant": "#d3e4fe",
                      "outline-variant": "#e0c0b1",
                      "secondary": "#006c49",
                      "on-tertiary-container": "#003554",
                      "surface-container-high": "#dce9ff",
                      "on-primary": "#ffffff",
                      "primary": "#9d4300",
                      "error": "#ba1a1a",
                      "primary-fixed-dim": "#ffb690",
                      "primary-fixed": "#ffdbca",
                      "on-surface": "#0b1c30",
                      "tertiary-fixed": "#cde5ff",
                      "secondary-container": "#6cf8bb",
                      "on-tertiary": "#ffffff",
                      "inverse-surface": "#213145",
                      "on-background": "#0b1c30",
                      "background": "#f8f9ff",
                      "surface-container-highest": "#d3e4fe",
                      "tertiary": "#006398",
                      "tertiary-container": "#00a2f4",
                      "outline": "#8c7164",
                      "on-surface-variant": "#584237",
                      "surface-tint": "#9d4300",
                      "inverse-primary": "#ffb690",
                      "on-secondary": "#ffffff",
                      "surface-bright": "#f8f9ff",
                      "surface-dim": "#cbdbf5",
                      "on-primary-container": "#582200",
                      "secondary-fixed": "#6ffbbe",
                      "surface": "#f8f9ff",
                      "on-primary-fixed": "#341100",
                      "on-secondary-container": "#00714d",
                      "primary-container": "#f97316",
                      "error-container": "#ffdad6",
                      "on-error": "#ffffff",
                      "on-tertiary-fixed": "#001d32"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "sm": "16px",
                      "xs": "8px",
                      "gutter": "24px",
                      "md": "24px",
                      "base": "4px",
                      "lg": "40px",
                      "container_max": "1280px",
                      "xl": "64px"
              },
              "fontFamily": {
                      "body-sm": ["Inter"],
                      "h1": ["Sora"],
                      "h3": ["Sora"],
                      "body-md": ["Inter"],
                      "h2": ["Sora"],
                      "label-caps": ["Inter"],
                      "body-lg": ["Inter"]
              },
              "fontSize": {
                      "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                      "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "h2": ["36px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "label-caps": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}]
              }
            },
          },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .perspective-3d {
            perspective: 1000px;
        }
        .tilt-card {
            transform: rotateY(-10deg) rotateX(5deg);
            transition: transform 0.3s ease-out;
        }
        .tilt-card:hover {
            transform: rotateY(0deg) rotateX(0deg);
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background selection:bg-primary-container selection:text-white">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md">
<div class="flex justify-between items-center h-20 px-6 md:px-12 max-w-7xl mx-auto">
<div class="text-2xl font-h1 font-bold text-slate-900 tracking-tight">GoTo<span class="text-primary-container">Eat</span></div>
<div class="hidden md:flex gap-8 items-center">
<a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="{{ url('/#restaurantes') }}">Para Restaurantes</a>
<a class="text-slate-600 font-medium hover:text-primary-container transition-colors duration-200" href="{{ url('/#comensales') }}">Para Comensales</a>
</div>
<div class="flex items-center gap-4">
<a class="text-slate-600 font-medium hover:text-primary-container transition-colors px-4 py-2" href="{{ route('login') }}">Iniciar Sesión</a>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="pt-32 pb-xl px-6 md:px-12 max-w-7xl mx-auto">
<div class="grid lg:grid-cols-12 gap-xl items-center">
<!-- Left Side: Registration Form -->
<div class="lg:col-span-6 xl:col-span-5">
<header class="mb-lg">
<h1 class="font-h1 text-h2 text-on-surface mb-xs">Crea tu cuenta</h1>
<p class="font-body-md text-body-lg text-on-surface-variant">Únete a la nueva era de la gestión y experiencia gastronómica.</p>
</header>

{{-- Error summary --}}
@if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-error-container border border-red-200 flex gap-3">
        <span class="material-symbols-outlined text-error mt-0.5">error</span>
        <ul class="text-sm text-error space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-md">
    @csrf
<!-- Role Selector -->
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-outline uppercase tracking-widest">Elige tu perfil</label>
<div class="grid grid-cols-2 gap-md">
<label class="relative cursor-pointer group">
<input class="peer sr-only" name="role" type="radio" value="comensal" {{ old('role', 'comensal') === 'comensal' ? 'checked' : '' }} required/>
<div class="p-md border-2 border-surface-container rounded-xl flex flex-col items-center gap-xs text-center transition-all peer-checked:border-primary-container peer-checked:bg-orange-50 group-hover:bg-surface-container-low shadow-sm h-full">
<span class="material-symbols-outlined text-3xl text-primary-container">restaurant</span>
<span class="font-h3 text-body-md text-on-surface">Comensal</span>
<p class="text-xs text-on-surface-variant leading-tight">Descubre y reserva en los mejores lugares</p>
</div>
</label>
<label class="relative cursor-pointer group">
<input class="peer sr-only" name="role" type="radio" value="restaurante" {{ old('role') === 'restaurante' ? 'checked' : '' }} required/>
<div class="p-md border-2 border-surface-container rounded-xl flex flex-col items-center gap-xs text-center transition-all peer-checked:border-primary-container peer-checked:bg-orange-50 group-hover:bg-surface-container-low shadow-sm h-full">
<span class="material-symbols-outlined text-3xl text-primary-container">storefront</span>
<span class="font-h3 text-body-md text-on-surface">Restaurante</span>
<p class="text-xs text-on-surface-variant leading-tight">Gestiona tu negocio y crece con nosotros</p>
</div>
</label>
</div>
</div>
<!-- Input Fields -->
<div class="space-y-gutter">
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant" for="name">Nombre completo</label>
<input name="name" value="{{ old('name') }}" class="w-full px-md py-sm bg-white border @error('name') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="name" placeholder="Ej. Juan Pérez" type="text" required autofocus autocomplete="name"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant" for="email">Correo electrónico</label>
<input name="email" value="{{ old('email') }}" class="w-full px-md py-sm bg-white border @error('email') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="email" placeholder="juan@ejemplo.com" type="email" required autocomplete="username"/>
</div>
<div class="space-y-base relative">
<label class="font-label-caps text-label-caps text-on-surface-variant" for="password">Contraseña</label>
<div class="relative">
<input name="password" class="w-full px-md py-sm bg-white border @error('password') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="password" placeholder="••••••••" type="password" required autocomplete="new-password"/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>

<div class="space-y-base relative">
<label class="font-label-caps text-label-caps text-on-surface-variant" for="password_confirmation">Confirmar contraseña</label>
<div class="relative">
<input name="password_confirmation" class="w-full px-md py-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="password_confirmation" placeholder="••••••••" type="password" required autocomplete="new-password"/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password';">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>
</div>
<!-- Submit Button -->
<button class="w-full bg-primary-container text-white py-sm rounded-xl font-h3 text-body-lg shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 transition-all duration-300" type="submit">
                        Crear Cuenta
                    </button>
<p class="text-center text-body-sm text-on-surface-variant">
                        ¿Ya tienes una cuenta? <a class="text-primary-container font-semibold hover:underline" href="{{ route('login') }}">Inicia sesión</a>
</p>
</form>
</div>
<!-- Right Side: 3D Illustration / Marketing -->
<div class="hidden lg:block lg:col-span-6 xl:col-span-7 perspective-3d">
<div class="relative w-full aspect-square flex items-center justify-center">
<!-- Background Accents -->
<div class="absolute w-[80%] h-[80%] bg-orange-100 rounded-full blur-3xl opacity-50 -z-10 animate-pulse"></div>
<!-- Feature Image Card -->
<div class="tilt-card relative bg-white p-6 rounded-3xl shadow-2xl border border-white/50 max-w-lg w-full">
<div class="overflow-hidden rounded-2xl mb-md">
<img class="w-full h-64 object-cover" data-alt="A high-end restaurant scene captured in a warm, inviting light. A professional chef is meticulously plating a colorful gourmet dish in a modern open kitchen. The atmosphere is vibrant yet efficient, reflecting a premium gastronomic service environment. Soft golden lighting highlights the textures of the food and the sleek stainless steel surfaces of the professional kitchen. The overall aesthetic is professional, modern, and appetite-stimulating, consistent with a top-tier hospitality SaaS platform." src="https://lh3.googleusercontent.com/aida-public/AB6AXuApSE9b7tzFK4d1qefw-87qTt_7MCXHnRFwsrWz25nR7Sc2SqKTPVD1YEUVnHNzQROJW4qRJRQWNcwZAvZ8xx5nhpnf-NuAul9NTgLVxOgsbbFgr7WwnjY3opZrhjysS0X-iWUb4DkoFuTeWpwmZy0ztbQH6e9m8nsvponRa-8LV9gsCjSXfS0-uCM7erNqsELLsaH6n61p1I1Sdm8yMsqn_LxHt7m8RhwgZENSJ5kiX0j9pwAjpzWGsQBGirQX5ofsUZtQYaUFRxM"/>
</div>
<div class="space-y-sm">
<div class="flex justify-between items-center">
<h3 class="font-h2 text-h3 text-slate-900">Eficiencia Gourmet</h3>
<span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Software Premium</span>
</div>
<p class="text-slate-600 font-body-md">Optimiza cada reserva, gestiona tus pedidos en tiempo real y ofrece una experiencia inolvidable a tus comensales con tecnología de vanguardia.</p>
<!-- Mini Dashboard UI Elements -->
<div class="grid grid-cols-3 gap-xs pt-xs">
<div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
<span class="block font-bold text-primary-container text-lg">98%</span>
<span class="text-[10px] text-slate-400 font-bold uppercase">Éxito</span>
</div>
<div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
<span class="block font-bold text-primary-container text-lg">+2.4k</span>
<span class="text-[10px] text-slate-400 font-bold uppercase">Reservas</span>
</div>
<div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
<span class="block font-bold text-primary-container text-lg">4.9/5</span>
<span class="text-[10px] text-slate-400 font-bold uppercase">Rating</span>
</div>
</div>
</div>
</div>
<!-- Floating Badge -->
<div class="absolute -top-4 -right-4 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-3 animate-bounce">
<div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div>
<p class="text-xs font-bold text-slate-900">#1 en Reservas</p>
<p class="text-[10px] text-slate-500">España &amp; LATAM</p>
</div>
</div>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-slate-950 w-full py-16 px-6 mt-xl">
<div class="max-w-7xl mx-auto border-t border-slate-800 pt-16">
<div class="grid grid-cols-2 md:grid-cols-4 gap-12">
<div class="col-span-2 md:col-span-1">
<div class="text-xl font-black text-white font-h1 mb-md">GoToEat</div>
<p class="text-slate-400 text-sm leading-relaxed">Redefiniendo la hospitalidad con tecnología de alta gama diseñada para el éxito operativo.</p>
</div>
<div>
<h4 class="text-white font-bold mb-md uppercase text-xs tracking-widest">Producto</h4>
<ul class="space-y-sm text-slate-400 font-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">Inventory</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Menu</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Staff</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-md uppercase text-xs tracking-widest">Compañía</h4>
<ul class="space-y-sm text-slate-400 font-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">About</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Careers</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Contact</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-md uppercase text-xs tracking-widest">Legal</h4>
<ul class="space-y-sm text-slate-400 font-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">Privacy</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Terms</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Cookies</a></li>
</ul>
</div>
</div>
<div class="mt-16 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-md">
<p class="text-slate-500 font-body-sm">© 2024 GoToEat. All rights reserved. Built for modern hospitality.</p>
<div class="flex gap-gutter">
<a class="text-slate-500 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
<a class="text-slate-500 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
</div>
</div>
</div>
</footer>
</body></html>
