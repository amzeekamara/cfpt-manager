<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'registration_id',
        'session_date',
        'status',
        'topic_id',
        'trainer_id',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
