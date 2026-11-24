<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseChapter extends Model
{
    protected $fillable = [
        'seminar_id',
        'topic_id',
        'trainer_id',
        'title',
        'content',
        'video_url',
        'attachment_path',
        'duration_minutes',
        'order_index',
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
}
