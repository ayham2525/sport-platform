<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'player_id', 'branch', 'scanned_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }
}
