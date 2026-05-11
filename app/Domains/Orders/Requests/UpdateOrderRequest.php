<?php

namespace App\Domains\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status'         => ['sometimes', 'in:pending,preparing,ready,completed,cancelled'],
            'tips'           => ['sometimes', 'numeric', 'min:0'],
            'payment_method' => ['sometimes', 'in:cash,card,transfer'],
            'waiter_id'      => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }
}
