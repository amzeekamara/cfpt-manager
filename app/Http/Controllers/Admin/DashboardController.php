<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Seminar;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $editions = \App\Models\Edition::orderBy('start_date', 'desc')->get();
        $editionId = $request->get('edition_id');

        // Determine current/selected edition
        $selectedEdition = null;
        if ($editionId) {
            $selectedEdition = \App\Models\Edition::find($editionId);
        }

        if (!$selectedEdition) {
            $selectedEdition = \App\Models\Edition::where('status', 'active')->latest()->first() 
                ?? \App\Models\Edition::latest()->first();
        }

        $stats = [
            'users_count' => User::count(),
            'active_seminars_count' => Seminar::when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->where('edition_id', $selectedEdition->id);
            })->where('status', 'active')->count(),
            'pending_enrollments_count' => Enrollment::when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->whereHas('seminar', function($sq) use ($selectedEdition) {
                    $sq->where('edition_id', $selectedEdition->id);
                });
            })->where('status', 'pending')->count(),
            'approved_enrollments_count' => Enrollment::when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->whereHas('seminar', function($sq) use ($selectedEdition) {
                    $sq->where('edition_id', $selectedEdition->id);
                });
            })->where('status', 'approved')->count(),
            'rejected_enrollments_count' => Enrollment::when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->whereHas('seminar', function($sq) use ($selectedEdition) {
                    $sq->where('edition_id', $selectedEdition->id);
                });
            })->where('status', 'rejected')->count(),
            'total_editions_count' => $editions->count(),
            'completion_rate' => 96,
        ];

        $recentEnrollments = Enrollment::with(['user', 'seminar'])
            ->when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->whereHas('seminar', function($sq) use ($selectedEdition) {
                    $sq->where('edition_id', $selectedEdition->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingSeminars = Seminar::where('status', 'active')
            ->when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->where('edition_id', $selectedEdition->id);
            })
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $recentLogs = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentEnrollments', 
            'upcomingSeminars', 
            'recentLogs', 
            'editions', 
            'selectedEdition'
        ));
    }
}
