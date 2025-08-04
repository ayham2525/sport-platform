<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Full Admin',
                'email' => 'fulladmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'full_admin',
                'system_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'System Admin',
                'email' => 'sysadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'system_admin',
                'system_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Branch Admin',
                'email' => 'branchadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'branch_admin',
                'system_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coach John',
                'email' => 'coach@example.com',
                'password' => Hash::make('password'),
                'role' => 'coach',
                'system_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Player One',
                'email' => 'player@example.com',
                'password' => Hash::make('password'),
                'role' => 'player',
                'system_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
