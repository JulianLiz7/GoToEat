<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GoToEat') }}</title>
    @include('partials.head-assets')
</head>
<body class="bg-background font-body text-on-background min-h-screen flex flex-col">

    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-outline-variant shadow-sm h-16 px-8 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-primary-container font-heading tracking-tight">GoToEat</a>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-container text-white rounded-xl text-sm font-semibold hover:opacity-90 transition-all">Registrarse</a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-primary-container rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <span class="material-symbols-outlined text-2xl text-white">restaurant</span>
                </div>
                <p class="text-sm text-on-surface-variant font-heading font-semibold">GoToEat</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-outline-variant p-8">
                {{ $slot }}
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
