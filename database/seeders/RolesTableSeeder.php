<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\System;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ✅ 1. Global Full Admin (only once)
        Role::updateOrInsert(
            ['slug' => 'full_admin', 'system_id' => null],
            [
                'name' => 'Full Admin',
                'description' => 'Global full admin role',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // ✅ 2. Define system-specific roles (excluding full_admin)
        $systemRoles = [
            ['name' => 'System Admin', 'slug' => 'system_admin', 'description' => 'System admin role'],
            ['name' => 'Branch Admin', 'slug' => 'branch_admin', 'description' => 'Branch admin role'],
            ['name' => 'Coach', 'slug' => 'coach', 'description' => 'Coach role'],
            ['name' => 'Player', 'slug' => 'player', 'description' => 'Player role'],
        ];

        // ✅ 3. Seed these roles for each system
        foreach (System::all() as $system) {
            foreach ($systemRoles as $role) {
                Role::updateOrInsert(
                    [
                        'slug' => $role['slug'],
                        'system_id' => $system->id,
                    ],
                    [
                        'name' => $role['name'],
                        'description' => $role['description'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }
    }
}

