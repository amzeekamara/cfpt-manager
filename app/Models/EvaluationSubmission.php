<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationSubmission extends Model
{
    protected $fillable = [
        'evaluation_id',
        'registration_id',
        'student_comment',
        'file_path',
        'submitted_at',
        'trainer_feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
