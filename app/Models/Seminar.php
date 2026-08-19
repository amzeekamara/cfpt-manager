<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'cohort_id',
        'title',
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

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class)->withPivot('remuneration', 'payment_status')->withTimestamps();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * Vérifie si le séminaire est à venir (candidatures ouvertes).
     */
    public function isUpcoming(): bool
    {
        return $this->start_date ? now()->lt($this->start_date) : true;
    }

    /**
     * Vérifie si le séminaire est en cours.
     */
    public function isOngoing(): bool
    {
        if (!$this->start_date) return false;
        $end = $this->end_date ?? $this->start_date->copy()->endOfDay();
        return now()->gte($this->start_date) && now()->lte($end);
    }

    /**
     * Vérifie si le séminaire est terminé.
     */
    public function isPast(): bool
    {
        $end = $this->end_date ?? $this->start_date;
        return $end ? now()->gt($end) : false;
    }

    /**
     * Règle métier : On ne peut postuler que pour les séminaires à venir.
     */
    public function isOpenForApplication(): bool
    {
        return $this->status === 'active' && $this->isUpcoming();
    }
}
