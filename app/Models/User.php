<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const MODEL_NAME = 'User';

    protected $fillable = [
        'name',
        'email',
        'profile_image',
        'password',
        'language',
        'role',
        'system_id',
        'branch_id',
        'academy_id', // stored as JSON
        'email_verified_at',
        'remember_token',
        'card_serial_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'system_id' => 'integer',
            'branch_id' => 'integer',
            'academy_id' => 'array', // cast JSON to PHP array
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship to System.
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    /**
     * Relationship to Branch.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get academy models related to the user (if academy_id is a JSON array of IDs).
     */
    public function academies()
    {
        return Academy::whereIn('id', $this->academy_id ?? [])->get();
    }

    public function getAcademiesAttribute()
    {
        $ids = is_array($this->academy_id)
            ? $this->academy_id
            : json_decode($this->academy_id, true);

        return Academy::whereIn('id', $ids ?? [])->get();
    }

     /**
     * Coach evaluations received by this user (when user is a coach)
     */
    public function receivedCoachEvaluations()
    {
        return $this->hasMany(CoachEvaluation::class, 'coach_id');
    }

    /**
     * Coach evaluations submitted by this user (when user is evaluator)
     */
    public function submittedCoachEvaluations()
    {
        return $this->hasMany(CoachEvaluation::class, 'evaluator_id');
    }

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function classesAsCoach()
    {
        return $this->hasMany(ClassModel::class, 'coach_id');
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'coach_id');
    }

    /**
     * Relationship to Role via slug.
     */
    public function roleRelation()
    {
        return $this->hasOne(Role::class, 'slug', 'role')->where(function ($query) {
            $query->where('system_id', $this->system_id);
        });
    }

    public function scopeCoach($query)
    {
        return $query->where('role', 'coach');
    }




}
