<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    const MODEL_NAME = 'Program';

    protected $fillable = [
        'system_id', 'branch_id', 'academy_id',
        'name_en', 'name_ar', 'name_ur',
        'class_count', 'price', 'vat',
        'currency', 'is_offer_active', 'offer_price', 'is_active'
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }

    public function days()
    {
        return $this->hasMany(ProgramDay::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'program_coupon');
    }
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function getCountryIdAttribute()
    {
        return optional($this->branch->city->state->country)->id;
    }

    public function getStateIdAttribute()
    {
        return optional($this->branch->city->state)->id;
    }

    public function getCityIdAttribute()
    {
        return optional($this->branch->city)->id;
    }

     public function players()
    {
        return $this->belongsToMany(Player::class, 'player_program');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }



}
