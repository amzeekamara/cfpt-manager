<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Choix du Séminaire & Thème
            'seminar_id' => 'required|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',

            // Identity
            'first_names' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
            'gender' => 'required|in:M,F',
            'nationality' => 'required|string',
            'residence_country' => 'required|string',
            'family_status' => 'required|in:single,married,divorced,widowed',
            'religion' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            
            // Contact
            'phone' => 'required|string',
            'address' => 'required|string',
            'emergency_name' => 'required|string',
            'emergency_phone' => 'required|string',
            'emergency_email' => 'nullable|email',
            'emergency_relation' => 'required|string',
            
            // Background & Pro
            'highest_degree' => 'required|string',
            'current_profession' => 'required|string',
            'current_employer' => 'required|string',
            'previous_employer' => 'nullable|string',
            'job_description' => 'nullable|string',
            'training_utility' => 'required|string',
            
            // Academic History (Arrays)
            'acad_establishment.*' => 'nullable|string',
            'acad_field.*' => 'nullable|string',
            'acad_degree.*' => 'nullable|string',
            'acad_year.*' => 'nullable|numeric',
            
            // Health & Logistics
            'health_record_obs' => 'nullable|string',
            'health_q' => 'nullable|array',
            'insurance_details' => 'nullable|string',
            'insurance_premium' => 'nullable|numeric',
            'accommodation_preference' => 'required|in:hotel,allocation,none',
            
            // Resources
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'official_intro' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'engagement_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'diploma' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ];
    }
}
