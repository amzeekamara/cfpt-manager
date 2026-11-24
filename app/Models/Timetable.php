<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        'department_id',
        'seminar_id',
        'topic_id',
        'trainer_id',
        'classroom_id',
        'day_of_week',
        'start_time',
        'end_time',
        'planned_hours',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'planned_hours' => 'float',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function trainerSessions()
    {
        return $this->hasMany(TrainerSession::class);
    }
}
