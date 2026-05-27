<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('room_types', 'name')],
            'description' => ['nullable', 'string'],
            'default_rate' => ['required', 'numeric', 'min:0'],
            'default_capacity' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }
}

