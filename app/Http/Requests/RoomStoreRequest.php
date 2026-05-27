<?php

namespace App\Http\Requests;

use App\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'room_number' => ['required', 'string', 'max:50', Rule::unique('rooms', 'room_number')],
            'floor' => ['required', 'integer', 'min:0', 'max:200'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'rate_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(array_map(fn ($s) => $s->value, RoomStatus::cases()))],
            'notes' => ['nullable', 'string'],
            'amenity_ids' => ['nullable', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['file', 'image', 'max:4096'],
        ];
    }
}

