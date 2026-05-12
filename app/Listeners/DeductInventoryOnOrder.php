<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Menu\Models\MenuItem;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeductInventoryOnOrder implements ShouldQueue
{
    public string $queue = 'inventory';

    public function handle(OrderCompleted $event): void
    {
        $order      = $event->order;
        $items      = $order->items ?? [];
        $restaurantId = $order->restaurant_id;

        foreach ($items as $item) {
            $menuItemId = $item['menu_item_id'] ?? null;
            $quantity   = $item['quantity'] ?? 1;

            if (!$menuItemId) continue;

            // Load escandallo (recipe) for this menu item
            $ingredients = \DB::table('menu_item_ingredients')
                ->where('menu_item_id', $menuItemId)
                ->get();

            foreach ($ingredients as $ingredient) {
                $deduct = $ingredient->quantity_required * $quantity;

                InventoryItem::where('id', $ingredient->inventory_item_id)
                    ->where('restaurant_id', $restaurantId)
                    ->decrement('quantity', $deduct);
            }
        }
    }
}
