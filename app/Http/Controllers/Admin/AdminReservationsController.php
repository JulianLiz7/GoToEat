<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Reservations\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReservationsController extends Controller
{
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        if (!$r) return redirect()->route('onboarding.step1');
        return $r;
    }

    public function index(Request $request)
    {
        $restaurant = $this->restaurant();
        $rid = $restaurant->id;

        $status = $request->get('status', 'upcoming');
        $date   = $request->get('date', now()->toDateString());

        $query = Reservation::with(['user', 'waiter.user', 'table'])
            ->where('restaurant_id', $rid);

        if ($status === 'upcoming') {
            $query->whereIn('status', ['pending', 'confirmed'])
                  ->where('reservation_date', '>=', now()->toDateString())
                  ->orderBy('reservation_date')
                  ->orderBy('reservation_time');
        } elseif ($status === 'today') {
            $query->whereDate('reservation_date', now()->toDateString())
                  ->orderBy('reservation_time');
        } elseif ($status === 'all') {
            $query->orderByDesc('reservation_date')->orderByDesc('reservation_time');
        } else {
            $query->where('status', $status)
                  ->orderByDesc('reservation_date');
        }

        $reservations = $query->paginate(20)->withQueryString();

        // KPIs del día
        $todayReservations  = Reservation::where('restaurant_id', $rid)
            ->whereDate('reservation_date', now()->toDateString())->count();

        $pendingCount = Reservation::where('restaurant_id', $rid)
            ->where('status', 'pending')->count();

        $confirmedCount = Reservation::where('restaurant_id', $rid)
            ->where('status', 'confirmed')
            ->where('reservation_date', '>=', now()->toDateString())->count();

        // Ingresos estimados (suma de selected_items de reservas confirmadas este mes)
        $confirmedThisMonth = Reservation::where('restaurant_id', $rid)
            ->where('status', 'confirmed')
            ->whereMonth('reservation_date', now()->month)
            ->get();

        $estimatedRevenue = $confirmedThisMonth->sum(function ($r) {
            if (!$r->selected_items) return 0;
            return collect($r->selected_items)->sum(fn($item) => ($item['price'] ?? 0) * ($item['qty'] ?? $item['quantity'] ?? 1));
        });

        // Empleados para asignar mesero
        $waiters = DB::table('employees')
            ->where('restaurant_id', $rid)
            ->where('status', 'active')
            ->whereIn('position', ['mesero', 'waiter', 'Mesero', 'Waiter'])
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.id', 'users.name', 'employees.position')
            ->get();

        return view('admin.reservations', compact(
            'restaurant', 'reservations', 'status', 'date',
            'todayReservations', 'pendingCount', 'confirmedCount',
            'estimatedRevenue', 'waiters'
        ));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $restaurant = $this->restaurant();
        abort_if($reservation->restaurant_id !== $restaurant->id, 403);

        $validated = $request->validate([
            'status'    => 'required|in:pending,confirmed,completed,cancelled',
            'waiter_id' => 'nullable|exists:employees,id',
            'table_id'  => 'nullable|exists:tables,id',
        ]);

        $oldTableId = $reservation->table_id;
        $reservation->update(array_filter($validated, fn($v) => $v !== null));

        // Sync table status when reservation status changes
        $newTableId = $validated['table_id'] ?? $oldTableId;

        if ($newTableId) {
            $table = $restaurant->tables()->find($newTableId);
            if ($table) {
                if ($validated['status'] === 'confirmed') {
                    $table->update([
                        'status'            => 'reservada',
                        'party_size'        => $reservation->party_size,
                        'customer_name'     => $reservation->user->name ?? null,
                        'reservation_notes' => $reservation->notes,
                        'is_reserved'       => true,
                    ]);
                } elseif (in_array($validated['status'], ['cancelled', 'completed'])) {
                    // Only free the table if no other active reservation uses it
                    $otherActive = Reservation::where('table_id', $newTableId)
                        ->where('id', '!=', $reservation->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->exists();
                    if (!$otherActive && $table->status === 'reservada') {
                        $table->update([
                            'status'            => 'disponible',
                            'customer_name'     => null,
                            'party_size'        => null,
                            'reservation_notes' => null,
                            'is_reserved'       => false,
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reservation $reservation)
    {
        $restaurant = $this->restaurant();
        abort_if($reservation->restaurant_id !== $restaurant->id, 403);

        $reservation->delete();
        return back()->with('success', 'Reserva eliminada.');
    }
}
