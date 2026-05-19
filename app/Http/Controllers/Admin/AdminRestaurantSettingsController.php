<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminRestaurantSettingsController extends Controller
{
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        if (!$r) return redirect()->route('onboarding.step1');
        return $r;
    }

    public function index()
    {
        $restaurant = $this->restaurant();
        return view('admin.settings', compact('restaurant'));
    }

    public function update(Request $request)
    {
        $restaurant = $this->restaurant();

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:500',
            'category'       => 'nullable|string|max:80',
            'cuisine_type'   => 'nullable|string|max:80',
            'address'        => 'nullable|string|max:200',
            'phone'          => 'nullable|string|max:20',
            'whatsapp'       => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:100',
            'website'        => 'nullable|url|max:200',
            'opening_hours'  => 'nullable|string|max:100',
            'primary_color'  => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color'=> 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'cover'          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'name'           => $validated['name'],
            'slug'           => Str::slug($validated['name']),
            'description'    => $validated['description'] ?? $restaurant->description,
            'category'       => $validated['category'] ?? $restaurant->category,
            'cuisine_type'   => $validated['cuisine_type'] ?? $restaurant->cuisine_type,
            'address'        => $validated['address'] ?? $restaurant->address,
            'phone'          => $validated['phone'] ?? $restaurant->phone,
            'whatsapp'       => $validated['whatsapp'] ?? $restaurant->whatsapp,
            'email'          => $validated['email'] ?? $restaurant->email,
            'website'        => $validated['website'] ?? null,
            'opening_hours'  => $validated['opening_hours'] ?? null,
            'primary_color'  => $validated['primary_color'] ?? $restaurant->primary_color ?? '#f97316',
            'secondary_color'=> $validated['secondary_color'] ?? $restaurant->secondary_color ?? '#006c49',
        ];

        // Logo upload
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($restaurant->logo_path) {
                Storage::disk('public')->delete($restaurant->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store("logos/{$restaurant->id}", 'public');
        }

        // Portada upload
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            if ($restaurant->cover_path) {
                Storage::disk('public')->delete($restaurant->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store("covers/{$restaurant->id}", 'public');
        }

        $restaurant->update($data);

        return back()->with('success', 'Configuración guardada correctamente.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150|unique:users,email,' . $user->id,
            'current_password'      => 'nullable|string',
            'password'              => 'nullable|string|min:8|confirmed',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['current_password'])) {
            if (!\Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])->with('tab', 'perfil');
            }
            if (!empty($validated['password'])) {
                $user->password = \Hash::make($validated['password']);
            }
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.')->with('tab', 'perfil');
    }

    public function deleteLogo()
    {
        $restaurant = $this->restaurant();
        if ($restaurant->logo_path) {
            Storage::disk('public')->delete($restaurant->logo_path);
            $restaurant->update(['logo_path' => null]);
        }
        return back()->with('success', 'Logo eliminado.');
    }

    public function deleteCover()
    {
        $restaurant = $this->restaurant();
        if ($restaurant->cover_path) {
            Storage::disk('public')->delete($restaurant->cover_path);
            $restaurant->update(['cover_path' => null]);
        }
        return back()->with('success', 'Foto de portada eliminada.');
    }
}
