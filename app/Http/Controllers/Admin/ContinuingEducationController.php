<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seminar;
use App\Models\Cohort;
use App\Models\Department;
use App\Models\Topic;
use App\Models\Registration;
use App\Models\User;
use App\Models\Role;
use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ContinuingEducationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $deptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : $request->get('department_id');
        $cohortId = $request->get('cohort_id');

        $cohorts = Cohort::orderByDesc('start_date')->get();
        $departments = Department::where('is_active', true)
            ->when($user && $user->isDepartmentHead() && $user->getEffectiveDepartmentId(), fn($q) => $q->where('id', $user->getEffectiveDepartmentId()))
            ->orderBy('id')
            ->get();

        $trainings = Seminar::continue()
            ->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->when($cohortId, fn($q) => $q->where('cohort_id', $cohortId))
            ->with(['cohort', 'departments', 'topics', 'registrations.user.candidateProfile'])
            ->orderByDesc('start_date')
            ->get();

        $selectedTrainingId = $request->get('training_id', $trainings->first()?->id);
        $activeTraining = $trainings->firstWhere('id', $selectedTrainingId) ?? $trainings->first();

        return view('admin.continuing_education.index', compact(
            'trainings',
            'activeTraining',
            'cohorts',
            'departments',
            'deptId',
            'cohortId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'partner_company' => 'required|string|max:255',
            'specific_theme' => 'required|string|max:255',
            'duration_label' => 'required|string|max:100',
            'fee_amount' => 'required|numeric|min:0',
            'cohort_id' => 'nullable|exists:cohorts,id',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string',
        ]);

        $cohortId = $request->cohort_id ?: Cohort::where('status', 'active')->first()?->id;

        $training = Seminar::create([
            'cohort_id' => $cohortId,
            'title' => $request->title,
            'description' => $request->description ?? "Formation continue destinée aux professionnels de {$request->partner_company} sur le thème : {$request->specific_theme}.",
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_participants' => $request->max_participants,
            'status' => 'active',
            'training_type' => 'continue',
            'partner_company' => $request->partner_company,
            'duration_label' => $request->duration_label,
            'fee_amount' => $request->fee_amount,
            'specific_theme' => $request->specific_theme,
        ]);

        $training->departments()->sync([$request->department_id]);

        Topic::create([
            'seminar_id' => $training->id,
            'name' => $request->specific_theme,
            'is_active' => true,
        ]);

        return redirect()->route('admin.continuing-education.index', ['training_id' => $training->id])
            ->with('success', "Formation continue « {$training->title} » créée pour le partenaire/entreprise {$training->partner_company}.");
    }

    public function enrollTrainee(Request $request, Seminar $training)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'job_function' => 'nullable|string|max:255',
        ]);

        $studentRole = Role::where('name', 'Candidat')->first();

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make('password'),
                'role_id' => $studentRole?->id,
                'must_change_password' => true,
            ]
        );

        $parts = explode(' ', $request->name, 2);
        CandidateProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_names' => $parts[0] ?? $request->name,
                'last_name' => $parts[1] ?? '',
                'phone' => $request->phone ?? '+221 77 000 00 00',
                'current_employer' => $request->company_name ?: $training->partner_company,
                'current_profession' => $request->job_function ?: 'Professionnel en Formation Continue',
                'matricule' => 'FC-' . date('Y') . '-' . str_pad((string) rand(1, 999), 3, '0', STR_PAD_LEFT),
            ]
        );

        $topic = $training->topics()->first();
        $certNum = 'ATT-CFPT-' . date('Y') . '-' . strtoupper(Str::random(5));

        Registration::updateOrCreate(
            [
                'user_id' => $user->id,
                'seminar_id' => $training->id,
            ],
            [
                'topic_id' => $topic?->id,
                'status' => 'approved',
                'company_name' => $request->company_name ?: $training->partner_company,
                'certificate_number' => $certNum,
                'certificate_issued_at' => now(),
            ]
        );

        return redirect()->route('admin.continuing-education.index', ['training_id' => $training->id])
            ->with('success', "Le stagiaire professionnel {$user->name} a été inscrit à la formation continue.");
    }

    public function downloadCertificatePdf(Registration $registration)
    {
        $registration->load(['user.candidateProfile', 'seminar.departments', 'seminar.cohort', 'topic']);

        if (!$registration->certificate_number) {
            $registration->update([
                'certificate_number' => 'ATT-CFPT-' . date('Y') . '-' . str_pad((string) $registration->id, 4, '0', STR_PAD_LEFT),
                'certificate_issued_at' => now(),
            ]);
        }

        $pdf = Pdf::loadView('exports.pdf.continuing_certificate', compact('registration'))
            ->setPaper('a4', 'landscape');

        $filename = 'Attestation_Formation_Continue_CFPT_' . str_replace(' ', '_', $registration->user->name) . '.pdf';
        return $pdf->download($filename);
    }

    public function destroy(Seminar $training)
    {
        $training->delete();
        return redirect()->route('admin.continuing-education.index')
            ->with('success', 'Formation continue supprimée.');
    }
}
