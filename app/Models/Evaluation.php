<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id',
        'topic_id',
        'trainer_id',
        'title',
        'instructions',
        'attachment_path',
        'type',
        'coefficient',
        'max_score',
        'evaluation_date',
        'is_published',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'coefficient' => 'decimal:2',
        'max_score' => 'decimal:2',
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

    public function submissions()
    {
        return $this->hasMany(EvaluationSubmission::class);
    }

    public function discussions()
    {
        return $this->hasMany(CourseDiscussion::class);
    }
}
