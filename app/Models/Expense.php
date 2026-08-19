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
        'cohort_id',
        'seminar_id',
        'expense_date',
        'expense_category_id',
        'receipt_path',
    ];

    protected $casts = [
        'expense_date' => 'datetime',
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
