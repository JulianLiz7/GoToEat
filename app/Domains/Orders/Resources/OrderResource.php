<?php

namespace App\Domains\Orders\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'total'      => (float) $this->total,
            'tips'       => (float) $this->tips,
            'items'      => $this->items ?? [],
            'table'      => $this->whenLoaded('table', fn () => [
                'id'     => $this->table->id,
                'number' => $this->table->number,
                'zone'   => $this->table->zone,
            ]),
            'waiter'     => $this->whenLoaded('waiter', fn () => [
                'id'   => $this->waiter->id,
                'name' => $this->waiter->name,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
