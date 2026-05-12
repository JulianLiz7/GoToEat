<x-admin-layout :restaurant="$restaurant">
<x-slot name="title">Agregar Empleado</x-slot>

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-xs text-gray-400 mb-6 font-medium">
    <a href="{{ route('admin.staff') }}" class="hover:text-orange-500 transition-colors">Management</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <a href="{{ route('admin.staff') }}" class="hover:text-orange-500 transition-colors">Staff List</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-orange-500 font-bold">Add Staff</span>
</nav>

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <h1 class="text-4xl font-black font-heading text-on-surface">Add Staff Member</h1>
        <p class="text-gray-500 mt-1">Registra un nuevo miembro del equipo de trabajo.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-gray-50 bg-gradient-to-r from-white to-orange-50/30 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <div>
                <h2 class="font-bold text-lg font-heading">New Employee</h2>
                <p class="text-sm text-gray-400">Completa la información del empleado</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.staff.store') }}" class="p-8 space-y-6">
            @csrf

            {{-- Aviso: solo usuarios registrados pueden ser empleados --}}
            <div class="flex gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <span class="material-symbols-outlined text-blue-500 text-[20px] shrink-0 mt-0.5">info</span>
                <p class="text-xs text-blue-700 leading-relaxed">
                    El empleado debe tener una cuenta registrada en la plataforma.
                    Si aún no tiene cuenta, primero debe registrarse y luego podrás asignarlo aquí.
                </p>
            </div>

            {{-- Selección de usuario --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                    Usuario registrado <span class="text-red-500">*</span>
                </label>
                <select name="user_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                    <option value="">— Seleccionar usuario —</option>
                    @foreach($availableUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @if($availableUsers->isEmpty())
                <p class="text-xs text-amber-600 mt-1.5">
                    <span class="material-symbols-outlined text-[14px] align-middle">warning</span>
                    No hay usuarios disponibles para asignar como empleados.
                </p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Cargo / Posición --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Cargo / Posición <span class="text-red-500">*</span>
                    </label>
                    <select name="position" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                        <option value="">— Seleccionar cargo —</option>
                        <option value="Chef">Chef</option>
                        <option value="Mesero">Mesero</option>
                        <option value="Cajero">Cajero</option>
                        <option value="Manager">Manager</option>
                        <option value="Bartender">Bartender</option>
                        <option value="Auxiliar">Auxiliar de cocina</option>
                        <option value="Domiciliario">Domiciliario</option>
                    </select>
                </div>

                {{-- Salario --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Salario mensual ($)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="salary" step="1000" min="0"
                               placeholder="1.300.000"
                               class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                    </div>
                </div>

                {{-- Fecha de inicio --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Fecha de vinculación
                    </label>
                    <input type="date" name="hire_date"
                           value="{{ now()->toDateString() }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Estado
                    </label>
                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm bg-white">
                        <option value="active" selected>Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="on_leave">De permiso</option>
                    </select>
                </div>

                {{-- Contacto de emergencia --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Contacto de emergencia
                    </label>
                    <input type="text" name="emergency_contact"
                           placeholder="Nombre y teléfono del contacto de emergencia"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm"/>
                </div>

                {{-- Notas --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        Notas adicionales
                    </label>
                    <textarea name="notes" rows="3"
                              placeholder="Observaciones, habilidades especiales, condiciones del contrato..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-sm resize-none"></textarea>
                </div>
            </div>

            {{-- Información de Ley 1935 --}}
            <div class="flex gap-3 p-4 bg-purple-50 rounded-xl border border-purple-100">
                <span class="material-symbols-outlined text-purple-500 text-[20px] shrink-0 mt-0.5">gavel</span>
                <p class="text-xs text-purple-700 leading-relaxed">
                    <strong>Ley 1935 de 2018:</strong> Las propinas que reciba este empleado se registrarán automáticamente al completar órdenes.
                    Las propinas pagadas con tarjeta quedan marcadas como pendientes de transferencia por el empleador.
                </p>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <a href="{{ route('admin.staff') }}"
                   class="px-6 py-3 rounded-xl font-semibold text-gray-500 hover:bg-gray-50 transition-colors text-sm">
                    ← Volver
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-primary-container text-white font-bold shadow-lg shadow-orange-200 hover:brightness-110 active:scale-95 transition-all flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Agregar empleado
                </button>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
