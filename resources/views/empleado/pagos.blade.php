<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Mis Pagos | GoToEat</title>
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
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-xl font-bold transition-all hover:translate-x-1" href="{{ route('empleado.pagos') }}">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
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
            <h2 class="font-heading text-lg font-bold text-on-surface">Gestión de Pagos</h2>
            <p class="text-xs text-on-surface-variant">Historial de salarios y comprobantes de pago.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                class="px-4 py-2 border-2 border-primary text-primary font-bold text-sm rounded-xl hover:bg-primary/5 active:scale-95 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span class="hidden sm:inline">Imprimir</span>
            </button>
        </div>
    </header>

    <!-- Content -->
    <div class="px-4 md:px-6 py-6 max-w-6xl mx-auto w-full space-y-6">

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Próximo Pago -->
            <div class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-6 text-white shadow-lg shadow-primary-container/20 relative overflow-hidden">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings:'FILL' 1">account_balance</span>
                    </div>
                    <p class="text-[11px] font-bold tracking-widest text-white/70 uppercase mb-1">Próximo Pago Estimado</p>
                    <p class="text-3xl font-bold font-heading">{{ $proximoPago }}</p>
                    <p class="text-xs text-white/70 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">event</span>
                        {{ $fechaProximoPago }}
                    </p>
                </div>
            </div>

            <!-- Salario Base -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600 text-lg" style="font-variation-settings:'FILL' 1">payments</span>
                    </div>
                    <span class="text-xs font-bold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Mensual</span>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Salario Base</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $salarioBase }}</p>
                <p class="text-xs text-gray-400 mt-1">COP bruto mensual</p>
            </div>

            <!-- Acumulado Anual -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/10 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-lg" style="font-variation-settings:'FILL' 1">savings</span>
                    </div>
                    <span class="text-xs font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $anioActual }}</span>
                </div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Acumulado Anual</p>
                <p class="text-2xl font-bold font-heading text-on-surface">{{ $acumuladoAnual }}</p>
                <p class="text-xs text-gray-400 mt-1">Total bruto recibido</p>
            </div>
        </div>

        <!-- Historial de Recibos -->
        <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">receipt_long</span>
                    <h3 class="font-bold text-on-surface">Historial de Recibos</h3>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">{{ $totalPeriodos }} periodos</span>
            </div>

            <!-- Table Header -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-surface-container text-[11px] font-bold tracking-widest text-gray-400 uppercase border-b border-gray-100">
                <div class="col-span-3">Periodo</div>
                <div class="col-span-2 text-right">Bruto</div>
                <div class="col-span-2 text-right">Deducciones</div>
                <div class="col-span-2 text-right">Neto</div>
                <div class="col-span-2">Estado</div>
                <div class="col-span-1"></div>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($recibos as $recibo)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-4 px-6 py-4 hover:bg-gray-50 transition-colors items-center">
                    <div class="md:col-span-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface">{{ $recibo['periodo'] }}</p>
                            <p class="text-[11px] text-gray-400 md:hidden">Bruto: {{ $recibo['bruto'] }}</p>
                        </div>
                    </div>
                    <div class="hidden md:block md:col-span-2 text-right">
                        <p class="text-sm font-medium text-on-surface">{{ $recibo['bruto'] }}</p>
                    </div>
                    <div class="hidden md:block md:col-span-2 text-right">
                        <p class="text-sm text-red-500 font-medium">-{{ $recibo['deducciones'] }}</p>
                    </div>
                    <div class="hidden md:block md:col-span-2 text-right">
                        <p class="text-sm font-bold text-emerald-600">{{ $recibo['neto'] }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $recibo['estado'] === 'Pagado' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $recibo['estado'] === 'Pagado' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            {{ $recibo['estado'] }}
                        </span>
                    </div>
                    <div class="md:col-span-1 flex justify-end">
                        <button onclick="window.print()" title="Imprimir recibo"
                            class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all active:scale-90">
                            <span class="material-symbols-outlined text-lg">print</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">receipt_long</span>
                    <p class="text-sm font-medium text-gray-400">Sin recibos disponibles</p>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Bottom Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- Información sobre deducciones -->
            <section class="bg-surface-container rounded-2xl p-6 border border-outline-variant/10">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-fixed rounded-2xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-xl">info</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface text-sm mb-1">Sobre tus Deducciones</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed">El 8% de deducción corresponde a salud y pensión. El valor exacto puede variar según tu tipo de contrato y beneficios adicionales.</p>
                    </div>
                </div>
            </section>

            <!-- CTA Perfil -->
            <a href="{{ route('empleado.perfil') }}" class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-6 text-white shadow-lg shadow-primary-container/20 relative overflow-hidden hover:opacity-95 transition-all block">
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10 flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1">person</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm mb-1">Ver mi Perfil Completo</h4>
                        <p class="text-sm text-white/80 leading-relaxed">Revisa tu información laboral, cargo y datos de contacto.</p>
                        <span class="inline-flex items-center gap-1 mt-3 text-xs font-bold bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all">
                            Ir al perfil <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </span>
                    </div>
                </div>
            </a>
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
    <a class="flex flex-col items-center gap-0.5 p-2 text-on-surface-variant" href="{{ route('empleado.turnos') }}">
        <span class="material-symbols-outlined text-[22px]">schedule</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-0.5 p-2 text-primary font-bold" href="{{ route('empleado.pagos') }}">
        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1">payments</span>
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
</body>
</html>
