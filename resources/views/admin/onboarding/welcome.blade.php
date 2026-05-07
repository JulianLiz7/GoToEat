<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>¡Bienvenido a GoToEat!</title>
    @include('partials.head-assets')
    <style>
        @keyframes pop { 0%{transform:scale(0.5);opacity:0} 70%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
        .pop-in { animation: pop 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.15s; opacity:0; }
        .delay-2 { animation-delay: 0.3s;  opacity:0; }
        .delay-3 { animation-delay: 0.45s; opacity:0; }
        .delay-4 { animation-delay: 0.6s;  opacity:0; }
    </style>
</head>
<body class="bg-background font-body text-on-background min-h-screen flex flex-col items-center justify-center px-4 py-12">

{{-- Confeti decorativo --}}
<div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
    @for($i = 0; $i < 18; $i++)
    <div class="absolute rounded-full opacity-20"
         style="
            width: {{ rand(6,14) }}px;
            height: {{ rand(6,14) }}px;
            background: {{ ['#f97316','#006c49','#006398','#ffb690','#6cf8bb'][$i % 5] }};
            left: {{ rand(0,100) }}%;
            top: {{ rand(0,100) }}%;
            animation: fadeUp {{ 0.5 + $i * 0.15 }}s ease forwards;
         ">
    </div>
    @endfor
</div>

<div class="w-full max-w-lg text-center">

    {{-- Ícono animado --}}
    <div class="pop-in w-24 h-24 bg-gradient-to-br from-primary-container to-primary rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-orange-500/30">
        <span class="material-symbols-outlined text-5xl text-white" style="font-variation-settings:'FILL' 1;">celebration</span>
    </div>

    {{-- Título --}}
    <h1 class="fade-up delay-1 font-heading text-4xl font-bold text-on-background mb-2">
        ¡Tu restaurante está listo!
    </h1>
    <p class="fade-up delay-2 text-on-surface-variant text-lg mb-8">
        <strong class="text-on-surface">{{ $restaurant->name }}</strong> ya está en GoToEat. Ahora puedes gestionar tu operación completa.
    </p>

    {{-- Resumen de lo creado --}}
    <div class="fade-up delay-2 bg-white rounded-2xl border border-outline-variant shadow-lg p-6 mb-6 text-left space-y-3">
        <div class="flex items-center gap-3 pb-3 border-b border-outline-variant">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-xl text-primary-container">store</span>
            </div>
            <div>
                <p class="font-semibold text-on-surface">{{ $restaurant->name }}</p>
                <p class="text-xs text-on-surface-variant">{{ $restaurant->category }} · {{ $restaurant->cuisine_type }}</p>
            </div>
            <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-secondary">● Activo</span>
        </div>

        @if($restaurant->address)
        <div class="flex items-center gap-3 text-sm text-on-surface-variant">
            <span class="material-symbols-outlined text-lg text-outline">location_on</span>
            {{ $restaurant->address }}
        </div>
        @endif

        @if($restaurant->phone)
        <div class="flex items-center gap-3 text-sm text-on-surface-variant">
            <span class="material-symbols-outlined text-lg text-outline">phone</span>
            {{ $restaurant->phone }}
        </div>
        @endif
    </div>

    {{-- Invitaciones enviadas --}}
    @if($invites->isNotEmpty())
    <div class="fade-up delay-3 bg-green-50 rounded-2xl border border-green-200 p-5 mb-6 text-left">
        <p class="text-sm font-bold text-secondary mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">group_add</span>
            {{ $invites->count() }} {{ $invites->count() === 1 ? 'invitación registrada' : 'invitaciones registradas' }}
        </p>
        <div class="space-y-2">
            @foreach($invites as $invite)
            <div class="flex items-center gap-2 text-sm text-on-surface">
                <span class="material-symbols-outlined text-base text-secondary">mail</span>
                <span>{{ $invite['email'] }}</span>
                <span class="ml-auto px-2 py-0.5 rounded-full text-xs bg-white border border-green-200 text-secondary font-medium capitalize">
                    {{ $invite['role'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Próximos pasos --}}
    <div class="fade-up delay-3 grid grid-cols-3 gap-3 mb-8">
        @foreach([
            ['menu_book',       'Crea tu menú',        'Agrega tus platos y precios'],
            ['table_restaurant','Configura mesas',     'Genera los QR de cada mesa'],
            ['groups',          'Gestiona personal',   'Asigna roles y turnos'],
        ] as [$icon, $title, $desc])
        <div class="bg-white rounded-xl border border-outline-variant p-4 text-center opacity-60">
            <span class="material-symbols-outlined text-2xl text-outline mb-1 block">{{ $icon }}</span>
            <p class="text-xs font-semibold text-on-surface">{{ $title }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">{{ $desc }}</p>
            <span class="text-xs text-outline">Pronto</span>
        </div>
        @endforeach
    </div>

    {{-- CTA principal --}}
    <a href="{{ route('dashboard') }}"
       class="fade-up delay-4 inline-flex items-center gap-2 bg-primary-container text-white px-10 py-4 rounded-2xl font-bold text-base hover:opacity-90 active:scale-[0.98] transition-all shadow-xl shadow-orange-500/25">
        <span class="material-symbols-outlined text-xl">dashboard</span>
        Ir a mi panel de control
    </a>

    <p class="fade-up delay-4 text-xs text-on-surface-variant mt-5">
        Puedes completar la configuración de tu restaurante en cualquier momento desde el panel.
    </p>
</div>

</body>
</html>
