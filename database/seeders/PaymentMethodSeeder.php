<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash',
                'name_ar' => 'نقداً',
                'name_ur' => 'نقد',
                'description' => 'Pay using physical cash.',
            ],
            [
                'name' => 'Credit Card',
                'name_ar' => 'بطاقة ائتمان',
                'name_ur' => 'کریڈٹ کارڈ',
                'description' => 'Visa, MasterCard and other credit cards.',
            ],
            [
                'name' => 'Debit Card',
                'name_ar' => 'بطاقة خصم',
                'name_ur' => 'ڈیبٹ کارڈ',
                'description' => 'Bank debit card payment.',
            ],
            [
                'name' => 'Bank Transfer',
                'name_ar' => 'تحويل مصرفي',
                'name_ur' => 'بینک ٹرانسفر',
                'description' => 'Direct bank-to-bank transfer.',
            ],
            [
                'name' => 'Apple Pay',
                'name_ar' => 'أبل باي',
                'name_ur' => 'ایپل پے',
                'description' => 'Secure payment via Apple devices.',
            ],
            [
                'name' => 'Google Pay',
                'name_ar' => 'جوجل باي',
                'name_ur' => 'گوگل پے',
                'description' => 'Pay using Google Pay app.',
            ],
            [
                'name' => 'PayPal',
                'name_ar' => 'باي بال',
                'name_ur' => 'پے پال',
                'description' => 'Secure online payment using PayPal.',
            ],
            [
                'name' => 'Cheque',
                'name_ar' => 'شيك',
                'name_ur' => 'چیک',
                'description' => 'Payment via issued cheque.',
            ],
            [
                'name' => 'Installments',
                'name_ar' => 'أقساط',
                'name_ur' => 'اقساط',
                'description' => 'Pay in scheduled installments.',
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method + ['is_active' => true]);
        }
    }
}
