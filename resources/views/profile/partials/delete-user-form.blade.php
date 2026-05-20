<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Eliminar Cuenta</h2>
        <p class="mt-1 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos sus datos serán borrados permanentemente.
            Antes de continuar, descarga cualquier información que desees conservar.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Eliminar Cuenta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">¿Estás seguro de que deseas eliminar tu cuenta?</h2>

            <p class="mt-1 text-sm text-gray-600">
                Esta acción es irreversible. Todos tus datos serán eliminados permanentemente.
                Ingresa tu contraseña para confirmar.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Contraseña" class="sr-only" />
                <div class="relative mt-1 w-3/4">
                    <input id="password" name="password" type="password" placeholder="Contraseña"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                    <button type="button" tabindex="-1" onclick="togglePwd('password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-danger-button class="ms-3">Eliminar Cuenta</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

@push('scripts')
<script>
if (typeof togglePwd === 'undefined') {
    function togglePwd(id, btn) {
        const inp = document.getElementById(id);
        const hidden = inp.type === 'password';
        inp.type = hidden ? 'text' : 'password';
        btn.querySelector('span').textContent = hidden ? 'visibility_off' : 'visibility';
    }
}
</script>
@endpush
