<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramDay extends Model
{
    protected $fillable = ['program_id', 'day'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
