<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SportsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sports')->insert([
            [
                'name_en' => 'Football',
                'name_ar' => 'كرة القدم',
                'description' => 'Team sport played with a spherical ball',
                'icon' => 'fas fa-futbol',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Basketball',
                'name_ar' => 'كرة السلة',
                'description' => 'Sport played on a rectangular court with a hoop',
                'icon' => 'fas fa-basketball-ball',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Swimming',
                'name_ar' => 'السباحة',
                'description' => 'Sport based on moving through water',
                'icon' => 'fas fa-swimmer',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Tennis',
                'name_ar' => 'التنس',
                'description' => 'Racquet sport played individually or in pairs',
                'icon' => 'fas fa-table-tennis',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
