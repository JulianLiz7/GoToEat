<?php

namespace App\Domains\Auth\Controllers\Web;

use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'restaurante') {
            $user->assignRole('admin');
        } else {
            $user->assignRole('cliente');
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->role === 'comensal') {
            return redirect()->route('welcome.comensal');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
