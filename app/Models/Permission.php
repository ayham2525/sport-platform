<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const MODEL_NAME = 'Permission';
    protected $fillable = [
        'action',
        'role_id',
        'model_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function model()
    {
        return $this->belongsTo(ModelEntity::class, 'model_id');
    }
}
