<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Single', 'default_rate' => 25000, 'default_capacity' => 1],
            ['name' => 'Double', 'default_rate' => 45000, 'default_capacity' => 2],
            ['name' => 'Deluxe', 'default_rate' => 75000, 'default_capacity' => 2],
            ['name' => 'VIP Suite', 'default_rate' => 150000, 'default_capacity' => 2],
            ['name' => 'Family Room', 'default_rate' => 120000, 'default_capacity' => 4],
            ['name' => 'Presidential Suite', 'default_rate' => 1250000, 'default_capacity' => 4],
        ];

        foreach ($types as $t) {
            RoomType::query()->firstOrCreate(
                ['slug' => Str::slug($t['name'])],
                [
                    'name' => $t['name'],
                    'slug' => Str::slug($t['name']),
                    'description' => null,
                    'default_rate' => number_format((float) $t['default_rate'], 2, '.', ''),
                    'default_capacity' => $t['default_capacity'],
                ]
            );
        }
    }
}

