<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerSession extends Model
{
    protected $fillable = ['trainer_id', 'seminar_id', 'topic_id', 'session_date', 'hours_taught'];

    protected $casts = [
        'session_date' => 'date',
        'hours_taught' => 'integer',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
