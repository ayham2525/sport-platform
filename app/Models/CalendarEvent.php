<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'program_id',
        'class_id',
        'coach_id',
        'system_id',
        'branch_id',
        'academy_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'color',
    ];

    // Program relation
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Class relation
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Coach relation
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // System relation
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    // Branch relation
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Academy relation
    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }
}
