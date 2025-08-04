<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BranchItem extends Pivot
{
    // Optional: define table name if different
    protected $table = 'branch_item';
    const MODEL_NAME = 'Branch Items';

    protected $fillable = [
        'branch_id',
        'item_id',
        'min_value',
        'max_value',
        'is_professional',
        'notes',
    ];
}
