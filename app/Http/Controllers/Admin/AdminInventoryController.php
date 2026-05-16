<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminInventoryController extends Controller
{
    // ── Helpers ──────────────────────────────────────────────────────
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        abort_unless($r, 403, 'Sin restaurante asociado.');
        return $r;
    }

    private function item(int $id)
    {
        $item = $this->restaurant()->inventoryItems()->find($id);
        abort_unless($item, 404, 'Ítem no encontrado.');
        return $item;
    }

    private function clearCache(): void
    {
        Cache::forget('dashboard_stats_' . $this->restaurant()->id);
    }

    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $restaurant = $this->restaurant();

        $query = $restaurant->inventoryItems()->latest();

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('category', $request->categoria);
        }

        // Filtro por búsqueda de nombre
        if ($request->filled('buscar')) {
            $query->where('name', 'like', '%' . $request->buscar . '%');
        }

        // Filtro por estado de stock
        if ($request->get('solo_bajo_stock')) {
            $query->whereColumn('quantity', '<=', 'min_stock')->where('status', 'active');
        }

        $items         = $query->paginate(20)->withQueryString();
        $allItems      = $restaurant->inventoryItems()->get(['quantity', 'min_stock', 'cost_price', 'status']);
        $lowStockItems = $allItems->filter(fn ($i) => $i->isLowStock());
        $lowStockCount = $lowStockItems->count();
        $totalValue    = $allItems->sum(fn ($i) => (float) ($i->quantity ?? 0) * (float) ($i->cost_price ?? 0));
        $categorias    = $restaurant->inventoryItems()->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');

        return view('admin.inventory', compact(
            'restaurant', 'items', 'lowStockItems', 'lowStockCount', 'totalValue', 'categorias'
        ));
    }

    // ── Store ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'category'   => 'nullable|string|max:100',
            'quantity'   => 'required|numeric|min:0',
            'unit'       => 'nullable|string|max:50',
            'min_stock'  => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'status'     => 'in:active,inactive',
        ]);

        $this->restaurant()->inventoryItems()->create($data + ['status' => 'active']);
        $this->clearCache();

        return redirect()->route('admin.inventory')->with('success', '✓ Ítem agregado al inventario correctamente.');
    }

    // ── Update ────────────────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'category'   => 'nullable|string|max:100',
            'quantity'   => 'required|numeric|min:0',
            'unit'       => 'nullable|string|max:50',
            'min_stock'  => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'status'     => 'in:active,inactive',
        ]);

        $this->item($id)->update($data);
        $this->clearCache();

        return redirect()->route('admin.inventory')->with('success', '✓ Ítem actualizado correctamente.');
    }

    // ── Destroy ───────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $item = $this->item($id);
        $name = $item->name;
        $item->delete();
        $this->clearCache();

        return redirect()->route('admin.inventory')->with('success', "✓ \"{$name}\" eliminado del inventario.");
    }

    // ── Adjust Stock ──────────────────────────────────────────────────
    public function adjustStock(Request $request, int $id)
    {
        $request->validate([
            'tipo'     => 'required|in:agregar,retirar,fijar',
            'cantidad' => 'required|numeric|min:0.001',
        ]);

        $item     = $this->item($id);
        $cantidad = (float) $request->cantidad;

        switch ($request->tipo) {
            case 'agregar':
                $item->increment('quantity', $cantidad);
                $msg = "✓ Se agregaron {$cantidad} {$item->unit} a \"{$item->name}\".";
                break;
            case 'retirar':
                $nueva = max(0, $item->quantity - $cantidad);
                $item->update(['quantity' => $nueva]);
                $msg = "✓ Se retiraron {$cantidad} {$item->unit} de \"{$item->name}\".";
                break;
            case 'fijar':
                $item->update(['quantity' => $cantidad]);
                $msg = "✓ Stock de \"{$item->name}\" fijado en {$cantidad} {$item->unit}.";
                break;
        }

        $this->clearCache();

        return redirect()->route('admin.inventory')->with('success', $msg);
    }
}
