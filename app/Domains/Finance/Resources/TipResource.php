<?php

namespace App\Domains\Finance\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'amount'         => (float) $this->amount,
            'date'           => $this->date->toDateString(),
            'payment_method' => $this->payment_method,
            'requires_transfer' => $this->requiresTransfer(),
            'employee'       => $this->whenLoaded('employee', fn () => [
                'id'       => $this->employee->id,
                'position' => $this->employee->position,
                'name'     => $this->employee->user?->name,
            ]),
            'order_id'       => $this->order_id,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
