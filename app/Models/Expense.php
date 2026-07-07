<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'edition_id',
        'seminar_id',
        'expense_date',
    ];

    protected $casts = [
        'expense_date' => 'datetime',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
