<?php

return [
    'title' => 'Reports',
    'payments_report' => 'Payments Report',
    'uniforms_report' => 'Uniforms Report',

    'payments' => [
        'title' => 'Payments Report',

        // New: grouped summary section
        'summary' => 'Summary',
        'summary_period' => 'Period',
        'summary_count' => 'Count',

        'filters' => [
            'title' => 'Filters',

            // New: quick period + grouping controls
            'quick_period' => 'Quick period',
            'custom' => 'Custom',
            'today' => 'Today',
            'this_week' => 'This week',
            'this_month' => 'This month',
            'group_by' => 'Group by',
            'none' => 'None',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',

            // Existing filters
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'status' => 'Status',
            'category' => 'Category',
            'branch' => 'Branch',
            'academy' => 'Academy',
            'program' => 'Program',
            'player' => 'Player ID',
            'payment_method' => 'Payment Method',
            'reset_contains' => 'Receipt/Reset contains',
            'reset_placeholder' => 'Type to search...',
            'branch_placeholder' => 'e.g. 10',
            'academy_placeholder' => 'e.g. 5',
            'program_placeholder' => 'e.g. 12',
            'player_placeholder' => 'e.g. 1234',
            'payment_method_placeholder' => 'e.g. 2',
            'any' => 'Any',
            'per_page' => 'Per Page',
        ],

        'totals' => [
            'base_total' => 'Base Total',
            'discount_total' => 'Discount Total',
            'vat_total' => 'VAT Total',
            'grand_total' => 'Grand Total',
            'paid_total' => 'Paid Total',
            'remaining_total' => 'Remaining Total',
        ],

        'status_breakdown' => 'Status breakdown',

        'table' => [
            'title' => 'Results',
            'category' => 'Category',
            'status' => 'Status',
            'payment_date' => 'Payment Date',
            'player' => 'Player',
            'program' => 'Program',
            'branch' => 'Branch',
            'academy' => 'Academy',
            'method' => 'Method',
            'base' => 'Base',
            'discount' => 'Discount',
            'vat' => 'VAT',
            'total' => 'Total',
            'paid' => 'Paid',
            'remaining' => 'Remaining',
            'currency' => 'Currency',
            'reset_number' => 'Receipt/Reset #',
        ],
    ],

    'actions' => [
        'apply_filters' => 'Apply Filters',
        'reset' => 'Reset',
        'export_csv' => 'Export CSV',
        'export_excel' => 'Export Excel',

    ],

    'table' => [
        'showing' => 'Showing',
        'of' => 'of',
        'no_results' => 'No results found.',
    ],
    'branch_summary' => [
    'title' => 'Payments Summary by Branch',
    'academy_title' => 'Academies in Selected Branch',
    'filters' => [
        'title' => 'Filters',
        'system' => 'System',
        'choose_system' => 'Choose a system',
        'branch' => 'Branch',
        'all_branches' => 'All branches',
        'date_from' => 'Date From',
        'date_to' => 'Date To',
    ],
    'table' => [
        'title' => 'Branch Summary',
        'range' => 'Range',
        'branch' => 'Branch',
        'academy' => 'Academy',
        'total_income' => 'Total Income',
        'expired' => 'Expired Subscribers',
        'card' => 'Card',
        'cash' => 'Cash',
        'online' => 'Online',
        'link' => 'Link',
        'tabby' => 'Tabby',
        'tamara' => 'Tamara',
        'total' => 'Total',
    ],
],

];
