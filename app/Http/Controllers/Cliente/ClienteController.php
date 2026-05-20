<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Domains\Reservations\Models\Reservation;
use App\Domains\Reservations\Models\RestaurantReview;
use App\Domains\Restaurant\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    // ── Dashboard / Explorar ─────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $search     = $request->get('search', '');
        $cuisine    = $request->get('cuisine', '');
        $priceRange = $request->get('price', '');
        $sortBy     = $request->get('sort', 'latest'); // latest|rating|price_asc|price_desc

        $restaurants = Restaurant::where('status', 'active')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('cuisine_type', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"))
            ->when($cuisine, fn ($q) => $q->where('cuisine_type', $cuisine))
            ->when($priceRange, fn ($q) => $q->where('price_range', $priceRange))
            ->when($sortBy === 'rating',     fn ($q) => $q->orderByDesc('avg_rating'))
            ->when($sortBy === 'price_asc',  fn ($q) => $q->orderByRaw("FIELD(price_range,'$','\\$\\$','\\$\\$\\$','\\$\\$\\$\\$')"))
            ->when($sortBy === 'price_desc', fn ($q) => $q->orderByRaw("FIELD(price_range,'\\$\\$\\$\\$','\\$\\$\\$','\\$\\$','$')"))
            ->when(!in_array($sortBy, ['rating','price_asc','price_desc']), fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        $cuisines = Restaurant::select('cuisine_type')
            ->where('status', 'active')
            ->distinct()
            ->whereNotNull('cuisine_type')
            ->orderBy('cuisine_type')
            ->pluck('cuisine_type');

        $featuredRestaurants = Restaurant::where('status', 'active')
            ->whereNotNull('cover_path')
            ->latest()
            ->take(3)
            ->get();

        return view('cliente.dashboard', compact(
            'restaurants', 'cuisines', 'search', 'cuisine', 'priceRange', 'sortBy', 'featuredRestaurants'
        ));
    }

    // ── Mis Reservas ─────────────────────────────────────────────────
    public function reservas()
    {
        $user = auth()->user();

        $upcoming = Reservation::with(['restaurant', 'waiter.user'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', '>=', now()->toDateString())
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->get();

        $past = Reservation::with(['restaurant', 'waiter.user'])
            ->where('user_id', $user->id)
            ->where(fn ($q) => $q->whereIn('status', ['completed', 'cancelled'])
                ->orWhere('reservation_date', '<', now()->toDateString()))
            ->orderByDesc('reservation_date')
            ->take(10)
            ->get();

        $stats = [
            'total'     => Reservation::where('user_id', $user->id)->count(),
            'month'     => Reservation::where('user_id', $user->id)
                ->whereMonth('reservation_date', now()->month)
                ->whereYear('reservation_date', now()->year)
                ->count(),
            'confirmed' => Reservation::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count(),
        ];

        $restaurants = Restaurant::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'cuisine_type', 'logo_path', 'primary_color']);

        return view('cliente.reservas', compact('upcoming', 'past', 'stats', 'restaurants'));
    }

    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id'    => 'required|exists:restaurants,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'party_size'       => 'required|integer|min:1|max:20',
            'notes'            => 'nullable|string|max:500',
            'selected_items'   => 'nullable|array',
        ]);

        Reservation::create([
            'user_id'          => auth()->id(),
            'restaurant_id'    => $validated['restaurant_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'party_size'       => $validated['party_size'],
            'notes'            => $validated['notes'] ?? null,
            'selected_items'   => $validated['selected_items'] ?? null,
            'status'           => 'pending',
        ]);

        return back()->with('success', '¡Reserva solicitada! El restaurante confirmará pronto.');
    }

    public function cancelReservation(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);
        abort_if(in_array($reservation->status, ['completed', 'cancelled']), 422);

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada correctamente.');
    }

    // ── Perfil ───────────────────────────────────────────────────────
    public function perfil()
    {
        $user = auth()->user();
        $reservationsCount = Reservation::where('user_id', $user->id)->count();
        return view('cliente.perfil', compact('user', 'reservationsCount'));
    }

    public function updatePerfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                 => 'required|string|max:100',
            'email'                => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone'                => 'nullable|string|max:20',
            'date_of_birth'        => 'nullable|date|before:today',
            'dietary_preferences'  => 'nullable|array',
            'avatar'               => 'nullable|file|image|max:5120',
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update(collect($validated)->except('avatar')->toArray() + [
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success_password', 'Contraseña actualizada correctamente.');
    }

    // ── Reseñas ──────────────────────────────────────────────────────
    public function storeReview(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:120',
            'body'   => 'nullable|string|max:1000',
        ]);

        RestaurantReview::updateOrCreate(
            ['user_id' => auth()->id(), 'restaurant_id' => $restaurant->id],
            $validated
        );

        // Recalcular promedio
        $avg   = RestaurantReview::where('restaurant_id', $restaurant->id)->avg('rating');
        $count = RestaurantReview::where('restaurant_id', $restaurant->id)->count();
        $restaurant->update(['avg_rating' => round($avg, 1), 'reviews_count' => $count]);

        return back()->with('success', '¡Gracias por tu reseña!');
    }

    public function reviews(Restaurant $restaurant)
    {
        $reviews = RestaurantReview::with('user')
            ->where('restaurant_id', $restaurant->id)
            ->latest()
            ->paginate(10);

        $userReview = auth()->check()
            ? RestaurantReview::where('user_id', auth()->id())
                ->where('restaurant_id', $restaurant->id)
                ->first()
            : null;

        return response()->json([
            'restaurant'  => $restaurant->only(['id','name','avg_rating','reviews_count','primary_color']),
            'reviews'     => $reviews->items(),
            'userReview'  => $userReview,
            'total'       => $reviews->total(),
        ]);
    }

    // ── Menú de un restaurante (modal) ───────────────────────────────
    public function menuRestaurant(Restaurant $restaurant)
    {
        $menu = $restaurant->menuItems()
            ->where('available', true)
            ->orderBy('category')
            ->get(['id', 'name', 'description', 'price', 'category', 'image_path']);

        return response()->json([
            'restaurant' => $restaurant->only(['id', 'name', 'primary_color', 'logo_path']),
            'menu'       => $menu,
        ]);
    }
}
