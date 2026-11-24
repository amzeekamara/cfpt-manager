<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Seminar;
use App\Models\Topic;
use App\Models\Trainer;
use App\Models\Registration;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $effectiveDeptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : $request->get('department_id');

        $departments = Department::where('is_active', true)
            ->when($user && $user->isDepartmentHead() && $user->getEffectiveDepartmentId(), function ($q) use ($user) {
                $q->where('id', $user->getEffectiveDepartmentId());
            })
            ->orderBy('name')
            ->get();

        $seminars = Seminar::where('status', 'active')
            ->when($effectiveDeptId, function ($q) use ($effectiveDeptId) {
                $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $effectiveDeptId));
            })
            ->with(['topics', 'departments'])
            ->orderBy('title')
            ->get();

        $trainers = Trainer::orderBy('name')->get();

        $selectedSeminarId = $request->get('seminar_id', $seminars->first()?->id);
        $selectedTopicId = $request->get('topic_id');

        $evaluationsQuery = Evaluation::with(['seminar', 'topic', 'trainer', 'grades'])
            ->when($selectedSeminarId, fn($q) => $q->where('seminar_id', $selectedSeminarId))
            ->when($selectedTopicId, fn($q) => $q->where('topic_id', $selectedTopicId))
            ->when($effectiveDeptId && !$selectedSeminarId, function ($q) use ($seminars) {
                $q->whereIn('seminar_id', $seminars->pluck('id'));
            })
            ->orderByDesc('evaluation_date');

        $evaluations = $evaluationsQuery->get();

        // Students enrolled in selected seminar for gradebook matrix & averages
        $registrations = collect();
        if ($selectedSeminarId) {
            $registrations = Registration::with(['user.candidateProfile', 'topic', 'grades.evaluation'])
                ->where('seminar_id', $selectedSeminarId)
                ->when($selectedTopicId, fn($q) => $q->where('topic_id', $selectedTopicId))
                ->where('status', 'approved')
                ->get();
        }

        // Selected evaluation for direct grade entry
        $activeEvaluationId = $request->get('evaluation_id', $evaluations->first()?->id);
        $activeEvaluation = $evaluations->firstWhere('id', $activeEvaluationId) ?? $evaluations->first();

        return view('admin.evaluations.index', compact(
            'departments',
            'seminars',
            'trainers',
            'evaluations',
            'registrations',
            'selectedSeminarId',
            'selectedTopicId',
            'activeEvaluation',
            'effectiveDeptId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:devoir,tp,examen',
            'coefficient' => 'required|numeric|min:0.5|max:20',
            'max_score' => 'required|numeric|min:5|max:100',
            'evaluation_date' => 'required|date',
            'is_published' => 'nullable|boolean',
        ]);

        $eval = Evaluation::create([
            'seminar_id' => $request->seminar_id,
            'topic_id' => $request->topic_id,
            'trainer_id' => $request->trainer_id,
            'title' => $request->title,
            'type' => $request->type,
            'coefficient' => $request->coefficient,
            'max_score' => $request->max_score,
            'evaluation_date' => $request->evaluation_date,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.evaluations.index', [
            'seminar_id' => $eval->seminar_id,
            'evaluation_id' => $eval->id,
        ])->with('success', "Évaluation « {$eval->title} » créée. Vous pouvez maintenant saisir les notes.");
    }

    public function storeGrades(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:' . $evaluation->max_score,
            'appreciations' => 'nullable|array',
            'appreciations.*' => 'nullable|string|max:255',
        ]);

        foreach ($request->input('scores', []) as $registrationId => $score) {
            if ($score === null || $score === '') {
                continue;
            }
            $appreciation = $request->input("appreciations.{$registrationId}");
            if (!$appreciation) {
                $norm = ($score / max(1, $evaluation->max_score)) * 20;
                $appreciation = match (true) {
                    $norm >= 16 => 'Très Bien',
                    $norm >= 14 => 'Bien',
                    $norm >= 12 => 'Assez Bien',
                    $norm >= 10 => 'Passable',
                    default => 'Insuffisant',
                };
            }

            Grade::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'registration_id' => $registrationId,
                ],
                [
                    'score' => $score,
                    'appreciation' => $appreciation,
                ]
            );
        }

        if ($request->has('is_published')) {
            $evaluation->update(['is_published' => $request->boolean('is_published')]);
        }

        return back()->with('success', "Notes enregistrées avec succès pour « {$evaluation->title} ».");
    }

    public function togglePublish(Evaluation $evaluation)
    {
        $evaluation->update(['is_published' => !$evaluation->is_published]);
        $msg = $evaluation->is_published
            ? 'Les notes sont désormais restituées et visibles par les étudiants.'
            : 'Les notes sont masquées pour les étudiants.';
        return back()->with('success', $msg);
    }

    public function destroy(Evaluation $evaluation)
    {
        $seminarId = $evaluation->seminar_id;
        $evaluation->delete();
        return redirect()->route('admin.evaluations.index', ['seminar_id' => $seminarId])
            ->with('success', 'Évaluation supprimée.');
    }

    public function exportReportCardPdf(Registration $registration)
    {
        $registration->load(['user.candidateProfile', 'seminar.departments', 'seminar.cohort', 'topic', 'grades.evaluation.topic', 'grades.evaluation.trainer', 'attendances']);

        $evaluations = Evaluation::with(['topic', 'trainer'])
            ->where('seminar_id', $registration->seminar_id)
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
}
