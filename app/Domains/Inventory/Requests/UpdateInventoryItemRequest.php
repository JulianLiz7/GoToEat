<?php

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => ['sometimes', 'string', 'max:150'],
            'category'   => ['sometimes', 'nullable', 'string', 'max:100'],
            'quantity'   => ['sometimes', 'numeric', 'min:0'],
            'unit'       => ['sometimes', 'nullable', 'string', 'max:50'],
            'min_stock'  => ['sometimes', 'numeric', 'min:0'],
            'cost_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'status'     => ['sometimes', 'in:active,inactive'],
        ];
    }
}
