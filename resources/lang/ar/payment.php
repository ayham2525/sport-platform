<?php

return [
    'titles' => [
        'payments_list'     => 'قائمة الدفعات',
        'edit_payment'      => 'تعديل الدفعة',
        'payment_details'   => 'تفاصيل الدفعة',
        'dashboard'         => 'لوحة التحكم',
        'payments'          => 'الدفعات',
        'view_payment'      => 'عرض الدفعة',
        'deleted_title' => 'تم الحذف!',
    ],

    'fields' => [
        'player'            => 'اللاعب',
        'program'           => 'البرنامج',
        'class_count'       => 'عدد الحصص',
        'base_price'        => 'السعر الأساسي',
        'vat_percent'       => 'نسبة الضريبة (%)',
        'vat_amount'        => 'قيمة الضريبة',
        'total_price'       => 'المبلغ الإجمالي',
        'paid'              => 'المبلغ المدفوع',
        'paid_amount'       => 'المبلغ المدفوع',
        'remaining'         => 'المبلغ المتبقي',
        'remaining_amount'  => 'المبلغ المتبقي',
        'status'            => 'الحالة',
        'payment_method'    => 'طريقة الدفع',
        'currency'          => 'العملة',
        'note'              => 'ملاحظة',
        'actions'           => 'الإجراءات',
        'amount'            => 'المبلغ',
        'category'          => 'الفئة',
        'items'             => 'العناصر',
        'item'              => 'عنصر',
        'quantity'          => 'الكمية',
        'classes'           => 'الحصص',
        'price'             => 'السعر',
        'is_vat_inclusive' => 'نوع الضريبة',
    ],
     'vat' => [
        'inclusive' => 'شامل الضريبة',
        'exclusive' => 'غير شامل الضريبة',
    ],

    'status' => [
        'pending'           => 'قيد الانتظار',
        'partial'           => 'مدفوع جزئياً',
        'paid'              => 'مدفوع',
        'active'            => 'نشط',
        'expired'           => 'منتهي',
        'unknown'           => 'لا يوجد دفعة',
    ],

    'actions' => [
        'edit'              => 'تعديل',
        'save'              => 'حفظ',
        'back'              => 'عودة',
        'filter'            => 'تصفية',
        'create'            => 'إنشاء دفعة',
        'add'               => 'إضافة دفعة',
        'list'              =>  'قائمة الدفعات',
        'add_item'          => 'إضافة عنصر',
        'update'            => 'تحديث الدفعة',
        'view'             => 'عرض الدفعة',
        'export'           => 'تصدير الدفعات',


    ],

    'filters' => [
        'select_system'     => 'اختر النظام',
        'select_branch'     => 'اختر الفرع',
        'select_academy'    => 'اختر الأكاديمية',
        'search_player'     => 'بحث باسم اللاعب',
        'select'            => 'اختر',
        'player_id'         => 'بحث برقم هوية اللاعب',
    ],

    'messages' => [
        'payment_updated_successfully' => 'تم تحديث الدفعة بنجاح.',
        'payment_created_successfully' => 'تم إنشاء الدفعة بنجاح.',
        'payment_deleted_successfully' => 'تم حذف الدفعة بنجاح.',
        'deleted_title' => 'تم الحذف!',
        'deleted_message' => 'تم حذف الدفعة بنجاح.',
        'error_title' => 'خطأ!',
        'error_message' => 'فشل في حذف الدفعة، يرجى المحاولة مرة أخرى.',

    ],

    'categories' => [
        'program'   => 'برنامج',
        'uniform'   => 'زي رسمي',
        'asset'     => 'أصل',
        'camp'      => 'معسكر',
        'class'     => 'صف',
    ],
    'confirm' => [
        'title' => 'هل أنت متأكد؟',
        'message' => 'سيتم حذف الدفعة بشكل نهائي.',
        'confirm_button' => 'نعم، احذفها!',
        'cancel_button' => 'إلغاء',
    ],
];
