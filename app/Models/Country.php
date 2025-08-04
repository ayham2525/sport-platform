<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    const MODEL_NAME = 'Country';
    protected $fillable = [
        'name',
        'name_native',
        'iso2',
        'iso3',
        'phone_code',
        'currency',
        'currency_symbol',
        'flag',
        'is_active',
    ];

    // Relations
    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }
}
