<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Message;
use App\Models\Cohort;
use App\Models\User;
use App\Notifications\AlertMessageNotification;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::whereNotNull('user_id')->latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $cohorts = Cohort::where('status', '!=', 'completed')->get();
        $roles = \App\Models\Role::whereIn('name', ['Candidat', 'Formateur', 'Coordinateur'])->get();
        $seminars = \App\Models\Seminar::all();
        $departments = \App\Models\Department::all();
        return view('admin.messages.create', compact('cohorts', 'roles', 'seminars', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cohort_id' => 'nullable|exists:cohorts,id',
            'target_roles' => 'nullable|array',
            'target_seminars' => 'nullable|array',
            'target_departments' => 'nullable|array',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'body' => $request->body,
            'cohort_id' => $request->cohort_id,
            'target_roles' => $request->target_roles,
            'target_seminars' => $request->target_seminars,
            'target_departments' => $request->target_departments,
        ]);

        // Fetch users to notify
        $query = User::query();

        // 1. Filter by Roles if provided
        if (!empty($request->target_roles)) {
            $query->whereIn('role_id', $request->target_roles);
        } else {
            // Default to Candidates and Trainers if no role specified, or just Candidates
            $query->whereHas('role', function($q) {
                $q->whereIn('name', ['Candidat', 'Formateur']);
            });
        }

        // 2. Filter by Seminars if provided
        if (!empty($request->target_seminars)) {
            $seminarIds = $request->target_seminars;
            $query->where(function ($q) use ($seminarIds) {
                $q->whereHas('registrations', function ($q2) use ($seminarIds) {
                    $q2->whereIn('seminar_id', $seminarIds)->where('status', 'approved');
                })->orWhereIn('email', \App\Models\Trainer::whereHas('seminars', function($q3) use ($seminarIds) {
                    $q3->whereIn('seminars.id', $seminarIds);
                })->pluck('email'));
            });
        }

        // 3. Filter by Departments if provided
        if (!empty($request->target_departments)) {
            $departmentIds = $request->target_departments;
            $query->where(function ($q) use ($departmentIds) {
                $q->whereHas('registrations.seminar.departments', function ($q2) use ($departmentIds) {
                    $q2->whereIn('departments.id', $departmentIds);
                })->orWhereIn('email', \App\Models\Trainer::whereHas('seminars.departments', function($q3) use ($departmentIds) {
                    $q3->whereIn('departments.id', $departmentIds);
                })->pluck('email'));
            });
        }

        $users = $query->get();

        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new AlertMessageNotification($message));
        }

        \App\Helpers\ActivityLogger::log(
            'Alerte Globale',
            "Un message '{$message->subject}' a été envoyé à {$users->count()} utilisateur(s)."
        );

        return redirect()->route('admin.messages.index')->with('success', 'Message envoyé avec succès à ' . $users->count() . ' utilisateur(s) !');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
