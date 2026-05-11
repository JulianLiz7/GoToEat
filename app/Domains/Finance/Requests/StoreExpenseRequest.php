<?php

namespace App\Domains\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'description'  => ['nullable', 'string'],
        ];
    }
}
