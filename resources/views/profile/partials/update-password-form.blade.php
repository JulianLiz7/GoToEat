<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Actualizar Contraseña</h2>
        <p class="mt-1 text-sm text-gray-600">Usa una contraseña larga y aleatoria para mantener tu cuenta segura.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Contraseña actual" />
            <div class="relative mt-1">
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('update_password_current_password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Nueva contraseña" />
            <div class="relative mt-1">
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('update_password_password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmar contraseña" />
            <div class="relative mt-1">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pr-10">
                <button type="button" tabindex="-1" onclick="togglePwd('update_password_password_confirmation', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Guardar cambios</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">Contraseña actualizada.</p>
            @endif
        </div>
    </form>
</section>

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
