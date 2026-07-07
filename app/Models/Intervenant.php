<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seminar_id',
        'remuneration',
        'payment_status',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
