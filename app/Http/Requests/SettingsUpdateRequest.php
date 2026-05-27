<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_name' => ['nullable', 'string', 'max:255'],
            'default_tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'currency_code' => ['nullable', 'string', 'max:10'],
        ];
    }
}

