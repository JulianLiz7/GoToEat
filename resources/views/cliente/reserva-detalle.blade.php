<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reserva #{{ $reservation->id }} — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f0f2f7; }
        .sidebar { background: linear-gradient(180deg, #111827 0%, #1a2332 100%); }
        .sidebar-item { color: rgba(255,255,255,0.6); transition: all .2s; }
        .sidebar-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar-item.active { background: rgba(249,115,22,0.15); color: #f97316; font-weight: 600; }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .ticket-dashes { border-style: dashed; }
        .qr-container svg { width: 100% !important; height: auto !important; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-body antialiased" x-data="{ sideOpen: false }">

{{-- SIDEBAR --}}
<aside class="sidebar hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 z-50 shadow-2xl">
    <div class="px-6 pt-8 pb-6">
        <a href="{{ route('explorar') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center"><span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">restaurant</span></div>
            <div><p class="font-black text-xl text-white tracking-tight leading-none">GoTo<span class="text-primary">Eat</span></p></div>
        </a>
    </div>
    <nav class="flex-1 px-3 space-y-1">
        <a href="{{ route('explorar') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]">explore</span><span class="text-sm font-medium">Explorar</span></a>
        <a href="{{ route('reservas') }}" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">calendar_month</span><span class="text-sm font-medium">Mis Reservas</span></a>
        <a href="{{ route('perfil') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl"><span class="material-symbols-outlined text-[20px]">person</span><span class="text-sm font-medium">Mi Perfil</span></a>
    </nav>
    <div class="px-4 py-6 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-white/50 hover:text-red-400 hover:bg-red-500/10 transition-all text-sm">
                <span class="material-symbols-outlined text-[18px]">logout</span>Cerrar sesión
            </button>
        </form>
    </div>
</aside>

<main class="md:ml-64 min-h-screen">
    {{-- TOP BAR --}}
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-4 px-6 py-4">
            <a href="{{ route('reservas') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-800 transition-colors text-sm font-medium">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Mis Reservas
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-semibold text-gray-900">Reserva #{{ $reservation->id }}</span>
        </div>
    </header>

    <div class="px-4 py-8 max-w-2xl mx-auto">

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        {{-- ── TICKET CARD ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            {{-- Restaurant cover header --}}
            <div class="relative h-40 bg-gray-900" style="@if($reservation->restaurant->cover_path) background-image: url('{{ Storage::url($reservation->restaurant->cover_path) }}'); background-size: cover; background-position: center; @endif">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/70"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 flex items-end gap-4">
                    @if($reservation->restaurant->logo_path)
                    <img src="{{ Storage::url($reservation->restaurant->logo_path) }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-white/40 shadow-lg">
                    @else
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-lg border-2 border-white/40"
                         style="background: {{ $reservation->restaurant->primary_color ?? '#f97316' }}">
                        {{ strtoupper(substr($reservation->restaurant->name, 0, 2)) }}
                    </div>
                    @endif
                    <div>
                        <h2 class="font-heading text-xl font-bold text-white">{{ $reservation->restaurant->name }}</h2>
                        @if($reservation->restaurant->cuisine_type)
                        <p class="text-white/70 text-sm">{{ $reservation->restaurant->cuisine_type }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Status badge --}}
            <div class="px-6 pt-5 pb-4 flex items-center justify-between border-b border-gray-100">
                <span class="status-{{ $reservation->status }} text-xs font-bold px-3 py-1.5 rounded-full">
                    {{ $reservation->statusLabel() }}
                </span>
                <span class="text-xs text-gray-400">Reserva #{{ $reservation->id }}</span>
            </div>

            {{-- Reservation details --}}
            <div class="px-6 py-5 grid grid-cols-2 gap-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings:'FILL' 1">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha</p>
                        <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->reservation_date)->isoFormat('D [de] MMMM') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings:'FILL' 1">schedule</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Hora</p>
                        <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings:'FILL' 1">group</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Personas</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $reservation->party_size }} personas</p>
                    </div>
                </div>
                @if($reservation->waiter)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 text-[20px]" style="font-variation-settings:'FILL' 1">person_pin</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Mesero</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $reservation->waiter->user->name ?? 'Asignado' }}</p>
                    </div>
                </div>
                @endif
            </div>

            @if($reservation->notes)
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Notas especiales</p>
                <p class="text-sm text-gray-700 italic">{{ $reservation->notes }}</p>
            </div>
            @endif

            @if($reservation->selected_items && count($reservation->selected_items))
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="text-xs text-gray-400 mb-2">Pre-orden seleccionada</p>
                <div class="space-y-1.5">
                    @foreach($reservation->selected_items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700">{{ $item['name'] ?? '' }} @if(!empty($item['qty'])) × {{ $item['qty'] }} @endif</span>
                        @if(!empty($item['price']))
                        <span class="text-gray-500">${{ number_format($item['price'] * ($item['qty'] ?? 1), 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── QR CODE (El Pase de Entrada) ────────────────────── --}}
            <div class="px-6 py-8 text-center">
                <div class="inline-block bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tu Pase de Entrada</p>

                    {{-- QR SVG --}}
                    <div class="qr-container w-48 h-48 mx-auto bg-white rounded-2xl p-3 shadow-sm border border-gray-100">
                        {!! $qrSvg !!}
                    </div>

                    <p class="text-xs text-gray-500 mt-4 max-w-xs mx-auto">
                        Presenta este código QR al llegar al restaurante.
                        El staff lo escaneará para confirmar tu reserva.
                    </p>
                    <p class="font-mono text-xs text-gray-400 mt-2 bg-gray-100 px-3 py-1.5 rounded-lg inline-block">
                        {{ strtoupper(substr($reservation->qr_token, 0, 8)) }}
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-6 flex gap-3">
                <a href="{{ route('restaurante.show', $reservation->restaurant) }}"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-3 rounded-xl text-center transition-all">
                    Ver restaurante
                </a>
                @if(in_array($reservation->status, ['pending', 'confirmed']))
                <form method="POST" action="{{ route('reservas.cancel', $reservation) }}" class="flex-1"
                      onsubmit="return confirm('¿Seguro que quieres cancelar esta reserva?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold py-3 rounded-xl transition-all">
                        Cancelar reserva
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Print hint --}}
        <p class="text-center text-xs text-gray-400 mt-4">
            <span class="material-symbols-outlined text-[14px] align-middle">info</span>
            Puedes tomar una captura de pantalla para guardar tu QR sin internet.
        </p>
    </div>
</main>
</body>
</html>
