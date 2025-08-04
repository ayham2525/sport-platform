<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformRequest extends Model
{


     const MODEL_NAME = 'Uniform Request';
    public const STATUS_OPTIONS = [
        'requested' => 'Requested',
        'ordered'   => 'Ordered',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
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
        'request_date',
        'approved_at',
        'ordered_at',
        'delivered_at',
        'notes',
        'admin_remarks',
    ];

    protected $dates = ['approved_at', 'ordered_at', 'delivered_at', 'request_date'];

    // Relationships
    public function player()    { return $this->belongsTo(Player::class); }
    public function user()      { return $this->belongsTo(User::class); }
    public function item()      { return $this->belongsTo(Item::class); }
    public function system()    { return $this->belongsTo(System::class); }
    public function branch()    { return $this->belongsTo(Branch::class); }
    public function currency()  { return $this->belongsTo(Currency::class); }
}
