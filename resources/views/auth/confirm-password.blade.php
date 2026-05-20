<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Contraseña')" />
            <div class="relative mt-1">
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                Confirmar
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script>
    function togglePwd(id, btn) {
        const inp = document.getElementById(id);
        const hidden = inp.type === 'password';
        inp.type = hidden ? 'text' : 'password';
        btn.querySelector('span').textContent = hidden ? 'visibility_off' : 'visibility';
    }
    </script>
    @endpush
</x-guest-layout>
