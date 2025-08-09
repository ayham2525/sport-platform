<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    const MODEL_NAME = 'Branch';
    protected $fillable = [
        'name',
        'name_ar',
        'name_ur',
        'city_id',
        'system_id',
        'address',
        'phone',
        'is_active',
        'maximum_player_number'
    ];

    /**
     * Get the city that this branch belongs to.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the system that this branch belongs to.
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function academies()
    {
        return $this->hasMany(Academy::class);
    }

    // Programs under this branch
    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    // Players assigned to this branch
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    // Payments recorded under this branch
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'branch_item')
            ->withPivot(['min_value', 'max_value', 'notes', 'is_professional'])
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        $field = 'name_' . $locale;

        return $this->$field ?? $this->name;
    }
}
