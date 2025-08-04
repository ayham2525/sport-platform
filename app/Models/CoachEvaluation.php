<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'coach_id',
        'evaluator_type',
        'evaluator_id',
        'submitted_at',
    ];

 
    protected $casts = [
    'submitted_at' => 'datetime',
];

    // Relationships
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class);
    }
}
