<?php

namespace App\Domains\Tables\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'number'             => ['sometimes', 'string', 'max:20'],
            'capacity'           => ['sometimes', 'integer', 'min:1', 'max:50'],
            'zone'               => ['sometimes', 'nullable', 'string', 'max:100'],
            'status'             => ['sometimes', 'in:disponible,ocupada,reservada,mantenimiento'],
            'is_reserved'        => ['sometimes', 'boolean'],
            'reservation_time'   => ['sometimes', 'nullable', 'date'],
            'customer_name'      => ['sometimes', 'nullable', 'string', 'max:150'],
            'customer_phone'     => ['sometimes', 'nullable', 'string', 'max:30'],
            'party_size'         => ['sometimes', 'nullable', 'integer', 'min:1'],
            'reservation_notes'  => ['sometimes', 'nullable', 'string'],
        ];
    }
}
