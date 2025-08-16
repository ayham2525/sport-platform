<?php

return [
    'title' => 'التقارير',
    'payments_report' => 'تقرير المدفوعات',
    'uniforms_report' => 'تقرير الزيّ الموحّد',

    'payments' => [
        'title' => 'تقرير المدفوعات',

        // ملخّص مجمّع
        'summary' => 'الملخّص',
        'summary_period' => 'الفترة',
        'summary_count' => 'العدد',

        'filters' => [
            'title' => 'عوامل التصفية',

            // الفترة السريعة + التجميع
            'quick_period' => 'الفترة السريعة',
            'custom' => 'مخصّص',
            'today' => 'اليوم',
            'this_week' => 'هذا الأسبوع',
            'this_month' => 'هذا الشهر',
            'group_by' => 'تجميع حسب',
            'none' => 'بدون',
            'daily' => 'يومي',
            'weekly' => 'أسبوعي',
            'monthly' => 'شهري',

            // بقية المرشّحات
            'date_from' => 'من تاريخ',
            'date_to' => 'إلى تاريخ',
            'status' => 'الحالة',
            'category' => 'الفئة',
            'branch' => 'الفرع',
            'academy' => 'الأكاديمية',
            'program' => 'البرنامج',
            'player' => 'معرّف اللاعب',
            'payment_method' => 'طريقة الدفع',
            'reset_contains' => 'يحتوي رقم الإيصال/السند على',
            'reset_placeholder' => 'اكتب للبحث...',
            'branch_placeholder' => 'مثال: 10',
            'academy_placeholder' => 'مثال: 5',
            'program_placeholder' => 'مثال: 12',
            'player_placeholder' => 'مثال: 1234',
            'payment_method_placeholder' => 'مثال: 2',
            'any' => 'أيّ',
            'per_page' => 'لكل صفحة',
        ],

        'totals' => [
            'base_total' => 'إجمالي السعر الأساسي',
            'discount_total' => 'إجمالي الخصم',
            'vat_total' => 'إجمالي ضريبة القيمة المضافة',
            'grand_total' => 'الإجمالي الكلي',
            'paid_total' => 'إجمالي المدفوع',
            'remaining_total' => 'إجمالي المتبقي',
        ],

        'status_breakdown' => 'توزيع الحالات',

        'table' => [
            'title' => 'النتائج',
            'category' => 'الفئة',
            'status' => 'الحالة',
            'payment_date' => 'تاريخ الدفع',
            'player' => 'اللاعب',
            'program' => 'البرنامج',
            'branch' => 'الفرع',
            'academy' => 'الأكاديمية',
            'method' => 'طريقة الدفع',
            'base' => 'الأساسي',
            'discount' => 'الخصم',
            'vat' => 'ض.ق.م',
            'total' => 'الإجمالي',
            'paid' => 'المدفوع',
            'remaining' => 'المتبقي',
            'currency' => 'العملة',
            'reset_number' => 'رقم الإيصال/السند',
        ],
    ],

    'actions' => [
        'apply_filters' => 'تطبيق المرشّحات',
        'reset' => 'إعادة التعيين',
        'export_csv' => 'تصدير CSV',
        'export_excel' => 'تصدير Excel',

    ],

    'table' => [
        'showing' => 'عرض',
        'of' => 'من',
        'no_results' => 'لا توجد نتائج.',
    ],
    'branch_summary' => [
    'title' => 'ملخّص المدفوعات حسب الفرع',
    'academy_title' => 'الأكاديميات في الفرع المحدّد',
    'filters' => [
        'title' => 'عوامل التصفية',
        'system' => 'النظام',
        'choose_system' => 'اختر نظاماً',
        'branch' => 'الفرع',
        'all_branches' => 'جميع الفروع',
        'date_from' => 'من تاريخ',
        'date_to' => 'إلى تاريخ',
    ],
    'table' => [
        'title' => 'ملخّص الفروع',
        'range' => 'النطاق',
        'branch' => 'الفرع',
        'academy' => 'الأكاديمية',
        'total_income' => 'إجمالي الدخل',
        'expired' => 'الاشتراكات المنتهية',
        'card' => 'بطاقة',
        'cash' => 'نقداً',
        'online' => 'أونلاين',
        'link' => 'رابط دفع',
        'tabby' => 'تابي',
        'tamara' => 'تمارا',
        'total' => 'الإجمالي',
    ],
],

'branch_payments_summary' => 'ملخص مدفوعات الفرع',

];
