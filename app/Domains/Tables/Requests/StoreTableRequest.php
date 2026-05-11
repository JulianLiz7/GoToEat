<?php

namespace App\Domains\Tables\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'number'   => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'zone'     => ['nullable', 'string', 'max:100'],
            'status'   => ['in:disponible,ocupada,reservada,mantenimiento'],
        ];
    }
}
