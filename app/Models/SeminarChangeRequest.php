<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_id',
        'new_seminar_id',
        'reason',
        'status',
        'admin_feedback',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function newSeminar()
    {
        return $this->belongsTo(Seminar::class, 'new_seminar_id');
    }
}
