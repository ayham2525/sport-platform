<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_value', 'discount_percent',
        'valid_from', 'valid_until', 'max_uses',
        'used_count', 'is_active'
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_coupon');
    }
}
