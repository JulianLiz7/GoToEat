<?php

namespace App\Domains\Menu\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:150'],
            'category'    => ['sometimes', 'nullable', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'image'       => ['sometimes', 'nullable', 'image', 'max:2048'],
            'tags'        => ['sometimes', 'nullable', 'string', 'max:255'],
            'available'   => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'prep_time'   => ['sometimes', 'nullable', 'integer', 'min:1'],
            'ingredients' => ['sometimes', 'nullable', 'array'],
            'ingredients.*.inventory_item_id' => ['required_with:ingredients', 'exists:inventory_items,id'],
            'ingredients.*.quantity_required' => ['required_with:ingredients', 'numeric', 'min:0.001'],
        ];
    }
}
