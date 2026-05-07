<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            @if(auth()->user()->ownedRestaurants()->exists())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Resumen de tu Restaurante') }}</h3>
                        <p>{{ __('Bienvenido al panel de control de: ') }} <strong>{{ auth()->user()->ownedRestaurants()->first()->name }}</strong></p>
                        <!-- Aquí irán los futuros widgets (Ventas de hoy, Mesas activas, etc.) -->
                    </div>
                </div>
            @else
                <livewire:restaurant.create-restaurant-form />
            @endif
        </div>
    </div>
</x-app-layout>
