<?php

use Livewire\Volt\Component;
use App\Domains\Restaurant\Actions\CreateRestaurantAction;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public string $name = '';
    public string $description = '';
    public string $category = '';
    public string $phone = '';
    public string $address = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(CreateRestaurantAction $action)
    {
        $this->validate();

        $restaurant = $action->execute(Auth::user(), [
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->dispatch('restaurant-created', $restaurant->id);
        
        session()->flash('status', 'Restaurante creado exitosamente.');
        
        return redirect()->route('dashboard');
    }
};
?>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Configura tu Restaurante') }}</h3>
        
        <p class="text-sm text-gray-600 mb-6">
            {{ __('Parece que aún no tienes un restaurante configurado. Ingresa los datos básicos para comenzar a usar la plataforma.') }}
        </p>

        <form wire:submit="save" class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Nombre del Restaurante')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="category" :value="__('Categoría (Ej: Comida Italiana, Comida Rápida)')" />
                <x-text-input wire:model="category" id="category" class="block mt-1 w-full" type="text" />
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="tel" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="address" :value="__('Dirección')" />
                <x-text-input wire:model="address" id="address" class="block mt-1 w-full" type="text" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Descripción Breve')" />
                <textarea wire:model="description" id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>
                    <span wire:loading.remove wire:target="save">{{ __('Crear Restaurante') }}</span>
                    <span wire:loading wire:target="save">{{ __('Guardando...') }}</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>