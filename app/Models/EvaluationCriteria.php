<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationCriteria extends Model
{
    use HasFactory;
    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'evaluation_id',
        'label',
        'input_type',
        'weight',
        'order',
        'required',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    // Relationships
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
