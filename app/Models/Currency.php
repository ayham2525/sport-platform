<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    const MODEL_NAME = 'Currency';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope a query to only include active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get full formatted label: "USD ($)"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->code}" . ($this->symbol ? " ({$this->symbol})" : '');
    }

    /**
     * Get all items that use this currency.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
