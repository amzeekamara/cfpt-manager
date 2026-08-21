<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'role_visibility',
        'seminar_id',
        'is_archived',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
