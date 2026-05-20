<!DOCTYPE html>
<html class="light" lang="es">
<head>
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

<!-- Modal: Marcar Entrada -->
<div id="modal-entrada" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-emerald-600 text-3xl" style="font-variation-settings:'FILL' 1">check_circle</span>
        </div>
        <h3 class="font-heading font-bold text-xl text-on-surface mb-2">¡Entrada Marcada!</h3>
        <p class="text-sm text-on-surface-variant mb-1">Hora de entrada registrada:</p>
        <p class="text-2xl font-bold text-primary mb-6" id="hora-entrada">--:--</p>
        <button onclick="document.getElementById('modal-entrada').classList.add('hidden')"
            class="w-full px-5 py-2.5 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all">
            Aceptar
        </button>
    </div>
</div>

<!-- Modal: Solicitar Cambio -->
<div id="modal-cambio" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-heading font-bold text-lg text-on-surface">Solicitar Cambio de Turno</h3>
            <button onclick="document.getElementById('modal-cambio').classList.add('hidden')" class="p-1 text-on-surface-variant hover:bg-surface-container rounded-lg transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Motivo</label>
                <textarea class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none" rows="3" placeholder="Describe el motivo del cambio..."></textarea>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Fecha deseada</label>
                <input type="date" class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="document.getElementById('modal-cambio').classList.add('hidden')"
                class="flex-1 px-4 py-2.5 border border-outline-variant/50 text-on-surface-variant font-bold text-sm rounded-xl hover:bg-surface-container transition-all">
                Cancelar
            </button>
            <button onclick="alert('Solicitud enviada al administrador.'); document.getElementById(\'modal-cambio\').classList.add(\'hidden\')"
                class="flex-1 px-4 py-2.5 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all">
                Enviar
            </button>
        </div>
    </div>
</div>

<!-- SideNavBar (Desktop) -->
<aside class="hidden md:flex flex-col h-full p-4 gap-2 bg-surface-container-low fixed left-0 top-0 w-64 z-[60] shadow-md">
    <div class="mb-8 px-2 py-4">
        <h1 class="font-heading text-h3 font-bold text-primary">GoTo<span class="text-primary-container">Eat</span></h1>
        <p class="text-on-surface-variant uppercase tracking-widest text-[10px] font-bold mt-1">Panel del Empleado</p>
    </div>
    <nav class="flex-1 flex flex-col gap-1">
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Inicio</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-xl font-bold transition-all hover:translate-x-1" href="{{ route('empleado.turnos') }}">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">schedule</span>
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
    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4">
        <div>
            <h2 class="font-heading text-lg font-bold text-on-surface">Gestión de Turnos</h2>
            <p class="text-xs text-on-surface-variant">Revisa tu cronograma y solicita cambios fácilmente.</p>
        </div>
        <button onclick="document.getElementById('modal-cambio').classList.remove('hidden')"
            class="px-4 py-2 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
            <span class="hidden sm:inline">Solicitar Cambio</span>
        </button>
    </header>

    <!-- Content -->
    <div class="px-4 md:px-6 py-6 max-w-6xl mx-auto w-full space-y-6">

        <!-- Calendario -->
        <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary-container">calendar_today</span>
                    <h3 class="font-heading font-bold text-on-surface">{{ $mesActual }}</h3>
                </div>
                <a href="{{ route('empleado.dashboard') }}" class="text-xs text-primary font-bold hover:underline flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[13px]">home</span> Inicio
                </a>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-7 gap-1.5 mb-2">
                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                    <div class="text-center text-[11px] font-bold tracking-widest text-gray-400 uppercase py-1.5">{{ $dia }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1.5">
                    @for($i = 0; $i < $offsetDias; $i++)
                    <div class="h-10 rounded-lg"></div>
                    @endfor

                    @for($d = 1; $d <= $diasMes; $d++)
                    @php
                        $isToday   = $d === $diaActual;
                        $hasTurno  = in_array($d, $diasConTurno);
                    @endphp
                    <div class="h-10 rounded-xl flex flex-col items-center justify-center text-sm font-medium transition-all cursor-default
                        {{ $isToday  ? 'bg-primary-container text-white font-bold shadow-md shadow-primary-container/30' : '' }}
                        {{ !$isToday && $hasTurno  ? 'bg-primary-fixed text-primary hover:bg-primary-fixed-dim' : '' }}
                        {{ !$isToday && !$hasTurno ? 'text-on-surface hover:bg-surface-container' : '' }}">
                        {{ $d }}
                        @if($hasTurno && !$isToday)
                        <span class="w-1 h-1 bg-primary-container rounded-full mt-0.5"></span>
                        @endif
                    </div>
                    @endfor
                </div>
                <div class="flex items-center gap-4 mt-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-primary-container inline-block"></span> Hoy</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-primary-fixed inline-block"></span> Turno asignado</span>
                </div>
            </div>
        </section>

        <!-- Turno Actual -->
        <section class="bg-gradient-to-r from-primary-container to-primary rounded-2xl p-6 text-white shadow-lg shadow-primary-container/20 relative overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">work_history</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold tracking-widest text-white/70 uppercase">Turno de Hoy</p>
                        <p class="text-xl font-heading font-bold">{{ $horaInicioTurno }}</p>
                        <p class="text-sm text-white/80">{{ $areaTurno }}</p>
                    </div>
                </div>
                <button onclick="marcarEntrada()"
                    class="px-5 py-2.5 bg-white text-primary font-bold text-sm rounded-xl hover:bg-white/90 active:scale-95 transition-all flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    Marcar Entrada
                </button>
            </div>
        </section>

        <!-- Próximos Turnos + Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Próximos Turnos -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">list_alt</span>
                    <h3 class="font-bold text-on-surface">Turnos Programados</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($proximosTurnos as $turno)
                    <div class="px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                {{ ($turno['tipo'] ?? 'mañana') === 'noche' ? 'bg-indigo-50' : 'bg-primary-fixed' }}">
                                <span class="material-symbols-outlined text-lg
                                    {{ ($turno['tipo'] ?? 'mañana') === 'noche' ? 'text-indigo-500' : 'text-primary' }}"
                                    style="font-variation-settings:'FILL' 1">
                                    {{ ($turno['tipo'] ?? 'mañana') === 'noche' ? 'dark_mode' : 'light_mode' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">{{ $turno['dia'] }}</p>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    {{ $turno['hora'] }}
                                    @if($turno['area'] ?? '')
                                    <span class="text-gray-300">•</span>
                                    {{ $turno['area'] }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-surface-container rounded-full text-xs font-bold text-on-surface-variant">{{ $turno['duracion'] }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">event_busy</span>
                        <p class="text-sm font-medium text-gray-400">Sin turnos programados</p>
                        <p class="text-xs text-gray-300 mt-1">Contacta a tu administrador para asignar turnos</p>
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    <button onclick="document.getElementById('modal-cambio').classList.remove('hidden')"
                        class="w-full py-2.5 border-2 border-dashed border-outline-variant/50 text-on-surface-variant text-sm rounded-xl hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                        Solicitar cambio de turno
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">timer</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Horas del Mes</p>
                            <p class="text-2xl font-bold font-heading text-on-surface">{{ $horasMes }} <span class="text-sm font-normal text-gray-400">h</span></p>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full" style="width: {{ $horasMesPct }}%"></div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1.5">{{ $horasMesPct }}% del mes completado</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 text-lg" style="font-variation-settings:'FILL' 1">verified</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Asistencia</p>
                            <p class="text-2xl font-bold font-heading text-emerald-600">{{ $asistencia }}<span class="text-sm font-normal text-gray-400">%</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-amber-500 text-lg" style="font-variation-settings:'FILL' 1">star</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Desempeño</p>
                            <p class="text-2xl font-bold font-heading text-on-surface">{{ $desempeno }} <span class="text-sm font-normal text-gray-400">/ 5</span></p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('empleado.pagos') }}" class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 p-5 flex items-center gap-3 hover:shadow-md transition-all block">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600 text-lg" style="font-variation-settings:'FILL' 1">payments</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Ver Pagos</p>
                        <p class="text-sm font-semibold text-on-surface">Historial salarial</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-300">chevron_right</span>
                </a>
            </div>
        </div>

    </div>

    <footer class="mt-auto border-t border-outline-variant/30 py-6 px-6 bg-surface-container-low">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-xs text-on-surface-variant">© {{ date('Y') }} GoToEat. Panel del Empleado.</p>
        </div>
    </footer>
</main>

<!-- Bottom NavBar (Mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface/95 backdrop-blur-md border-t border-outline-variant/30 px-2 py-2 flex justify-around items-center z-[60]">
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.dashboard') }}">
        <span class="material-symbols-outlined text-[22px]">dashboard</span>
        <span class="text-[10px]">Inicio</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-primary font-bold" href="{{ route('empleado.turnos') }}">
        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1">schedule</span>
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
function marcarEntrada() {
    const ahora = new Date();
    const hora = ahora.getHours().toString().padStart(2, '0') + ':' + ahora.getMinutes().toString().padStart(2, '0');
    document.getElementById('hora-entrada').textContent = hora;
    document.getElementById('modal-entrada').classList.remove('hidden');
}
</script>
</body>
</html>
