<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    const MODEL_NAME = 'City';
    protected $fillable = [
        'name',
        'state_id',
        'is_active',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->hasOneThrough(
            Country::class,
            State::class,
            'id',
            'id',
            'state_id',
            'country_id'
        );
    }
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

}
