<!DOCTYPE html>
<html class="light" lang="es"><head>
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
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="text-body-sm">Turnos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-body-sm">Pagos</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold transition-transform hover:translate-x-1" href="#">
            <span class="material-symbols-outlined">person</span>
            <span class="text-body-sm">Perfil</span>
        </a>
    </nav>
    <div class="mt-auto pt-6 border-t border-outline-variant/30">
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="w-10 h-10 bg-primary-container rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ substr(auth()->user()->name ?? 'C', 0, 1) }}{{ substr(auth()->user()->name ?? 'M', strpos(auth()->user()->name ?? 'C M', ' ') + 1, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name ?? 'Carlos Mendoza' }}</p>
                <p class="text-[10px] text-on-surface-variant">ID: {{ $employeeId ?? 'EMP-29402' }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="md:ml-64 flex flex-col min-h-screen">

    <!-- Top Bar -->
    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm flex justify-between items-center w-full px-6 py-4">
        <div>
            <h2 class="font-heading text-lg font-bold text-on-surface">Mi Perfil</h2>
            <p class="text-xs text-on-surface-variant">Gestiona tu información personal, laboral y configuración de acceso.</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-all active:scale-90 relative">
                <span class="material-symbols-outlined">notifications</span>
            </button>
        </div>
    </header>

    <!-- Profile Content -->
    <div class="px-6 py-8 max-w-5xl mx-auto w-full space-y-8">

        <!-- Profile Header Card -->
        <section class="bg-white rounded-2xl overflow-hidden shadow-sm border border-outline-variant/10">
            <!-- Banner -->
            <div class="h-32 bg-gradient-to-r from-primary-container via-primary to-primary relative">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxLjUiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-50"></div>
            </div>
            <!-- Profile Info -->
            <div class="px-8 pb-8 -mt-12 relative">
                <div class="flex flex-col md:flex-row items-start md:items-end gap-6">
                    <div class="w-24 h-24 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                        <div class="w-full h-full bg-gradient-to-br from-primary-container to-primary rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr(auth()->user()->name ?? 'C', 0, 1) }}{{ substr(auth()->user()->name ?? 'M', strpos(auth()->user()->name ?? 'C M', ' ') + 1, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 pt-2">
                        <h2 class="font-heading text-h3 font-bold text-on-surface">{{ auth()->user()->name ?? 'Carlos Alberto Mendoza' }}</h2>
                        <p class="text-body-md text-on-surface-variant flex items-center gap-2 mt-1">
                            <span class="material-symbols-outlined text-[16px] text-primary-container">restaurant</span>
                            {{ $cargo ?? 'Jefe de Cocina Sénior' }} • {{ $sede ?? 'Sede Polanco, CDMX' }}
                        </p>
                    </div>
                    <button class="px-5 py-2.5 bg-primary-container text-white font-bold text-sm rounded-xl hover:opacity-90 active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Editar Perfil
                    </button>
                </div>
            </div>
        </section>

        <!-- Info Sections Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Información Personal -->
            <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">person</span>
                    <h3 class="font-bold text-on-surface">Información Personal</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">email</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Correo electrónico</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ auth()->user()->email ?? 'carlos.mendoza@gotoeat.com' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">phone</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Teléfono</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $telefono ?? '+52 55 1234 5678' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">home</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Dirección</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $direccion ?? 'Calle de los Olivos 45, Col. Condesa, Alcaldía Cuauhtémoc, Ciudad de México, CP 06140' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">cake</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Nacimiento</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">{{ $nacimiento ?? '14 de Mayo, 1988' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">flag</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Nacionalidad</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">{{ $nacionalidad ?? 'Mexicana' }}</p>
                            </div>
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
                <div class="p-6 space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">badge</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">ID de Empleado</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $employeeId ?? 'EMP-29402' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">restaurant</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Cargo</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $cargo ?? 'Jefe de Cocina' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">location_on</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Sede</p>
                            <p class="text-sm font-medium text-on-surface mt-0.5">{{ $sede ?? 'Polanco I' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Fecha de Ingreso</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">{{ $fechaIngreso ?? '12 Ene 2021' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-on-surface-variant text-lg">description</span>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase">Tipo de Contrato</p>
                                <p class="text-sm font-medium text-on-surface mt-0.5">{{ $tipoContrato ?? 'Indefinido' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Configuración de Cuenta -->
        <section class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container">settings</span>
                <h3 class="font-bold text-on-surface">Configuración de Cuenta</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-5 bg-surface-container rounded-xl border border-outline-variant/10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-xl">lock</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface">Seguridad de la cuenta</p>
                            <p class="text-sm text-on-surface-variant">Cambia tu contraseña periódicamente para mantener tu cuenta segura.</p>
                        </div>
                    </div>
                    <button class="px-5 py-2.5 border-2 border-primary text-primary font-bold text-sm rounded-xl hover:bg-primary/5 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">key</span>
                        Cambiar Contraseña
                    </button>
                </div>
            </div>
        </section>

        <!-- Logros del Empleado -->
        <section class="bg-gradient-to-br from-primary-container to-primary rounded-2xl p-8 text-white shadow-lg shadow-primary-container/20 overflow-hidden relative">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings:'FILL' 1">emoji_events</span>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-bold tracking-widest text-white/70 uppercase mb-1">Logros del Empleado</p>
                    <h4 class="font-heading text-xl font-bold mb-1">Chef del Mes: Marzo 2024</h4>
                    <p class="text-sm text-white/80 leading-relaxed">Reconocimiento por excelencia en eficiencia operativa y liderazgo de equipo.</p>
                </div>
                <button class="px-5 py-2.5 bg-white/20 backdrop-blur-sm text-white font-bold text-sm rounded-xl hover:bg-white/30 transition-all flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">military_tech</span>
                    Ver Todos
                </button>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-outline-variant/30 py-8 px-6 bg-surface-container-low">
        <div class="max-w-5xl mx-auto text-center">
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
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="#">
        <span class="material-symbols-outlined">calendar_month</span>
        <span class="text-[10px]">Turnos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-on-surface-variant" href="#">
        <span class="material-symbols-outlined">payments</span>
        <span class="text-[10px]">Pagos</span>
    </a>
    <a class="flex flex-col items-center gap-1 p-2 text-primary font-bold" href="#">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
        <span class="text-[10px]">Perfil</span>
    </a>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</body></html>
