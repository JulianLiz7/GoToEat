<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Verificar Reserva — GoToEat</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f0f2f7; }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body class="font-body antialiased min-h-screen flex flex-col items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-200">
                <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings:'FILL' 1">qr_code_scanner</span>
            </div>
            <h1 class="font-heading text-2xl font-bold text-gray-900">Verificación de Reserva</h1>
            <p class="text-gray-500 text-sm mt-1">GoToEat — Sistema de control de acceso</p>
        </div>

        {{-- Reservation card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            {{-- Status banner --}}
            @if($reservation->status === 'confirmed')
            <div class="bg-green-500 text-white p-4 text-center">
                <span class="material-symbols-outlined text-[32px] block mx-auto mb-1" style="font-variation-settings:'FILL' 1">verified</span>
                <p class="font-bold text-lg">¡Reserva Confirmada!</p>
                <p class="text-green-100 text-sm">El cliente tiene acceso autorizado</p>
            </div>
            @elseif($reservation->status === 'pending')
            <div class="bg-amber-400 text-amber-900 p-4 text-center">
                <span class="material-symbols-outlined text-[32px] block mx-auto mb-1" style="font-variation-settings:'FILL' 1">pending</span>
                <p class="font-bold text-lg">Reserva Pendiente</p>
                <p class="text-amber-800 text-sm">Debe ser confirmada antes del acceso</p>
            </div>
            @elseif($reservation->status === 'cancelled')
            <div class="bg-red-500 text-white p-4 text-center">
                <span class="material-symbols-outlined text-[32px] block mx-auto mb-1" style="font-variation-settings:'FILL' 1">cancel</span>
                <p class="font-bold text-lg">Reserva Cancelada</p>
                <p class="text-red-100 text-sm">Esta reserva ha sido cancelada</p>
            </div>
            @elseif($reservation->status === 'completed')
            <div class="bg-blue-500 text-white p-4 text-center">
                <span class="material-symbols-outlined text-[32px] block mx-auto mb-1" style="font-variation-settings:'FILL' 1">task_alt</span>
                <p class="font-bold text-lg">Reserva Completada</p>
                <p class="text-blue-100 text-sm">Esta reserva ya fue atendida</p>
            </div>
            @endif

            {{-- Details --}}
            <div class="p-6 space-y-4">
                {{-- Cliente --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($reservation->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $reservation->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $reservation->user->email }}</p>
                    </div>
                </div>

                {{-- Reservation info --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-orange-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Fecha</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Hora</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Personas</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $reservation->party_size }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500 mb-1">Reserva #</p>
                        <p class="font-semibold text-gray-800 text-sm">#{{ $reservation->id }}</p>
                    </div>
                </div>

                {{-- Restaurante --}}
                <div class="border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                    @if($reservation->restaurant->logo_path)
                    <img src="{{ Storage::url($reservation->restaurant->logo_path) }}" class="w-10 h-10 rounded-xl object-cover">
                    @else
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-xs font-bold" style="background: {{ $reservation->restaurant->primary_color ?? '#f97316' }}">
                        {{ strtoupper(substr($reservation->restaurant->name, 0, 2)) }}
                    </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $reservation->restaurant->name }}</p>
                        <p class="text-xs text-gray-500">{{ $reservation->restaurant->cuisine_type ?? 'Restaurante' }}</p>
                    </div>
                </div>

                @if($reservation->notes)
                <div class="bg-blue-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500 mb-1">Notas del cliente</p>
                    <p class="text-sm text-gray-700 italic">{{ $reservation->notes }}</p>
                </div>
                @endif

                @if($reservation->selected_items && count($reservation->selected_items))
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pre-orden</p>
                    <div class="space-y-1.5">
                        @foreach($reservation->selected_items as $item)
                        <div class="flex justify-between text-sm bg-gray-50 rounded-lg px-3 py-2">
                            <span class="text-gray-700">{{ $item['name'] ?? '' }}{{ !empty($item['qty']) ? ' × '.$item['qty'] : '' }}</span>
                            @if(!empty($item['price']))
                            <span class="text-gray-500 font-medium">${{ number_format($item['price'] * ($item['qty'] ?? 1), 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Admin/Mesero actions --}}
            @if(auth()->user()?->hasAnyRole(['admin','mesero']))
            <div class="px-6 pb-6 space-y-3">
                @if($reservation->status === 'pending')
                <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-all">
                        ✓ Confirmar y dar acceso
                    </button>
                </form>
                @elseif($reservation->status === 'confirmed')
                <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-all">
                        Marcar como completada
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">GoToEat · Verificación de reservas</p>
    </div>

</body>
</html>
