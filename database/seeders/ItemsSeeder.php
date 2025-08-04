<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['system_id' => 1, 'name_en' => 'Football', 'name_ar' => 'كرة قدم', 'price' => 50.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Basketball', 'name_ar' => 'كرة سلة', 'price' => 55.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Tennis Racket', 'name_ar' => 'مضرب تنس', 'price' => 90.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Volleyball', 'name_ar' => 'كرة طائرة', 'price' => 40.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Swimming Goggles', 'name_ar' => 'نظارات سباحة', 'price' => 30.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Running Shoes', 'name_ar' => 'أحذية جري', 'price' => 120.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Boxing Gloves', 'name_ar' => 'قفازات ملاكمة', 'price' => 80.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Yoga Mat', 'name_ar' => 'حصيرة يوغا', 'price' => 45.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Resistance Bands', 'name_ar' => 'أشرطة مقاومة', 'price' => 35.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Jump Rope', 'name_ar' => 'حبل قفز', 'price' => 20.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Cricket Bat', 'name_ar' => 'مضرب كريكيت', 'price' => 85.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Baseball Glove', 'name_ar' => 'قفاز بيسبول', 'price' => 70.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Hockey Stick', 'name_ar' => 'عصا هوكي', 'price' => 95.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Badminton Racket', 'name_ar' => 'مضرب بادمنتون', 'price' => 60.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Skateboard', 'name_ar' => 'لوح تزلج', 'price' => 130.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Helmet', 'name_ar' => 'خوذة', 'price' => 75.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Shin Guards', 'name_ar' => 'واقيات الساق', 'price' => 25.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Sports Bag', 'name_ar' => 'حقيبة رياضية', 'price' => 65.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Water Bottle', 'name_ar' => 'قارورة ماء', 'price' => 15.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 1, 'name_en' => 'Training Cones', 'name_ar' => 'أقماع تدريب', 'price' => 18.00, 'currency_id' => 1, 'active' => true],

            ['system_id' => 2, 'name_en' => 'Football', 'name_ar' => 'كرة قدم', 'price' => 50.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Basketball', 'name_ar' => 'كرة سلة', 'price' => 55.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Tennis Racket', 'name_ar' => 'مضرب تنس', 'price' => 90.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Volleyball', 'name_ar' => 'كرة طائرة', 'price' => 40.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Swimming Goggles', 'name_ar' => 'نظارات سباحة', 'price' => 30.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Running Shoes', 'name_ar' => 'أحذية جري', 'price' => 120.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Boxing Gloves', 'name_ar' => 'قفازات ملاكمة', 'price' => 80.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Yoga Mat', 'name_ar' => 'حصيرة يوغا', 'price' => 45.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Resistance Bands', 'name_ar' => 'أشرطة مقاومة', 'price' => 35.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Jump Rope', 'name_ar' => 'حبل قفز', 'price' => 20.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Cricket Bat', 'name_ar' => 'مضرب كريكيت', 'price' => 85.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Baseball Glove', 'name_ar' => 'قفاز بيسبول', 'price' => 70.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Hockey Stick', 'name_ar' => 'عصا هوكي', 'price' => 95.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Badminton Racket', 'name_ar' => 'مضرب بادمنتون', 'price' => 60.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Skateboard', 'name_ar' => 'لوح تزلج', 'price' => 130.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Helmet', 'name_ar' => 'خوذة', 'price' => 75.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Shin Guards', 'name_ar' => 'واقيات الساق', 'price' => 25.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Sports Bag', 'name_ar' => 'حقيبة رياضية', 'price' => 65.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Water Bottle', 'name_ar' => 'قارورة ماء', 'price' => 15.00, 'currency_id' => 1, 'active' => true],
            ['system_id' => 2, 'name_en' => 'Training Cones', 'name_ar' => 'أقماع تدريب', 'price' => 18.00, 'currency_id' => 1, 'active' => true],

        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['name_en' => $item['name_en'], 'system_id' => $item['system_id']],
                $item
            );
        }
    }
}
