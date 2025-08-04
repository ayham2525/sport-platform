<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'serial_number',
        'type',
        'issued_at',
        'expired_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
