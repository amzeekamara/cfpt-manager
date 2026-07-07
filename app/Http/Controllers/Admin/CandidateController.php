<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Role;
use App\Models\CandidateProfile;
use Illuminate\Http\Request;
use App\Notifications\CandidateStatusNotification;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $editionId = $request->get('edition_id');
        $selectedEdition = null;
        if ($editionId) {
            $selectedEdition = \App\Models\Edition::find($editionId);
        }

        if (!$selectedEdition && !$request->has('edition_id')) {
            $selectedEdition = \App\Models\Edition::where('status', 'active')->latest()->first() 
                ?? \App\Models\Edition::latest()->first();
            $editionId = $selectedEdition ? $selectedEdition->id : null;
        }

        $query = Enrollment::with(['user.candidateProfile', 'seminar.edition']);

        // Edition Filtering
        if ($editionId) {
            $query->whereHas('seminar', function($q) use ($editionId) {
                $q->where('edition_id', $editionId);
            });
        }

        // Other Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seminar_id')) {
            $query->where('seminar_id', $request->seminar_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Stats filtered by edition if provided
        $statsQuery = Enrollment::query();
        if ($editionId) {
            $statsQuery->whereHas('seminar', function($q) use ($editionId) {
                $q->where('edition_id', $editionId);
            });
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $statsQuery)->where('status', 'rejected')->count(),
        ];

        $editions = \App\Models\Edition::orderBy('created_at', 'desc')->get();
        $seminars = Seminar::when($editionId, function($q) use ($editionId) {
            return $q->where('edition_id', $editionId);
        })->where('status', '!=', 'completed')->get();

        return view('admin.candidates', compact('candidates', 'stats', 'seminars', 'editions', 'selectedEdition'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user.candidateProfile.academicHistories', 'user.candidateDocuments', 'seminar.edition']);
        return view('admin.candidates.show', compact('enrollment'));
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,waiting_list',
        ]);

        $oldStatus = $enrollment->status;
        $enrollment->update(['status' => $validated['status']]);

        // Notification asynchrone (Queue)
        $enrollment->user->notify(new CandidateStatusNotification($enrollment));

        return back()->with('success', 'Statut mis à jour ! Le candidat sera notifié par email.');
    }

    public function edit(Enrollment $enrollment)
    {
        $enrollment->load(['user.candidateProfile.academicHistories', 'user.candidateDocuments', 'seminar.edition']);
        $seminars = Seminar::where('status', '!=', 'completed')->get();
        return view('admin.candidates.edit', compact('enrollment', 'seminars'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            // Identity
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $enrollment->user_id,
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

            // Seminar & Status
            'seminar_id' => 'required|exists:seminars,id',
            'status' => 'required|in:pending,approved,rejected,waiting_list',

            // Academic History (Arrays)
            'acad_establishment.*' => 'nullable|string',
            'acad_field.*' => 'nullable|string',
            'acad_degree.*' => 'nullable|string',
            'acad_year.*' => 'nullable|string',
            'acad_distinction.*' => 'nullable|string',
        ]);

        // 1. Update User
        $enrollment->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // 2. Update Profile
        $profile = $enrollment->user->candidateProfile;
        $profileData = $request->only([
            'birth_date', 'birth_place', 'gender', 'family_status', 'religion',
            'nationality', 'residence_country', 'address', 'phone',
            'current_profession', 'current_employer', 'previous_employer', 'job_description',
            'training_utility', 'emergency_contact_name', 'emergency_contact_relation',
            'emergency_contact_phone', 'emergency_contact_email', 'health_record_obs',
            'accommodation_preference', 'insurance_details', 'insurance_premium'
        ]);

        // Health JSON
        $profileData['health_questionnaire_json'] = [
            'chronic' => $request->health_chronic,
            'treatment' => $request->health_treatment,
        ];

        $profile->update($profileData);

        // 3. Sync Academic History
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

        // 4. Update Enrollment
        $enrollment->update([
            'seminar_id' => $validated['seminar_id'],
            'status' => $validated['status'],
        ]);

        // Photo Upload (Optional handle in edit)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates/photos', 'public');
            $profile->update(['photo_path' => $path]);
        }

        ActivityLogger::log(
            'Mise à jour Dossier Candidat',
            "Le dossier de '{$enrollment->user->name}' a été mis à jour par l'administration.",
            $enrollment
        );

        return redirect()->route('admin.candidates.show', $enrollment->id)->with('success', 'Dossier mis à jour avec succès.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $name = $enrollment->user->name;
        $user = $enrollment->user;
        
        $enrollment->delete();
        // Optionnel: On peut garder le User ou le supprimer aussi
        // $user->delete(); 

        ActivityLogger::log(
            'Suppression Candidature',
            "La candidature de '{$name}' a été supprimée.",
            null
        );

        return redirect()->route('admin.candidates')->with('success', 'Candidature supprimée.');
    }

    public function create()
    {
        $seminars = Seminar::where('status', '!=', 'completed')->get();
        return view('admin.candidates.create', compact('seminars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // User Data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8',
            
            // Profile Data
            'religion' => 'nullable|string',
            'nationality' => 'nullable|string',
            'phone' => 'nullable|string',
            'current_profession' => 'nullable|string',
            'current_employer' => 'nullable|string',
            
            // Enrollment Data
            'seminar_id' => 'required|exists:seminars,id',
            'status' => 'required|in:pending,approved,waiting_list',
        ]);

        $candidateRole = Role::where('name', 'Candidate')->first();
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->password ?? Str::random(12)),
            'role_id' => $candidateRole ? $candidateRole->id : null,
        ]);

        // Initial Profile
        $profile = CandidateProfile::create([
            'user_id' => $user->id,
            'religion' => $validated['religion'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'current_profession' => $validated['current_profession'] ?? null,
            'current_employer' => $validated['current_employer'] ?? null,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'seminar_id' => $validated['seminar_id'],
            'status' => $validated['status'],
        ]);

        ActivityLogger::log(
            'Inscription Candidat (Manuelle)',
            "Le candidat '{$user->name}' a été inscrit manuellement.",
            $enrollment
        );

        // Notification asynchrone (Queue)
        $user->notify(new CandidateStatusNotification($enrollment));

        return redirect()->route('admin.candidates')->with('success', 'Candidat inscrit avec succès !');
    }
}
