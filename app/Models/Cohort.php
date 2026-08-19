<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'total_budget',
        'start_date',
        'end_date',
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

    /**
     * Vérifie si la cohorte est à venir.
     */
    public function isUpcoming(): bool
    {
        return $this->start_date ? now()->lt($this->start_date) : true;
    }

    /**
     * Vérifie si la cohorte est en cours.
     */
    public function isOngoing(): bool
    {
        if (!$this->start_date) return false;
        $end = $this->end_date ?? $this->start_date->copy()->endOfDay();
        return now()->gte($this->start_date) && now()->lte($end);
    }

    /**
     * Vérifie si la cohorte est terminée.
     */
    public function isPast(): bool
    {
        $end = $this->end_date ?? $this->start_date;
        return $end ? now()->gt($end) : false;
    }

    /**
     * Règle métier : On ne peut postuler que pour les cohortes à venir.
     */
    public function isOpenForApplication(): bool
    {
        return $this->status === 'active' && $this->isUpcoming();
    }
}
