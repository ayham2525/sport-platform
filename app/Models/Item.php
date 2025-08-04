<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    const MODEL_NAME = 'Item';

    protected $fillable = [
        'system_id',
        'name_en',
        'name_ar',
        'price',
        'currency_id',
        'active',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_item')
            ->withPivot(['min_value', 'max_value', 'notes', 'is_professional'])
            ->withTimestamps();
    }
}
