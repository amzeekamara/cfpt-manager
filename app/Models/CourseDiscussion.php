<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDiscussion extends Model
{
    protected $fillable = [
        'seminar_id',
        'topic_id',
        'evaluation_id',
        'user_id',
        'trainer_id',
        'author_name',
        'author_role',
        'body',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
