<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'WiFi',
            'TV',
            'Swimming Pool',
            'Air Conditioning',
            'Breakfast',
            'Parking',
        ];

        foreach ($items as $name) {
            Amenity::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name)]
            );
        }
    }
}

