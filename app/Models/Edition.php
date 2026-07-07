<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'total_budget',
        'start_date',
        'end_date',
        'themes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_budget' => 'decimal:2',
    ];

    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
