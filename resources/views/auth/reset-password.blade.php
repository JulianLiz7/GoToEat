<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="'Correo electrónico'" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email"
                          :value="old('email', $request->email)"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Nueva contraseña'" />
            <div class="relative mt-1">
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Confirmar contraseña'" />
            <div class="relative mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Restablecer contraseña
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
