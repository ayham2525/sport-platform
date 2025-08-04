<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    const MODEL_NAME = 'Role';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'system_id'
    ];

    /**
     * If each user has one role
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

}
