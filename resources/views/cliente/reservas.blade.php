<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mis Reservas — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-nav { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-background text-on-surface antialiased font-body-md">

<aside class="hidden md:flex flex-col h-screen w-64 bg-surface-container-low border-r border-outline-variant/20 fixed left-0 top-0 z-50 shadow-md">
    <div class="px-6 py-6 border-b border-outline-variant/20">
        <a href="{{ route('explorar') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">restaurant</span>
            </div>
            <div>
                <p class="font-black text-xl text-primary leading-none">GoToEat</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-widest mt-0.5">Gastronomía</p>
            </div>
        </a>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1">
        @foreach([['explorar','explore','Explorar'],['reservas','calendar_month','Mis Reservas'],['perfil','person','Mi Perfil']] as [$r,$i,$l])
        @php $a = request()->routeIs($r); @endphp
        <a href="{{ route($r) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $a ? 'bg-primary-container text-white font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]" @if($a) style="font-variation-settings:'FILL' 1" @endif>{{ $i }}</span>
            <span class="text-sm">{{ $l }}</span>
        </a>
        @endforeach
    </nav>
    <div class="px-3 py-4 border-t border-outline-variant/20 space-y-1">
        <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-surface-container-high transition-all">
            <img src="{{ auth()->user()->avatarUrl() }}" class="w-8 h-8 rounded-full object-cover border border-outline-variant/30" alt=""/>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-error hover:bg-red-50 transition-all text-sm">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

<main class="md:ml-64 min-h-screen flex flex-col pb-20 md:pb-0">

    <header class="sticky top-0 z-40 glass-nav bg-surface/80 border-b border-outline-variant/20 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <h1 class="font-bold text-lg text-on-surface">Mis Reservas</h1>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('modalReserva').classList.remove('hidden')"
                        class="flex items-center gap-2 px-4 py-2 bg-primary-container text-white rounded-xl font-semibold text-sm hover:bg-primary active:scale-[0.97] transition-all shadow-sm shadow-orange-200">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Nueva Reserva
                </button>
                <img src="{{ auth()->user()->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover" alt=""/>
            </div>
        </div>
    </header>

    @if(session('success'))
    <div class="max-w-5xl mx-auto px-4 md:px-8 pt-4 w-full">
        <div class="p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-semibold text-secondary">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 md:px-8 py-8 w-full">

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-2xl font-black text-on-surface">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold mt-1">Total</p>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-2xl font-black text-primary-container">{{ $stats['month'] }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold mt-1">Este mes</p>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                <p class="text-2xl font-black text-secondary">{{ $stats['confirmed'] }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold mt-1">Confirmadas</p>
            </div>
        </div>

        {{-- Próximas --}}
        <div class="mb-8">
            <h2 class="font-bold text-lg text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container text-[22px]" style="font-variation-settings:'FILL' 1">upcoming</span>
                Próximas y Pendientes
            </h2>

            @if($upcoming->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-200 mb-3 block">calendar_today</span>
                <p class="font-semibold text-on-surface">No tienes reservas próximas</p>
                <p class="text-sm text-gray-400 mt-1 mb-4">Explora los restaurantes y reserva tu mesa.</p>
                <a href="{{ route('explorar') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white rounded-xl font-bold text-sm hover:bg-primary transition-all">
                    <span class="material-symbols-outlined text-[18px]">explore</span>
                    Explorar
                </a>
            </div>
            @else
            <div class="space-y-4">
                @foreach($upcoming as $res)
                @php
                    $rp = $res->restaurant->primary_color ?? '#f97316';
                    $rl = $res->restaurant->logo_path ? Storage::url($res->restaurant->logo_path) : null;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 p-5">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0 overflow-hidden border-2"
                             style="background:{{ $rp }}15;border-color:{{ $rp }}30">
                            @if($rl)
                                <img src="{{ $rl }}" class="w-full h-full object-contain p-1" alt=""/>
                            @else
                                <span class="material-symbols-outlined text-[28px]" style="color:{{ $rp }};font-variation-settings:'FILL' 1">restaurant</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <h3 class="font-bold text-on-surface truncate">{{ $res->restaurant->name }}</h3>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0
                                    @if($res->status==='confirmed') bg-secondary/10 text-secondary
                                    @elseif($res->status==='pending') bg-amber-100 text-amber-700
                                    @else bg-error/10 text-error @endif">
                                    {{ $res->statusLabel() }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 mb-2">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    {{ $res->reservation_date->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ substr($res->reservation_time, 0, 5) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">group</span>
                                    {{ $res->party_size }} {{ $res->party_size === 1 ? 'persona' : 'personas' }}
                                </span>
                            </div>
                            @if($res->waiter)
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                Mesero: <span class="font-semibold ml-0.5">{{ $res->waiter->user->name ?? $res->waiter->position ?? 'Asignado' }}</span>
                            </p>
                            @else
                            <p class="text-xs text-gray-400">Mesero: Por asignar</p>
                            @endif
                            @if($res->notes)
                            <p class="mt-1.5 text-xs text-gray-400 italic">{{ Str::limit($res->notes, 80) }}</p>
                            @endif
                        </div>
                    </div>
                    @if(in_array($res->status, ['pending','confirmed']))
                    <div class="px-5 pb-4 flex justify-end border-t border-gray-50 pt-3">
                        <form method="POST" action="{{ route('reservas.cancel', $res) }}"
                              onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="flex items-center gap-1.5 px-4 py-1.5 border border-error/30 text-error rounded-xl text-xs font-bold hover:bg-red-50 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[15px]">cancel</span>
                                Cancelar Reserva
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Historial --}}
        @if($past->isNotEmpty())
        <div>
            <h2 class="font-bold text-lg text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[22px] text-gray-400">history</span>
                Historial de Reservas
            </h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @foreach($past as $res)
                @php $rp = $res->restaurant->primary_color ?? '#f97316'; @endphp
                <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $rp }}15">
                        <span class="material-symbols-outlined text-[20px]" style="color:{{ $rp }};font-variation-settings:'FILL' 1">restaurant</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-on-surface truncate">{{ $res->restaurant->name }}</p>
                        <p class="text-xs text-gray-400">{{ $res->reservation_date->locale('es')->isoFormat('D MMM, YYYY') }} · {{ substr($res->reservation_time,0,5) }} · {{ $res->party_size }} pax</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                        @if($res->status==='completed') bg-blue-50 text-blue-600
                        @elseif($res->status==='cancelled') bg-gray-100 text-gray-500
                        @else bg-secondary/10 text-secondary @endif">
                        {{ $res->statusLabel() }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>

<nav class="md:hidden fixed bottom-0 left-0 right-0 glass-nav bg-surface/90 border-t border-outline-variant/20 flex justify-around items-center py-2 z-50">
    @foreach([['explorar','explore','Explorar'],['reservas','calendar_month','Reservas'],['perfil','person','Perfil']] as [$r,$i,$l])
    @php $a = request()->routeIs($r); @endphp
    <a href="{{ route($r) }}" class="flex flex-col items-center gap-0.5 px-4 py-1 {{ $a ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined text-[22px]" @if($a) style="font-variation-settings:'FILL' 1" @endif>{{ $i }}</span>
        <span class="text-[10px] font-semibold">{{ $l }}</span>
    </a>
    @endforeach
</nav>

{{-- Modal Nueva Reserva --}}
<div id="modalReserva"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
     onclick="if(event.target===this)document.getElementById('modalReserva').classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:90vh;">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-primary-container">calendar_add_on</span>
                Nueva Reserva
            </h3>
            <button onclick="document.getElementById('modalReserva').classList.add('hidden')" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-[20px] text-gray-400">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('reservas.store') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Restaurante *</label>
                    <select name="restaurant_id" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                        <option value="">Seleccionar restaurante...</option>
                        @foreach($restaurants as $rest)
                        <option value="{{ $rest->id }}" {{ old('restaurant_id') == $rest->id ? 'selected' : '' }}>{{ $rest->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Fecha *</label>
                        <input name="reservation_date" type="date" required
                               min="{{ now()->toDateString() }}"
                               value="{{ old('reservation_date', now()->addDay()->toDateString()) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Hora *</label>
                        <select name="reservation_time" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all">
                            @foreach(['12:00','12:30','13:00','13:30','14:00','14:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00'] as $h)
                            <option value="{{ $h }}" {{ old('reservation_time') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Personas *</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="var i=document.getElementById('psR');i.value=Math.max(1,parseInt(i.value||2)-1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">−</button>
                        <input id="psR" name="party_size" type="number" min="1" max="20" value="{{ old('party_size',2) }}" required
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-center font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none"/>
                        <button type="button" onclick="var i=document.getElementById('psR');i.value=Math.min(20,parseInt(i.value||2)+1)"
                                class="w-10 h-10 rounded-xl bg-gray-100 font-bold text-lg hover:bg-gray-200 active:scale-90 transition-all">+</button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1.5">Notas especiales</label>
                    <textarea name="notes" rows="2" placeholder="Alergias, mesa preferida, ocasión especial..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-primary-container outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                </div>
                @if($errors->any())
                <div class="p-3 bg-error/10 border border-error/20 rounded-xl">
                    @foreach($errors->all() as $e)<p class="text-xs text-error">{{ $e }}</p>@endforeach
                </div>
                @endif
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('modalReserva').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">Cancelar</button>
                <button type="submit" class="flex-1 py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold hover:bg-primary active:scale-[0.97] transition-all shadow-sm">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded',function(){document.getElementById('modalReserva').classList.remove('hidden');});</script>
@endif

</body>
</html>
