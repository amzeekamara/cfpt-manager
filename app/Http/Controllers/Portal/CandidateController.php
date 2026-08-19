<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CandidateProfile;
use App\Models\Message;
use App\Models\ProfileUpdateRequest;
use App\Models\SeminarChangeRequest;
use App\Models\Seminar;
use App\Models\Resource;

class CandidateController extends Controller
{
    public function dashboard(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $profile = CandidateProfile::with(['user.registrations.seminar.cohort'])
            ->where('user_id', $userId)
            ->first();

        if (!$profile) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $profile = new CandidateProfile();
            $profile->first_names = $user->name;
            $profile->last_name = '';
            $profile->phone = 'N/A';
            $profile->birth_date = now();
            $profile->setRelation('user', $user);
        }

        // Get the active registration (assuming the latest one for simplicity)
        $registration = $profile->user->registrations()->latest()->first();
        
        $messages = collect();
        if ($registration) {
            $cohortId = $registration->seminar ? $registration->seminar->cohort_id : null;
            $seminarId = $registration->seminar_id;

            $messages = Message::with(['trainer', 'user'])
                ->where(function ($query) use ($cohortId) {
                    $query->whereNull('cohort_id');
                    if ($cohortId) {
                        $query->orWhere('cohort_id', $cohortId);
                    }
                })
                ->where(function ($query) use ($seminarId) {
                    $query->whereNull('seminar_id');
                    if ($seminarId) {
                        $query->orWhere('seminar_id', $seminarId);
                    }
                })
                ->where(function ($query) use ($registration) {
                    $query->whereNull('target_topics');
                    if ($registration->topic_id) {
                        // Support string or int in JSON array
                        $query->orWhereJsonContains('target_topics', (string)$registration->topic_id)
                              ->orWhereJsonContains('target_topics', (int)$registration->topic_id);
                    }
                })
                ->with(['readByUsers' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->latest()
                ->get();
        }

        // Pass seminars for the change request modal
        $seminars = Seminar::where('status', 'active')->orWhere('status', 'published')->get();

        // Fetch Resources for the seminar
        $resources = collect();
        if ($registration && $registration->seminar_id) {
            $resources = Resource::where('seminar_id', $registration->seminar_id)
                ->whereIn('role_visibility', ['all', 'candidat'])
                ->where('is_archived', false)
                ->latest()
                ->get();
        }

        return view('portal.candidate.dashboard', compact('profile', 'registration', 'messages', 'seminars', 'resources'));
    }

    public function markMessageAsRead(Message $message)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user->readMessages()->where('message_id', $message->id)->exists()) {
            $user->readMessages()->attach($message->id, ['read_at' => now()]);
        }
        return response()->json(['success' => true]);
    }

    public function editProfile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $profile = CandidateProfile::with('academicHistories')->where('user_id', $user->id)->first();
        
        if (!$profile) {
            $profile = new CandidateProfile();
            $profile->first_names = $user->name;
            $profile->last_name = '';
            $profile->phone = '';
            $profile->birth_date = now();
            $profile->setRelation('user', $user);
        }
        
        return view('portal.candidate.profile_edit', compact('profile'));
    }

    public function requestProfileUpdate(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Prevent multiple pending requests
        if ($user->profileUpdateRequests()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande de modification en attente.');
        }

        $changes = $request->except(['_token', 'photo']);
        
        // Si une nouvelle photo est uploadée, on peut la stocker temporairement ou la mettre dans le JSON
        // Pour simplifier on ignore la photo dans la modification pour l'instant ou on la gère séparément.

        ProfileUpdateRequest::create([
            'user_id' => $user->id,
            'requested_changes' => $changes,
            'status' => 'pending',
        ]);

        // Notifier les administrateurs
        $admins = \App\Models\User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Admin', 'Super Admin']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\StaffActionNotification(
                $user,
                'Candidat - Modification Profil',
                'Le candidat ' . $user->name . ' a soumis une demande de modification de son profil.'
            ));
        }

        return redirect()->back()->with('success', 'Votre demande de modification a été envoyée et est en attente de validation par un administrateur.');
    }

    public function requestSeminarChange(Request $request)
    {
        $request->validate([
            'new_seminar_id' => 'required|exists:seminars,id',
            'registration_id' => 'required|exists:registrations,id',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        if (SeminarChangeRequest::where('registration_id', $request->registration_id)->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande de changement en attente pour cette inscription.');
        }

        SeminarChangeRequest::create([
            'user_id' => $user->id,
            'registration_id' => $request->registration_id,
            'new_seminar_id' => $request->new_seminar_id,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Votre demande de changement de séminaire a été envoyée à l\'administration.');
    }
}
