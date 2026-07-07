<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_profile_id',
        'establishment_name',
        'field_of_study',
        'degree_obtained',
        'distinction',
        'graduation_year',
        'level',
    ];

    public function candidateProfile()
    {
        return $this->belongsTo(CandidateProfile::class);
    }
}
