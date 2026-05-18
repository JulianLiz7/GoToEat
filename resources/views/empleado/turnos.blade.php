<!DOCTYPE html>
<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Mis Turnos | GoToEat</title>
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
        <p class="text-on-surface-variant uppercase tracking-widest text-[10px] font-bold mt-1">Employee Portal</p>
    </div>
    <nav class="flex-1 flex flex-col gap-2">
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-body-sm">Dashboard</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="text-body-sm">Turnos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-body-sm">Pagos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">person</span>
            <span class="text-body-sm">Perfil</span>
        </a>
    </nav>
    <div class="mt-auto pt-6 border-t border-outline-variant/30">
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="w-10 h-10 bg-primary-container rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ substr(auth()->user()->name ?? 'C', 0, 1) }}{{ substr(auth()->user()->name ?? 'R', strpos(auth()->user()->name ?? 'C R', ' ') + 1, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name ?? 'Carlos Ruiz' }}</p>
                <p class="text-[10px] text-on-surface-variant">{{ $cargo ?? 'Mozo Senior' }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="md:ml-64 flex flex-col min-h-screen">

    <!-- Top Bar -->
    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4">
        <div>
            <h2 class="font-heading text-lg font-bold text-on-surface">Gestión de Turnos</h2>
            <p class="text-xs text-on-surface-variant">Revisa tu cronograma y solicita cambios fácilmente.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                Solicitar Cambio
            </button>
        </div>
    </header>

    <!-- Content -->
    <div class="px-6 py-8 max-w-6xl mx-auto w-full space-y-8">

        <!-- Calendar Header -->
        <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary-container">calendar_today</span>
                    <h3 class="font-heading font-bold text-on-surface text-lg">{{ $mesActual ?? 'Octubre 2023' }}</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button class="w-9 h-9 rounded-lg border border-outline-variant/30 flex items-center justify-center hover:bg-surface-container transition-all active:scale-90">
                        <span class="material-symbols-outlined text-on-surface-variant text-lg">chevron_left</span>
                    </button>
                    <button class="px-3 py-1.5 text-xs font-bold text-primary bg-primary/10 rounded-lg">Hoy</button>
                    <button class="w-9 h-9 rounded-lg border border-outline-variant/30 flex items-center justify-center hover:bg-surface-container transition-all active:scale-90">
                        <span class="material-symbols-outlined text-on-surface-variant text-lg">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="p-6">
                <!-- Days of Week Header -->
                <div class="grid grid-cols-7 gap-2 mb-3">
                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                    <div class="text-center text-[11px] font-bold tracking-widest text-gray-400 uppercase py-2">{{ $dia }}</div>
                    @endforeach
                </div>
                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-2">
                    {{-- Días vacíos del inicio del mes --}}
                    @for($i = 0; $i < ($offsetDias ?? 6); $i++)
                    <div class="h-12 rounded-lg"></div>
                    @endfor

                    @for($d = 1; $d <= ($diasMes ?? 31); $d++)
                    @php
                        $isToday = $d === ($diaActual ?? 5);
                        $hasTurno = in_array($d, $diasConTurno ?? [5, 6, 7, 12, 13, 14, 19, 20, 21, 26, 27, 28]);
                    @endphp
                    <div class="h-12 rounded-lg flex flex-col items-center justify-center text-sm font-medium transition-all cursor-pointer
                        {{ $isToday ? 'bg-primary-container text-white font-bold shadow-md shadow-primary-container/30 ring-2 ring-primary-container/30' : '' }}
                        {{ !$isToday && $hasTurno ? 'bg-primary-fixed text-primary hover:bg-primary-fixed-dim' : '' }}
                        {{ !$isToday && !$hasTurno ? 'text-on-surface hover:bg-surface-container' : '' }}">
                        {{ $d }}
                        @if($hasTurno && !$isToday)
                        <span class="w-1 h-1 bg-primary-container rounded-full mt-0.5"></span>
                        @endif
                    </div>
                    @endfor
                </div>
            </div>
        </section>

        <!-- Current Shift Highlight -->
        <section class="bg-gradient-to-r from-primary-container to-primary rounded-2xl p-6 text-white shadow-lg shadow-primary-container/20 relative overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">work_history</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold tracking-widest text-white/70 uppercase">Turno Actual</p>
                        <p class="text-xl font-heading font-bold">Hoy, {{ $horaInicioTurno ?? '19:00' }} hs</p>
                        <p class="text-sm text-white/80">Área: {{ $areaTurno ?? 'Salón VIP - Terraza' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button class="px-5 py-2.5 bg-white text-primary font-bold text-sm rounded-xl hover:bg-white/90 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        Marcar Entrada
                    </button>
                </div>
            </div>
        </section>

        <!-- Próximos Turnos + Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Próximos Turnos (2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">list_alt</span>
                    <h3 class="font-bold text-on-surface">Próximos Turnos</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($proximosTurnos ?? [
                        ['dia' => 'Viernes 06 Oct', 'hora' => '14:00 - 22:00', 'duracion' => '8h', 'area' => 'Salón Principal', 'tipo' => 'tarde'],
                        ['dia' => 'Sábado 07 Oct', 'hora' => '19:00 - 03:00', 'duracion' => '8h', 'area' => 'Salón VIP', 'tipo' => 'noche']
                    ] as $turno)
                    <div class="px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                {{ ($turno['tipo'] ?? 'tarde') === 'noche' ? 'bg-indigo-50' : 'bg-primary-fixed' }}">
                                <span class="material-symbols-outlined text-lg
                                    {{ ($turno['tipo'] ?? 'tarde') === 'noche' ? 'text-indigo-500' : 'text-primary' }}"
                                    style="font-variation-settings:'FILL' 1">
                                    {{ ($turno['tipo'] ?? 'tarde') === 'noche' ? 'dark_mode' : 'light_mode' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">{{ $turno['dia'] }}</p>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    {{ $turno['hora'] }}
                                    <span class="text-gray-300">•</span>
                                    {{ $turno['area'] ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-surface-container rounded-full text-xs font-bold text-on-surface-variant">{{ $turno['duracion'] }}</span>
                            <span class="material-symbols-outlined text-gray-300 text-lg">chevron_right</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">event_busy</span>
                        <p class="text-sm font-medium text-gray-400">Sin turnos programados</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stats Column -->
            <div class="space-y-6">
                <!-- Horas del Mes -->
                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">timer</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Horas del Mes</p>
                            <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasMes ?? '142.5' }} <span class="text-sm font-normal text-gray-400">h</span></p>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full transition-all duration-500" style="width: {{ $horasMesPct ?? '89' }}%"></div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2">{{ $horasMesPct ?? '89' }}% del objetivo mensual</p>
                </div>

                <!-- Asistencia -->
                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 text-lg" style="font-variation-settings:'FILL' 1">verified</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Asistencia</p>
                            <p class="text-2xl font-bold font-heading text-emerald-600">{{ $asistencia ?? '98.2' }}<span class="text-sm font-normal text-gray-400">%</span></p>
                        </div>
                    </div>
                </div>

                <!-- Desempeño -->
                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-amber-500 text-lg" style="font-variation-settings:'FILL' 1">star</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Desempeño</p>
                            <p class="text-2xl font-bold font-heading text-on-surface">{{ $desempeno ?? '4.9' }} <span class="text-sm font-normal text-gray-400">/ 5</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-outline-variant/30 py-8 px-6 bg-surface-container-low">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-body-sm text-on-surface-variant">© 2024 GoToEat Employee Portal. Sistema de gestión de personal y hospitalidad.</p>
        </div>
    </footer>
</main>

<!-- Bottom NavBar (Mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface/90 backdrop-blur-md border-t border-outline-variant/30 px-4 py-2 flex justify-around items-center z-[60]">
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="#">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px]">Dashboard</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-primary font-bold" href="#">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="#">
        <span class="material-symbols-outlined">payments</span>
        <span class="text-[10px]">Pagos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="#">
        <span class="material-symbols-outlined">person</span>
        <span class="text-[10px]">Perfil</span>
    </a>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>
