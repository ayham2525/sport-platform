<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $systems = [
            ['id' => 1, 'name' => 'Green Sport'],
            ['id' => 2, 'name' => 'Ahly Sport'],
            ['id' => 3, 'name' => 'Drassa'],
            ['id' => 4, 'name' => 'HR'],
            ['id' => 5, 'name' => 'Accountant'],
            ['id' => 7, 'name' => 'Drassa Plus'],
        ];

        foreach ($systems as $system) {
            System::updateOrCreate(['id' => $system['id']], $system);
        }
    }
}
