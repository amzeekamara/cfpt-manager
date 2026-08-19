<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'trainer_id',
        'user_id',
        'cohort_id',
        'department_id',
        'seminar_id',
        'subject',
        'body',
        'attachment_path',
        'target_roles',
        'target_seminars',
        'target_departments',
        'target_topics',
    ];

    protected function casts(): array
    {
        return [
            'target_roles' => 'array',
            'target_seminars' => 'array',
            'target_departments' => 'array',
            'target_topics' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function readByUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('read_at')->withTimestamps();
    }
}
