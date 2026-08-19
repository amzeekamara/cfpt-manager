<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'grade',
        'job_function',
        'first_names',
        'last_name',
        'birth_date',
        'birth_place',
        'gender',
        'nationality',
        'residence_country',
        'photo_path',
        'family_status',
        'religion',
        'address',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
        'emergency_contact_relation',
        'highest_degree',
        'current_profession',
        'current_employer',
        'previous_employer',
        'job_description',
        'training_utility',
        'health_record_obs',
        'health_questionnaire_json',
        'insurance_details',
        'insurance_premium',
        'accommodation_preference',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'health_questionnaire_json' => 'array',
        'insurance_premium' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicHistories()
    {
        return $this->hasMany(Education::class);
    }
}
