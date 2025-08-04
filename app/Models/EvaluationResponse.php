<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_evaluation_id',
        'criteria_id',
        'value',
    ];

    public function coachEvaluation()
    {
        return $this->belongsTo(CoachEvaluation::class);
    }

    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'criteria_id');
    }
}
