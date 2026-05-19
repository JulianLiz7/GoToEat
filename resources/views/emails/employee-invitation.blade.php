<x-mail::message>
# ¡Hola, {{ $user->name }}!

Has sido registrado exitosamente como empleado en **{{ config('app.name', 'GoToEat') }}** con el cargo de **{{ $position }}**.

Tus credenciales de acceso son:

<x-mail::panel>
**Usuario:** `{{ $user->username }}`  
**Contraseña temporal:** `{{ $password }}`
</x-mail::panel>

Puedes iniciar sesión haciendo clic en el siguiente botón:

<x-mail::button :url="url('/login')">
Iniciar Sesión
</x-mail::button>

Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión.

Bienvenido al equipo **{{ config('app.name', 'GoToEat') }}**.
</x-mail::message>
