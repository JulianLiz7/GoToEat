<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Configura tu restaurante – GoToEat</title>
    @include('partials.head-assets')
    <style>
        .step-shadow { box-shadow: 0 0 0 4px rgba(249,115,22,0.15); }
        .active-step-shadow { box-shadow: 0 0 0 4px rgba(249,115,22,0.15); }
    </style>
</head>
<body class="bg-background font-body text-on-background min-h-screen flex flex-col">

{{-- Header --}}
<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-outline-variant shadow-sm h-16 px-8 flex items-center justify-between">
    <span class="text-2xl font-bold text-primary-container font-heading tracking-tight">GoToEat</span>
    <span class="text-sm text-on-surface-variant">
        Hola, <strong class="text-on-surface">{{ auth()->user()->name }}</strong>
    </span>
</header>

<main class="flex-grow flex items-start justify-center py-10 px-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        {{-- ── Columna izquierda: branding ──────────────────── --}}
        <div class="lg:col-span-5 space-y-6 hidden lg:block">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-outline-variant">
                <h1 class="font-heading text-4xl font-bold text-on-background mb-4 leading-tight">
                    Bienvenido al futuro de la gestión gastronómica.
                </h1>
                <p class="text-on-surface-variant text-base mb-6">
                    Únete a miles de propietarios de restaurantes que han optimizado sus operaciones, aumentado sus ingresos y deleitado a sus clientes con GoToEat.
                </p>
                <div class="relative rounded-xl overflow-hidden aspect-video shadow-2xl">
                    <img class="w-full h-full object-cover"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWcnLASfKpwzTjigDLVfUl9vNiKBS-cWbGXktmYkjnT2b9zpStKqik-OywAQQG0hdM9hiirk0KK9VzE5Lr50PMf7tpvCOI748DkDsZjZqRnXA4apCnsevIC1dKo7jgulhT1Cod9Q1GemYsn8TgpiTBUlYI-IFKzwg5zuh17eDrF0gHS4jGQb-Rx6VcTcqBOGJO0bOeTKR6xb8RNmm-MCGul1zqgIkHLmx8B_9vlfyClsGpXJK35ofb79jotgD5O1o1DvNtfPDv5vE"
                         alt="Interior de restaurante"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-5">
                        <span class="text-white font-heading text-xl font-semibold">Gestión Digital, Conexión Humana.</span>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        @foreach([
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuDK2psOlMJY90O6CpTuDwgE_gk9abaw3XHjiEM0l73xdUMxU8jvcHkIKmJrrCswieWgLROOrPnwrLxrsNH8W_uYbXCIShO5rjBEO2hUYkBRF227qvkyKprnOOkKNmMZ8mum7UOxSDbRWXsKjPR4IO_hwl8zykPCNbCBpN1decUUORuZL09YPElTXpQPtUplM36UIlcqHtlt-Dir5iSwtWCNVAWuuK7LhIw7zHAh_GkMRq4WIl2-iKSULZpZtRo8oJT68f3CljXRj6w',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuAG1nVoBbxK-1KewW008LWkdyjToQ3b7ObjPnm6ZFATCGglJujhuzLecJ9IIvWxU0eDOS20Akc2XKI3Mon9PwjN_rVKY2W2293vs6oyHhwTC-ESjg9sGcybjsPsll_0SXl2GRWymYWvX43tnw0Jk_QMCvvw7YOnhFq9knodWzVNLtvkPVNxMiRY-JgxHsbJYovqlgHYJs2v_nv2e2LgsJWIuwrrhiz2ppNm5esuqISs4mVeifj2_JrZRDjUeKa1erRgQFE_FoKzMSc',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuAg5haTRnpQuxr80D0FlpTfI9z1G9jbjHG2fmHMknPz4c6fYz0LLEtRbaietV9iJ2arGvugODvDyrZ7nLxeLeyl1VkjLXM0mpTb51CBZZsqsg3JI17ypUfG9AeIbqZyfZ9q5RAVOVE3padj5YstSlVOsFzzS4Jb4kzHEwOK4SkzRq3T_Ec1aLyS3cMUXcQ7_3TDKZfprDw1x5rYQkzub1qqmMEjhYSiDaqBQjd0ZHMB9e5p-TGqbc-bIZ_DgyzLrgOidzrvpNlJUNg'
                        ] as $avatar)
                        <img src="{{ $avatar }}" class="w-10 h-10 rounded-full border-2 border-white object-cover" alt=""/>
                        @endforeach
                    </div>
                    <p class="text-sm text-on-surface-variant">Confiado por líderes culinarios a nivel mundial.</p>
                </div>
            </div>
        </div>

        {{-- ── Columna derecha: stepper + formulario ────────── --}}
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-xl border border-outline-variant overflow-hidden">

            {{-- Stepper --}}
            <div class="bg-surface-container-low px-8 py-5 border-b border-outline-variant">
                <div class="flex items-center justify-between max-w-sm mx-auto">
                    {{-- Paso 1 (activo) --}}
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-sm active-step-shadow">1</div>
                        <span class="text-xs font-bold text-primary-container uppercase tracking-wider">Detalles</span>
                    </div>
                    <div class="h-px bg-outline-variant flex-grow mx-3 mb-5"></div>
                    {{-- Paso 2 --}}
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-surface-container text-outline flex items-center justify-center font-bold text-sm">2</div>
                        <span class="text-xs font-semibold text-outline uppercase tracking-wider">Contacto</span>
                    </div>
                    <div class="h-px bg-outline-variant flex-grow mx-3 mb-5"></div>
                    {{-- Paso 3 --}}
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-surface-container text-outline flex items-center justify-center font-bold text-sm">3</div>
                        <span class="text-xs font-semibold text-outline uppercase tracking-wider">Equipo</span>
                    </div>
                </div>
            </div>

            {{-- Formulario --}}
            <div class="p-8 md:p-10">
                <div class="mb-7">
                    <h2 class="font-heading text-2xl font-bold text-on-background mb-1">Detalles del restaurante</h2>
                    <p class="text-on-surface-variant text-sm">Cuéntanos sobre tu establecimiento para personalizar tu experiencia.</p>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-error-container border border-red-200 text-sm text-error space-y-1">
                    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('onboarding.step1.store') }}" class="space-y-5">
                    @csrf

                    {{-- Nombre --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Nombre del restaurante *</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name', $data['name'] ?? '') }}"
                                   required placeholder="ej. El Tenedor de Oro"
                                   class="w-full px-4 py-3 pr-11 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface"/>
                            <span class="material-symbols-outlined absolute right-3 top-3 text-outline text-xl">restaurant</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Categoría --}}
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Categoría *</label>
                            <div class="relative">
                                <select name="category" required
                                        class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface appearance-none bg-white">
                                    <option value="">Selecciona…</option>
                                    @foreach(['Alta Cocina','Restaurante Casual','Comida Rápida','Café / Panadería','Bar / Pub','Food Truck','Buffet','Asadero','Marisquería','Pizzería'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $data['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-3 text-outline text-xl pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        {{-- Tipo de cocina --}}
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Tipo de cocina *</label>
                            <div class="relative">
                                <select name="cuisine_type" required
                                        class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface appearance-none bg-white">
                                    <option value="">Selecciona…</option>
                                    @foreach(['Italiana','Mexicana','Japonesa','Americana','Mediterránea','Colombiana','China','Francesa','Española','Peruana','Árabe','Vegetariana','Fusión','Internacional'] as $c)
                                    <option value="{{ $c }}" {{ old('cuisine_type', $data['cuisine_type'] ?? '') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-3 text-outline text-xl pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Descripción <span class="font-normal normal-case">(opcional)</span></label>
                        <textarea name="description" rows="3" placeholder="Cuéntale a tus clientes qué hace especial a tu restaurante…"
                                  class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface resize-none">{{ old('description', $data['description'] ?? '') }}</textarea>
                    </div>

                    {{-- Navegación --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>Volver
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-primary-container text-white px-7 py-3 rounded-xl font-bold text-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-orange-500/20">
                            Continuar a Contacto
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Trust footer --}}
            <div class="px-8 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-center gap-8">
                <div class="flex items-center gap-2 text-outline grayscale hover:grayscale-0 hover:text-on-surface transition-all">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                    <span class="text-xs font-bold uppercase tracking-wider">Datos seguros</span>
                </div>
                <div class="flex items-center gap-2 text-outline grayscale hover:grayscale-0 hover:text-on-surface transition-all">
                    <span class="material-symbols-outlined text-lg">support_agent</span>
                    <span class="text-xs font-bold uppercase tracking-wider">Soporte 24/7</span>
                </div>
            </div>
        </div>

    </div>
</main>

</body>
</html>
