<?php

namespace App\Domains\Menu\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'category'    => $this->category,
            'description' => $this->description,
            'price'       => (float) $this->price,
            'image'       => $this->image,
            'tags'        => $this->tags,
            'available'   => $this->available,
            'is_featured' => $this->is_featured,
            'prep_time'   => $this->prep_time,
            'ingredients' => $this->whenLoaded('ingredients', fn () =>
                $this->ingredients->map(fn ($ing) => [
                    'inventory_item_id' => $ing->inventory_item_id,
                    'quantity_required' => (float) $ing->quantity_required,
                    'item_name'         => $ing->inventoryItem?->name,
                    'unit'              => $ing->inventoryItem?->unit,
                ])
            ),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
