<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Academy extends Model
{
    use SoftDeletes;

    const MODEL_NAME = 'Academy';
    protected $fillable = [
        'branch_id',
        'name_en',
        'name_ar',
        'name_ur',
        'description_en',
        'description_ar',
        'description_ur',
        'contact_email',
        'phone',
        'is_active',
    ];

    /**
     * Get the branch that owns the academy.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Total payments collected in this academy
    public function totalRevenue()
    {
        return $this->payments()->sum('paid_amount');
    }

    // Number of players currently assigned
    public function playersCount()
    {
        return $this->players()->count();
    }
}
