<?php

namespace App\Domains\Inventory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'category'    => $this->category,
            'quantity'    => (float) $this->quantity,
            'unit'        => $this->unit,
            'min_stock'   => (float) $this->min_stock,
            'cost_price'  => $this->cost_price ? (float) $this->cost_price : null,
            'status'      => $this->status,
            'is_low_stock'=> $this->isLowStock(),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
