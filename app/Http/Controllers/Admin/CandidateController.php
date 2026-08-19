<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Role;
use App\Models\CandidateProfile;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use App\Notifications\CandidateStatusNotification;
use App\Http\Requests\Admin\CandidateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $cohortId = $request->get('cohort_id');
        $selectedCohort = null;
        if ($cohortId) {
            $selectedCohort = \App\Models\Cohort::find($cohortId);
        }

        if (!$selectedCohort && !$request->has('cohort_id')) {
            $selectedCohort = \App\Models\Cohort::where('status', 'active')->latest()->first() 
                ?? \App\Models\Cohort::latest()->first();
            $cohortId = $selectedCohort ? $selectedCohort->id : null;
        }

        $query = Registration::with(['user.candidateProfile', 'user.candidateResources', 'seminar.cohort', 'topic']);

        // Cohort Filtering
        if ($cohortId) {
            $query->whereHas('seminar', function($q) use ($cohortId) {
                $q->where('cohort_id', $cohortId);
            });
        }

        // Other Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seminar_id')) {
            $query->where('seminar_id', $request->seminar_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Stats filtered by cohort if provided
        $statsQuery = Registration::query();
        if ($cohortId) {
            $statsQuery->whereHas('seminar', function($q) use ($cohortId) {
                $q->where('cohort_id', $cohortId);
            });
        }

        // Optimisation: Récupération des comptages groupés par statut pour éviter 4 requêtes distinctes
        $registrationCounts = (clone $statsQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $total = array_sum($registrationCounts);

        $stats = [
            'total' => $total,
            'pending' => ($registrationCounts['pending'] ?? 0) + ($registrationCounts['waiting_list'] ?? 0),
            'approved' => $registrationCounts['approved'] ?? 0,
            'rejected' => $registrationCounts['rejected'] ?? 0,
        ];

        $cohorts = \App\Models\Cohort::orderBy('created_at', 'desc')->get();
        $seminars = Seminar::when($cohortId, function($q) use ($cohortId) {
            return $q->where('cohort_id', $cohortId);
        })->where('status', '!=', 'completed')->get();
        
        $topics = \App\Models\Topic::when($request->seminar_id, function($q) use ($request) {
            return $q->where('seminar_id', $request->seminar_id);
        })->get();

        return view('admin.candidates', compact('candidates', 'stats', 'seminars', 'cohorts', 'selectedCohort', 'topics'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['user.candidateProfile.academicHistories', 'user.candidateResources', 'seminar.cohort']);
        return view('admin.candidates.show', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,waiting_list',
        ]);

        $registration->update(['status' => $validated['status']]);

        // Notification asynchrone (Queue)
        $registration->user->notify(new CandidateStatusNotification($registration));

        return back()->with('success', 'Statut mis à jour ! Le candidat sera notifié par email.');
    }

    public function edit(Registration $registration)
    {
        $registration->load(['user.candidateProfile.academicHistories', 'user.candidateResources', 'seminar.cohort', 'topic']);
        
        if (!$registration->user->candidateProfile) {
            $profile = new \App\Models\CandidateProfile([
                'first_names' => $registration->user->name,
                'last_name' => '',
            ]);
            $registration->user->setRelation('candidateProfile', $profile);
        }

        $seminars = Seminar::where('status', '!=', 'completed')->get();
        $topicsBySeminar = \App\Models\Topic::whereIn('seminar_id', $seminars->pluck('id'))->get()->groupBy('seminar_id');
        
        return view('admin.candidates.edit', compact('registration', 'seminars', 'topicsBySeminar'));
    }

    public function update(CandidateRequest $request, Registration $registration)
    {
        $validated = $request->validated();

        $registration->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $profileData = $this->extractProfileData($request);
        $profile = CandidateProfile::updateOrCreate(['user_id' => $registration->user->id], $profileData);

        $this->syncAcademicHistories($profile, $request);

        $registration->update([
            'seminar_id' => $validated['seminar_id'],
            'topic_id' => $validated['topic_id'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->saveCandidateFiles($registration->user, $profile, $request);

        ActivityLogger::log(
            'Mise à jour Dossier Candidat',
            "Le dossier de '{$registration->user->name}' a été mis à jour par l'administration.",
            $registration
        );

        return redirect()->route('admin.candidates.show', $registration->id)->with('success', 'Dossier mis à jour avec succès.');
    }

    public function destroy(Registration $registration)
    {
        $name = $registration->user->name;
        $registration->delete();

        ActivityLogger::log('Suppression Candidature', "La candidature de '{$name}' a été supprimée.", null);

        return redirect()->route('admin.candidates')->with('success', 'Candidature supprimée.');
    }

    public function create()
    {
        $seminars = Seminar::where('status', '!=', 'completed')->get();
        $topicsBySeminar = \App\Models\Topic::whereIn('seminar_id', $seminars->pluck('id'))->get()->groupBy('seminar_id');
        return view('admin.candidates.create', compact('seminars', 'topicsBySeminar'));
    }

    public function store(CandidateRequest $request)
    {
        $validated = $request->validated();
        $candidateRole = Role::where('name', 'Candidat')->first();
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->password ?? Str::random(12)),
            'role_id' => $candidateRole?->id,
            'must_change_password' => true,
        ]);

        $profileData = $this->extractProfileData($request);
        $profileData['user_id'] = $user->id;
        $profile = CandidateProfile::create($profileData);

        $this->syncAcademicHistories($profile, $request);

        $this->saveCandidateFiles($user, $profile, $request);

        $registration = Registration::create([
            'user_id' => $user->id,
            'seminar_id' => $validated['seminar_id'],
            'topic_id' => $validated['topic_id'] ?? null,
            'status' => $validated['status'],
        ]);

        ActivityLogger::log('Inscription Candidat (Manuelle)', "Le candidat '{$user->name}' a été inscrit manuellement.", $registration);

        $user->notify(new CandidateStatusNotification($registration));

        return redirect()->route('admin.candidates')->with('success', 'Candidat inscrit avec succès !');
    }

    private function saveCandidateFiles(User $user, CandidateProfile $profile, Request $request): void
    {
        // 1. Photo de profil / Avatar
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates/photos', 'public');
            $profile->update(['photo_path' => $path]);
            $user->update(['avatar' => $path]);
        } elseif ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('candidates/photos', 'public');
            $profile->update(['photo_path' => $path]);
            $user->update(['avatar' => $path]);
        }

        // 2. Documents & Pièces justificatives
        $docTypes = [
            'document_diploma' => 'Diplôme / Attestation',
            'document_motivation' => 'Lettre de Motivation',
            'document_cv' => 'Curriculum Vitae (CV)',
            'document_passport' => "Pièce d'identité / Passeport",
        ];

        foreach ($docTypes as $field => $label) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('candidates/documents', 'public');
                ApplicationDocument::create([
                    'user_id' => $user->id,
                    'resource_type' => $label,
                    'file_path' => $path,
                    'original_filename' => $request->file($field)->getClientOriginalName(),
                ]);
            }
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $otherFile) {
                if ($otherFile) {
                    $path = $otherFile->store('candidates/documents', 'public');
                    ApplicationDocument::create([
                        'user_id' => $user->id,
                        'resource_type' => 'Autre Justificatif',
                        'file_path' => $path,
                        'original_filename' => $otherFile->getClientOriginalName(),
                    ]);
                }
            }
        }
    }

    private function extractProfileData(Request $request): array
    {
        $data = $request->only([
            'birth_date', 'birth_place', 'gender', 'family_status', 'religion',
            'nationality', 'residence_country', 'address', 'phone',
            'current_profession', 'current_employer', 'previous_employer', 'job_description',
            'training_utility', 'emergency_contact_name', 'emergency_contact_relation',
            'emergency_contact_phone', 'emergency_contact_email', 'health_record_obs',
            'accommodation_preference', 'insurance_details', 'insurance_premium',
            'matricule', 'grade', 'job_function'
        ]);

        $data['health_questionnaire_json'] = [
            'chronic' => $request->health_chronic,
            'treatment' => $request->health_treatment,
        ];

        return $data;
    }

    private function syncAcademicHistories(CandidateProfile $profile, Request $request): void
    {
        $profile->academicHistories()->delete();
        if ($request->has('acad_establishment')) {
            foreach ($request->acad_establishment as $index => $est) {
                if (!empty($est)) {
                    $profile->academicHistories()->create([
                        'establishment_name' => $est,
                        'field_of_study' => $request->acad_field[$index] ?? null,
                        'degree_obtained' => $request->acad_degree[$index] ?? null,
                        'graduation_year' => $request->acad_year[$index] ?? null,
                        'distinction' => $request->acad_distinction[$index] ?? null,
                    ]);
                }
            }
        }
    }
}
