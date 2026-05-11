<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'user_id'           => ['required', 'exists:users,id'],
            'position'          => ['required', 'string', 'max:100'],
            'salary'            => ['nullable', 'numeric', 'min:0'],
            'hire_date'         => ['nullable', 'date'],
            'status'            => ['in:active,inactive,on_leave'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string'],
            'shifts'            => ['nullable', 'array'],
        ];
    }
}
