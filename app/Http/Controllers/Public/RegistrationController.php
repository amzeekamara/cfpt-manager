<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use App\Models\User;
use App\Models\Role;
use App\Models\CandidateProfile;
use App\Models\AcademicHistory;
use App\Models\CandidateDocument;
use App\Notifications\CandidateStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function show(Edition $edition)
    {
        return view('public.registration.form', compact('edition'));
    }

    public function store(Request $request, Edition $edition)
    {
        // Extensive Validation
        $request->validate([
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
            
            // Documents
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'official_intro' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'engagement_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'diploma' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create User
            $candidateRole = Role::where('name', 'Candidate')->first();
            $tempPassword = Str::random(10);
            
            $user = User::create([
                'name' => $request->first_names . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($tempPassword),
                'role_id' => $candidateRole ? $candidateRole->id : null,
            ]);

            // 2. Save Photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('candidates/photos', 'public');
            }

            // 3. Create Profile
            $profile = CandidateProfile::create([
                'user_id' => $user->id,
                'first_names' => $request->first_names,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'residence_country' => $request->residence_country,
                'photo_path' => $photoPath,
                'family_status' => $request->family_status,
                'religion' => $request->religion,
                'address' => $request->address,
                'phone' => $request->phone,
                'emergency_contact_name' => $request->emergency_name,
                'emergency_contact_phone' => $request->emergency_phone,
                'emergency_contact_email' => $request->emergency_email,
                'emergency_contact_relation' => $request->emergency_relation,
                'highest_degree' => $request->highest_degree,
                'current_profession' => $request->current_profession,
                'current_employer' => $request->current_employer,
                'previous_employer' => $request->previous_employer,
                'job_description' => $request->job_description,
                'training_utility' => $request->training_utility,
                'health_record_obs' => $request->health_record_obs,
                'health_questionnaire_json' => $request->health_q,
                'insurance_details' => $request->insurance_details,
                'insurance_premium' => $request->insurance_premium,
                'accommodation_preference' => $request->accommodation_preference,
            ]);

            // 4. Save Academic History
            if ($request->has('acad_establishment')) {
                foreach ($request->acad_establishment as $index => $establishment) {
                    if ($establishment) {
                        AcademicHistory::create([
                            'candidate_profile_id' => $profile->id,
                            'establishment_name' => $establishment,
                            'field_of_study' => $request->acad_field[$index] ?? 'N/A',
                            'degree_obtained' => $request->acad_degree[$index] ?? 'N/A',
                            'distinction' => $request->acad_distinction[$index] ?? null,
                            'graduation_year' => $request->acad_year[$index] ?? date('Y'),
                            'level' => ($index === 0) ? 'secondary' : 'university', // Simple logic for demo
                        ]);
                    }
                }
            }

            // 5. Save Documents
            $docTypes = ['cv', 'official_intro', 'engagement_letter', 'diploma'];
            foreach ($docTypes as $type) {
                if ($request->hasFile($type)) {
                    $this->saveDocument($user->id, $type, $request->file($type));
                }
            }

            // 6. Create Enrollment for the Edition
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'seminar_id' => $edition->seminars()->first()?->id,
                'status' => 'pending',
            ]);

            DB::commit();

            // Notification asynchrone (Queue)
            $user->notify(new CandidateStatusNotification($enrollment));

            \Log::info("New Candidate Registered: {$user->email}. Temp Password: {$tempPassword}");

            return redirect()->route('welcome')->with('success', 'Votre candidature a été soumise avec succès ! Un email de confirmation vous sera envoyé.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Registration Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    private function saveDocument($userId, $type, $file)
    {
        $path = $file->store('candidates/documents', 'public');
        CandidateDocument::create([
            'user_id' => $userId,
            'document_type' => $type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);
    }
}
