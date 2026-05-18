<!DOCTYPE html>
<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Registro | GoToEat - Gestión Gastronómica Moderna</title>
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body text-body-md text-on-background selection:bg-primary-container selection:text-white">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md">
<div class="flex justify-between items-center h-20 px-6 md:px-12 max-w-7xl mx-auto">
<div class="text-2xl font-heading font-bold text-slate-900 tracking-tight">GoTo<span class="text-primary-container">Eat</span></div>
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
<main class="pt-32 pb-16 px-6 md:px-12 max-w-7xl mx-auto">
<div class="grid lg:grid-cols-12 gap-16 items-center">
<!-- Left Side: Registration Form -->
<div class="lg:col-span-6 xl:col-span-5">
<header class="mb-10">
<h1 class="font-heading text-h2 text-on-surface mb-2">Crea tu cuenta</h1>
<p class="font-body text-body-lg text-on-surface-variant">Únete a la nueva era de la gestión y experiencia gastronómica.</p>
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

<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf
<!-- Role Selector -->
<div class="space-y-2">
<label class="font-body text-label-caps text-outline uppercase tracking-widest">Elige tu perfil</label>
<div class="grid grid-cols-2 gap-6">
<label class="relative cursor-pointer group">
<input class="peer sr-only" name="role" type="radio" value="comensal" {{ old('role', 'comensal') === 'comensal' ? 'checked' : '' }} required/>
<div class="p-6 border-2 border-surface-container rounded-xl flex flex-col items-center gap-2 text-center transition-all peer-checked:border-primary-container peer-checked:bg-orange-50 group-hover:bg-surface-container-low shadow-sm h-full">
<span class="material-symbols-outlined text-3xl text-primary-container">restaurant</span>
<span class="font-heading text-body-md text-on-surface">Comensal</span>
<p class="text-xs text-on-surface-variant leading-tight">Descubre y reserva en los mejores lugares</p>
</div>
</label>
<label class="relative cursor-pointer group">
<input class="peer sr-only" name="role" type="radio" value="restaurante" {{ old('role') === 'restaurante' ? 'checked' : '' }} required/>
<div class="p-6 border-2 border-surface-container rounded-xl flex flex-col items-center gap-2 text-center transition-all peer-checked:border-primary-container peer-checked:bg-orange-50 group-hover:bg-surface-container-low shadow-sm h-full">
<span class="material-symbols-outlined text-3xl text-primary-container">storefront</span>
<span class="font-heading text-body-md text-on-surface">Restaurante</span>
<p class="text-xs text-on-surface-variant leading-tight">Gestiona tu negocio y crece con nosotros</p>
</div>
</label>
</div>
</div>
<!-- Input Fields -->
<div class="space-y-6">
<div class="space-y-1">
<label class="font-body text-label-caps text-on-surface-variant" for="name">Nombre completo</label>
<input name="name" value="{{ old('name') }}" class="w-full px-6 py-4 bg-white border @error('name') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="name" placeholder="Ej. Juan Pérez" type="text" required autofocus autocomplete="name"/>
</div>
<div class="space-y-1">
<label class="font-body text-label-caps text-on-surface-variant" for="email">Correo electrónico</label>
<input name="email" value="{{ old('email') }}" class="w-full px-6 py-4 bg-white border @error('email') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="email" placeholder="juan@ejemplo.com" type="email" required autocomplete="username"/>
</div>
<div class="space-y-1 relative">
<label class="font-body text-label-caps text-on-surface-variant" for="password">Contraseña</label>
<div class="relative">
<input name="password" class="w-full px-6 py-4 bg-white border @error('password') border-error bg-error-container/20 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="password" placeholder="••••••••" type="password" required autocomplete="new-password"/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>

<div class="space-y-1 relative">
<label class="font-body text-label-caps text-on-surface-variant" for="password_confirmation">Confirmar contraseña</label>
<div class="relative">
<input name="password_confirmation" class="w-full px-6 py-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all placeholder:text-slate-400" id="password_confirmation" placeholder="••••••••" type="password" required autocomplete="new-password"/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password';">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>
</div>
<!-- Submit Button -->
<button class="w-full bg-primary-container text-white py-4 rounded-xl font-heading text-body-lg shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 transition-all duration-300" type="submit">
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
<div class="overflow-hidden rounded-2xl mb-6">
<img class="w-full h-64 object-cover" data-alt="A high-end restaurant scene captured in a warm, inviting light. A professional chef is meticulously plating a colorful gourmet dish in a modern open kitchen. The atmosphere is vibrant yet efficient, reflecting a premium gastronomic service environment. Soft golden lighting highlights the textures of the food and the sleek stainless steel surfaces of the professional kitchen. The overall aesthetic is professional, modern, and appetite-stimulating, consistent with a top-tier hospitality SaaS platform." src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1974&auto=format&fit=crop"/>
</div>
<div class="space-y-4">
<div class="flex justify-between items-center">
<h3 class="font-heading text-h3 text-slate-900">Eficiencia Gourmet</h3>
<span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Software Premium</span>
</div>
<p class="text-slate-600 font-body text-body-md">Optimiza cada reserva, gestiona tus pedidos en tiempo real y ofrece una experiencia inolvidable a tus comensales con tecnología de vanguardia.</p>
<!-- Mini Dashboard UI Elements -->
<div class="grid grid-cols-3 gap-2 pt-2">
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
<footer class="bg-slate-950 w-full py-16 px-6 mt-16">
<div class="max-w-7xl mx-auto border-t border-slate-800 pt-16">
<div class="grid grid-cols-2 md:grid-cols-4 gap-12">
<div class="col-span-2 md:col-span-1">
<div class="text-xl font-black text-white font-heading mb-6">GoToEat</div>
<p class="text-slate-400 text-sm leading-relaxed">Redefiniendo la hospitalidad con tecnología de alta gama diseñada para el éxito operativo.</p>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Producto</h4>
<ul class="space-y-4 text-slate-400 font-body text-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">Inventory</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Menu</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Staff</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Compañía</h4>
<ul class="space-y-4 text-slate-400 font-body text-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">About</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Careers</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Contact</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Legal</h4>
<ul class="space-y-4 text-slate-400 font-body text-body-sm">
<li><a class="hover:text-orange-400 transition-colors" href="#">Privacy</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Terms</a></li>
<li><a class="hover:text-orange-400 transition-colors" href="#">Cookies</a></li>
</ul>
</div>
</div>
<div class="mt-16 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-6">
<p class="text-slate-500 font-body text-body-sm">© 2024 GoToEat. All rights reserved. Built for modern hospitality.</p>
<div class="flex gap-6">
<a class="text-slate-500 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
<a class="text-slate-500 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
</div>
</div>
</div>
</footer>
</body></html>
