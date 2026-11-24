<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'seminar_id',
        'topic_id',
        'trainer_id',
        'title',
        'type',
        'coefficient',
        'max_score',
        'evaluation_date',
        'is_published',
    ];

    protected $casts = [
        'coefficient' => 'float',
        'max_score' => 'float',
        'evaluation_date' => 'date',
        'is_published' => 'boolean',
    ];

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

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
