<?php

namespace App\Domains\Menu\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'tags'        => ['nullable', 'string', 'max:255'],
            'available'   => ['boolean'],
            'is_featured' => ['boolean'],
            'prep_time'   => ['nullable', 'integer', 'min:1'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.inventory_item_id' => ['required_with:ingredients', 'exists:inventory_items,id'],
            'ingredients.*.quantity_required' => ['required_with:ingredients', 'numeric', 'min:0.001'],
        ];
    }
}
