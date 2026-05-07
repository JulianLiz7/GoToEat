<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Crear cuenta – GoToEat</title>
    @include('partials.head-assets')
</head>
<body class="bg-background font-body text-on-background min-h-screen flex flex-col">

{{-- Header --}}
<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-outline-variant shadow-sm h-16 px-8 flex items-center justify-between">
    <a href="{{ url('/') }}" class="text-2xl font-bold text-primary-container font-heading tracking-tight">GoToEat</a>
    <a href="{{ route('login') }}" class="text-sm font-medium text-on-surface-variant hover:text-primary-container transition-colors">
        ¿Ya tienes cuenta? <span class="text-primary-container font-semibold">Inicia sesión</span>
    </a>
</header>

<main class="flex-grow flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- Brand mark --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-container rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="material-symbols-outlined text-3xl text-white">restaurant</span>
            </div>
            <h1 class="text-3xl font-bold text-on-background font-heading">Crea tu cuenta</h1>
            <p class="text-on-surface-variant mt-1 text-sm">Únete a GoToEat — es gratis</p>
        </div>

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

        {{-- Form card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-outline-variant p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Nombre --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Nombre completo
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">person</span>
                        <input type="text" name="name" value="{{ old('name') }}"
                            required autofocus autocomplete="name"
                            placeholder="Tu nombre"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border @error('name') border-error bg-error-container/20 @else border-outline-variant @enderror
                                   focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none transition-all text-on-surface"/>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            required autocomplete="username"
                            placeholder="tu@correo.com"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border @error('email') border-error bg-error-container/20 @else border-outline-variant @enderror
                                   focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none transition-all text-on-surface"/>
                    </div>
                </div>

                {{-- Contraseña --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">lock</span>
                        <input type="password" name="password"
                            required autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border @error('password') border-error bg-error-container/20 @else border-outline-variant @enderror
                                   focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none transition-all text-on-surface"/>
                    </div>
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">
                        Confirmar contraseña
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">lock_reset</span>
                        <input type="password" name="password_confirmation"
                            required autocomplete="new-password"
                            placeholder="Repite tu contraseña"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant
                                   focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none transition-all text-on-surface"/>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-primary-container text-white py-3.5 rounded-xl font-bold text-base
                           hover:opacity-90 active:scale-[0.98] transition-all shadow-lg
                           flex items-center justify-center gap-2 mt-2">
                    <span class="material-symbols-outlined text-xl">how_to_reg</span>
                    Crear cuenta
                </button>
            </form>
        </div>

        {{-- Info cards --}}
        <div class="mt-5 grid grid-cols-2 gap-3">
            <div class="bg-white/80 rounded-xl border border-outline-variant p-4 text-center">
                <span class="material-symbols-outlined text-primary-container block mb-1">store</span>
                <p class="text-xs font-semibold text-on-surface">Tengo un restaurante</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Configúralo desde tu panel</p>
            </div>
            <div class="bg-white/80 rounded-xl border border-outline-variant p-4 text-center">
                <span class="material-symbols-outlined text-secondary block mb-1">explore</span>
                <p class="text-xs font-semibold text-on-surface">Solo quiero explorar</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Encuentra restaurantes</p>
            </div>
        </div>

        <p class="text-center text-xs text-on-surface-variant mt-5">
            Al registrarte aceptas nuestros
            <a href="#" class="text-primary-container hover:underline">Términos de Servicio</a>
            y
            <a href="#" class="text-primary-container hover:underline">Política de Privacidad</a>.
        </p>
    </div>
</main>

</body>
</html>
