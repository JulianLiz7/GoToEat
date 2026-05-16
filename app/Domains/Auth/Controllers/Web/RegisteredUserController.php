<?php

namespace App\Domains\Auth\Controllers\Web;

use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:comensal,restaurante'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $roleName = $request->role === 'restaurante' ? 'admin' : 'cliente';

        // Garantiza que el rol exista antes de asignarlo.
        // Si el seeder no se corrió, lo crea en el momento.
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($roleName);

        event(new Registered($user));

        Auth::login($user);

        return $request->role === 'comensal'
            ? redirect()->route('welcome.comensal')
            : redirect(route('dashboard', absolute: false));
    }
}
