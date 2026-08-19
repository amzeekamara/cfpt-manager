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
        'seminar_id',
        'remuneration',
        'payment_status',
    ];

    public function seminars()
    {
        return $this->belongsToMany(Seminar::class)->withPivot('remuneration', 'payment_status')->withTimestamps();
    }
}
