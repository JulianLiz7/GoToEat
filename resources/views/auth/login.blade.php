<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Iniciar Sesión | GoToEat</title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-variant font-body text-body-md text-on-surface selection:bg-primary-fixed selection:text-on-primary-container">
<main class="min-h-screen flex items-center justify-center p-4 md:p-10 relative overflow-hidden">

    {{-- Background decorative elements --}}
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-1/4 -right-1/4 w-[600px] h-[600px] bg-secondary-container opacity-20 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-primary-container opacity-10 rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-[480px] z-10 perspective-container">
        <div class="glass-card rounded-xl p-10 md:p-16 shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-white/40">

            {{-- Logo & Header --}}
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center shadow-lg shadow-primary-container/20">
                        <span class="material-symbols-outlined text-white text-2xl">restaurant_menu</span>
                    </div>
                    <span class="font-heading text-h3 text-primary-container tracking-tight font-black">GoToEat</span>
                </div>
                <h1 class="font-heading text-h3 text-on-background mb-1">Bienvenido de nuevo</h1>
                <p class="font-body text-body-sm text-on-surface-variant">Accede a tu panel de gestión de restaurante</p>
            </div>

            {{-- Session errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-error/10 border border-error/30 rounded-lg flex items-start gap-2">
                    <span class="material-symbols-outlined text-error text-xl shrink-0 mt-[2px]">error</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="font-body text-body-sm text-error">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="font-body text-label-caps text-on-surface-variant block uppercase" for="email">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">mail</span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            autofocus
                            required
                            placeholder="nombre@restaurante.com"
                            class="w-full pl-[48px] pr-4 py-6 bg-surface-bright border @error('email') border-error @else border-outline-variant @enderror rounded-lg focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container transition-all outline-none text-body-md"
                        />
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="font-body text-label-caps text-on-surface-variant block uppercase" for="password">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">lock</span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-[48px] pr-4 py-6 bg-surface-bright border @error('password') border-error @else border-outline-variant @enderror rounded-lg focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container transition-all outline-none text-body-md"
                        />
                    </div>
                </div>

                {{-- Options --}}
                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input
                                name="remember"
                                type="checkbox"
                                class="peer appearance-none w-5 h-5 border border-outline-variant rounded bg-surface-bright checked:bg-primary-container checked:border-primary-container transition-all cursor-pointer"
                            />
                            <span class="material-symbols-outlined absolute text-white text-sm scale-0 peer-checked:scale-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transition-transform pointer-events-none">check</span>
                        </div>
                        <span class="font-body text-body-sm text-on-surface-variant group-hover:text-primary transition-colors">Recordarme</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="font-body text-body-sm font-semibold text-primary hover:text-primary-container transition-colors" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-6 bg-primary-container text-white font-heading text-body-md rounded-lg shadow-lg shadow-primary-container/20 hover:shadow-xl hover:shadow-primary-container/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2"
                >
                    Iniciar Sesión
                    <span class="material-symbols-outlined text-xl">login</span>
                </button>
            </form>

            {{-- Footer --}}
            <div class="mt-16 pt-10 border-t border-outline-variant/30 text-center">
                <p class="font-body text-body-sm text-on-surface-variant">
                    ¿No tienes una cuenta?
                    <a class="font-semibold text-secondary hover:text-on-secondary-container transition-colors" href="{{ route('register') }}">Registrarse Gratis</a>
                </p>
            </div>
        </div>

        <div class="mt-10 flex items-center justify-center gap-6 grayscale opacity-50">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-body-sm">verified_user</span>
                <span class="font-body text-label-caps text-[10px]">SSL 256 bits cifrado</span>
            </div>
            <div class="h-1 w-1 bg-outline-variant rounded-full"></div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-body-sm">shield</span>
                <span class="font-body text-label-caps text-[10px]">Datos protegidos</span>
            </div>
        </div>
    </div>

    {{-- Right side graphic --}}
    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/3 w-[600px] h-[400px] rotate-12">
        <div class="w-full h-full bg-white rounded-[40px] shadow-2xl border border-white/50 overflow-hidden relative">
            <img
                class="w-full h-full object-cover"
                src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1974&auto=format&fit=crop"
                alt="Panel de gestión GoToEat"
            />
            <div class="absolute inset-0 bg-gradient-to-tr from-primary-container/20 to-transparent"></div>
        </div>
    </div>
</main>
</body>
</html>
