<?php

namespace App\Domains\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'table_id'          => ['nullable', 'exists:tables,id'],
            'waiter_id'         => ['nullable', 'exists:users,id'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'items.*.unit_price'   => ['required', 'numeric', 'min:0'],
            'tips'              => ['nullable', 'numeric', 'min:0'],
            'payment_method'    => ['nullable', 'in:cash,card,transfer'],
        ];
    }
}
