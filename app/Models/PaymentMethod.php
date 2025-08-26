<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

      const MODEL_NAME = 'Payment Method';

    protected $fillable = [
        'name',
        'name_ar',
        'name_ur',
        'description',
        'is_active',
    ];

    protected $casts = [
    'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
