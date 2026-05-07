<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Información de contacto – GoToEat</title>
    @include('partials.head-assets')
    <style>.active-step-shadow { box-shadow: 0 0 0 4px rgba(249,115,22,0.15); }</style>
</head>
<body class="bg-background font-body text-on-background min-h-screen flex flex-col">

<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-outline-variant shadow-sm h-16 px-8 flex items-center justify-between">
    <span class="text-2xl font-bold text-primary-container font-heading tracking-tight">GoToEat</span>
    <span class="text-sm text-on-surface-variant">
        Hola, <strong class="text-on-surface">{{ auth()->user()->name }}</strong>
    </span>
</header>

<main class="flex-grow flex items-start justify-center py-10 px-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        {{-- Columna izquierda --}}
        <div class="lg:col-span-5 space-y-6 hidden lg:block">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-outline-variant">
                <div class="w-14 h-14 bg-primary-container/10 rounded-2xl flex items-center justify-center mb-5">
                    <span class="material-symbols-outlined text-3xl text-primary-container">location_on</span>
                </div>
                <h2 class="font-heading text-3xl font-bold text-on-background mb-3">¿Dónde encontramos tu restaurante?</h2>
                <p class="text-on-surface-variant text-base mb-6">
                    Tu información de contacto es lo primero que los clientes verán cuando busquen tu restaurante en GoToEat.
                </p>
                <div class="space-y-4">
                    @foreach([
                        ['location_on',  'Dirección visible en el explorador de restaurantes'],
                        ['phone',        'Teléfono para reservas y consultas directas'],
                        ['mail',         'Email para notificaciones y comunicaciones'],
                        ['qr_code_2',    'QR de mesa generado automáticamente a partir de tu dirección'],
                    ] as [$icon, $text])
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-lg text-primary-container">{{ $icon }}</span>
                        </div>
                        <p class="text-sm text-on-surface-variant mt-1.5">{{ $text }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Columna derecha --}}
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-xl border border-outline-variant overflow-hidden">

            {{-- Stepper --}}
            <div class="bg-surface-container-low px-8 py-5 border-b border-outline-variant">
                <div class="flex items-center justify-between max-w-sm mx-auto">
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-secondary text-white flex items-center justify-center font-bold text-sm">
                            <span class="material-symbols-outlined text-lg">check</span>
                        </div>
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Detalles</span>
                    </div>
                    <div class="h-px bg-primary-container flex-grow mx-3 mb-5"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-sm active-step-shadow">2</div>
                        <span class="text-xs font-bold text-primary-container uppercase tracking-wider">Contacto</span>
                    </div>
                    <div class="h-px bg-outline-variant flex-grow mx-3 mb-5"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-surface-container text-outline flex items-center justify-center font-bold text-sm">3</div>
                        <span class="text-xs font-semibold text-outline uppercase tracking-wider">Equipo</span>
                    </div>
                </div>
            </div>

            {{-- Formulario --}}
            <div class="p-8 md:p-10">
                <div class="mb-7">
                    <h2 class="font-heading text-2xl font-bold text-on-background mb-1">Información de contacto</h2>
                    <p class="text-on-surface-variant text-sm">Estos datos le permiten a tus clientes encontrarte y contactarte. Todos son opcionales.</p>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-error-container border border-red-200 text-sm text-error space-y-1">
                    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('onboarding.step2.store') }}" class="space-y-5">
                    @csrf

                    {{-- Teléfono --}}
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Teléfono</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">phone</span>
                            <input type="tel" name="phone" value="{{ old('phone', $data['phone'] ?? '') }}"
                                   placeholder="+57 300 000 0000"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface"/>
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Dirección</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">location_on</span>
                            <input type="text" name="address" value="{{ old('address', $data['address'] ?? '') }}"
                                   placeholder="Calle 123 # 45-67, Ciudad"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface"/>
                        </div>
                    </div>

                    {{-- Email del restaurante --}}
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Email del restaurante</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">mail</span>
                            <input type="email" name="email" value="{{ old('email', $data['email'] ?? '') }}"
                                   placeholder="contacto@mirestaurante.com"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface"/>
                        </div>
                    </div>

                    {{-- Resumen del paso anterior --}}
                    <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant">
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Restaurante</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg text-primary-container">restaurant</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ session('onboarding.name') }}</p>
                                <p class="text-xs text-on-surface-variant">{{ session('onboarding.category') }} · {{ session('onboarding.cuisine_type') }}</p>
                            </div>
                            <a href="{{ route('onboarding.step1') }}" class="ml-auto text-xs text-primary-container hover:underline">Editar</a>
                        </div>
                    </div>

                    {{-- Navegación --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('onboarding.step1') }}"
                           class="flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>Volver
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-primary-container text-white px-7 py-3 rounded-xl font-bold text-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-orange-500/20">
                            Continuar a Equipo
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-8 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-center gap-8">
                <div class="flex items-center gap-2 text-outline">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                    <span class="text-xs font-bold uppercase tracking-wider">Datos seguros</span>
                </div>
                <div class="flex items-center gap-2 text-outline">
                    <span class="material-symbols-outlined text-lg">support_agent</span>
                    <span class="text-xs font-bold uppercase tracking-wider">Soporte 24/7</span>
                </div>
            </div>
        </div>

    </div>
</main>

</body>
</html>
