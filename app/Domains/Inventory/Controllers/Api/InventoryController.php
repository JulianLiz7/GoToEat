<?php

namespace App\Domains\Inventory\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Requests\StoreInventoryItemRequest;
use App\Domains\Inventory\Requests\UpdateInventoryItemRequest;
use App\Domains\Inventory\Resources\InventoryItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InventoryController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $items = $this->restaurant()
            ->inventoryItems()
            ->latest()
            ->paginate(30);

        return InventoryItemResource::collection($items);
    }

    public function lowStock(): AnonymousResourceCollection
    {
        $items = $this->restaurant()
            ->inventoryItems()
            ->whereColumn('quantity', '<=', 'min_stock')
            ->get();

        return InventoryItemResource::collection($items);
    }

    public function store(StoreInventoryItemRequest $request): JsonResponse
    {
        $item = $this->restaurant()->inventoryItems()->create($request->validated());

        return (new InventoryItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->restaurant()->inventoryItems()->find($id);

        return $item
            ? (new InventoryItemResource($item))->response()
            : $this->notFound();
    }

    public function update(UpdateInventoryItemRequest $request, int $id): JsonResponse
    {
        $item = $this->restaurant()->inventoryItems()->find($id);

        if (!$item) return $this->notFound();

        $item->update($request->validated());

        return (new InventoryItemResource($item))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $item = $this->restaurant()->inventoryItems()->find($id);

        if (!$item) return $this->notFound();

        $item->delete();

        return response()->json(['message' => 'Inventory item deleted.']);
    }
}
