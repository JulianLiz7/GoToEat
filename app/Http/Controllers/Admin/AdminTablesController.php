<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Reservations\Models\Reservation;
use App\Domains\Finance\Models\Tip;
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

    // ── Index (vista unificada Mesas + Reservas) ──────────────────────
    public function index(Request $request)
    {
        $restaurant = $this->restaurant();
        $rid        = $restaurant->id;
        $zonaFiltro = $request->get('zona', '');

        // Mesas
        $query = $restaurant->tables()->orderBy('zone')->orderBy('number');
        if ($zonaFiltro) {
            $query->where('zone', $zonaFiltro);
        }
        $tables = $query->get();

        // Órdenes activas por mesa
        $activeOrders = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereIn('status', ['pending', 'preparing'])
            ->whereNotNull('table_id')
            ->get()
            ->keyBy('table_id');

        // Meseros
        $meseros = DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('employees.restaurant_id', $rid)
            ->where('employees.status', 'active')
            ->select('employees.id as employee_id', 'employees.user_id', 'employees.position', 'users.name')
            ->get();

        $zonas = $restaurant->tables()
            ->distinct()->whereNotNull('zone')->orderBy('zone')->pluck('zone');

        // ── Reservas (próximas y de hoy) ──────────────────────────────
        $statusFiltro = $request->get('rstatus', 'upcoming');

        $resQuery = Reservation::with(['user', 'waiter.user', 'table'])
            ->where('restaurant_id', $rid);

        if ($statusFiltro === 'upcoming') {
            $resQuery->whereIn('status', ['pending', 'confirmed'])
                     ->where('reservation_date', '>=', now()->toDateString())
                     ->orderBy('reservation_date')->orderBy('reservation_time');
        } elseif ($statusFiltro === 'today') {
            $resQuery->whereDate('reservation_date', now()->toDateString())
                     ->orderBy('reservation_time');
        } elseif ($statusFiltro === 'all') {
            $resQuery->orderByDesc('reservation_date')->orderByDesc('reservation_time');
        } else {
            $resQuery->where('status', $statusFiltro)
                     ->orderByDesc('reservation_date');
        }

        $reservations     = $resQuery->paginate(25)->withQueryString();

        // Reservas próximas agrupadas por tabla (para mostrar en el plano)
        $reservationsByTable = Reservation::with('user')
            ->where('restaurant_id', $rid)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', '>=', now()->toDateString())
            ->whereNotNull('table_id')
            ->orderBy('reservation_date')->orderBy('reservation_time')
            ->get()
            ->groupBy('table_id');

        // Reservas pendientes sin mesa asignada
        $unassigned = Reservation::with('user')
            ->where('restaurant_id', $rid)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', '>=', now()->toDateString())
            ->whereNull('table_id')
            ->orderBy('reservation_date')->orderBy('reservation_time')
            ->get();

        // KPIs
        $todayCount     = Reservation::where('restaurant_id', $rid)->whereDate('reservation_date', now())->count();
        $pendingCount   = Reservation::where('restaurant_id', $rid)->where('status', 'pending')->count();
        $confirmedCount = Reservation::where('restaurant_id', $rid)->where('status', 'confirmed')
                                     ->where('reservation_date', '>=', now()->toDateString())->count();

        $estimatedRevenue = Reservation::where('restaurant_id', $rid)
            ->where('status', 'confirmed')
            ->whereMonth('reservation_date', now()->month)
            ->get()
            ->sum(function ($r) {
                if (!$r->selected_items) return 0;
                return collect($r->selected_items)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? $i['quantity'] ?? 1));
            });

        // Meseros para asignar en reservas
        $waiters = DB::table('employees')
            ->where('restaurant_id', $rid)->where('status', 'active')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.id', 'users.name')
            ->get();

        return view('admin.tables', compact(
            'restaurant', 'tables', 'activeOrders', 'meseros', 'zonas', 'zonaFiltro',
            'reservations', 'reservationsByTable', 'unassigned', 'statusFiltro',
            'todayCount', 'pendingCount', 'confirmedCount', 'estimatedRevenue', 'waiters'
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

        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$data['number']} creada.");
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

    // ── Sentar cliente (sin reserva o con reserva ya confirmada) ──────
    public function sentar(Request $request, int $id)
    {
        $request->validate([
            'party_size'      => 'required|integer|min:1|max:50',
            'customer_name'   => 'nullable|string|max:100',
            'waiter_user_id'  => 'nullable|exists:users,id',
            'notes'           => 'nullable|string|max:255',
            'reservation_id'  => 'nullable|exists:reservations,id',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $table->update([
            'status'            => 'ocupada',
            'party_size'        => $request->party_size,
            'customer_name'     => $request->customer_name,
            'reservation_notes' => $request->notes,
            'is_reserved'       => false,
        ]);

        // Pre-cargar ítems de la reserva en la orden si viene de una reserva
        $orderItems = [];
        if ($request->reservation_id) {
            $res = Reservation::find($request->reservation_id);
            if ($res && $res->restaurant_id === $restaurant->id) {
                $res->update(['status' => 'confirmed', 'table_id' => $id]);
                if ($res->selected_items) {
                    $orderItems = $res->selected_items;
                }
            }
        }

        $restaurant->orders()->create([
            'table_id'  => $table->id,
            'waiter_id' => $request->waiter_user_id ?: null,
            'status'    => 'pending',
            'total'     => 0,
            'tips'      => 0,
            'items'     => $orderItems,
        ]);

        $this->clearCache();
        $nombre = $request->customer_name ? " — {$request->customer_name}" : '';
        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$table->number}{$nombre} registrada como ocupada.");
    }

    // ── Check In (reserva llega → mesa ocupada) ───────────────────────
    public function checkIn(Request $request, int $id)
    {
        $request->validate([
            'waiter_user_id' => 'nullable|exists:users,id',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $table->update([
            'status'      => 'ocupada',
            'is_reserved' => false,
        ]);

        // Cargar ítems de pre-orden si hay reserva
        $orderItems = [];
        if ($request->reservation_id) {
            $res = Reservation::find($request->reservation_id);
            if ($res && $res->restaurant_id === $restaurant->id) {
                $res->update(['status' => 'confirmed', 'table_id' => $id]);
                if ($res->selected_items) {
                    $orderItems = $res->selected_items;
                }
            }
        }

        $restaurant->orders()->create([
            'table_id'  => $table->id,
            'waiter_id' => $request->waiter_user_id ?: null,
            'status'    => 'pending',
            'total'     => 0,
            'tips'      => 0,
            'items'     => $orderItems,
        ]);

        $this->clearCache();
        return redirect()->route('admin.tables')->with('success', "✓ Check-in realizado para mesa {$table->number}.");
    }

    // ── Liberar mesa (cierra orden + completa reserva vinculada) ──────
    public function liberar(int $id)
    {
        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        // Cerrar órdenes activas
        $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->update(['status' => 'completed']);

        // Completar reserva vinculada a esta mesa si aún está activa
        Reservation::where('table_id', $table->id)
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->update(['status' => 'completed']);

        $table->update([
            'status'            => 'disponible',
            'customer_name'     => null,
            'party_size'        => null,
            'reservation_notes' => null,
            'is_reserved'       => false,
        ]);

        $this->clearCache();
        return redirect()->route('admin.tables')->with('success', "✓ Mesa {$table->number} liberada.");
    }

    // ── Cambiar estado de la orden activa ────────────────────────────
    public function updateOrderStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,preparing,ready,completed']);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->update(['status' => $request->status]);

        $this->clearCache();

        $labels = ['pending' => 'pendiente', 'preparing' => 'en preparación', 'ready' => 'listo para servir', 'completed' => 'completado'];
        $label  = $labels[$request->status] ?? $request->status;
        return redirect()->route('admin.tables')
            ->with('success', "✓ Pedido de mesa {$table->number} marcado como {$label}.");
    }

    // ── Cambiar estado / registrar reserva manual ────────────────────
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
            $data['customer_name']     = null;
            $data['customer_phone']    = null;
            $data['party_size']        = null;
            $data['reservation_time']  = null;
            $data['reservation_notes'] = null;
            $data['is_reserved']       = false;
        }

        $table->update($data);
        $this->clearCache();

        $msgs = ['reservada' => '✓ Mesa marcada como reservada.', 'disponible' => '✓ Mesa disponible.'];
        return redirect()->route('admin.tables')->with('success', $msgs[$data['status']] ?? '✓ Estado actualizado.');
    }

    // ── Asignar mesa a una reserva (y confirmarla) ────────────────────
    public function assignReservation(Request $request, int $tableId)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($tableId);

        $reservation = Reservation::where('id', $request->reservation_id)
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        // Actualizar reserva
        $reservation->update([
            'table_id' => $table->id,
            'status'   => 'confirmed',
        ]);

        // Marcar la mesa como reservada con datos del cliente
        $table->update([
            'status'            => 'reservada',
            'party_size'        => $reservation->party_size,
            'customer_name'     => $reservation->user->name ?? null,
            'reservation_notes' => $reservation->notes,
            'is_reserved'       => true,
        ]);

        $this->clearCache();
        return redirect()->route('admin.tables')
            ->with('success', "✓ Reserva de {$reservation->user->name} asignada a Mesa {$table->number}.");
    }

    // ── Recibo / Factura de mesa ──────────────────────────────────────
    public function recibo(int $id)
    {
        // Admin or mesero/employee can access this
        $user = auth()->user();
        if ($user->hasAnyRole(['mesero', 'chef', 'cajero'])) {
            // Employee: find the restaurant they belong to
            $restaurant = DB::table('employees')
                ->join('restaurants', 'employees.restaurant_id', '=', 'restaurants.id')
                ->where('employees.user_id', $user->id)
                ->select('restaurants.*')
                ->first();
            if (!$restaurant) abort(403);
            // Convert to Eloquent model
            $restaurant = \App\Domains\Restaurant\Models\Restaurant::find($restaurant->id);
        } else {
            $restaurant = $this->restaurant();
        }
        $table      = $this->table($id);

        $order = $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready', 'completed'])
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('admin.tables')
                ->with('error', "No hay orden activa para Mesa {$table->number}.");
        }

        // Enriquecer ítems con datos del menú (categoría + precio oficial)
        $rawItems = $order->items ?? [];
        $menuIds  = array_filter(array_column($rawItems, 'id'));
        $menuData = DB::table('menu_items')
            ->whereIn('id', $menuIds)
            ->get(['id', 'name', 'category', 'price', 'image'])
            ->keyBy('id');

        $items = collect($rawItems)->map(function ($item) use ($menuData) {
            $id      = $item['id'] ?? null;
            $menu    = $id ? $menuData->get($id) : null;
            return [
                'id'       => $id,
                'name'     => $menu->name    ?? $item['name']  ?? 'Ítem',
                'category' => $menu->category ?? $item['category'] ?? null,
                'price'    => $menu->price    ?? $item['price'] ?? 0,
                'qty'      => $item['qty']    ?? $item['quantity'] ?? 1,
                'image'    => $menu->image    ?? null,
            ];
        })->toArray();

        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);

        // Mesero asignado
        $waiter  = null;
        $meseros = collect();
        if ($order->waiter_id) {
            $waiter = DB::table('employees')
                ->join('users', 'employees.user_id', '=', 'users.id')
                ->where('users.id', $order->waiter_id)
                ->where('employees.restaurant_id', $restaurant->id)
                ->select('employees.id as employee_id', 'employees.user_id', 'employees.position', 'users.name')
                ->first();
        }
        $meseros = DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('employees.restaurant_id', $restaurant->id)
            ->where('employees.status', 'active')
            ->select('employees.id as employee_id', 'employees.user_id', 'employees.position', 'users.name')
            ->get();

        // Propina previa si ya se registró
        $tipRecord = DB::table('tips')
            ->where('order_id', $order->id)
            ->where('restaurant_id', $restaurant->id)
            ->first();

        // Menú del restaurante para selección de platos
        $menuByCategory = DB::table('menu_items')
            ->where('restaurant_id', $restaurant->id)
            ->where('available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('admin.tables-recibo', compact(
            'restaurant', 'table', 'order', 'items', 'subtotal',
            'waiter', 'meseros', 'tipRecord', 'menuByCategory'
        ));
    }

    // ── Actualizar ítems del recibo desde selección de menú ──────────
    public function updateReciboItems(Request $request, int $id)
    {
        $request->validate([
            'items'   => 'nullable|array',
            'items.*.id'    => 'required|integer',
            'items.*.qty'   => 'required|integer|min:1',
        ]);

        $restaurant = auth()->user()->hasAnyRole(['mesero','chef','cajero'])
            ? \App\Domains\Restaurant\Models\Restaurant::find(
                DB::table('employees')->where('user_id', auth()->id())->value('restaurant_id')
            )
            : $this->restaurant();

        $table = $this->table($id);

        $order = $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->first();

        if (!$order) {
            return back()->with('error', 'No hay orden activa.');
        }

        // Enriquecer ítems con precios reales del menú
        $sentItems  = $request->input('items', []);
        $menuIds    = array_column($sentItems, 'id');
        $menuItems  = DB::table('menu_items')->whereIn('id', $menuIds)->get()->keyBy('id');

        $enriched = [];
        $subtotal = 0;
        foreach ($sentItems as $item) {
            $menu  = $menuItems->get($item['id'] ?? null);
            if (!$menu) continue;
            $qty   = (int) ($item['qty'] ?? 1);
            $price = (float) $menu->price;
            $enriched[] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'category' => $menu->category,
                'price'    => $price,
                'qty'      => $qty,
            ];
            $subtotal += $price * $qty;
        }

        $order->update([
            'items' => $enriched,
            'total' => $subtotal,
        ]);

        $this->clearCache();

        return redirect()->route('admin.tables.recibo', $id)
            ->with('success', '✓ Pedido actualizado con ' . count($enriched) . ' ítem(s). Total: $' . number_format($subtotal, 0, ',', '.'));
    }

    // ── Cerrar cuenta con propina ─────────────────────────────────────
    public function cerrarCuenta(Request $request, int $id)
    {
        $request->validate([
            'tip_amount'     => 'nullable|numeric|min:0',
            'tip_employee_id'=> 'nullable|exists:employees,id',
            'payment_method' => 'required|in:efectivo,tarjeta,transferencia',
        ]);

        $restaurant = $this->restaurant();
        $table      = $this->table($id);

        $order = $restaurant->orders()
            ->where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('admin.tables')->with('error', 'No hay orden activa.');
        }

        $tipAmount = (float) ($request->tip_amount ?? 0);
        $rawItems  = $order->items ?? [];
        $menuIds   = array_filter(array_column($rawItems, 'id'));
        $menuData  = DB::table('menu_items')->whereIn('id', $menuIds)->get(['id','price'])->keyBy('id');
        $subtotal  = collect($rawItems)->sum(function ($i) use ($menuData) {
            $price = ($menuData->get($i['id'] ?? null)->price ?? $i['price'] ?? 0);
            return $price * ($i['qty'] ?? $i['quantity'] ?? 1);
        });
        $total     = $subtotal + $tipAmount;

        // Registrar propina en la tabla tips (→ nómina del mesero)
        if ($tipAmount > 0 && $request->tip_employee_id) {
            Tip::create([
                'restaurant_id'  => $restaurant->id,
                'order_id'       => $order->id,
                'employee_id'    => $request->tip_employee_id,
                'amount'         => $tipAmount,
                'date'           => now()->toDateString(),
                'payment_method' => $request->payment_method,
            ]);
        }

        // Cerrar la orden
        $order->update([
            'status' => 'completed',
            'total'  => $subtotal,
            'tips'   => $tipAmount,
        ]);

        // Liberar la mesa
        $table->update([
            'status'            => 'disponible',
            'customer_name'     => null,
            'party_size'        => null,
            'reservation_notes' => null,
            'is_reserved'       => false,
        ]);

        $this->clearCache();

        $msg = "✓ Mesa {$table->number} cerrada. Total: $" . number_format($total, 0, ',', '.');
        if ($tipAmount > 0) {
            $msg .= " (Propina: $" . number_format($tipAmount, 0, ',', '.') . " registrada en nómina)";
        }

        return redirect()->route('admin.tables')->with('success', $msg);
    }
}
