<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Mi Perfil | GoToEat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body text-on-surface min-h-screen">

<!-- Modal: Editar Perfil -->
<div id="modal-perfil" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-heading font-bold text-lg text-on-surface">Editar Perfil</h3>
            <button onclick="document.getElementById('modal-perfil').classList.add('hidden')" class="p-1 text-on-surface-variant hover:bg-surface-container rounded-lg transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 text-emerald-700 text-sm rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('empleado.perfil.update') }}">
            @csrf @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                        class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name') border-error @enderror">
                    @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="+57 300 000 0000"
                        class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Correo electrónico</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                        class="w-full border border-outline-variant/30 rounded-xl px-4 py-3 text-sm bg-surface-container text-on-surface-variant cursor-not-allowed">
                    <p class="text-[11px] text-gray-400 mt-1">El correo no puede modificarse aquí.</p>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-perfil').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 border border-outline-variant/50 text-on-surface-variant font-bold text-sm rounded-xl hover:bg-surface-container transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all">
                    Guardar Cambios
                </button>
            </div>
        </form>
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
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.turnos') }}">
            <span class="material-symbols-outlined">schedule</span>
            <span class="text-sm">Turnos</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-all hover:translate-x-1" href="{{ route('empleado.pagos') }}">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-sm">Pagos</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-xl font-bold transition-all hover:translate-x-1" href="{{ route('empleado.perfil') }}">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">person</span>
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
            <h2 class="font-heading text-lg font-bold text-on-surface">Mi Perfil</h2>
            <p class="text-xs text-on-surface-variant">Información personal, laboral y acceso a tu cuenta.</p>
        </div>
        <button onclick="document.getElementById('modal-perfil').classList.remove('hidden')"
            class="px-4 py-2 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            <span class="hidden sm:inline">Editar Perfil</span>
        </button>
    </header>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-open modal on success to show the message, then close immediately
        });
    </script>
    <div class="mx-4 md:mx-6 mt-4 px-4 py-3 bg-emerald-50 text-emerald-700 text-sm rounded-xl flex items-center gap-2 border border-emerald-200">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <!-- Profile Content -->
    <div class="px-4 md:px-6 py-6 max-w-5xl mx-auto w-full space-y-6">

        <!-- Profile Header Card -->
        <section class="bg-white rounded-2xl overflow-hidden shadow-sm border border-outline-variant/10">
            <div class="h-28 bg-gradient-to-r from-primary-container via-primary to-primary relative">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20px 20px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            </div>
            <div class="px-6 pb-6 -mt-10 relative">
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-5">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white shrink-0">
                        <div class="w-full h-full bg-gradient-to-br from-primary-container to-primary rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1 pt-2">
                        <h2 class="font-heading text-xl font-bold text-on-surface">{{ auth()->user()->name }}</h2>
                        <p class="text-sm text-on-surface-variant flex items-center gap-2 mt-0.5">
                            <span class="material-symbols-outlined text-[15px] text-primary-container">restaurant</span>
                            {{ $cargo }} • {{ $sede }}
                        </p>
                    </div>
                    <button onclick="document.getElementById('modal-perfil').classList.remove('hidden')"
                        class="px-4 py-2 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all flex items-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        Editar
                    </button>
                </div>
            </div>
        </section>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- Información Personal -->
            <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">person</span>
                        <h3 class="font-bold text-on-surface">Información Personal</h3>
                    </div>
                    <button onclick="document.getElementById('modal-perfil').classList.remove('hidden')"
                        class="p-1.5 text-on-surface-variant hover:bg-surface-container rounded-lg transition-all" title="Editar">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">email</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Correo electrónico</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">phone</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Teléfono</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $telefono }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">alternate_email</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Usuario</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ auth()->user()->username ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Información Laboral -->
            <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">work</span>
                    <h3 class="font-bold text-on-surface">Información Laboral</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">badge</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">ID de Empleado</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $employeeId }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">restaurant</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Cargo</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $cargo }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">location_on</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Restaurante</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $sede }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-1">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Ingreso</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">{{ $fechaIngreso }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">account_balance_wallet</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Salario</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">${{ $salario }} COP</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Acciones de Cuenta -->
        <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container">settings</span>
                <h3 class="font-bold text-on-surface">Configuración de Cuenta</h3>
            </div>
            <div class="p-6 space-y-3">
                <!-- Cambiar Contraseña -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-surface-container rounded-xl border border-outline-variant/10">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary">lock</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-sm">Seguridad de la cuenta</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Cambia tu contraseña periódicamente para mantener tu cuenta segura.</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="px-4 py-2 border-2 border-primary text-primary font-bold text-sm rounded-xl hover:bg-primary/5 active:scale-95 transition-all flex items-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-[16px]">key</span>
                        Cambiar Contraseña
                    </a>
                </div>

                <!-- Ver Pagos -->
                <a href="{{ route('empleado.pagos') }}" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-surface-container rounded-xl border border-outline-variant/10 hover:border-primary/30 transition-all block">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-emerald-600">payments</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-sm">Mis Pagos y Recibos</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Consulta tu historial salarial y descarga tus recibos de pago.</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 border border-outline-variant/50 text-on-surface-variant font-bold text-sm rounded-xl flex items-center gap-2 shrink-0">
                        Ver pagos <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </span>
                </a>

                <!-- Ver Turnos -->
                <a href="{{ route('empleado.turnos') }}" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-surface-container rounded-xl border border-outline-variant/10 hover:border-primary/30 transition-all block">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-blue-600">schedule</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-sm">Mis Turnos</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Revisa tu cronograma y solicita cambios de turno.</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 border border-outline-variant/50 text-on-surface-variant font-bold text-sm rounded-xl flex items-center gap-2 shrink-0">
                        Ver turnos <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </span>
                </a>
            </div>
        </section>

        <!-- Logros -->
        <section class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-6 text-white shadow-lg shadow-primary-container/20 overflow-hidden relative">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">emoji_events</span>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-bold tracking-widest text-white/70 uppercase mb-1">Reconocimiento</p>
                    <h4 class="font-heading text-lg font-bold mb-1">¡Buen trabajo, {{ explode(' ', auth()->user()->name)[0] }}!</h4>
                    <p class="text-sm text-white/80">Tu desempeño es valorado por el equipo. Sigue así y sé el empleado del mes.</p>
                </div>
                <a href="{{ route('empleado.dashboard') }}"
                    class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-bold text-sm rounded-xl hover:bg-white/30 transition-all flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Mi Panel
                </a>
            </div>
        </section>

    </div>

    <footer class="mt-auto border-t border-outline-variant/30 py-6 px-6 bg-surface-container-low">
        <div class="max-w-5xl mx-auto text-center">
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
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.turnos') }}">
        <span class="material-symbols-outlined text-[22px]">schedule</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.pagos') }}">
        <span class="material-symbols-outlined text-[22px]">payments</span>
        <span class="text-[10px]">Pagos</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-primary font-bold" href="{{ route('empleado.perfil') }}">
        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1">person</span>
        <span class="text-[10px]">Perfil</span>
    </a>
    <button onclick="document.getElementById('logout-form').submit()" class="flex flex-col items-center gap-0.5 p-2 text-error">
        <span class="material-symbols-outlined text-[22px]">logout</span>
        <span class="text-[10px]">Salir</span>
    </button>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

@if(session('success'))
<script>
    // Si hay éxito, abrir modal brevemente para mostrar el mensaje
    document.addEventListener('DOMContentLoaded', function() {
        // El mensaje ya se muestra en el banner superior
    });
</script>
@endif
</body>
</html>
