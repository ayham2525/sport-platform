<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // ──────────────── Relationships ────────────────

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function criteria()
    {
        return $this->hasMany(EvaluationCriteria::class);
    }

    public function coachEvaluations()
    {
        return $this->hasMany(CoachEvaluation::class);
    }

    // ──────────────── Scopes & Helpers ────────────────

    public function isActivePeriod()
    {
        if ($this->type === 'general') return true;

        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    public function isStudentType()
    {
        return $this->type === 'student';
    }

    public function isInternalType()
    {
        return $this->type === 'internal';
    }

    public function isGeneralType()
    {
        return $this->type === 'general';
    }
}
