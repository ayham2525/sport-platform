<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;
    const MODEL_NAME = 'Class';
    protected $fillable = [
        'program_id',
        'academy_id',
        'day',
        'start_time',
        'end_time',
        'location',
        'coach_name',
        'coach_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'class_model_player');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'class_id');
    }
}
