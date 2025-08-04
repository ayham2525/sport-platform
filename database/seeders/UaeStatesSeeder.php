<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UaeStatesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('states')->insert([
            [
                'name' => 'Abu Dhabi',
                'name_native' => 'أبو ظبي',
                'code' => 'AD',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dubai',
                'name_native' => 'دبي',
                'code' => 'DU',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sharjah',
                'name_native' => 'الشارقة',
                'code' => 'SH',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ajman',
                'name_native' => 'عجمان',
                'code' => 'AJ',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Fujairah',
                'name_native' => 'الفجيرة',
                'code' => 'FU',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ras Al Khaimah',
                'name_native' => 'رأس الخيمة',
                'code' => 'RK',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Umm Al Quwain',
                'name_native' => 'أم القيوين',
                'code' => 'UQ',
                'country_id' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
