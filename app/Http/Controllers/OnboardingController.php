<?php

namespace App\Http\Controllers;

use App\Domains\Restaurant\Actions\CreateRestaurantAction;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    // Redirige al dashboard si el admin ya tiene restaurante
    private function guardCompleted()
    {
        if (auth()->user()->ownedRestaurants()->exists()) {
            return redirect()->route('dashboard');
        }
        return null;
    }

    public function step1()
    {
        if ($redirect = $this->guardCompleted()) return $redirect;
        return view('admin.onboarding.step1', ['data' => session('onboarding', [])]);
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string'],
            'cuisine_type' => ['required', 'string'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ]);

        session(['onboarding' => array_merge(
            session('onboarding', []),
            $request->only(['name', 'category', 'cuisine_type', 'description'])
        )]);

        return redirect()->route('onboarding.step2');
    }

    public function step2()
    {
        if ($redirect = $this->guardCompleted()) return $redirect;
        if (empty(session('onboarding.name'))) return redirect()->route('onboarding.step1');
        return view('admin.onboarding.step2', ['data' => session('onboarding', [])]);
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'phone'   => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'email'   => ['nullable', 'email', 'max:255'],
        ]);

        session(['onboarding' => array_merge(
            session('onboarding', []),
            $request->only(['phone', 'address', 'email'])
        )]);

        return redirect()->route('onboarding.step3');
    }

    public function step3()
    {
        if ($redirect = $this->guardCompleted()) return $redirect;
        if (empty(session('onboarding.name'))) return redirect()->route('onboarding.step1');
        return view('admin.onboarding.step3', ['data' => session('onboarding', [])]);
    }

    public function storeStep3(Request $request)
    {
        $request->validate([
            'invites'        => ['nullable', 'array', 'max:5'],
            'invites.*.email'=> ['nullable', 'email'],
            'invites.*.role' => ['nullable', 'string'],
        ]);

        $data   = session('onboarding', []);
        $action = new CreateRestaurantAction();
        $action->execute(auth()->user(), $data);

        // Guardar invitaciones en sesión para mostrarlas en la pantalla de bienvenida
        $invites = collect($request->input('invites', []))
            ->filter(fn($i) => !empty($i['email']))
            ->values();

        session()->forget('onboarding');
        session(['onboarding_invites' => $invites]);

        return redirect()->route('onboarding.welcome');
    }

    public function welcome()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();
        if (!$restaurant) return redirect()->route('onboarding.step1');

        $invites = session('onboarding_invites', collect());
        session()->forget('onboarding_invites');

        return view('admin.onboarding.welcome', compact('restaurant', 'invites'));
    }
}
