<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'iso_code',
    ];

    // Optional: if you want to display name based on app locale
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar
            ? $this->name_ar
            : $this->name_en;
    }

    // Optional: relationship to players
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
