<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelEntity extends Model
{
    use HasFactory;

    protected $table = 'models';
    const MODEL_NAME = 'Model';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'system_id',
        'only_admin',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'model_id');
    }
}
