<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Seminar;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $seminarId = $request->get('seminar_id');
        $date = $request->get('date');
        $status = $request->get('status');

        $user = auth()->user();
        $deptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : null;

        $query = Attendance::with(['registration.user', 'registration.seminar', 'topic', 'trainer'])
            ->when($deptId, function ($q) use ($deptId) {
                $q->whereHas('registration.seminar.departments', fn($dq) => $dq->where('departments.id', $deptId));
            });

        if ($seminarId) {
            $query->whereHas('registration', function($q) use ($seminarId) {
                $q->where('seminar_id', $seminarId);
            });
        }

        if ($date) {
            $query->whereDate('session_date', $date);
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Calculate stats with a single grouped query
        $statusCounts = (clone $query)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();
        $totalPresences = $statusCounts['present'] ?? 0;
        $totalAbsences = $statusCounts['absent'] ?? 0;
        $total = $totalPresences + $totalAbsences;
        $attendanceRate = $total > 0 ? round(($totalPresences / $total) * 100, 1) : 0;

        $attendances = $query->orderByDesc('session_date')->paginate(50);
        $seminars = Seminar::when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->orderBy('title')
            ->get();

        return view('admin.attendances.index', compact('attendances', 'seminars', 'seminarId', 'date', 'status', 'totalPresences', 'totalAbsences', 'total', 'attendanceRate'));
    }
}
