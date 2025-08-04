<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_currency',
        'target_currency',
        'rate',
        'fetched_at',
    ];

    protected $casts = [
        'rate' => 'decimal:8',
        'fetched_at' => 'datetime',
    ];

    /**
     * Scope to get latest rate for a currency pair.
     */
    public function scopeLatestRate($query, $base, $target)
    {
        return $query->where('base_currency', strtoupper($base))
                     ->where('target_currency', strtoupper($target))
                     ->orderByDesc('fetched_at')
                     ->limit(1);
    }
}
