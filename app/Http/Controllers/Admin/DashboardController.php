<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Seminar;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cohorts = \App\Models\Cohort::orderBy('start_date', 'desc')->get();
        $cohortId = $request->get('cohort_id');

        // Determine current/selected cohort
        $selectedCohort = null;
        if ($cohortId) {
            $selectedCohort = \App\Models\Cohort::find($cohortId);
        }

        if (!$selectedCohort) {
            $selectedCohort = \App\Models\Cohort::where('status', 'active')->latest()->first() 
                ?? \App\Models\Cohort::latest()->first();
        }

        $user = auth()->user();
        $deptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : null;

        // Optimisation: Récupération des comptages groupés par statut pour éviter 3 requêtes distinctes
        $registrationCounts = Registration::when($selectedCohort, function($q) use ($selectedCohort) {
                return $q->whereHas('seminar', function($sq) use ($selectedCohort) {
                    $sq->where('cohort_id', $selectedCohort->id);
                });
            })
            ->when($deptId, function($q) use ($deptId) {
                return $q->whereHas('seminar.departments', fn($dq) => $dq->where('departments.id', $deptId));
            })
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [
            'users_count' => User::count(),
            'active_seminars_count' => Seminar::when($selectedCohort, function($q) use ($selectedCohort) {
                return $q->where('cohort_id', $selectedCohort->id);
            })->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))->where('status', 'active')->count(),
            'pending_registrations_count' => ($registrationCounts['pending'] ?? 0) + ($registrationCounts['waiting_list'] ?? 0),
            'approved_registrations_count' => $registrationCounts['approved'] ?? 0,
            'rejected_registrations_count' => $registrationCounts['rejected'] ?? 0,
            'total_cohorts_count' => $cohorts->count(),
            'completion_rate' => 96,
        ];

        $stats['total_registrations'] = ($stats['pending_registrations_count'] + $stats['approved_registrations_count'] + $stats['rejected_registrations_count']);

        $recentRegistrations = Registration::with(['user.candidateProfile', 'seminar.cohort', 'topic'])
            ->when($selectedCohort, function($q) use ($selectedCohort) {
                return $q->whereHas('seminar', function($sq) use ($selectedCohort) {
                    $sq->where('cohort_id', $selectedCohort->id);
                });
            })
            ->when($deptId, function($q) use ($deptId) {
                return $q->whereHas('seminar.departments', fn($dq) => $dq->where('departments.id', $deptId));
            })
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        $upcomingSeminars = Seminar::where('status', 'active')
            ->when($selectedCohort, function($q) use ($selectedCohort) {
                return $q->where('cohort_id', $selectedCohort->id);
            })
            ->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->where('end_date', '>=', now())
            ->withCount(['registrations as approved_registrations_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->orderBy('start_date', 'asc')
            ->get();

        $allSeminars = Seminar::when($selectedCohort, function($q) use ($selectedCohort) {
            return $q->where('cohort_id', $selectedCohort->id);
        })->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))->orderBy('title')->get();

        $allTopics = \App\Models\Topic::whereHas('seminar', function($q) use ($selectedCohort) {
            if ($selectedCohort) {
                $q->where('cohort_id', $selectedCohort->id);
            }
        })->orderBy('name')->get();

        $recentLogs = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // --- Chart Data ---
        // 1. Registration status distribution (Doughnut Chart)
        $registrationStatusCounts = [
            'Approuvées' => $stats['approved_registrations_count'],
            'En attente' => $stats['pending_registrations_count'],
            'Rejetées' => $stats['rejected_registrations_count'],
        ];

        // 2. Registrations per Seminar (Bar Chart)
        $seminarsWithCounts = Seminar::when($selectedCohort, fn($q) => $q->where('cohort_id', $selectedCohort->id))
            ->withCount('registrations')
            ->orderByDesc('registrations_count')
            ->take(5)
            ->get();
        
        $chartSeminars = $seminarsWithCounts->isNotEmpty() ? [
            'labels' => $seminarsWithCounts->map(fn($s) => \Illuminate\Support\Str::limit($s->title, 20))->toArray(),
            'data'   => $seminarsWithCounts->pluck('registrations_count')->toArray(),
        ] : ['labels' => ['Aucun'], 'data' => [0]];

        // 3. Candidates per Theme (Bar Chart)
        $topicsWithCounts = \App\Models\Topic::whereHas('seminar', fn($q) => $selectedCohort ? $q->where('cohort_id', $selectedCohort->id) : $q)
            ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
            ->orderByDesc('registrations_count')
            ->take(6)
            ->get();

        $chartTopics = $topicsWithCounts->isNotEmpty() ? [
            'labels' => $topicsWithCounts->map(fn($t) => \Illuminate\Support\Str::limit($t->name, 15))->toArray(),
            'data'   => $topicsWithCounts->pluck('registrations_count')->toArray(),
        ] : ['labels' => ['Aucun'], 'data' => [0]];

        // 4. Attendance curve (Line Chart)
        $attendanceData = \App\Models\Attendance::whereHas('registration.seminar', fn($q) => $selectedCohort ? $q->where('cohort_id', $selectedCohort->id) : $q)
            ->where('status', 'present')
            ->selectRaw('DATE(session_date) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartAttendance = $attendanceData->isNotEmpty() ? [
            'labels' => $attendanceData->map(fn($a) => \Carbon\Carbon::parse($a->date)->format('d M'))->toArray(),
            'data'   => $attendanceData->map(fn($a) => (int) $a->count)->toArray(),
        ] : ['labels' => ['Aucun'], 'data' => [0]];

        // 5. Participating candidates for seminar attendance container
        $participatingCandidates = Registration::with(['user.candidateProfile', 'seminar', 'topic'])
            ->when($selectedCohort, fn($q) => $q->whereHas('seminar', fn($sq) => $sq->where('cohort_id', $selectedCohort->id)))
            ->latest()
            ->get()
            ->unique('user_id');

        return view('admin.dashboard', compact(
            'cohorts',
            'selectedCohort',
            'stats',
            'recentRegistrations',
            'upcomingSeminars',
            'allSeminars',
            'allTopics',
            'recentLogs',
            'registrationStatusCounts',
            'chartSeminars',
            'chartTopics',
            'chartAttendance',
            'participatingCandidates'
        ));
    }
}
