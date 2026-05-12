<?php

namespace App\Domains\Finance\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'amount'       => (float) $this->amount,
            'expense_date' => $this->expense_date->toDateString(),
            'description'  => $this->description,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];
    }
}
