<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_template_id',
        'answers_json',
        'respondent_ip',
        'enrollment_id',
        'submitted_at',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
