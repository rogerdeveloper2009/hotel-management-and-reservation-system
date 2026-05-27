<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmenityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $amenity = $this->route('amenity');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('amenities', 'name')->ignore($amenity->id)],
        ];
    }
}

