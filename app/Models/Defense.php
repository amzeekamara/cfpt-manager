<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Defense extends Model
{
    protected $fillable = ['enrollment_id', 'defense_date', 'grade', 'jury_comments', 'status'];

    protected $casts = [
        'defense_date' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
