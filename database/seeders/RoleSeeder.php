<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'label' => 'Super Admin'],
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'receptionist', 'label' => 'Receptionist'],
            ['name' => 'manager', 'label' => 'Manager'],
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate(['name' => $role['name']], $role);
        }
    }
}

