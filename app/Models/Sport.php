<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{

   const MODEL_NAME = 'Sport';
    protected $fillable = [
        'name_en',
        'name_ar',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
