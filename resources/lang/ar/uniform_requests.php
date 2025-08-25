<?php

return [

    'titles' => [
        'uniform_requests' => 'طلبات الزي',
    ],

    // العناوين العامة
    'title'         => 'طلبات الزي',
    'create_title'  => 'إضافة طلب زي جديد',
    'edit_title'    => 'تعديل طلب الزي',
    'dashboard'     => 'لوحة التحكم',
    'list'          => 'قائمة طلبات الزي',
    'new'           => 'إضافة طلب جديد',
    'no_data'       => 'لا توجد طلبات زي.',

    // التأكيدات
    'confirm_delete_title'  => 'هل أنت متأكد؟',
    'confirm_delete_text'   => 'سيتم حذف الطلب نهائيًا!',
    'confirm_delete_button' => 'نعم، احذف',
    'cancel'                => 'إلغاء',

    // حالات عامة
    'statuses' => [
        'requested' => 'تم الطلب',
        'approved'  => 'معتمد',
        'ordered'   => 'تم طلبه',
        'delivered' => 'تم التسليم',
        'rejected'  => 'مرفوض',
        'cancelled' => 'ملغى',
        'returned'  => 'مرتجع',
        'non'       => 'بدون',
        'received'  => 'تم الاستلام',   // احتياطي للتوافق
        'recived'   => 'تم الاستلام',   // تهجئة بديلة للتوافق
        'pending'    => 'قيد الانتظار',
        'processing' => 'قيد المعالجة',
        'completed'  => 'مكتمل',
    ],

    // حالات الفرع
    'branch_statuses' => [
        'requested' => 'تم الطلب',
        'approved'  => 'معتمد',
        'rejected'  => 'مرفوض',
        'cancelled' => 'ملغى',
        'non'       => 'بدون',
        'received'  => 'تم الاستلام',
        'recived'   => 'تم الاستلام',   // احتياطي للتوافق
        'ordered'   => 'تم طلبه',
    ],

    // حالات المكتب
    'office_statuses' => [
        'pending'    => 'قيد الانتظار',
        'processing' => 'قيد المعالجة',
        'completed'  => 'مكتمل',
        'cancelled'  => 'ملغى',
        'delivered'  => 'تم التسليم',
        'received'   => 'تم الاستلام',
        'recived'    => 'تم الاستلام', // احتياطي للتوافق
        'non'        => 'بدون',
    ],

    // الحقول
    'fields' => [
        'system'         => 'النظام',
        'branch'         => 'الفرع',
        'player'         => 'اللاعب',
        'item'           => 'الصنف',
        'size'           => 'المقاس',
        'color'          => 'اللون',
        'quantity'       => 'الكمية',
        'amount'         => 'المبلغ',
        'currency'       => 'العملة',
        'notes'          => 'ملاحظات',
        'status'         => 'الحالة',
        'branch_status'  => 'حالة الفرع',
        'office_status'  => 'حالة المكتب',
        'payment_method' => 'طريقة الدفع',
        'requested_at'   => 'تاريخ الطلب',
        'admin_remarks'  => 'ملاحظات الإدارة',
        'approved_at'    => 'تاريخ الاعتماد',
        'ordered_at'     => 'تاريخ الطلب من المورد',
        'delivered_at'   => 'تاريخ التسليم',
        'request_date'   => 'تاريخ الطلب',
           'stock_status' => 'حالة المخزون',
    ],

    // الإجراءات
    'actions' => [
        'edit'   => 'تعديل',
        'delete' => 'حذف',
        'save'   => 'حفظ',
        'list'   => 'عرض طلبات الزي',
        'add'    => 'إضافة طلب زي',
        'cancel' => 'إلغاء',
    ],

    // المقاسات
    'sizes' => [
        '6xs_24_4'   => '6XS - 24 - 4',
        '5xs_26_6'   => '5XS - 26 - 6',
        '4xs_28_8'   => '4XS - 28 - 8',
        '3xs_30_10'  => '3XS - 30 - 10',
        '2xs_32_12'  => '2XS - 32 - 12',
        '1xs_34_14'  => '1XS - 34 - 14',
        'XS'         => 'XS - صغير جدًا',
        'S'          => 'S - صغير',
        'M'          => 'M - متوسط',
        'L'          => 'L - كبير',
        'XL'         => 'XL - كبير جدًا',
        'XXL'        => '2XL - كبير جدًا 2',
        'XXXL'       => '3XL - كبير جدًا 3',
        'Youth_S'    => 'شباب S - صغير',
        'Youth_M'    => 'شباب M - متوسط',
        'Youth_L'    => 'شباب L - كبير',
        'Adult_S'    => 'بالغ S - صغير',
        'Adult_M'    => 'بالغ M - متوسط',
        'Adult_L'    => 'بالغ L - كبير',
        'Custom'     => 'مقاس مخصص',
    ],

    // خيارات النموذج
    'select_system'         => 'اختر النظام',
    'select_branch'         => 'اختر الفرع',
    'select_player'         => 'اختر اللاعب',
    'select_item'           => 'اختر الصنف',
    'select_size'           => 'اختر المقاس',
    'select_status'         => 'اختر الحالة',
    'select_branch_status'  => 'اختر حالة الفرع',
    'select_office_status'  => 'اختر حالة المكتب',
    'select_payment_method' => 'اختر طريقة الدفع',
    'all_statuses'          => 'كل الحالات',

    // رسائل فلاش
    'created_successfully' => 'تم إنشاء طلب الزي بنجاح.',
    'updated_successfully' => 'تم تحديث طلب الزي بنجاح.',
    'deleted_successfully' => 'تم حذف طلب الزي بنجاح.',
    'select'               => 'اختر',

    'messages' => [
        'no_requests' => 'لا توجد طلبات زي.',
    ],

    'select_stock_status' => 'اختر حالة المخزون',
    'stock_statuses' => [
        'in_stock'     => 'متوفر',
        'out_of_stock' => 'غير متوفر',
        'reserved'     => 'محجوز',
        'pending'      => 'قيد الانتظار',
    ],
];
