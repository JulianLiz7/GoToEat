<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>GoToEat - Perfil de Usuario</title>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Sora:wght@400;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body-md text-on-background min-h-screen">
<!-- TopNavBar -->
<header class="bg-surface/80 dark:bg-on-surface/80 backdrop-blur-md font-h3 text-h3 tracking-tight font-body-md text-body-md docked full-width top-0 sticky border-b border-primary/20 dark:border-primary/20 shadow-sm z-50">
<div class="flex justify-between items-center w-full px-6 py-4 max-w-container_max mx-auto">
<div class="flex items-center gap-10">
<span class="font-h3 text-h3 font-bold text-primary">GoToEat</span>
<nav class="hidden md:flex gap-6 items-center">
<a class="text-on-surface-variant dark:text-surface-variant hover:text-primary transition-colors" href="{{ route('dashboard') }}">Explorar</a>
<a class="text-on-surface-variant dark:text-surface-variant hover:text-primary transition-colors" href="{{ route('reservas') }}">Reservas</a>

</nav>
</div>
<div class="flex items-center gap-4">
<button class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold hover:scale-95 active:scale-90 transition-transform">Nueva Reserva</button>
<div class="flex items-center gap-2 ml-4">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-2 hover:bg-surface-container-low rounded-full transition-all">notifications</span>
<span class="material-symbols-outlined text-primary font-bold cursor-pointer p-2 bg-surface-container-low rounded-full transition-all">account_circle</span>
</div>
</div>
</div>
</header>
<div class="max-w-container_max mx-auto flex">
<!-- SideNavBar -->
<aside class="hidden md:flex flex-col h-[calc(100vh-80px)] w-64 sticky top-20 bg-surface-container-low p-4 gap-2 shadow-md">
<div class="mb-10 p-2">
<div class="flex items-center gap-4 mb-2">
<div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary">
<img alt="Avatar del usuario" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfUPVitbrCMR1p3w1TKXks4ay9r_3iHeQaynHVPvvr5dqTxy5_LhnkURU700pzpsCn_zgAA_5AFH9qbjsOOxa8kiRiOZ69r3bbzMY29L91Ia286wDnd5X0kndDQjvXv38S0dJdwsYiGpjsGtEh-1zp-EIfyGCdaJ0f0zswIGQVnUrg3VFK7U0D7zDYO2np1hNCEpanL-jEcBCKP2td3apgBDxHe2OfxICuYe80ogxZ9jW2ivBCrSVTexMB-abFImAe4w14yJ6FqbM"/>
</div>
<div>
<h4 class="font-h3 text-body-md font-bold text-on-surface">Panel de Usuario</h4>
<p class="text-body-sm text-on-surface-variant">Gastronomía Premium</p>
</div>
</div>
</div>
<nav class="flex-grow flex flex-col gap-2">
<a class="flex items-center gap-4 p-4 text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined">search</span>
<span class="font-body-sm">Explorar</span>
</a>
<a class="flex items-center gap-4 p-4 text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="{{ route('reservas') }}">
<span class="material-symbols-outlined">calendar_today</span>
<span class="font-body-sm">Mis Reservas</span>
</a>
<a class="flex items-center gap-4 p-4 bg-primary text-on-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="{{ route('perfil') }}">
<span class="material-symbols-outlined">person</span>
<span class="font-body-sm">Perfil</span>
</a>
<a class="flex items-center gap-4 p-4 text-primary hover:bg-primary/10 rounded-lg transition-transform hover:translate-x-1 mt-auto font-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
<span class="material-symbols-outlined">logout</span>
<span class="font-body-sm">Cerrar Sesión</span>
</a>
</nav>
<div class="mt-16 border-t border-primary/20 pt-4 flex flex-col gap-2">
<a class="flex items-center gap-4 p-4 text-on-surface-variant hover:bg-primary/10 hover:text-primary rounded-lg transition-transform hover:translate-x-1" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="font-body-sm">Ajustes</span>
</a>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-grow p-6 md:p-16">
<div class="max-w-3xl mx-auto space-y-10">
<!-- Header Section -->
<section class="space-y-2">
<h1 class="font-h1 text-h2 text-on-surface">Configuración de Perfil</h1>
<p class="text-body-lg text-on-surface-variant">Gestiona tu información personal y la seguridad de tu cuenta GoToEat.</p>
</section>
<!-- Update Personal Data Card -->
<div class="bg-surface-container-lowest rounded-xl p-10 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)] border border-primary/10">
<div class="flex items-center gap-4 mb-10 border-b border-primary/10 pb-4">
<span class="material-symbols-outlined text-primary">edit_note</span>
<h2 class="font-h2 text-h3 text-on-surface">Actualizar Datos Personales</h2>
</div>
<form class="space-y-6">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2 md:col-span-2">
<label class="font-label-caps text-on-surface-variant uppercase">Nombre Completo</label>
<input class="w-full bg-surface border border-primary/20 rounded-lg p-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Ingresa tu nombre" type="text" value="{{ auth()->user()->name }}"/>
<p class="text-body-sm text-primary/70 italic">Este es el único campo editable en esta sección.</p>
</div>
<div class="space-y-2 opacity-60">
<label class="font-label-caps text-on-surface-variant uppercase">Correo Electrónico</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-4 cursor-not-allowed" disabled="" placeholder="email@ejemplo.com" type="email" value="{{ auth()->user()->email }}"/>
</div>
<div class="space-y-2 opacity-60">
<label class="font-label-caps text-on-surface-variant uppercase">ID de Usuario</label>
<input class="w-full bg-surface-container-low border border-primary/20 rounded-lg p-4 cursor-not-allowed" disabled="" type="text" value="GE-882910"/>
</div>
</div>
<div class="pt-6 flex justify-end">
<button class="bg-primary text-on-primary font-bold px-10 py-4 rounded-lg shadow-lg shadow-primary/20 hover:scale-95 active:scale-90 transition-all duration-200" type="submit">
                                Guardar Cambios
                            </button>
</div>
</form>
</div>
<!-- Security Section Card -->
<div class="bg-surface-container-lowest rounded-xl p-10 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.05)] border border-primary/10">
<div class="flex items-center gap-4 mb-10 border-b border-primary/10 pb-4">
<span class="material-symbols-outlined text-primary">security</span>
<h2 class="font-h2 text-h3 text-on-surface">Seguridad de la Cuenta</h2>
</div>
<div class="space-y-6">
<h3 class="font-h3 text-body-md font-bold text-on-surface-variant mb-4">Cambiar Contraseña</h3>
<form method="post" action="{{ route('password.update') }}" class="space-y-6">
@csrf
@method('put')
<div class="space-y-6">
<div class="space-y-2">
<label class="font-label-caps text-on-surface-variant uppercase" for="update_password_current_password">Contraseña Actual</label>
<div class="relative">
<input id="update_password_current_password" name="current_password" class="w-full bg-surface border border-primary/20 rounded-lg p-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="••••••••" type="password" autocomplete="current-password"/>
<span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant cursor-pointer">visibility</span>
</div>
@if($errors->updatePassword->has('current_password'))
    <div class="text-error text-body-sm mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
@endif
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2">
<label class="font-label-caps text-on-surface-variant uppercase" for="update_password_password">Nueva Contraseña</label>
<input id="update_password_password" name="password" class="w-full bg-surface border border-primary/20 rounded-lg p-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Ingresa nueva contraseña" type="password" autocomplete="new-password"/>
@if($errors->updatePassword->has('password'))
    <div class="text-error text-body-sm mt-1">{{ $errors->updatePassword->first('password') }}</div>
@endif
</div>
<div class="space-y-2">
<label class="font-label-caps text-on-surface-variant uppercase" for="update_password_password_confirmation">Confirmar Nueva Contraseña</label>
<input id="update_password_password_confirmation" name="password_confirmation" class="w-full bg-surface border border-primary/20 rounded-lg p-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Confirma nueva contraseña" type="password" autocomplete="new-password"/>
@if($errors->updatePassword->has('password_confirmation'))
    <div class="text-error text-body-sm mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
@endif
</div>
</div>
</div>
<div class="pt-6 flex justify-between items-center">
@if (session('status') === 'password-updated')
    <span class="text-body-sm text-secondary flex items-center gap-2 font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        ¡Contraseña actualizada con éxito!
    </span>
@else
    <span class="text-body-sm text-on-surface-variant flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">shield</span>
        Protege tu cuenta con una contraseña segura
    </span>
@endif
<button class="bg-primary text-on-primary shadow-lg shadow-primary/20 font-bold px-10 py-4 rounded-lg hover:scale-95 active:scale-90 transition-all duration-200" type="submit">
                                Actualizar Contraseña
                            </button>
</div>
</form>
</div>
</div>
<!-- Removed Sessions Section as requested -->
<!-- Removed Banners as requested -->
</div>
</main>
</div>
<!-- Mobile Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-primary/10 px-6 py-2 flex justify-around items-center z-50 glass-nav">
<a class="flex flex-col items-center gap-2 text-on-surface-variant p-2" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined">search</span>
<span class="text-[10px]">Explorar</span>
</a>
<a class="flex flex-col items-center gap-2 text-on-surface-variant p-2" href="{{ route('reservas') }}">
<span class="material-symbols-outlined">calendar_today</span>
<span class="text-[10px]">Reservas</span>
</a>
<a class="flex flex-col items-center gap-2 text-primary font-bold p-2" href="{{ route('perfil') }}">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
<span class="text-[10px]">Perfil</span>
</a>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>