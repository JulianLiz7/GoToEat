<?php

namespace App\Domains\Tables\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'number'             => $this->number,
            'capacity'           => $this->capacity,
            'zone'               => $this->zone,
            'status'             => $this->status,
            'qr_code'            => $this->qr_code,
            'is_reserved'        => $this->is_reserved,
            'reservation_time'   => $this->reservation_time?->toDateTimeString(),
            'customer_name'      => $this->customer_name,
            'customer_phone'     => $this->customer_phone,
            'party_size'         => $this->party_size,
            'reservation_notes'  => $this->reservation_notes,
            'created_at'         => $this->created_at->toDateTimeString(),
        ];
    }
}
