<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Player;
use App\Models\Academy;
use App\Models\Program;
use App\Models\ClassModel;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    const MODEL_NAME = 'Payment';

    const CATEGORIES = [
    'program'  => 'Program',
    'uniform'  => 'Uniform',
    'asset'    => 'Asset',
    'camp'     => 'Camp',
    //'class'   => 'Class',

];
protected $fillable = [
    'system_id',
    'player_id',
    'program_id',
    'branch_id',
    'academy_id',
    'payment_method_id',
    'class_count',
    'base_price',
    'discount',
    'vat_percent',
    'vat_amount',
    'total_price',
    'paid_amount',
    'remaining_amount',
    'currency',
    'original_currency',
    'exchange_rate_used',
    'status',
    'status_student',
    'note',
    'payment_date',
    'start_date',
    'end_date',
    'items',
    'category',
    'reset_number',
    'class_time_from',
    'class_time_to',
    'receipt_path',
];

    protected $casts = [
        'payment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'items' => 'array',
        'base_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    // Relationships
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'class_payment',
            'payment_id',
            'class_id'
        )
        ->withPivot('quantity')
        ->withTimestamps();
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function scopeDateRange($q, ?string $from, ?string $to)
    {
        if ($from) $q->whereDate('payment_date', '>=', $from);
        if ($to)   $q->whereDate('payment_date', '<=', $to);
        return $q;
    }

    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    public function scopeCategory($q, ?string $category)
    {
        return $category ? $q->where('category', $category) : $q;
    }

    public function scopeBranch($q, $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    public function scopeAcademy($q, $academyId)
    {
        return $academyId ? $q->where('academy_id', $academyId) : $q;
    }

    public function scopeProgram($q, $programId)
    {
        return $programId ? $q->where('program_id', $programId) : $q;
    }

    public function scopePlayer($q, $playerId)
    {
        return $playerId ? $q->where('player_id', $playerId) : $q;
    }

    public function scopePaymentMethod($q, $methodId)
    {
        return $methodId ? $q->where('payment_method_id', $methodId) : $q;
    }

    public function scopeSystem($q, $systemId)
    {
        return $systemId && Schema::hasColumn($this->getTable(), 'system_id')
            ? $q->where('system_id', $systemId)
            : $q;
    }

    public function scopeSearchReset($q, ?string $term)
    {
        return $term ? $q->where('reset_number', 'like', "%{$term}%") : $q;
    }
}
