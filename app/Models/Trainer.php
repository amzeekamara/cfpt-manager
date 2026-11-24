<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Trainer extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'seminar_id',
        'remuneration',
        'payment_status',
    ];

    public function seminars()
    {
        return $this->belongsToMany(Seminar::class)->withPivot('remuneration', 'payment_status')->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(TrainerSession::class)->orderByDesc('session_date');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function getTotalHoursTaughtAttribute()
    {
        return $this->sessions()->sum('hours_taught');
    }
}
