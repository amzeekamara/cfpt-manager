<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerSession extends Model
{
    protected $fillable = ['trainer_id', 'seminar_id', 'topic_id', 'session_date', 'hours_taught'];
}
