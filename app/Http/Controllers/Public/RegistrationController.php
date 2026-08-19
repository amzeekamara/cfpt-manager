<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Topic;
use App\Models\User;
use App\Models\Role;
use App\Models\Registration;
use App\Models\CandidateProfile;
use App\Models\Education;
use App\Models\ApplicationDocument;
use App\Notifications\CandidateStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\PublicRegistrationRequest;

class RegistrationController extends Controller
{
    /**
     * Affiche directement le formulaire unique de candidature.
     */
    public function create(Request $request, ?Cohort $cohort = null)
    {
        // Récupération de tous les séminaires avec leurs thèmes et départements
        $seminars = Seminar::with(['topics', 'cohort', 'departments'])
            ->orderBy('start_date', 'asc')
            ->get();

        // Sélection par défaut via paramètre URL ?seminar_id=XX ou via cohort
        $selectedSeminarId = $request->query('seminar_id');
        if (!$selectedSeminarId && $cohort) {
            $selectedSeminarId = $cohort->seminars()->first()?->id;
        }

        // Si non défini, présélectionner le premier séminaire ouvert aux candidatures
        if (!$selectedSeminarId) {
            $firstOpen = $seminars->first(fn($s) => $s->isOpenForApplication());
            $selectedSeminarId = $firstOpen?->id;
        }

        return view('public.registration.form', compact('seminars', 'cohort', 'selectedSeminarId'));
    }

    /**
     * Traite et enregistre le dossier de candidature.
     */
    public function store(PublicRegistrationRequest $request)
    {
        $validated = $request->validated();

        $seminar = Seminar::findOrFail($request->seminar_id);

        // Vérification de la règle d'éligibilité : le séminaire doit être à venir
        if (!$seminar->isOpenForApplication()) {
            return back()->withInput()->with('error', "Impossible de soumettre votre candidature : les inscriptions pour le séminaire « {$seminar->title} » sont closes car la session est déjà en cours ou terminée.");
        }

        try {
            DB::beginTransaction();

            // 1. Création du compte utilisateur
            $candidateRole = Role::where('name', 'Candidat')->first();
            $tempPassword = Str::random(10);
            
            $user = User::create([
                'name' => $request->first_names . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($tempPassword),
                'role_id' => $candidateRole ? $candidateRole->id : null,
            ]);

            // 2. Sauvegarde de la photo d'identité
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('candidates/photos', 'public');
            }

            // 3. Création du profil candidat
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

            // 4. Historique académique
            if ($request->has('acad_establishment')) {
                foreach ($request->acad_establishment as $index => $establishment) {
                    if ($establishment) {
                        Education::create([
                            'candidate_profile_id' => $profile->id,
                            'establishment_name' => $establishment,
                            'field_of_study' => $request->acad_field[$index] ?? 'N/A',
                            'degree_obtained' => $request->acad_degree[$index] ?? 'N/A',
                            'distinction' => $request->acad_distinction[$index] ?? null,
                            'graduation_year' => $request->acad_year[$index] ?? date('Y'),
                            'level' => ($index === 0) ? 'secondary' : 'university',
                        ]);
                    }
                }
            }

            // 5. Sauvegarde des documents justificatifs
            $docTypes = ['cv', 'official_intro', 'engagement_letter', 'diploma'];
            foreach ($docTypes as $type) {
                if ($request->hasFile($type)) {
                    $this->saveResource($user->id, $type, $request->file($type));
                }
            }

            // 6. Création de l'inscription rattachée au séminaire et au thème choisi
            $registration = Registration::create([
                'user_id' => $user->id,
                'seminar_id' => $seminar->id,
                'topic_id' => $request->topic_id ?: null,
                'status' => 'pending',
            ]);

            DB::commit();

            // Génération d'un jeton d'initialisation de mot de passe sécurisé à durée limitée
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            $setupUrl = route('password.setup', ['token' => $token, 'email' => $user->email]);

            // Notification asynchrone au candidat avec lien d'activation
            $user->notify(new CandidateStatusNotification($registration, $setupUrl));

            // Notification instantanée aux Administrateurs et Coordinateurs
            $admins = User::whereHas('role', fn($q) => $q->whereIn('name', ['Administrateur', 'Coordinateur', 'Admin']))->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send(
                    $admins,
                    new \App\Notifications\StaffActionNotification(
                        $user,
                        'Nouvelle Candidature Externe',
                        "Le candidat {$user->name} ({$user->email}) a postulé au séminaire « {$seminar->title} ». Dossier complet à examiner."
                    )
                );
            }

            \App\Helpers\ActivityLogger::log(
                'Nouvelle Candidature',
                "Candidature reçue pour {$user->name} ({$user->email}) sur le séminaire '{$seminar->title}'."
            );

            \Log::info("New Candidate Registered: {$user->email}");

            return redirect()->route('welcome')->with('success', "Votre candidature au séminaire « {$seminar->title} » a été soumise avec succès ! Un email contenant votre lien d'activation vous a été envoyé.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Registration Error: " . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null
            ]);
            return back()->withInput()->with('error', 'Une erreur inattendue est survenue lors de la soumission de votre dossier. Veuillez vérifier vos informations ou contacter le secrétariat du CFPT.');
        }
    }

    private function saveResource($userId, $type, $file)
    {
        $path = $file->store('candidates/resources', 'public');
        ApplicationDocument::create([
            'user_id' => $userId,
            'resource_type' => $type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);
    }
}
