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
use App\Models\Timetable;
use App\Models\Evaluation;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $registration = $profile->user->registrations()->with(['seminar.cohort', 'seminar.departments', 'topic', 'attendances.topic', 'attendances.trainer', 'grades.evaluation.topic'])->latest()->first();

        $studentTimetables = collect();
        $studentEvaluations = collect();
        $studentAverage = null;
        $totalCoef = 0;

        if ($registration && $registration->seminar_id) {
            $studentTimetables = Timetable::with(['topic', 'trainer', 'classroom', 'department'])
                ->where('seminar_id', $registration->seminar_id)
                ->where(function ($q) use ($registration) {
                    $q->whereNull('topic_id');
                    if ($registration->topic_id) {
                        $q->orWhere('topic_id', $registration->topic_id);
                    }
                })
                ->orderBy('start_time')
                ->get();

            $publishedEvaluations = Evaluation::with(['topic', 'trainer'])
                ->where('seminar_id', $registration->seminar_id)
                ->where('is_published', true)
                ->where(function ($q) use ($registration) {
                    $q->whereNull('topic_id');
                    if ($registration->topic_id) {
                        $q->orWhere('topic_id', $registration->topic_id);
                    }
                })
                ->orderByDesc('evaluation_date')
                ->get();

            $gradesByEval = $registration->grades->keyBy('evaluation_id');
            $weightedSum = 0;

            foreach ($publishedEvaluations as $eval) {
                $g = $gradesByEval->get($eval->id);
                $s20 = null;
                if ($g && $g->score !== null) {
                    $s20 = ($g->score / max(1, $eval->max_score)) * 20;
                    $weightedSum += $s20 * $eval->coefficient;
                    $totalCoef += $eval->coefficient;
                }
                $studentEvaluations->push([
                    'evaluation' => $eval,
                    'grade' => $g,
                    'score20' => $s20,
                ]);
            }

            if ($totalCoef > 0) {
                $studentAverage = round($weightedSum / $totalCoef, 2);
            }
        }
        
        $messagesQuery = Message::with(['trainer', 'user', 'seminar', 'cohort']);

        if ($registration) {
            $cohortId = $registration->seminar ? $registration->seminar->cohort_id : null;
            $seminarId = $registration->seminar_id;
            $topicId = $registration->topic_id;

            $messagesQuery->where(function ($query) use ($cohortId) {
                $query->whereNull('cohort_id');
                if ($cohortId) {
                    $query->orWhere('cohort_id', $cohortId);
                }
            });

            $messagesQuery->where(function ($query) use ($seminarId) {
                $query->whereNull('seminar_id');
                if ($seminarId) {
                    $query->orWhere('seminar_id', $seminarId)
                          ->orWhereJsonContains('target_seminars', (string)$seminarId)
                          ->orWhereJsonContains('target_seminars', (int)$seminarId);
                }
            });

            $messagesQuery->where(function ($query) use ($topicId) {
                $query->whereNull('target_topics')
                      ->orWhere('target_topics', '[]')
                      ->orWhere('target_topics', 'null');
                if ($topicId) {
                    $query->orWhereJsonContains('target_topics', (string)$topicId)
                          ->orWhereJsonContains('target_topics', (int)$topicId);
                }
            });
        }

        $messages = $messagesQuery->with(['readByUsers' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->get();

        // Pass seminars for the change request modal
        $seminars = Seminar::where('status', 'active')->orWhere('status', 'published')->get();

        // Fetch Resources for the candidate (both global resources and seminar-specific resources)
        $resources = Resource::where('is_archived', false)
            ->whereIn('role_visibility', ['all', 'candidat'])
            ->where(function ($query) use ($registration) {
                $query->whereNull('seminar_id');
                if ($registration && $registration->seminar_id) {
                    $query->orWhere('seminar_id', $registration->seminar_id);
                }
            })
            ->latest()
            ->get();

        return view('portal.candidate.dashboard', compact(
            'profile',
            'registration',
            'messages',
            'seminars',
            'resources',
            'studentTimetables',
            'studentEvaluations',
            'studentAverage',
            'totalCoef'
        ));
    }

    public function downloadReportCard()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $registration = \App\Models\Registration::with(['user.candidateProfile', 'seminar.departments', 'seminar.cohort', 'topic', 'grades.evaluation.topic', 'grades.evaluation.trainer', 'attendances'])
            ->where('user_id', $userId)
            ->latest()
            ->firstOrFail();

        $evaluations = Evaluation::with(['topic', 'trainer'])
            ->where('seminar_id', $registration->seminar_id)
            ->where('is_published', true)
            ->where(function ($q) use ($registration) {
                $q->whereNull('topic_id');
                if ($registration->topic_id) {
                    $q->orWhere('topic_id', $registration->topic_id);
                }
            })
            ->orderBy('evaluation_date')
            ->get();

        $gradesByEval = $registration->grades->keyBy('evaluation_id');
        $totalWeighted = 0;
        $totalCoef = 0;
        $rows = [];

        foreach ($evaluations as $eval) {
            $grade = $gradesByEval->get($eval->id);
            $score20 = null;
            if ($grade && $grade->score !== null) {
                $score20 = ($grade->score / max(1, $eval->max_score)) * 20;
                $totalWeighted += $score20 * $eval->coefficient;
                $totalCoef += $eval->coefficient;
            }
            $rows[] = [
                'evaluation' => $eval,
                'grade' => $grade,
                'score20' => $score20,
            ];
        }

        $generalAverage = $totalCoef > 0 ? round($totalWeighted / $totalCoef, 2) : null;
        $mention = match (true) {
            $generalAverage === null => 'Non évalué',
            $generalAverage >= 16 => 'Très Bien',
            $generalAverage >= 14 => 'Bien',
            $generalAverage >= 12 => 'Assez Bien',
            $generalAverage >= 10 => 'Passable',
            default => 'Ajourné / Insuffisant',
        };

        $presentCount = $registration->attendances->where('status', 'present')->count();
        $absentCount = $registration->attendances->where('status', 'absent')->count();

        $pdf = Pdf::loadView('exports.pdf.report_card', compact(
            'registration',
            'rows',
            'generalAverage',
            'totalCoef',
            'mention',
            'presentCount',
            'absentCount'
        ))->setPaper('a4', 'portrait');

        $filename = 'Bulletin_Notes_CFPT_' . str_replace(' ', '_', $registration->user->name) . '.pdf';
        return $pdf->download($filename);
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
