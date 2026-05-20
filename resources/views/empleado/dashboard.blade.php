<!DOCTYPE html>
<html class="light" lang="es"><head>
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
    <div class="mb-10 px-2 py-4">
        <h1 class="font-heading text-h3 font-bold text-primary">GoTo<span class="text-primary-container">Eat</span></h1>
        <p class="text-body-sm text-on-surface-variant uppercase tracking-widest text-[10px] font-bold mt-1">Panel del Empleado</p>
    </div>
    <nav class="flex-1 flex flex-col gap-2">
        <a class="flex items-center gap-4 px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="{{ route('empleado.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-body-sm">Inicio</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="{{ route('empleado.turnos') }}">
            <span class="material-symbols-outlined">schedule</span>
            <span class="text-body-sm">Turnos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="{{ route('empleado.pagos') }}">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-body-sm">Pagos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="{{ route('empleado.perfil') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="text-body-sm">Perfil</span>
        </a>
    </nav>
    <div class="mt-auto flex flex-col gap-2 pt-6 border-t border-outline-variant/30">
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1 text-error" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-body-sm">Cerrar Sesión</span>
        </a>
    </div>
</aside>

<!-- Main Content -->
<main class="md:ml-64 flex flex-col min-h-screen">

    <!-- Top Bar -->
    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4">
        <div class="flex items-center gap-4">
            <h2 class="font-heading text-lg font-bold text-on-surface">Inicio</h2>
        </div>
        <div class="flex items-center gap-4">
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all active:scale-90 relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
            </button>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-container rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'J', 0, 1) }}{{ substr(auth()->user()->name ?? 'R', strpos(auth()->user()->name ?? 'J R', ' ') + 1, 1) }}
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="px-6 py-8 max-w-7xl mx-auto w-full space-y-8">

        <!-- Employee Profile Header -->
        <section class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/10">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-container to-primary rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-primary-container/30">
                    {{ substr(auth()->user()->name ?? 'J', 0, 1) }}{{ substr(auth()->user()->name ?? 'R', strpos(auth()->user()->name ?? 'J R', ' ') + 1, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="font-heading text-h3 font-bold text-on-surface">{{ auth()->user()->name ?? 'Julián Rodríguez' }}</h2>
                    <p class="text-body-md text-on-surface-variant">{{ $cargo ?? 'Chef de Cuisine' }}</p>
                    <div class="flex flex-wrap gap-3 mt-3">
                        <span class="px-3 py-1 bg-surface-container rounded-full text-label-caps text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">badge</span>
                            ID: {{ $badgeId ?? 'GE-882910' }}
                        </span>
                        <span class="px-3 py-1 bg-surface-container rounded-full text-label-caps text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            Ingreso: {{ $fechaIngreso ?? '17 Feb 2026' }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Horas Hoy -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary-fixed rounded-xl">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1">schedule</span>
                    </div>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Horas Hoy</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasHoy ?? '8h' }} <span class="text-base font-normal text-gray-400">/ {{ $horasTarget ?? '10h' }}</span></p>
                <div class="w-full h-1.5 bg-gray-100 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-primary-container rounded-full transition-all duration-500" style="width: {{ $horasPct ?? '80' }}%"></div>
                </div>
            </div>

            <!-- Esta Semana -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1">date_range</span>
                    </div>
                    <span class="text-xs font-bold bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        +{{ $horasExtrasSemana ?? '4h' }} vs prev.
                    </span>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Esta Semana</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasSemana ?? '42h' }}</p>
            </div>

            <!-- Salario Base -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl">
                        <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">account_balance_wallet</span>
                    </div>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Salario Base</p>
                <p class="text-2xl font-bold font-heading text-on-surface">${{ $salarioBase ?? '2.000.000' }}</p>
                <p class="text-xs text-gray-400 mt-1">COP / Mes</p>
            </div>
        </div>

        {{-- ── Reservas del día (mesero) ──────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container" style="font-variation-settings:'FILL' 1">event_seat</span>
                    <h3 class="font-bold text-on-surface">Reservas del Día</h3>
                </div>
                <span class="text-xs font-bold text-primary-container bg-primary-container/10 px-2.5 py-1 rounded-full">
                    {{ ($todayReservations ?? collect())->count() }} reservas
                </span>
            </div>
            @if(($todayReservations ?? collect())->isEmpty())
            <div class="px-6 py-8 text-center text-gray-400 text-sm">
                <span class="material-symbols-outlined text-3xl text-gray-200 block mb-2">calendar_today</span>
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
                            <th class="px-4 py-3">Pre-orden</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 text-center">QR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($todayReservations ?? [] as $res)
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
                                @if(count($items) > 0)
                                <div class="space-y-0.5">
                                    @foreach(array_slice($items, 0, 2) as $item)
                                    <p class="text-xs">{{ $item['name'] ?? '' }} ×{{ $item['qty'] ?? 1 }}</p>
                                    @endforeach
                                    @if(count($items) > 2)
                                    <p class="text-[11px] text-gray-400">+{{ count($items)-2 }} más</p>
                                    @endif
                                    <p class="text-xs font-bold text-primary-container mt-0.5">
                                        ${{ number_format(collect($items)->sum(fn($i) => ($i['price']??0)*($i['qty']??1)), 0, ',', '.') }}
                                    </p>
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
                                @if($res->qr_token)
                                <a href="{{ route('reserva.verificar', $res->qr_token) }}" target="_blank"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 hover:bg-orange-100 text-primary rounded-lg transition-colors" title="Verificar QR">
                                    <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                                </a>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Two Column: Turnos + Notificaciones -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Próximos Turnos -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">event</span>
                    <h3 class="font-bold text-on-surface">Próximos Turnos</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($turnos ?? [['dia' => 'Martes - Mañana', 'hora' => '08:00 AM — 04:00 PM'], ['dia' => 'Miércoles - Tarde', 'hora' => '10:00 AM — 06:00 PM']] as $turno)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
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
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">event_busy</span>
                        <p class="text-sm font-medium text-gray-400">Sin turnos programados</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">notifications_active</span>
                    <h3 class="font-bold text-on-surface">Notificaciones</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($notificaciones ?? [
                        ['titulo' => 'Tu turno inicia en 30 minutos', 'mensaje' => 'Recuerda marcar tu entrada en el sistema biométrico.', 'icono' => 'alarm', 'color' => 'orange'],
                        ['titulo' => 'Reunión programada a las 4:00 PM', 'mensaje' => 'Briefing diario sobre el nuevo menú de temporada con el Staff.', 'icono' => 'groups', 'color' => 'blue'],
                        ['titulo' => 'Solicitud de permiso aprobada', 'mensaje' => 'Tu solicitud para el día 25 de mayo ha sido confirmada por RRHH.', 'icono' => 'check_circle', 'color' => 'emerald']
                    ] as $notif)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-{{ $notif['color'] ?? 'orange' }}-50 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-{{ $notif['color'] ?? 'orange' }}-500 text-lg">{{ $notif['icono'] ?? 'notifications' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ $notif['titulo'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $notif['mensaje'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">notifications_off</span>
                        <p class="text-sm font-medium text-gray-400">Sin notificaciones</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Bottom Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Cambio de Turno CTA -->
            <div class="bg-surface-container rounded-2xl p-8 border border-outline-variant/10 flex items-start gap-6">
                <div class="w-14 h-14 bg-primary-fixed rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-2xl">swap_horiz</span>
                </div>
                <div>
                    <h4 class="font-bold text-on-surface text-body-md mb-1">¿Necesitas cambiar un turno?</h4>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Solicita intercambios con tus compañeros directamente desde la plataforma de gestión.</p>
                </div>
            </div>

            <!-- Empleado del Mes -->
            <div class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-8 text-white flex items-start gap-6 shadow-lg shadow-primary-container/20">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">emoji_events</span>
                </div>
                <div>
                    <h4 class="font-bold text-body-md mb-1">Empleado del Mes</h4>
                    <p class="text-sm text-white/80 leading-relaxed mb-3">¡Felicidades! Has sido nominado por tu excelente desempeño en la estación de postres.</p>
                    <a href="#" class="inline-block bg-white/20 backdrop-blur-sm text-white font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-lg hover:bg-white/30 transition-all">Ver Reconocimientos</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-outline-variant/30 py-8 px-6 bg-surface-container-low">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-body-sm text-on-surface-variant">© 2024 GoToEat Platform. Todos los derechos reservados.</p>
            <p class="text-body-sm text-on-surface-variant">Versión del Panel: 4.2.1-stable</p>
        </div>
    </footer>
</main>

<!-- Bottom NavBar (Mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface/90 backdrop-blur-md border-t border-outline-variant/30 px-4 py-2 flex justify-around items-center z-[60]">
    <a class="flex flex-col items-center gap-1 p-2 text-primary font-bold" href="{{ route('empleado.dashboard') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
        <span class="text-[10px]">Inicio</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="{{ route('empleado.turnos') }}">
        <span class="material-symbols-outlined">schedule</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="{{ route('empleado.pagos') }}">
        <span class="material-symbols-outlined">payments</span>
        <span class="text-[10px]">Pagos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="{{ route('empleado.perfil') }}">
        <span class="material-symbols-outlined">person</span>
        <span class="text-[10px]">Perfil</span>
    </a>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>
