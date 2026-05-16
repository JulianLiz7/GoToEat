<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────
    private function restaurant()
    {
        $r = auth()->user()->ownedRestaurants()->first();
        abort_unless($r, 403);
        return $r;
    }

    private function item(int $id)
    {
        $item = $this->restaurant()->menuItems()->find($id);
        abort_unless($item, 404, 'Plato no encontrado.');
        return $item;
    }

    private function clearCache(): void
    {
        Cache::forget('dashboard_stats_' . $this->restaurant()->id);
    }

    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $restaurant  = $this->restaurant();
        $categoriaFiltro = $request->get('categoria', '');

        $query = $restaurant->menuItems()->latest();
        if ($categoriaFiltro) {
            $query->where('category', $categoriaFiltro);
        }

        $itemsByCategory = $query->get()->groupBy(fn($i) => $i->category ?: 'Sin categoría');
        $todasCategorias = $restaurant->menuItems()
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(fn($c) => $c ?: 'Sin categoría')
            ->unique()
            ->values();

        return view('admin.menu', compact('restaurant', 'itemsByCategory', 'todasCategorias', 'categoriaFiltro'));
    }

    // ── Store ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'prep_time'   => 'nullable|integer|min:1',
            'tags'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['available']   = $request->input('available_radio', '1') === '1';
        $data['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        $this->restaurant()->menuItems()->create($data);
        $this->clearCache();

        return redirect()->route('admin.menu')->with('success', '✓ Plato creado exitosamente.');
    }

    // ── Update ────────────────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'prep_time'   => 'nullable|integer|min:1',
            'tags'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        $item = $this->item($id);

        $data['available']   = $request->input('available_radio', '1') === '1';
        $data['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe en storage local
            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        $item->update($data);
        $this->clearCache();

        return redirect()->route('admin.menu')->with('success', '✓ Plato actualizado correctamente.');
    }

    // ── Destroy ───────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $item = $this->item($id);
        $name = $item->name;

        if ($item->image && !str_starts_with($item->image, 'http')) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        $this->clearCache();

        return redirect()->route('admin.menu')->with('success', "✓ \"{$name}\" eliminado del menú.");
    }

    // ── Toggle disponibilidad ─────────────────────────────────────────
    public function toggleAvailable(int $id)
    {
        $item = $this->item($id);
        $item->update(['available' => !$item->available]);
        $this->clearCache();

        $estado = $item->available ? 'disponible' : 'no disponible';
        return redirect()->route('admin.menu')->with('success', "✓ \"{$item->name}\" marcado como {$estado}.");
    }
}
