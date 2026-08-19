<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Message;
use App\Models\Cohort;
use App\Models\Department;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{
    public function dashboard(Request $request)
    {
        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::with('seminars')->findOrFail($trainerId);
        
        $messages = Message::where('trainer_id', $trainerId)->latest()->get();
        
        $cohorts = Cohort::all();
        $departments = Department::all();
        
        $seminarIds = $trainer->seminars->pluck('id');
        $resources = Resource::whereIn('seminar_id', $seminarIds)->latest()->get();
        $topicsBySeminar = \App\Models\Topic::whereIn('seminar_id', $seminarIds)->get()->groupBy('seminar_id');
        $topicsWithCounts = \App\Models\Topic::whereIn('seminar_id', $seminarIds)
            ->with(['seminar'])
            ->withCount(['registrations' => function($q) {
                $q->where('status', 'approved');
            }])
            ->get();

        $totalCandidates = 0;
        foreach ($trainer->seminars as $seminar) {
            $totalCandidates += $seminar->registrations()->where('status', 'approved')->count();
        }

        return view('portal.trainer.dashboard', compact('trainer', 'messages', 'cohorts', 'departments', 'resources', 'topicsBySeminar', 'topicsWithCounts', 'totalCandidates'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cohort_id' => 'nullable|exists:cohorts,id',
            'department_id' => 'nullable|exists:departments,id',
            'seminar_id' => 'nullable|exists:seminars,id',
            'topic_ids' => 'nullable|array',
            'topic_ids.*' => 'exists:topics,id',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::findOrFail($trainerId);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages/attachments', 'public');
        }

        Message::create([
            'trainer_id' => $trainerId,
            'cohort_id' => $request->cohort_id,
            'department_id' => $request->department_id,
            'seminar_id' => $request->seminar_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'target_topics' => $request->topic_ids,
        ]);

        return back()->with('success', 'Message envoyé avec succès aux candidats.');
    }

    public function attendances(Request $request)
    {
        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::with('seminars')->findOrFail($trainerId);

        $date = $request->input('date', date('Y-m-d'));
        $seminarId = $request->input('seminar_id');
        
        $registrations = collect();
        $topics = collect();

        if ($seminarId && $trainer->seminars->contains('id', $seminarId)) {
            $topics = \App\Models\Topic::where('seminar_id', $seminarId)->where('is_active', true)->get();
            $topicId = $request->input('topic_id'); // Optional filter by topic if needed for display, but usually attendances are per registration.

            $registrations = \App\Models\Registration::with(['user.candidateProfile', 'attendances' => function($q) use ($date, $topicId) {
                $q->where('session_date', $date);
                if ($topicId) {
                    $q->where('topic_id', $topicId);
                }
            }])
            ->where('seminar_id', $seminarId)
            ->where('status', 'approved')
            ->get();
        }

        return view('portal.trainer.attendances', compact('trainer', 'registrations', 'date', 'seminarId', 'topics'));
    }

    public function storeAttendances(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'seminar_id' => 'required|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',
            'attendances' => 'required|array',
            'attendances.*' => 'in:present,absent',
        ]);

        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::findOrFail($trainerId);
        
        if (!$trainer->seminars->contains('id', $request->seminar_id)) {
            abort(403, 'Accès non autorisé à ce séminaire.');
        }

        $date = $request->input('date');
        $topicId = $request->input('topic_id');

        foreach ($request->input('attendances') as $registrationId => $status) {
            $registration = \App\Models\Registration::where('id', $registrationId)
                ->where('seminar_id', $request->seminar_id)
                ->first();

            if ($registration) {
                \App\Models\Attendance::updateOrCreate(
                    [
                        'registration_id' => $registrationId, 
                        'session_date' => $date,
                        'topic_id' => $topicId // Treat different topics on same day as different sessions
                    ],
                    [
                        'status' => $status,
                        'trainer_id' => $trainerId
                    ]
                );
            }
        }

        // Enregistrer les heures effectuées par le formateur pour cette session
        if ($request->filled('hours_taught') && $request->input('hours_taught') > 0) {
            \App\Models\TrainerSession::updateOrCreate(
                [
                    'trainer_id' => $trainerId,
                    'seminar_id' => $request->seminar_id,
                    'session_date' => $date,
                    'topic_id' => $topicId
                ],
                [
                    'hours_taught' => $request->input('hours_taught')
                ]
            );
        }

        return back()->with('success', 'Les présences ont été enregistrées avec succès.');
    }

    public function storeResource(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'seminar_id' => 'required|exists:seminars,id',
            'file' => 'required|file|max:10240', // 10MB max
            'role_visibility' => 'required|in:all,candidat',
        ]);

        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::findOrFail($trainerId);

        if (!$trainer->seminars->contains('id', $request->seminar_id)) {
            abort(403, 'Vous ne pouvez pas ajouter de ressources à ce séminaire.');
        }

        $path = $request->file('file')->store('resources', 'public');

        Resource::create([
            'title' => $request->title,
            'file_path' => $path,
            'seminar_id' => $request->seminar_id,
            'role_visibility' => $request->role_visibility,
        ]);

        return back()->with('success', 'Ressource ajoutée avec succès.');
    }

    public function destroyResource(Request $request, Resource $resource)
    {
        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::findOrFail($trainerId);
        
        if (!$trainer->seminars->contains('id', $resource->seminar_id)) {
            abort(403, 'Accès non autorisé.');
        }

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return back()->with('success', 'Ressource supprimée avec succès.');
    }

    public function exportTopicCandidates(Request $request, \App\Models\Topic $topic)
    {
        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Trainer::findOrFail($trainerId);

        if (!$trainer->seminars->contains('id', $topic->seminar_id)) {
            abort(403, 'Accès non autorisé à ce thème.');
        }

        $registrations = \App\Models\Registration::with('user.candidateProfile')
            ->where('topic_id', $topic->id)
            ->where('status', 'approved')
            ->get();

        $csvFileName = 'candidats_theme_' . str_replace(' ', '_', $topic->name) . '.csv';

        return response()->streamDownload(function() use ($registrations) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8 Excel
            fputcsv($handle, ['Nom Complet', 'Email', 'Téléphone', 'Profession'], ';');
            
            foreach ($registrations as $reg) {
                $user = $reg->user;
                $profile = $user->candidateProfile;
                fputcsv($handle, [
                    $user->name,
                    $user->email,
                    $profile->phone ?? '',
                    $profile->profession ?? ''
                ], ';');
            }
            fclose($handle);
        }, $csvFileName, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
        ]);
    }
}
