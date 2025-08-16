<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformRequest extends Model
{
    const MODEL_NAME = 'Uniform Request';

    // Main workflow status (matches table enum)
    public const STATUS_OPTIONS = [
        'requested' => 'Requested',
        'approved'  => 'Approved',
        'ordered'   => 'Ordered',
        'delivered' => 'Delivered',
        'rejected'  => 'Rejected',
        'cancelled' => 'Cancelled',
        'returned'  => 'Returned',
    ];

    // Branch-level status (matches migration enum)
    public const BRANCH_STATUS_OPTIONS = [
        'requested' => 'Requested',
        'approved'  => 'Approved',
        'rejected'  => 'Rejected',
        'cancelled' => 'Cancelled',
        'non'       => 'Non',
        'received'  => 'Received',
        'ordered'   => 'Ordered',
    ];

    // Office-level status (matches migration enum)
    public const OFFICE_STATUS_OPTIONS = [
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'delivered'  => 'Delivered',
        'received'   => 'Received',
        'non'        => 'Non',
    ];

    protected $fillable = [
        'player_id',
        'user_id',
        'item_id',
        'system_id',
        'branch_id',
        'currency_id',
        'size',
        'color',
        'quantity',
        'amount',
        'status',
        'branch_status',   // NEW
        'office_status',   // NEW
        'payment_method',  // NEW
        'request_date',
        'approved_at',
        'ordered_at',
        'delivered_at',
        'notes',
        'admin_remarks',
    ];

    protected $casts = [
        'quantity'      => 'integer',
        'amount'        => 'decimal:2',
        'request_date'  => 'date',
        'approved_at'   => 'datetime',
        'ordered_at'    => 'datetime',
        'delivered_at'  => 'datetime',
    ];

    protected $dates = ['approved_at', 'ordered_at', 'delivered_at', 'request_date'];

    // Relationships
    public function player()   { return $this->belongsTo(Player::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function item()     { return $this->belongsTo(Item::class); }
    public function system()   { return $this->belongsTo(System::class); }
    public function branch()   { return $this->belongsTo(Branch::class); }
    public function currency() { return $this->belongsTo(Currency::class); }

    // Optional: handy accessors for labels (useful in Blade)
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getBranchStatusLabelAttribute(): string
    {
        return self::BRANCH_STATUS_OPTIONS[$this->branch_status] ?? ucfirst((string) $this->branch_status);
    }

    public function getOfficeStatusLabelAttribute(): string
    {
        return self::OFFICE_STATUS_OPTIONS[$this->office_status] ?? ucfirst((string) $this->office_status);
    }

    // Optional scopes for filtering
    public function scopeBranchStatus($q, $status) { return $q->where('branch_status', $status); }
    public function scopeOfficeStatus($q, $status) { return $q->where('office_status', $status); }
    public function scopeMainStatus($q, $status)   { return $q->where('status', $status); }
}
