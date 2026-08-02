<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'intervenant_id',
        'edition_id',
        'department_id',
        'seminar_id',
        'subject',
        'body',
    ];

    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class);
    }

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
