<?php

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:150'],
            'category'   => ['nullable', 'string', 'max:100'],
            'quantity'   => ['required', 'numeric', 'min:0'],
            'unit'       => ['nullable', 'string', 'max:50'],
            'min_stock'  => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'status'     => ['in:active,inactive'],
        ];
    }
}
