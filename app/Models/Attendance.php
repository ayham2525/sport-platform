<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

     const MODEL_NAME = 'Attendance';
    protected $fillable = ['user_id', 'player_id', 'branch_id', 'scanned_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
