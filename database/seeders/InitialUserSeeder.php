<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'ChangeMe123!';

        $superAdminRole = Role::query()->where('name', 'super_admin')->firstOrFail();

        User::query()->firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => null,
                'password' => $password,
                'role_id' => $superAdminRole->id,
            ]
        );

        $defaults = [
            ['username' => 'admin', 'name' => 'Admin', 'role' => 'admin'],
            ['username' => 'reception', 'name' => 'Receptionist', 'role' => 'receptionist'],
            ['username' => 'manager', 'name' => 'Manager', 'role' => 'manager'],
        ];

        foreach ($defaults as $d) {
            $role = Role::query()->where('name', $d['role'])->first();
            if (! $role) {
                continue;
            }

            User::query()->firstOrCreate(
                ['username' => $d['username']],
                [
                    'name' => $d['name'],
                    'email' => null,
                    'password' => $password,
                    'role_id' => $role->id,
                ]
            );
        }
    }
}

