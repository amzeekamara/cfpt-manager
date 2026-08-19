<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $registration = $this->route('registration');
        $userId = $registration ? $registration->user_id : null;

        $rules = [
            // Identity
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($userId ? ',' . $userId : ''),
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string',
            'gender' => 'nullable|in:M,F',
            'family_status' => 'nullable|string',
            'religion' => 'nullable|string',
            'nationality' => 'nullable|string',
            'residence_country' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            
            // Professional
            'current_profession' => 'nullable|string',
            'current_employer' => 'nullable|string',
            'previous_employer' => 'nullable|string',
            'job_description' => 'nullable|string',
            'training_utility' => 'nullable|string',
            
            // Emergency
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_relation' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'emergency_contact_email' => 'nullable|email',

            // Health & Logistics
            'health_record_obs' => 'nullable|string',
            'health_chronic' => 'nullable|in:yes,no',
            'health_treatment' => 'nullable|in:yes,no',
            'accommodation_preference' => 'nullable|string',
            'insurance_details' => 'nullable|string',
            'insurance_premium' => 'nullable|numeric',
            
            // New Fields
            'matricule' => 'nullable|string',
            'grade' => 'nullable|string',
            'job_function' => 'nullable|string',

            // Seminar & Status
            'seminar_id' => 'required|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',
            'status' => 'required|in:pending,approved,rejected,waiting_list',

            // Photo & Documents
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'document_diploma' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:10240',
            'document_motivation' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:10240',
            'document_cv' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:10240',
            'document_passport' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'other_documents' => 'nullable|array',
            'other_documents.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:10240',
            'other_doc_titles' => 'nullable|array',
            'other_doc_titles.*' => 'nullable|string|max:255',

            // Academic History (Arrays)
            'acad_establishment.*' => 'nullable|string',
            'acad_field.*' => 'nullable|string',
            'acad_degree.*' => 'nullable|string',
            'acad_year.*' => 'nullable|string',
            'acad_distinction.*' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }
}
