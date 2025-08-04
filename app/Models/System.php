<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = ['name', 'description'];

    const MODEL_NAME = 'System';

    /**
     * Get the users that belong to the system.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the branches that belong to the system.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function models()
    {
        return $this->hasMany(ModelEntity::class);
    }

    /**
     * All evaluations related to this system
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }
}
