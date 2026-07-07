<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id',
        'title',
        'department_name',
        'description',
        'image',
        'start_date',
        'end_date',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function intervenants()
    {
        return $this->hasMany(Intervenant::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
