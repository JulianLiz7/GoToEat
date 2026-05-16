<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminTablesController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        abort_unless($r, 403);
        return $r;
    }

    private function table(int $id)
    {
        $t = $this->restaurant()->tables()->find($id);
        abort_unless($t, 404, 'Mesa no encontrada.');
        return $t;
    }

    private function clearCache(): void
    {
        Cache::forget('dashboard_stats_' . $this->restaurant()->id);
    }

    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $restaurant = $this->restaurant();
        $zonaFiltro = $request->get('zona', '');

        $query = $restaurant->tables()->orderBy('zone')->orderBy('number');
        if ($zonaFiltro) {
            $query->where('zone', $zonaFiltro);
        }
        $tables = $query->get();

        // Cargar orden activa por mesa (pending o preparing)
        $activeOrders = DB::table('orders')
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->whereNotNull('table_id')
            ->get()
            ->keyBy('table_id');

        // Cargar meseros del restaurante para el select
        $meseros = DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('employees.restaurant_id', $restaurant->id)
            ->where('employees.status', 'active')
            ->whereIn('employees.position', ['Mesero', 'mesero', 'Cajero', 'cajero', 'Manager', 'manager'])
            ->select('employees.id as employee_id', 'employees.user_id', 'employees.position', 'users.name')
            ->get();

        // Si no hay meseros por posición, traer todos los activos
        if ($meseros->isEmpty()) {
            $meseros = DB::table('employees')
                ->join('users', 'employees.user_id', '=', 'users.id')
                ->where('employees.restaurant_id', $restaurant->id)
                ->where('employees.status', 'active')
                ->select('employees.id as employee_id', 'employees.user_id', 'employees.position', 'users.name')
                ->get();
        }

        $zonas = $restaurant->tables()
            ->distinct()->whereNotNull('zone')->orderBy('zone')->pluck('zone');

        return view('admin.tables', compact(
            'restaurant', 'tables', 'activeOrders', 'meseros', 'zonas', 'zonaFiltro'
        ));
    }

    // ── Create / Store ────────────────────────────────────────────────
    public function create()
    {
        $restaurant    = $this->restaurant();
        $existingZones = $restaurant->tables()->distinct()->whereNotNull('zone')->orderBy('zone')->pluck('zone');
        return view('admin.tables-create', compact('restaurant', 'existingZones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number'   => 'required|string|max:20',
            'capacity' => 'required|integer|min:1|max:50',
            'zone'     => 'nullable|string|max:100',
            'status'   => 'in:disponible,reservada,mantenimiento',
        ]);

        $this->restaurant()->tables()->create($data + ['status' => 'disponible']);
        $this->clearCache();

        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$data['number']} creada exitosamente.");
    }

    // ── Update ────────────────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'number'   => 'required|string|max:20',
            'capacity' => 'required|integer|min:1|max:50',
            'zone'     => 'nullable|string|max:100',
        ]);

        $this->table($id)->update($data);
        $this->clearCache();

        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$data['number']} actualizada.");
    }

    // ── Destroy ───────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $table = $this->table($id);
        $num   = $table->number;
        $table->delete();
        $this->clearCache();

        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$num} eliminada.");
    }

    // ── Sentar cliente ────────────────────────────────────────────────
    // Marca la mesa como ocupada y crea una orden pendiente
    public function sentar(Request $request, int $id)
    {
        $request->validate([
            'party_size'    => 'required|integer|min:1|max:50',
            'customer_name' => 'nullable|string|max:100',
            'waiter_user_id'=> 'nullable|exists:users,id',
            'notes'         => 'nullable|string|max:255',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $table->update([
            'status'             => 'ocupada',
            'party_size'         => $request->party_size,
            'customer_name'      => $request->customer_name,
            'reservation_notes'  => $request->notes,
            'is_reserved'        => false,
        ]);

        // Crear orden en blanco para esta mesa
        $restaurant->orders()->create([
            'table_id'  => $table->id,
            'waiter_id' => $request->waiter_user_id ?: null,
            'status'    => 'pending',
            'total'     => 0,
            'tips'      => 0,
            'items'     => [],
        ]);

        $this->clearCache();
        $nombre = $request->customer_name ? " — {$request->customer_name}" : '';
        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$table->number}{$nombre} registrada como ocupada.");
    }

    // ── Check In (reserva → ocupada) ──────────────────────────────────
    public function checkIn(Request $request, int $id)
    {
        $request->validate([
            'waiter_user_id' => 'nullable|exists:users,id',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $table->update([
            'status'      => 'ocupada',
            'is_reserved' => false,
        ]);

        $restaurant->orders()->create([
            'table_id'  => $table->id,
            'waiter_id' => $request->waiter_user_id ?: null,
            'status'    => 'pending',
            'total'     => 0,
            'tips'      => 0,
            'items'     => [],
        ]);

        $this->clearCache();
        return redirect()->route('admin.tables')->with('success', "✓ Check-in realizado para mesa {$table->number}.");
    }

    // ── Liberar mesa ──────────────────────────────────────────────────
    // Cierra la orden activa y deja la mesa disponible
    public function liberar(int $id)
    {
        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        // Cerrar órdenes activas de esta mesa
        $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->update(['status' => 'completed']);

        $table->update([
            'status'            => 'disponible',
            'customer_name'     => null,
            'party_size'        => null,
            'reservation_notes' => null,
            'is_reserved'       => false,
        ]);

        $this->clearCache();
        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$table->number} liberada y disponible.");
    }

    // ── Cambiar estado / registrar reserva ───────────────────────────
    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status'            => 'required|in:disponible,ocupada,reservada,mantenimiento',
            'customer_name'     => 'nullable|string|max:100',
            'customer_phone'    => 'nullable|string|max:30',
            'party_size'        => 'nullable|integer|min:1',
            'reservation_time'  => 'nullable|date',
            'reservation_notes' => 'nullable|string|max:255',
        ]);

        $table = $this->table($id);

        if ($data['status'] === 'reservada') {
            $data['is_reserved'] = true;
        } elseif ($data['status'] === 'disponible') {
            // Limpiar datos de reserva/ocupación al liberar manualmente
            $data['customer_name']     = null;
            $data['customer_phone']    = null;
            $data['party_size']        = null;
            $data['reservation_time']  = null;
            $data['reservation_notes'] = null;
            $data['is_reserved']       = false;
        }

        $table->update($data);
        $this->clearCache();

        $msgs = ['reservada' => '✓ Reserva registrada.', 'disponible' => '✓ Mesa marcada como disponible.'];
        return redirect()->route('admin.tables')->with('success', $msgs[$data['status']] ?? '✓ Estado actualizado.');
    }
}
