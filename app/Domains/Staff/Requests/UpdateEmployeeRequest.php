<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'position'          => ['sometimes', 'string', 'max:100'],
            'salary'            => ['sometimes', 'numeric', 'min:0'],
            'hire_date'         => ['sometimes', 'date'],
            'status'            => ['sometimes', 'in:active,inactive,on_leave'],
            'emergency_contact' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes'             => ['sometimes', 'nullable', 'string'],
            'shifts'            => ['sometimes', 'nullable', 'array'],
        ];
    }
}
