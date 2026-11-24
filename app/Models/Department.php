<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'head_user_id', 'description', 'is_active'];

    public function seminars()
    {
        return $this->belongsToMany(Seminar::class);
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
