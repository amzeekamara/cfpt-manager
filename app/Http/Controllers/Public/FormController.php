<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use App\Models\FormSubmission;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\Registration;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\NewFormSubmissionNotification;
use App\Http\Requests\PublicFormSubmissionRequest;

class FormController extends Controller
{
    /**
     * Display the public shareable form
     */
    public function show(string $token)
    {
        $form = FormTemplate::where('share_token', $token)
            ->where('is_active', true)
            ->with('seminar')
            ->firstOrFail();

        // Resolve data_source options for select/radio/checkbox fields
        $resolvedOptions = [];
        foreach ($form->fields_json ?? [] as $field) {
            $ds = $field['data_source'] ?? '';
            if (!$ds) continue;

            if ($ds === 'seminars') {
                $resolvedOptions[$field['key']] = \App\Models\Seminar::where('status', '!=', 'completed')
                    ->pluck('title')->toArray();
            } elseif ($ds === 'cohorts') {
                $resolvedOptions[$field['key']] = \App\Models\Cohort::orderByDesc('start_date')
                    ->pluck('title')->toArray();
            } elseif ($ds === 'countries') {
                $resolvedOptions[$field['key']] = \App\Helpers\CountryHelper::all();
            }
        }

        return view('public.form', compact('form', 'resolvedOptions'));
    }

    /**
     * Handle submission of the public form (Option B: creates User + Profile + Registration)
     */
    public function store(PublicFormSubmissionRequest $request, string $token)
    {
        $form = FormTemplate::where('share_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validated();
        $answers = $validated['answers'] ?? [];

        // Ne pas stocker les objets de fichiers dans le JSON
        $answersForJson = $answers;
        if (isset($answersForJson['photo'])) {
            unset($answersForJson['photo']);
        }
        if (isset($answersForJson['diplomas'])) {
            foreach ($answersForJson['diplomas'] as $k => $d) {
                unset($answersForJson['diplomas'][$k]['file']);
            }
        }

        // Save raw submission
        $submission = FormSubmission::create([
            'form_template_id' => $form->id,
            'answers_json'     => $answersForJson,
            'respondent_ip'    => $request->ip(),
        ]);

        // === Option B: Traitement sécurisé du profil et de l'inscription ===
        $email     = $answers['email'] ?? null;
        $seminarId = $form->seminar_id;

        if ($email && $seminarId) {
            $user = User::where('email', $email)->first();

            // 1. Si l'utilisateur n'existe pas encore : initialisation sécurisée
            if (!$user) {
                $candidateRole = Role::where('name', 'Candidat')->first();
                $fullName = trim(($answers['prenom'] ?? '') . ' ' . ($answers['nom'] ?? '')) ?: 'Participant';

                $user = User::create([
                    'name'     => $fullName,
                    'email'    => $email,
                    'password' => Hash::make(Str::random(16)),
                    'role_id'  => $candidateRole?->id,
                ]);

                $photoPath = null;
                if ($request->hasFile('answers.photo')) {
                    $photoPath = $request->file('answers.photo')->store('avatars', 'public');
                }

                $profile = CandidateProfile::create([
                    'user_id'       => $user->id,
                    'first_names'   => $answers['prenom'] ?? '',
                    'last_name'     => $answers['nom'] ?? '',
                    'matricule'     => $answers['matricule'] ?? null,
                    'photo_path'    => $photoPath,
                    'gender'        => isset($answers['sexe']) ? ($answers['sexe'] === 'Masculin' ? 'M' : ($answers['sexe'] === 'Féminin' ? 'F' : 'M')) : 'M',
                    'birth_date'    => !empty($answers['date_naissance']) ? $answers['date_naissance'] : null,
                    'birth_place'   => $answers['lieu_naissance'] ?? null,
                    'nationality'   => $answers['nationalite'] ?? null,
                    'family_status' => isset($answers['situation_fam']) ? 
                        (['Célibataire' => 'single', 'Marié(e)' => 'married', 'Divorcé(e)' => 'divorced', 'Veuf/Veuve' => 'widowed'][$answers['situation_fam']] ?? 'single') : 'single',
                    'religion'      => $answers['religion'] ?? null,
                    'phone'         => $answers['whatsapp'] ?? null,
                    'residence_country' => $answers['pays'] ?? null,
                    'address'       => $answers['adresse'] ?? null,
                    'emergency_contact_name' => $answers['urgence_nom'] ?? null,
                    'emergency_contact_phone' => $answers['urgence'] ?? null,
                    'current_profession' => $answers['profession'] ?? null,
                    'current_employer'   => $answers['employeur'] ?? null,
                    'grade'              => $answers['grade'] ?? null,
                    'job_function'       => $answers['fonction'] ?? null,
                    'previous_employer'  => $answers['emp_precedent'] ?? null,
                    'job_description'    => $answers['desc_travail'] ?? null,
                    'accommodation_preference' => $answers['hebergement'] ?? null,
                    'insurance_details'        => $answers['assurance'] ?? null,
                    'health_questionnaire_json' => [
                        'chronic'   => isset($answers['sante_chronique']) ? ($answers['sante_chronique'] === 'Oui' ? 'yes' : 'no') : null,
                        'treatment' => isset($answers['sante_traitement']) ? ($answers['sante_traitement'] === 'Oui' ? 'yes' : 'no') : null,
                    ],
                    'health_record_obs' => $answers['sante_obs'] ?? null,
                ]);

                // Historique académique initial
                $diplomas = $answers['diplomas'] ?? [];
                foreach ($diplomas as $index => $diploma) {
                    if (!empty($diploma['nom']) || !empty($diploma['etab'])) {
                        $path = null;
                        if ($request->hasFile("answers.diplomas.{$index}.file")) {
                            $path = $request->file("answers.diplomas.{$index}.file")->store('diplomas', 'public');
                        }
                        $profile->academicHistories()->create([
                            'degree_obtained'    => $diploma['nom'] ?? 'Non spécifié',
                            'establishment_name' => $diploma['etab'] ?? 'Non spécifié',
                            'graduation_year'    => $diploma['annee'] ?? date('Y'),
                            'resource_path'      => $path,
                            'field_of_study'     => 'Général',
                            'level'              => 'secondary',
                        ]);
                    }
                }

                $registration = Registration::firstOrCreate(
                    ['user_id' => $user->id, 'seminar_id' => $seminarId],
                    ['status' => 'pending']
                );

                $submission->update(['registration_id' => $registration->id]);

            } elseif (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $user->id) {
                // 2. Si l'utilisateur est dûment authentifié comme propriétaire du compte
                $profile = CandidateProfile::firstOrCreate(['user_id' => $user->id]);

                $photoPath = $profile->photo_path;
                if ($request->hasFile('answers.photo')) {
                    $photoPath = $request->file('answers.photo')->store('avatars', 'public');
                }

                $profile->update([
                    'first_names'   => $answers['prenom'] ?? $profile->first_names ?? '',
                    'last_name'     => $answers['nom'] ?? $profile->last_name ?? '',
                    'matricule'     => $answers['matricule'] ?? $profile->matricule,
                    'photo_path'    => $photoPath,
                    'gender'        => isset($answers['sexe']) ? ($answers['sexe'] === 'Masculin' ? 'M' : ($answers['sexe'] === 'Féminin' ? 'F' : $profile->gender)) : $profile->gender,
                    'birth_date'    => !empty($answers['date_naissance']) ? $answers['date_naissance'] : $profile->birth_date,
                    'birth_place'   => $answers['lieu_naissance'] ?? $profile->birth_place,
                    'nationality'   => $answers['nationalite'] ?? $profile->nationality,
                    'phone'         => $answers['whatsapp'] ?? $profile->phone,
                    'address'       => $answers['adresse'] ?? $profile->address,
                ]);

                $registration = Registration::firstOrCreate(
                    ['user_id' => $user->id, 'seminar_id' => $seminarId],
                    ['status' => 'pending']
                );

                $submission->update(['registration_id' => $registration->id]);

            } else {
                // 3. Utilisateur existant mais formulaire public non authentifié :
                // La soumission $submission est enregistrée de manière autonome sans écraser le profil existant.
                \Log::info("Form submitted for existing email {$email} without authentication. Profile overwrite prevented.");
            }
        }

        // Notify Admins
        $admins = User::whereHas('role', function($q) {
            $q->where('name', 'Administrateur');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewFormSubmissionNotification($submission));
        }

        return view('public.form_success', compact('form'));
    }
}
