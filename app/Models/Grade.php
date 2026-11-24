<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'evaluation_id',
        'registration_id',
        'score',
        'appreciation',
    ];

    protected $casts = [
        'score' => 'float',
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
