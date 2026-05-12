<?php

namespace App\Domains\Orders\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Requests\StoreOrderRequest;
use App\Domains\Orders\Requests\UpdateOrderRequest;
use App\Domains\Orders\Resources\OrderResource;
use App\Events\OrderPlaced;
use App\Events\OrderCompleted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $orders = $this->restaurant()
            ->orders()
            ->with(['table', 'waiter'])
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $restaurant = $this->restaurant();

        // Compute total from items
        $total = collect($validated['items'])->sum(
            fn ($item) => $item['quantity'] * $item['unit_price']
        );

        $order = $restaurant->orders()->create([
            'table_id'  => $validated['table_id'] ?? null,
            'waiter_id' => $validated['waiter_id'] ?? null,
            'status'    => 'pending',
            'total'     => $total,
            'tips'      => $validated['tips'] ?? 0,
            'items'     => $validated['items'],
        ]);

        // Mark table as occupied
        if ($order->table_id) {
            $restaurant->tables()->where('id', $order->table_id)->update(['status' => 'ocupada']);
        }

        event(new OrderPlaced($order));

        return (new OrderResource($order->load(['table', 'waiter'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->restaurant()->orders()->with(['table', 'waiter'])->find($id);

        return $order
            ? (new OrderResource($order))->response()
            : $this->notFound();
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->restaurant()->orders()->find($id);

        if (!$order) return $this->notFound();

        $prevStatus = $order->status;
        $order->update($request->validated());

        // Fire completion event to trigger inventory deduction and tip settlement
        if ($prevStatus !== 'completed' && $order->status === 'completed') {
            event(new OrderCompleted($order));

            // Free the table
            if ($order->table_id) {
                $this->restaurant()->tables()
                    ->where('id', $order->table_id)
                    ->update(['status' => 'disponible']);
            }
        }

        return (new OrderResource($order->load(['table', 'waiter'])))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $order = $this->restaurant()->orders()->find($id);

        if (!$order) return $this->notFound();

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Order cancelled.']);
    }
}
