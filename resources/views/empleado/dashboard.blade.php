<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Panel del Empleado | GoToEat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body text-on-surface min-h-screen">

<!-- SideNavBar (Desktop) -->
<aside class="hidden md:flex flex-col h-full p-4 gap-2 bg-surface-container-low fixed left-0 top-0 w-64 z-[60] shadow-md">
    <div class="mb-8 px-2 py-4">
        <h1 class="font-heading text-h3 font-bold text-primary">GoTo<span class="text-primary-container">Eat</span></h1>
        <p class="text-body-sm text-on-surface-variant uppercase tracking-widest text-[10px] font-bold mt-1">Panel del Empleado</p>
    </div>
    <nav class="flex-1 flex flex-col gap-1">
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-xl font-bold transition-all hover:translate-x-1" href="{{ route('empleado.dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">dashboard</span>
            <span class="text-sm">Inicio</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.turnos') }}">
            <span class="material-symbols-outlined">schedule</span>
            <span class="text-sm">Turnos</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.pagos') }}">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-sm">Pagos</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.perfil') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Perfil</span>
        </a>
    </nav>
    <div class="mt-auto flex flex-col gap-2 pt-4 border-t border-outline-variant/30">
        <div class="flex items-center gap-3 px-4 py-2">
            <div class="w-9 h-9 bg-primary-container rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-on-surface-variant truncate">{{ $cargo }}</p>
            </div>
        </div>
        <button onclick="document.getElementById('logout-form').submit()"
            class="flex items-center gap-3 px-4 py-2.5 text-error hover:bg-error/5 rounded-xl transition-all hover:translate-x-1 w-full text-left">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span class="text-sm font-medium">Cerrar Sesión</span>
        </button>
    </div>
</aside>

<!-- Main Content -->
<main class="md:ml-64 flex flex-col min-h-screen pb-20 md:pb-0">

    <!-- Top Bar -->
    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4"
            x-data="notifPoller({{ count($notificaciones) }}, '{{ route('empleado.notificaciones') }}')"
            x-init="init()">
        <div>
            <h2 class="font-heading text-lg font-bold text-on-surface">Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}</h2>
            <p class="text-xs text-on-surface-variant">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Campana de notificaciones con badge --}}
            <button @click="panelOpen = !panelOpen" class="relative p-2 rounded-xl hover:bg-surface-container-high transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-2xl"
                      :style="count > 0 ? 'font-variation-settings:FILL 1' : ''"
                      :class="count > 0 ? 'text-primary' : ''">notifications</span>
                <template x-if="count > 0">
                    <span class="absolute top-1 right-1 w-5 h-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white"
                          x-text="count > 9 ? '9+' : count"></span>
                </template>
            </button>

            <a href="{{ route('empleado.perfil') }}" class="flex items-center gap-2 px-3 py-1.5 bg-surface-container rounded-xl hover:bg-surface-container-high transition-all">
                <div class="w-8 h-8 bg-primary-container rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                </div>
                <span class="text-sm font-medium text-on-surface hidden sm:block">{{ explode(' ', auth()->user()->name)[0] }}</span>
            </a>
        </div>

        {{-- Panel desplegable de notificaciones --}}
        <div x-show="panelOpen" x-cloak @click.outside="panelOpen = false"
             class="absolute top-full right-4 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings:'FILL' 1">notifications_active</span>
                    <span class="font-bold text-sm text-on-surface">Avisos del equipo</span>
                    <span x-show="count > 0" class="bg-error text-white text-[10px] font-black px-1.5 py-0.5 rounded-full" x-text="count"></span>
                </div>
                <button @click="panelOpen = false" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                <template x-if="items.length === 0">
                    <div class="py-8 text-center text-gray-400 text-sm">Sin avisos activos</div>
                </template>
                <template x-for="n in items" :key="n.id">
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-0.5"
                                 :class="`bg-${n.color}-50`">
                                <span class="material-symbols-outlined text-sm"
                                      :class="`text-${n.color}-500`"
                                      style="font-variation-settings:'FILL' 1"
                                      x-text="n.icono"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-on-surface truncate" x-text="n.titulo"></p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="n.mensaje"></p>
                                <p class="text-[10px] text-gray-400 mt-1" x-text="n.tiempo"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Toast de nueva notificación --}}
        <div x-show="toast" x-cloak x-transition
             class="fixed bottom-6 right-6 z-[100] bg-on-surface text-white rounded-2xl shadow-2xl px-5 py-4 flex items-start gap-3 max-w-sm">
            <span class="material-symbols-outlined text-primary text-[22px] shrink-0" style="font-variation-settings:'FILL' 1">notification_important</span>
            <div>
                <p class="font-bold text-sm" x-text="toastMsg.titulo"></p>
                <p class="text-xs text-gray-300 mt-0.5" x-text="toastMsg.mensaje"></p>
            </div>
            <button @click="toast = false" class="shrink-0 text-gray-400 hover:text-white ml-2">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="px-4 md:px-6 py-6 max-w-7xl mx-auto w-full space-y-6">

        <!-- Employee Profile Header -->
        <section class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-container to-primary rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-primary-container/30 shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="font-heading text-xl font-bold text-on-surface">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-on-surface-variant mt-0.5">{{ $cargo }}</p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2.5 py-1 bg-surface-container rounded-full text-xs text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[13px]">badge</span>
                            ID: {{ $badgeId }}
                        </span>
                        <span class="px-2.5 py-1 bg-surface-container rounded-full text-xs text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[13px]">calendar_today</span>
                            Ingreso: {{ $fechaIngreso }}
                        </span>
                        @if($employee->restaurant)
                        <span class="px-2.5 py-1 bg-surface-container rounded-full text-xs text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[13px]">restaurant</span>
                            {{ $employee->restaurant->name }}
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('empleado.perfil') }}" class="px-4 py-2 border border-outline-variant/50 text-on-surface-variant text-sm rounded-xl hover:bg-surface-container transition-all flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    Editar perfil
                </a>
            </div>
        </section>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2.5 bg-primary-fixed rounded-xl">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1">schedule</span>
                    </div>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Horas Hoy</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasHoy }} <span class="text-base font-normal text-gray-400">/ {{ $horasTarget }}</span></p>
                <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2.5 overflow-hidden">
                    <div class="h-full bg-primary-container rounded-full transition-all duration-500" style="width: {{ $horasPct }}%"></div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2.5 bg-blue-50 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1">date_range</span>
                    </div>
                    <span class="text-xs font-bold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">trending_up</span>
                        +{{ $horasExtrasSemana }}
                    </span>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Esta Semana</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasSemana }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2.5 bg-emerald-50 rounded-xl">
                        <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">account_balance_wallet</span>
                    </div>
                    <a href="{{ route('empleado.pagos') }}" class="text-xs text-primary font-bold hover:underline flex items-center gap-0.5">
                        Ver pagos <span class="material-symbols-outlined text-[13px]">chevron_right</span>
                    </a>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Salario Base</p>
                <p class="text-2xl font-bold font-heading text-on-surface">${{ $salarioBase }}</p>
                <p class="text-xs text-gray-400 mt-1">COP / Mes</p>
            </div>
        </div>

        <!-- Reservas del día -->
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container" style="font-variation-settings:'FILL' 1">event_seat</span>
                    <h3 class="font-bold text-on-surface">Reservas Asignadas</h3>
                </div>
                <span class="text-xs font-bold text-primary-container bg-primary-container/10 px-2.5 py-1 rounded-full">
                    {{ $todayReservations->count() }} reservas
                </span>
            </div>
            @if($todayReservations->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400 text-sm">
                <span class="material-symbols-outlined text-4xl text-gray-200 block mb-2">calendar_today</span>
                Sin reservas próximas asignadas.
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Fecha & Hora</th>
                            <th class="px-4 py-3 text-center">Pax</th>
                            <th class="px-4 py-3">Mesa</th>
                            <th class="px-4 py-3">Pre-orden</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($todayReservations as $res)
                        @php
                            $items   = $res->selected_items ?? [];
                            $stColor = match($res->status) {
                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                'pending'   => 'bg-amber-100 text-amber-700',
                                default     => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-on-surface">{{ $res->user->name ?? '—' }}</p>
                                @if($res->user->phone ?? null)
                                <p class="text-xs text-gray-400">{{ $res->user->phone }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ $res->reservation_date->locale('es')->isoFormat('ddd D MMM') }}</p>
                                <p class="text-xs text-gray-400">{{ substr($res->reservation_time, 0, 5) }}</p>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">{{ $res->party_size }}</td>
                            <td class="px-4 py-3">
                                @if($res->table)
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-xl">
                                    <span class="material-symbols-outlined text-[13px]">table_restaurant</span>
                                    Mesa {{ $res->table->number }}
                                    @if($res->table->zone) · {{ $res->table->zone }} @endif
                                </span>
                                @else
                                <span class="text-xs text-gray-300">Sin asignar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(count($items) > 0)
                                <div class="space-y-0.5">
                                    @foreach(array_slice($items, 0, 2) as $item)
                                    <p class="text-xs">{{ $item['name'] ?? '' }} ×{{ $item['qty'] ?? 1 }}</p>
                                    @endforeach
                                    @if(count($items) > 2)
                                    <p class="text-[11px] text-gray-400">+{{ count($items)-2 }} más</p>
                                    @endif
                                </div>
                                @elseif($res->notes)
                                <p class="text-xs text-gray-400 italic">{{ Str::limit($res->notes, 40) }}</p>
                                @else
                                <span class="text-xs text-gray-300">Sin pre-orden</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $stColor }}">
                                    {{ $res->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($res->qr_token)
                                    <a href="{{ route('reserva.verificar', $res->qr_token) }}" target="_blank"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 hover:bg-orange-100 text-primary rounded-lg transition-colors" title="Verificar QR">
                                        <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                                    </a>
                                    @endif
                                    @if($res->table_id)
                                    <a href="{{ route('empleado.mesa.recibo', $res->table_id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-primary hover:bg-orange-600 text-white rounded-lg transition-colors" title="Ver Recibo">
                                        <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Turnos + Notificaciones -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- Próximos Turnos -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">event</span>
                        <h3 class="font-bold text-on-surface">Próximos Turnos</h3>
                    </div>
                    <a href="{{ route('empleado.turnos') }}" class="text-xs text-primary font-bold hover:underline flex items-center gap-0.5">
                        Ver todos <span class="material-symbols-outlined text-[13px]">chevron_right</span>
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($turnos as $turno)
                    <a href="{{ route('empleado.turnos') }}" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors block">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ $turno['dia'] }}</p>
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    {{ $turno['hora'] }}
                                </p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-gray-300 text-lg">chevron_right</span>
                    </a>
                    @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">event_busy</span>
                        <p class="text-sm font-medium text-gray-400">Sin turnos programados</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Notificaciones (sincronizado con el poller) -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden"
                 x-data="{ get notifs() { return document.querySelector('header').__x && document.querySelector('header').__x.$data ? document.querySelector('header').__x.$data.items : [] } }">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1">notifications_active</span>
                        <h3 class="font-bold text-on-surface">Notificaciones del Equipo</h3>
                    </div>
                    <span class="text-xs text-gray-400">Actualiza cada 30s</span>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($notificaciones as $notif)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                {{ $notif['color'] === 'orange' ? 'bg-orange-50' : ($notif['color'] === 'blue' ? 'bg-blue-50' : 'bg-emerald-50') }}">
                                <span class="material-symbols-outlined text-lg
                                    {{ $notif['color'] === 'orange' ? 'text-orange-500' : ($notif['color'] === 'blue' ? 'text-blue-500' : 'text-emerald-500') }}"
                                      style="font-variation-settings:'FILL' 1">{{ $notif['icono'] ?? 'notifications' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ $notif['titulo'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $notif['mensaje'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">notifications_off</span>
                        <p class="text-sm font-medium text-gray-400">Sin avisos del equipo</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Bottom Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <a href="{{ route('empleado.turnos') }}" class="bg-surface-container rounded-2xl p-6 border border-outline-variant/10 flex items-start gap-5 hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-primary-fixed rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-primary text-xl">swap_horiz</span>
                </div>
                <div>
                    <h4 class="font-bold text-on-surface text-sm mb-1">Ver y gestionar mis turnos</h4>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Revisa tu cronograma, horas y estadísticas de asistencia.</p>
                </div>
            </a>

            <a href="{{ route('empleado.pagos') }}" class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-6 text-white flex items-start gap-5 shadow-lg shadow-primary-container/20 hover:opacity-95 transition-all group">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1">payments</span>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-1">Consultar mis pagos</h4>
                    <p class="text-sm text-white/80 leading-relaxed">Salario, historial de recibos y acumulado anual.</p>
                </div>
            </a>
        </div>

    </div>

    <footer class="mt-auto border-t border-outline-variant/30 py-6 px-6 bg-surface-container-low">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
            <p class="text-xs text-on-surface-variant">© {{ date('Y') }} GoToEat. Todos los derechos reservados.</p>
            <p class="text-xs text-on-surface-variant">Panel del Empleado</p>
        </div>
    </footer>
</main>

<!-- Bottom NavBar (Mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface/95 backdrop-blur-md border-t border-outline-variant/30 px-2 py-2 flex justify-around items-center z-[60]">
    <a class="flex flex-col items-center gap-0.5 p-2 text-primary font-bold" href="{{ route('empleado.dashboard') }}">
        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1">dashboard</span>
        <span class="text-[10px]">Inicio</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.turnos') }}">
        <span class="material-symbols-outlined text-[22px]">schedule</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.pagos') }}">
        <span class="material-symbols-outlined text-[22px]">payments</span>
        <span class="text-[10px]">Pagos</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.perfil') }}">
        <span class="material-symbols-outlined text-[22px]">person</span>
        <span class="text-[10px]">Perfil</span>
    </a>
    <button onclick="document.getElementById('logout-form').submit()" class="flex flex-col items-center gap-0.5 p-2 text-error">
        <span class="material-symbols-outlined text-[22px]">logout</span>
        <span class="text-[10px]">Salir</span>
    </button>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

<script>
function notifPoller(initialCount, url) {
    return {
        count: initialCount,
        items: [],
        panelOpen: false,
        toast: false,
        toastMsg: { titulo: '', mensaje: '' },
        _prevCount: initialCount,
        _timer: null,

        init() {
            this.fetchNotifs();
            this._timer = setInterval(() => this.fetchNotifs(), 30000);
        },

        async fetchNotifs() {
            try {
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                const newCount = data.count ?? 0;
                this.items = data.items ?? [];

                if (newCount > this._prevCount && this._prevCount >= 0) {
                    const newest = this.items[0];
                    if (newest) {
                        this.toastMsg = { titulo: newest.titulo, mensaje: newest.mensaje };
                        this.toast = true;
                        setTimeout(() => this.toast = false, 6000);
                    }
                }

                this._prevCount = newCount;
                this.count = newCount;
            } catch (e) {
                // silenciar errores de red
            }
        }
    };
}
</script>
</body>
</html>
