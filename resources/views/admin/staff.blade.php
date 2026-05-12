<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Personal</x-slot>

{{-- ══ Breadcrumb + Header ════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-2 font-medium">
            <span>Management</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-orange-500 font-bold">Staff List</span>
        </nav>
        <h1 class="text-4xl font-black font-heading text-on-surface">Staff Directory</h1>
        <p class="text-gray-500 mt-1">Manage your restaurant team and monitor real-time availability.</p>
    </div>
    <a href="{{ route('admin.staff.create') }}"
       class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-orange-200 hover:scale-105 active:scale-95 transition-all">
        <span class="material-symbols-outlined">person_add</span>
        Add Staff
    </a>
</div>

{{-- ══ KPIs Bento ════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    @php
    $stats = [
        ['label' => 'Total Staff',   'value' => $employees->count(),                          'icon' => 'groups',       'bg' => 'bg-orange-50', 'text' => 'text-orange-500'],
        ['label' => 'Active Now',    'value' => $employees->where('status','active')->count(), 'icon' => 'check_circle', 'bg' => 'bg-emerald-50','text' => 'text-emerald-600'],
        ['label' => 'On Leave',      'value' => $employees->where('status','on_leave')->count(),'icon' => 'coffee',      'bg' => 'bg-blue-50',   'text' => 'text-blue-500'],
        ['label' => 'Inactive',      'value' => $employees->where('status','inactive')->count(),'icon' => 'warning',     'bg' => 'bg-amber-50',  'text' => 'text-amber-500'],
    ];
    @endphp
    @foreach($stats as $s)
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl {{ $s['bg'] }} flex items-center justify-center {{ $s['text'] }} group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">{{ $s['icon'] }}</span>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold font-heading text-on-surface">{{ $s['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ Tabla de empleados ══════════════════════════════════════ --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden mb-12">

    {{-- Toolbar --}}
    <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <p class="text-sm font-medium text-gray-500 italic">"Always be serving" — Team Motto</p>
        <div class="flex items-center gap-2">
            <button class="p-2 text-gray-400 hover:text-orange-500 transition-colors">
                <span class="material-symbols-outlined">filter_list</span>
            </button>
            <button class="p-2 text-gray-400 hover:text-orange-500 transition-colors">
                <span class="material-symbols-outlined">download</span>
            </button>
        </div>
    </div>

    @if($employees->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">group</span>
        <p class="text-gray-400 font-medium">Sin empleados registrados</p>
        <a href="{{ route('admin.staff.create') }}"
           class="mt-4 text-orange-500 font-semibold text-sm hover:underline">Agregar el primero</a>
    </div>
    @else
    {{-- Tabla --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[11px] uppercase tracking-widest text-gray-400 font-bold border-b border-gray-50 bg-gray-50/10">
                    <th class="px-8 py-5">Employee Name</th>
                    <th class="px-8 py-5">Role</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5">Contact Info</th>
                    <th class="px-8 py-5">Salary</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($employees as $employee)
                @php
                $roleBadge = match($employee->position ?? '') {
                    'Chef','chef'       => 'bg-orange-50 text-orange-600 border-orange-100',
                    'Mesero','mesero'   => 'bg-purple-50 text-purple-600 border-purple-100',
                    'Cajero','cajero'   => 'bg-blue-50 text-blue-600 border-blue-100',
                    'Manager','manager' => 'bg-blue-50 text-blue-600 border-blue-100',
                    default             => 'bg-gray-50 text-gray-600 border-gray-100',
                };
                $statusConfig = match($employee->status) {
                    'active'   => ['dot' => 'bg-emerald-500 animate-pulse', 'label' => 'Active',    'color' => 'text-emerald-600'],
                    'on_leave' => ['dot' => 'bg-blue-400',                  'label' => 'On Leave',  'color' => 'text-blue-500'],
                    default    => ['dot' => 'bg-gray-400',                  'label' => 'Inactive',  'color' => 'text-gray-400'],
                };
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    {{-- Nombre --}}
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600 font-bold text-sm shrink-0">
                                {{ strtoupper(substr($employee->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-on-surface">{{ $employee->user->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-400">#EMP-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Rol --}}
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $roleBadge }}">
                            {{ ucfirst($employee->position ?? 'Sin cargo') }}
                        </span>
                    </td>
                    {{-- Estado --}}
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }}"></span>
                            <span class="text-sm font-medium {{ $statusConfig['color'] }}">{{ $statusConfig['label'] }}</span>
                        </div>
                    </td>
                    {{-- Contacto --}}
                    <td class="px-8 py-5 text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span>{{ $employee->user->email ?? '—' }}</span>
                            @if($employee->user->phone ?? false)
                            <span class="text-xs font-medium text-gray-400">{{ $employee->user->phone }}</span>
                            @endif
                        </div>
                    </td>
                    {{-- Salario --}}
                    <td class="px-8 py-5 text-sm font-semibold text-on-surface">
                        {{ $employee->salary ? '$' . number_format($employee->salary, 0) . '/mes' : '—' }}
                    </td>
                    {{-- Acciones --}}
                    <td class="px-8 py-5 text-right">
                        <button class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between">
        <p class="text-sm text-gray-400 font-medium">{{ $employees->count() }} empleado(s) en total</p>
    </div>
    @endif
</div>

{{-- ══ Banner AI Scheduler ════════════════════════════════════ --}}
<div class="relative overflow-hidden bg-on-surface rounded-3xl p-12 text-white flex flex-col md:flex-row items-center justify-between gap-8 group">
    <div class="relative z-10 max-w-lg">
        <span class="inline-block px-4 py-1.5 bg-orange-500/20 text-orange-400 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
            New Feature
        </span>
        <h3 class="text-3xl font-black font-heading mb-4 leading-tight">Smart Shift Scheduling with AI</h3>
        <p class="text-gray-400 text-base mb-8 leading-relaxed">
            Reduce labor costs by 15% with predictive staffing based on historical booking data and local events.
        </p>
        <button class="px-8 py-4 bg-orange-500 hover:bg-orange-400 rounded-2xl font-bold transition-all shadow-xl shadow-orange-500/30 flex items-center gap-3 text-sm">
            Try AI Scheduler
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">auto_awesome</span>
        </button>
    </div>
    <div class="relative w-full max-w-xs">
        <div class="absolute inset-0 bg-gradient-to-tr from-orange-500/40 to-transparent blur-3xl opacity-50"></div>
        <div class="relative transform rotate-12 group-hover:rotate-6 transition-transform duration-500 ease-out">
            <div class="w-full aspect-square bg-gradient-to-br from-orange-400 to-orange-600 rounded-3xl shadow-2xl flex items-center justify-center p-8">
                <div class="bg-white/10 backdrop-blur-xl w-full h-full rounded-2xl border border-white/20 p-4 space-y-4">
                    <div class="w-1/2 h-2 bg-white/20 rounded-full"></div>
                    <div class="w-3/4 h-2 bg-white/20 rounded-full"></div>
                    <div class="grid grid-cols-7 gap-1 mt-4">
                        @foreach([40, 80, 100, 40, 60, 20, 90] as $h)
                        <div class="aspect-square bg-orange-400/{{ $h > 70 ? '90' : ($h > 50 ? '60' : '40') }} rounded-sm"></div>
                        @endforeach
                    </div>
                    <div class="w-full h-20 bg-white/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl opacity-50">insights</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-admin-layout>
