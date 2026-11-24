<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['seminar_id', 'name', 'is_active'];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
