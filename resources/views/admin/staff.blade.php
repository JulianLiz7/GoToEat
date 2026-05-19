<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Personal</x-slot>

{{-- ══ Encabezado ════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-2 font-medium">
            <span>Administración</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-orange-500 font-bold">Personal</span>
        </nav>
        <h1 class="text-4xl font-black font-heading text-on-surface">Directorio de Personal</h1>
        <p class="text-gray-500 mt-1">Gestiona tu equipo y monitorea disponibilidad en tiempo real.</p>
    </div>
    <div class="flex gap-3 shrink-0">
        <button onclick="abrirModal('modalNotificacion')"
                class="flex items-center gap-2 bg-on-surface text-white px-5 py-3 rounded-2xl font-bold
                       hover:bg-gray-800 active:scale-95 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">campaign</span>
            Enviar aviso
        </button>
        <a href="{{ route('admin.staff.create') }}"
           class="flex items-center gap-2 bg-primary-container text-white px-5 py-3 rounded-2xl font-bold
                  hover:brightness-110 active:scale-95 transition-all shadow-lg shadow-orange-200">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Agregar empleado
        </a>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ══ Canal de notificaciones activas ════════════════════════════ --}}
@if($activeNotifications->isNotEmpty())
<div class="mb-8 space-y-3">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-[18px] text-orange-500">campaign</span>
        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide">
            Avisos activos para el equipo
        </h3>
        <span class="text-xs bg-orange-100 text-orange-700 font-bold px-2 py-0.5 rounded-full">
            {{ $activeNotifications->count() }}
        </span>
    </div>

    @foreach($activeNotifications as $notif)
    @php
    $notifStyles = match($notif->type) {
        'urgente' => ['bg' => 'bg-red-50',    'border' => 'border-red-300',    'icon' => 'priority_high',   'iconColor' => 'text-red-600',    'badge' => 'bg-red-100 text-red-700'],
        'aviso'   => ['bg' => 'bg-amber-50',  'border' => 'border-amber-300',  'icon' => 'warning',         'iconColor' => 'text-amber-600',  'badge' => 'bg-amber-100 text-amber-700'],
        default   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'icon' => 'info',            'iconColor' => 'text-blue-600',   'badge' => 'bg-blue-100 text-blue-700'],
    };
    @endphp
    <div class="{{ $notifStyles['bg'] }} border {{ $notifStyles['border'] }} rounded-2xl p-4 flex items-start gap-4">
        <div class="shrink-0 w-10 h-10 rounded-xl bg-white/70 flex items-center justify-center">
            <span class="material-symbols-outlined {{ $notifStyles['iconColor'] }} text-[22px]"
                  style="font-variation-settings:'FILL' 1">{{ $notifStyles['icon'] }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
                <p class="font-bold text-on-surface text-sm">{{ $notif->title }}</p>
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $notifStyles['badge'] }}">
                    {{ ucfirst($notif->type) }}
                </span>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $notif->message }}</p>
            <div class="flex items-center gap-3 mt-2">
                <span class="text-[11px] text-gray-400">
                    Por <span class="font-semibold">{{ $notif->sender?->name ?? 'Admin' }}</span>
                    · {{ $notif->created_at->diffForHumans() }}
                </span>
                <span class="text-[11px] flex items-center gap-1 {{ $notif->expires_at ? 'text-amber-600' : 'text-gray-400' }}">
                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                    {{ $notif->timeLeft() }}
                </span>
            </div>
        </div>
        {{-- Archivar --}}
        <form method="POST" action="{{ route('admin.staff.notify.archive', $notif->id) }}" class="shrink-0">
            @csrf @method('DELETE')
            <button type="submit"
                    title="Archivar notificación"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/60 text-gray-400 hover:text-gray-600 transition-all">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </form>
    </div>
    @endforeach
</div>
@endif

{{-- ══ KPI Bento ════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    @php
    $kpis = [
        ['label'=>'Total empleados', 'value'=>$employees->count(),                            'icon'=>'groups',       'bg'=>'bg-orange-50', 'text'=>'text-orange-600'],
        ['label'=>'Activos',         'value'=>$employees->where('status','active')->count(),  'icon'=>'check_circle', 'bg'=>'bg-emerald-50','text'=>'text-emerald-600'],
        ['label'=>'De permiso',      'value'=>$employees->where('status','on_leave')->count(),'icon'=>'coffee',       'bg'=>'bg-blue-50',   'text'=>'text-blue-600'],
        ['label'=>'Inactivos',       'value'=>$employees->where('status','inactive')->count(),'icon'=>'warning',      'bg'=>'bg-amber-50',  'text'=>'text-amber-500'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl {{ $k['bg'] }} flex items-center justify-center {{ $k['text'] }} group-hover:scale-110 transition-transform shrink-0">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">{{ $k['icon'] }}</span>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ $k['label'] }}</p>
            <p class="text-2xl font-black font-heading text-on-surface">{{ $k['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ Tabla de empleados ══════════════════════════════════════════ --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">

    {{-- Toolbar --}}
    <div class="px-7 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
        <p class="text-sm font-medium text-gray-500 italic">"Siempre al servicio" — Lema del equipo</p>
        <span class="text-xs text-gray-400">{{ $employees->count() }} empleado(s) en total</span>
    </div>

    @if($employees->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">group</span>
        <p class="text-gray-400 font-medium text-lg">Sin empleados registrados</p>
        <a href="{{ route('admin.staff.create') }}" class="mt-4 text-orange-500 font-semibold text-sm hover:underline">
            Agregar el primero
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[11px] uppercase tracking-widest text-gray-400 font-bold border-b border-gray-100 bg-gray-50/10">
                    <th class="px-7 py-4">Empleado</th>
                    <th class="px-7 py-4">Cargo</th>
                    <th class="px-7 py-4">Estado</th>
                    <th class="px-7 py-4">Contacto</th>
                    <th class="px-7 py-4">Salario</th>
                    <th class="px-7 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($employees as $emp)
                @php
                $statusCfg = match($emp->status) {
                    'active'   => ['dot' => 'bg-emerald-500 animate-pulse', 'label' => 'Activo',     'color' => 'text-emerald-700'],
                    'on_leave' => ['dot' => 'bg-blue-400',                  'label' => 'De permiso', 'color' => 'text-blue-600'],
                    default    => ['dot' => 'bg-gray-300',                  'label' => 'Inactivo',   'color' => 'text-gray-400'],
                };
                $cargoBadge = match(strtolower($emp->position ?? '')) {
                    'chef'    => 'bg-orange-50 text-orange-600 border-orange-100',
                    'mesero'  => 'bg-purple-50 text-purple-600 border-purple-100',
                    'cajero'  => 'bg-blue-50 text-blue-600 border-blue-100',
                    'manager','administrador' => 'bg-gray-100 text-gray-700 border-gray-200',
                    default   => 'bg-gray-50 text-gray-500 border-gray-100',
                };
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    {{-- Empleado --}}
                    <td class="px-7 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-container/10 border border-primary-container/20
                                        flex items-center justify-center text-primary-container font-black text-sm shrink-0">
                                {{ strtoupper(substr($emp->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-on-surface text-sm">{{ $emp->user->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-400">#EMP-{{ str_pad($emp->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Cargo --}}
                    <td class="px-7 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $cargoBadge }}">
                            {{ ucfirst($emp->position ?? 'Sin cargo') }}
                        </span>
                    </td>
                    {{-- Estado --}}
                    <td class="px-7 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $statusCfg['dot'] }}"></span>
                            <span class="text-sm font-medium {{ $statusCfg['color'] }}">{{ $statusCfg['label'] }}</span>
                        </div>
                    </td>
                    {{-- Contacto --}}
                    <td class="px-7 py-4 text-sm text-gray-500">
                        <p>{{ $emp->user->email ?? '—' }}</p>
                        @if($emp->user->phone ?? false)
                        <p class="text-xs text-gray-400">{{ $emp->user->phone }}</p>
                        @endif
                    </td>
                    {{-- Salario --}}
                    <td class="px-7 py-4 text-sm font-semibold text-on-surface">
                        {{ $emp->salary ? '$' . number_format($emp->salary, 0) . '/mes' : '—' }}
                    </td>
                    {{-- Acciones --}}
                    <td class="px-7 py-4">
                        <div class="flex items-center justify-end gap-1">
                            {{-- Editar --}}
                            <button type="button"
                                    title="Editar empleado"
                                    onclick="abrirEditarEmp(
                                        {{ $emp->id }},
                                        '{{ addslashes($emp->user->name ?? '') }}',
                                        '{{ addslashes($emp->position ?? '') }}',
                                        '{{ $emp->salary ?? '' }}',
                                        '{{ $emp->hire_date?->toDateString() ?? '' }}',
                                        '{{ $emp->status }}',
                                        '{{ addslashes($emp->emergency_contact ?? '') }}',
                                        '{{ addslashes($emp->notes ?? '') }}'
                                    )"
                                    class="p-2 text-amber-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
                            {{-- Eliminar --}}
                            <form method="POST"
                                  action="{{ route('admin.staff.destroy', $emp->id) }}"
                                  onsubmit="return confirm('¿Eliminar a {{ addslashes($emp->user->name ?? 'este empleado') }} del equipo?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="Eliminar empleado"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-[18px]">person_remove</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- ══ Historial de notificaciones ════════════════════════════════ --}}
@if($notificationHistory->isNotEmpty())
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
    <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-gray-400">history</span>
            Historial de avisos al equipo
        </h3>
        <span class="text-xs text-gray-400">Últimos {{ $notificationHistory->count() }}</span>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($notificationHistory as $notif)
        @php
        $expired  = $notif->isExpired();
        $archived = !is_null($notif->archived_at);
        @endphp
        <div class="px-7 py-4 flex items-start gap-4 {{ ($expired || $archived) ? 'opacity-60' : '' }}">
            <div class="w-8 h-8 rounded-lg {{ match($notif->type) { 'urgente'=>'bg-red-100', 'aviso'=>'bg-amber-100', default=>'bg-blue-100' } }} flex items-center justify-center shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-[16px] {{ match($notif->type) { 'urgente'=>'text-red-600','aviso'=>'text-amber-600',default=>'text-blue-600' } }}"
                      style="font-variation-settings:'FILL' 1">
                    {{ match($notif->type) { 'urgente'=>'priority_high','aviso'=>'warning',default=>'info' } }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-on-surface">{{ $notif->title }}</p>
                    @if($expired)
                    <span class="text-[10px] bg-gray-100 text-gray-500 font-bold px-2 py-0.5 rounded-full">Vencida</span>
                    @elseif($archived)
                    <span class="text-[10px] bg-gray-100 text-gray-500 font-bold px-2 py-0.5 rounded-full">Archivada</span>
                    @else
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full">Activa</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $notif->message }}</p>
                <p class="text-[11px] text-gray-400 mt-1">
                    {{ $notif->sender?->name ?? 'Admin' }} ·
                    {{ $notif->created_at->format('d/m/Y H:i') }}
                    @if($notif->expires_at)
                     · Vigencia hasta {{ $notif->expires_at->format('d/m/Y H:i') }}
                    @else
                     · Permanente
                    @endif
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ Banner IA ════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden bg-on-surface rounded-3xl p-10 text-white flex flex-col md:flex-row items-center justify-between gap-8 group">
    <div class="relative z-10 max-w-lg">
        <span class="inline-block px-4 py-1.5 bg-orange-500/20 text-orange-400 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
            Nueva función
        </span>
        <h3 class="text-3xl font-black font-heading mb-4 leading-tight">
            Turnos inteligentes con IA
        </h3>
        <p class="text-gray-400 text-base mb-7 leading-relaxed">
            Reduce costos laborales hasta un 15% con asignación predictiva de turnos basada en datos históricos de reservas y eventos locales.
        </p>
        <a href="{{ route('admin.ai') }}"
           class="inline-flex items-center gap-3 px-7 py-3.5 bg-orange-500 hover:bg-orange-400 rounded-2xl font-bold transition-all shadow-xl shadow-orange-500/30 text-sm">
            Consultar al asistente IA
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">auto_awesome</span>
        </a>
    </div>
    <div class="relative w-full max-w-xs shrink-0">
        <div class="absolute inset-0 bg-gradient-to-tr from-orange-500/30 to-transparent blur-3xl opacity-40"></div>
        <div class="relative transform rotate-12 group-hover:rotate-6 transition-transform duration-500">
            <div class="w-full aspect-square bg-gradient-to-br from-orange-400 to-orange-600 rounded-3xl shadow-2xl p-8">
                <div class="bg-white/10 w-full h-full rounded-2xl border border-white/20 p-4 space-y-3">
                    <div class="w-1/2 h-2 bg-white/20 rounded-full"></div>
                    <div class="w-3/4 h-2 bg-white/20 rounded-full"></div>
                    <div class="grid grid-cols-7 gap-1 mt-3">
                        @foreach([40,80,100,40,60,20,90] as $h)
                        <div class="aspect-square bg-orange-400/{{ $h > 70 ? '90' : ($h > 50 ? '60' : '40') }} rounded-sm"></div>
                        @endforeach
                    </div>
                    <div class="w-full h-16 bg-white/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl opacity-40">insights</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODALES
══════════════════════════════════════════════════════════════════ --}}

{{-- ── Modal: Credenciales del nuevo empleado ─────────────────────── --}}
@if(session('new_employee_credentials'))
@php
    $creds = session('new_employee_credentials');
@endphp
<div id="modalCredenciales" class="fixed inset-0 z-50 flex items-center justify-center p-5 bg-black/60 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center relative border border-gray-100 transform transition-all duration-300 scale-100">
        
        {{-- Badge superior decorativo --}}
        <div class="w-20 h-20 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-orange-100/50">
            <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1">key</span>
        </div>

        {{-- Encabezado --}}
        <h2 class="text-2xl font-black font-heading text-on-surface mb-2">¡Empleado Registrado!</h2>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            Se ha creado exitosamente el perfil de <strong class="text-gray-800">{{ $creds['name'] }}</strong> ({{ $creds['position'] }}). Copia sus credenciales de acceso para entregárselas directamente:
        </p>

        {{-- Caja de credenciales --}}
        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-4 mb-6 text-left">
            {{-- Usuario --}}
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nombre de usuario (o Correo)</span>
                <div class="flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-gray-200">
                    <span id="credUsername" class="text-sm font-mono font-bold text-on-surface select-all">{{ $creds['username'] }}</span>
                    <button type="button" onclick="copiarTexto('credUsername', 'btnCopyUser')" id="btnCopyUser"
                            class="text-gray-400 hover:text-orange-500 transition-colors flex items-center gap-1 text-xs font-bold">
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                        <span>Copiar</span>
                    </button>
                </div>
            </div>

            {{-- Contraseña --}}
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Contraseña temporal</span>
                <div class="flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-gray-200">
                    <span id="credPassword" class="text-sm font-mono font-bold text-orange-600 select-all">{{ $creds['password'] }}</span>
                    <button type="button" onclick="copiarTexto('credPassword', 'btnCopyPass')" id="btnCopyPass"
                            class="text-gray-400 hover:text-orange-500 transition-colors flex items-center gap-1 text-xs font-bold">
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                        <span>Copiar</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Advertencia de seguridad --}}
        <div class="flex gap-2.5 p-3.5 bg-amber-50 rounded-xl border border-amber-100 text-left mb-6">
            <span class="material-symbols-outlined text-amber-500 text-[18px] shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">warning</span>
            <p class="text-[11px] text-amber-800 leading-normal">
                Por motivos de seguridad, esta contraseña solo se mostrará <strong>una vez</strong>. Asegúrate de copiarla ahora antes de cerrar esta ventana.
            </p>
        </div>

        {{-- Botón de acción --}}
        <button type="button" onclick="cerrarModal('modalCredenciales')"
                class="w-full py-4 rounded-2xl bg-primary-container text-white font-bold shadow-lg shadow-orange-200 hover:brightness-110 active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            Entendido y Copiado
        </button>
    </div>
</div>

<script>
function copiarTexto(elementId, btnId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById(btnId);
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] text-emerald-500">check</span><span class="text-emerald-600">Copiado</span>';
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 2000);
    }).catch(err => {
        console.error('Error al copiar: ', err);
    });
}
</script>
@endif

{{-- ── Modal: Editar empleado ─────────────────────────────────────── --}}
<div id="modalEditarEmp" class="hidden fixed inset-0 z-50 flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col" style="max-height:90vh">

        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">manage_accounts</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Editar empleado</h2>
                    <p id="editarEmpNombre" class="text-xs text-gray-400 truncate max-w-[200px]"></p>
                </div>
            </div>
            <button onclick="cerrarModal('modalEditarEmp')"
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="formEditarEmp" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PUT')
            <div class="flex-1 overflow-y-auto px-7 py-6 space-y-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                            Cargo <span class="text-red-500">*</span>
                        </label>
                        <select name="position" id="editEmpPosition"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none bg-white">
                            <option value="Mesero">Mesero</option>
                            <option value="Chef">Chef</option>
                            <option value="Cajero">Cajero</option>
                            <option value="Manager">Manager</option>
                            <option value="Auxiliar">Auxiliar de cocina</option>
                            <option value="Bartender">Bartender</option>
                            <option value="Domiciliario">Domiciliario</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Estado</label>
                        <select name="status" id="editEmpStatus"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none bg-white">
                            <option value="active">Activo</option>
                            <option value="on_leave">De permiso</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Salario mensual ($)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="salary" id="editEmpSalary" step="1000" min="0"
                               placeholder="1.300.000"
                               class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Fecha de vinculación</label>
                    <input type="date" name="hire_date" id="editEmpHireDate"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Contacto de emergencia</label>
                    <input type="text" name="emergency_contact" id="editEmpEmergency"
                           placeholder="Nombre y teléfono"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Notas</label>
                    <textarea name="notes" id="editEmpNotes" rows="2"
                              placeholder="Observaciones sobre este empleado..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none resize-none"></textarea>
                </div>
            </div>

            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex justify-end gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalEditarEmp')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-bold hover:bg-amber-600 active:scale-95 shadow-sm shadow-amber-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Enviar notificación al equipo ────────────────────────── --}}
<div id="modalNotificacion" class="hidden fixed inset-0 z-50 flex items-center justify-center p-5 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg flex flex-col" style="max-height:90vh">

        <div class="shrink-0 px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-gray-50/60 rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-on-surface rounded-xl flex items-center justify-center text-white shrink-0">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">campaign</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg font-heading">Enviar aviso al equipo</h2>
                    <p class="text-xs text-gray-400">Todos los empleados verán este mensaje</p>
                </div>
            </div>
            <button onclick="cerrarModal('modalNotificacion')"
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full text-gray-400">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.staff.notify') }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto px-7 py-6 space-y-5">

                {{-- Tipo de aviso --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Tipo de aviso</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="info" class="peer hidden" checked>
                            <div class="py-3.5 rounded-2xl border-2 border-gray-200 text-center transition-all
                                        text-gray-400 hover:border-gray-300
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                <span class="material-symbols-outlined text-[20px] block mx-auto mb-1"
                                      style="font-variation-settings:'FILL' 1">info</span>
                                <span class="text-xs font-bold">Información</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="aviso" class="peer hidden">
                            <div class="py-3.5 rounded-2xl border-2 border-gray-200 text-center transition-all
                                        text-gray-400 hover:border-gray-300
                                        peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700">
                                <span class="material-symbols-outlined text-[20px] block mx-auto mb-1"
                                      style="font-variation-settings:'FILL' 1">warning</span>
                                <span class="text-xs font-bold">Aviso</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="urgente" class="peer hidden">
                            <div class="py-3.5 rounded-2xl border-2 border-gray-200 text-center transition-all
                                        text-gray-400 hover:border-gray-300
                                        peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700">
                                <span class="material-symbols-outlined text-[20px] block mx-auto mb-1"
                                      style="font-variation-settings:'FILL' 1">priority_high</span>
                                <span class="text-xs font-bold">Urgente</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Título --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required maxlength="120"
                           placeholder="ej. Reunión de equipo hoy a las 3pm"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-gray-800 focus:ring-4 focus:ring-gray-800/10 outline-none"/>
                </div>

                {{-- Mensaje --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Mensaje <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" required rows="4" maxlength="1000"
                              placeholder="Escribe aquí el mensaje para todo tu equipo..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-gray-800 focus:ring-4 focus:ring-gray-800/10 outline-none resize-none"></textarea>
                </div>

                {{-- Vigencia --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                        Vigencia del aviso
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([
                            ['1h',   '1 hora'],
                            ['4h',   '4 horas'],
                            ['8h',   '8 horas'],
                            ['24h',  '1 día'],
                            ['3d',   '3 días'],
                            ['7d',   '7 días'],
                            ['30d',  '30 días'],
                            ['permanente', 'Permanente'],
                        ] as [$val, $lbl])
                        <label class="cursor-pointer">
                            <input type="radio" name="duracion" value="{{ $val }}" class="peer hidden"
                                   {{ $val === '24h' ? 'checked' : '' }}>
                            <div class="py-2 px-1 rounded-xl border-2 border-gray-200 text-center text-xs font-semibold transition-all
                                        text-gray-500 hover:border-gray-400
                                        peer-checked:border-on-surface peer-checked:bg-on-surface peer-checked:text-white">
                                {{ $lbl }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2">
                        <span class="material-symbols-outlined text-[13px] align-middle">info</span>
                        El mensaje desaparece del feed activo al vencer, pero queda en el historial.
                    </p>
                </div>
            </div>

            <div class="shrink-0 px-7 py-5 border-t border-gray-100 flex justify-end gap-3 bg-white rounded-b-3xl">
                <button type="button" onclick="cerrarModal('modalNotificacion')"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-on-surface text-white text-sm font-bold hover:bg-gray-800 active:scale-95 shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Enviar a todo el equipo
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Scripts ─────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
function abrirModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }

['modalEditarEmp','modalNotificacion'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) cerrarModal(id);
    });
});

function abrirEditarEmp(id, nombre, position, salary, hireDate, status, emergency, notes) {
    const form = document.getElementById('formEditarEmp');
    form.action = `/admin/staff/${id}`;
    document.getElementById('editarEmpNombre').textContent = nombre;

    // Selects con auto-insert si el valor no está en las opciones
    function setSelect(name, value) {
        const sel = form.querySelector(`[name="${name}"]`);
        if (!sel) return;
        sel.value = value;
        if (sel.value !== value && value) {
            const opt = document.createElement('option');
            opt.value = value; opt.textContent = value;
            sel.prepend(opt);  sel.value = value;
        }
    }

    setSelect('position', position);
    setSelect('status',   status);

    form.querySelector('[name="salary"]').value            = salary;
    form.querySelector('[name="hire_date"]').value         = hireDate;
    form.querySelector('[name="emergency_contact"]').value = emergency;
    form.querySelector('[name="notes"]').value             = notes;

    abrirModal('modalEditarEmp');
}
</script>
@endpush

</x-admin-layout>
