<?php

namespace App\Domains\Menu\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Menu\Requests\StoreMenuItemRequest;
use App\Domains\Menu\Requests\UpdateMenuItemRequest;
use App\Domains\Menu\Resources\MenuItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuItemController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $items = $this->restaurant()
            ->menuItems()
            ->with(['ingredients.inventoryItem'])
            ->latest()
            ->paginate(30);

        return MenuItemResource::collection($items);
    }

    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $ingredients = $validated['ingredients'] ?? [];
        unset($validated['ingredients']);

        $item = $this->restaurant()->menuItems()->create($validated);

        if ($ingredients) {
            $item->ingredients()->createMany($ingredients);
        }

        return (new MenuItemResource($item->load('ingredients.inventoryItem')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->restaurant()->menuItems()->with('ingredients.inventoryItem')->find($id);

        return $item
            ? (new MenuItemResource($item))->response()
            : $this->notFound();
    }

    public function update(UpdateMenuItemRequest $request, int $id): JsonResponse
    {
        $item = $this->restaurant()->menuItems()->find($id);

        if (!$item) return $this->notFound();

        $validated = $request->validated();
        $ingredients = $validated['ingredients'] ?? null;
        unset($validated['ingredients']);

        $item->update($validated);

        if ($ingredients !== null) {
            $item->ingredients()->delete();
            $item->ingredients()->createMany($ingredients);
        }

        return (new MenuItemResource($item->load('ingredients.inventoryItem')))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $item = $this->restaurant()->menuItems()->find($id);

        if (!$item) return $this->notFound();

        $item->delete();

        return response()->json(['message' => 'Menu item deleted.']);
    }

    public function toggleAvailability(int $id): JsonResponse
    {
        $item = $this->restaurant()->menuItems()->find($id);

        if (!$item) return $this->notFound();

        $item->update(['available' => !$item->available]);

        return (new MenuItemResource($item))->response();
    }
}
