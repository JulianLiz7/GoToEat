<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Invitar equipo – GoToEat</title>
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
                <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center mb-5">
                    <span class="material-symbols-outlined text-3xl text-secondary">groups</span>
                </div>
                <h2 class="font-heading text-3xl font-bold text-on-background mb-3">
                    Construye tu equipo desde el primer día.
                </h2>
                <p class="text-on-surface-variant text-base mb-6">
                    Invita a tu personal para que gestionen pedidos, mesas e inventario según su rol.
                </p>
                <div class="space-y-4">
                    @foreach([
                        ['admin',        'Administrador', 'Acceso completo al panel de gestión', 'bg-orange-50 text-primary-container'],
                        ['chef',         'Chef / Cocina',  'Gestiona pedidos y estado de la cocina', 'bg-blue-50 text-tertiary'],
                        ['waiter',       'Mesero',         'Atiende mesas y toma pedidos', 'bg-green-50 text-secondary'],
                        ['cashier',      'Cajero',         'Gestiona pagos y cierre de caja', 'bg-purple-50 text-purple-600'],
                    ] as [$val, $label, $desc, $color])
                    <div class="flex items-start gap-3 p-3 rounded-xl border border-outline-variant">
                        <div class="w-9 h-9 {{ $color }} rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-lg">badge</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-on-surface">{{ $label }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-on-surface-variant mt-5 p-3 bg-surface-container-low rounded-xl border border-outline-variant">
                    <span class="material-symbols-outlined text-sm align-middle text-outline mr-1">info</span>
                    Puedes saltarte este paso y agregar más personas desde el panel de personal.
                </p>
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
                    <div class="h-px bg-secondary flex-grow mx-3 mb-5"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-secondary text-white flex items-center justify-center font-bold text-sm">
                            <span class="material-symbols-outlined text-lg">check</span>
                        </div>
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Contacto</span>
                    </div>
                    <div class="h-px bg-primary-container flex-grow mx-3 mb-5"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-sm active-step-shadow">3</div>
                        <span class="text-xs font-bold text-primary-container uppercase tracking-wider">Equipo</span>
                    </div>
                </div>
            </div>

            {{-- Formulario --}}
            <div class="p-8 md:p-10">
                <div class="mb-7">
                    <h2 class="font-heading text-2xl font-bold text-on-background mb-1">Invita a tu equipo</h2>
                    <p class="text-on-surface-variant text-sm">Ingresa los correos de las personas que trabajarán contigo. Recibirán una invitación para unirse.</p>
                </div>

                <form method="POST" action="{{ route('onboarding.step3.store') }}" class="space-y-5" x-data="{ invites: [{ email:'', role:'waiter' }, { email:'', role:'waiter' }, { email:'', role:'waiter' }] }">
                    @csrf

                    {{-- Filas de invitación --}}
                    <template x-for="(invite, i) in invites" :key="i">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-xl pointer-events-none">mail</span>
                                <input type="email"
                                       :name="`invites[${i}][email]`"
                                       x-model="invite.email"
                                       placeholder="correo@ejemplo.com"
                                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface text-sm"/>
                            </div>
                            <div class="relative">
                                <select :name="`invites[${i}][role]`"
                                        x-model="invite.role"
                                        class="pl-3 pr-8 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container outline-none transition-all text-on-surface text-sm appearance-none bg-white">
                                    <option value="waiter">Mesero</option>
                                    <option value="chef">Chef</option>
                                    <option value="cashier">Cajero</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-2 top-3 text-outline text-base pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </template>

                    {{-- Agregar más --}}
                    <button type="button"
                            @click="invites.push({ email:'', role:'waiter' })"
                            class="flex items-center gap-2 text-sm font-semibold text-primary-container hover:opacity-80 transition-all">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Agregar otra persona
                    </button>

                    {{-- Resumen --}}
                    <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant">
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Resumen del restaurante</p>
                        <div class="space-y-1 text-sm text-on-surface">
                            <p><span class="text-on-surface-variant">Nombre:</span> <strong>{{ session('onboarding.name') }}</strong></p>
                            <p><span class="text-on-surface-variant">Categoría:</span> {{ session('onboarding.category') }} · {{ session('onboarding.cuisine_type') }}</p>
                            @if(session('onboarding.address'))
                            <p><span class="text-on-surface-variant">Dirección:</span> {{ session('onboarding.address') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Navegación --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('onboarding.step2') }}"
                           class="flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>Volver
                        </a>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('onboarding.step3.store') }}"
                               onclick="event.preventDefault(); document.querySelector('form').submit();"
                               class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors">
                                Omitir este paso
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-primary-container text-white px-7 py-3 rounded-xl font-bold text-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-orange-500/20">
                                <span class="material-symbols-outlined text-lg">rocket_launch</span>
                                Crear mi restaurante
                            </button>
                        </div>
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
