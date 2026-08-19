<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'candidate_profile_id',
        'establishment_name',
        'field_of_study',
        'degree_obtained',
        'distinction',
        'graduation_year',
        'level',
        'resource_path',
    ];

    public function candidateProfile()
    {
        return $this->belongsTo(CandidateProfile::class);
    }
}
