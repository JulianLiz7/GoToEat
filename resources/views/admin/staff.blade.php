<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Personal</x-slot>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold font-heading text-on-background">Personal</h2>
        <p class="text-gray-500 mt-1">Equipo de trabajo y gestión de turnos.</p>
    </div>
    <a href="{{ route('admin.staff.create') }}"
       class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all shadow-sm shadow-orange-200">
        <span class="material-symbols-outlined text-[20px]">person_add</span>
        Agregar empleado
    </a>
</div>

{{-- Resumen --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
    $byRole = $employees->groupBy(fn($e) => $e->position ?? 'Sin cargo');
    @endphp
    <div class="bg-white p-5 rounded-2xl shadow-sm text-center">
        <p class="text-3xl font-bold font-heading text-on-surface">{{ $employees->count() }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Total empleados</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm text-center">
        <p class="text-3xl font-bold font-heading text-emerald-600">{{ $employees->where('status','active')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Activos</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm text-center">
        <p class="text-3xl font-bold font-heading text-amber-600">{{ $employees->where('status','on_leave')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">De permiso</p>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm text-center">
        <p class="text-3xl font-bold font-heading text-gray-400">{{ $employees->where('status','inactive')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Inactivos</p>
    </div>
</div>

{{-- Lista de empleados --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-on-background flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-primary-container">group</span>
            Directorio del equipo
        </h3>
    </div>

    @if($employees->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-200 mb-3">group</span>
            <p class="text-gray-400 font-medium">Sin empleados registrados</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($employees as $employee)
            <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr($employee->user->name ?? '?', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm">{{ $employee->user->name ?? 'Usuario eliminado' }}</p>
                    <p class="text-xs text-gray-400">{{ $employee->position ?? 'Sin cargo' }}</p>
                </div>
                <div class="hidden md:block text-xs text-gray-400">
                    Desde {{ $employee->hire_date?->format('M Y') ?? '—' }}
                </div>
                <div class="text-sm font-semibold text-gray-600">
                    ${{ number_format($employee->salary ?? 0, 0) }}/mes
                </div>
                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full shrink-0
                    {{ match($employee->status) {
                        'active'   => 'bg-emerald-100 text-emerald-700',
                        'on_leave' => 'bg-amber-100 text-amber-700',
                        default    => 'bg-gray-100 text-gray-500'
                    } }}">
                    {{ match($employee->status) {
                        'active'   => 'Activo',
                        'on_leave' => 'De permiso',
                        default    => 'Inactivo'
                    } }}
                </span>
            </div>
            @endforeach
        </div>
    @endif
</div>

</x-admin-layout>
