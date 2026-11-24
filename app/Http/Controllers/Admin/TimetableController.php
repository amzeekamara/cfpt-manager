<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Department;
use App\Models\Classroom;
use App\Models\Seminar;
use App\Models\Topic;
use App\Models\Trainer;
use Carbon\Carbon;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $effectiveDeptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : $request->get('department_id');

        $departments = Department::where('is_active', true)
            ->when($user && $user->isDepartmentHead() && $user->getEffectiveDepartmentId(), function ($q) use ($user) {
                $q->where('id', $user->getEffectiveDepartmentId());
            })
            ->with('classrooms')
            ->orderBy('name')
            ->get();

        $seminars = Seminar::where('status', 'active')
            ->when($effectiveDeptId, function ($q) use ($effectiveDeptId) {
                $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $effectiveDeptId));
            })
            ->with(['topics', 'departments'])
            ->withCount(['registrations as enrolled_count' => fn($q) => $q->where('status', 'approved')])
            ->orderBy('title')
            ->get();

        $trainers = Trainer::orderBy('name')->get();
        $classrooms = Classroom::where('is_active', true)
            ->when($effectiveDeptId, fn($q) => $q->where('department_id', $effectiveDeptId))
            ->with('department')
            ->orderBy('name')
            ->get();

        $timetablesQuery = Timetable::with(['department', 'seminar', 'topic', 'trainer', 'classroom'])
            ->when($effectiveDeptId, fn($q) => $q->where('department_id', $effectiveDeptId))
            ->when($request->filled('seminar_id'), fn($q) => $q->where('seminar_id', $request->seminar_id))
            ->when($request->filled('trainer_id'), fn($q) => $q->where('trainer_id', $request->trainer_id))
            ->when($request->filled('classroom_id'), fn($q) => $q->where('classroom_id', $request->classroom_id));

        $timetables = $timetablesQuery->orderBy('start_time')->get();

        // All active slots across all departments to compute live room & trainer availability
        $allSlots = Timetable::with(['classroom', 'trainer', 'seminar', 'topic'])
            ->where('is_active', true)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'day_of_week' => $s->day_of_week,
                    'start_time' => Carbon::parse($s->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($s->end_time)->format('H:i'),
                    'classroom_id' => (string) $s->classroom_id,
                    'classroom_name' => $s->classroom?->name ?? 'Salle',
                    'trainer_id' => (string) $s->trainer_id,
                    'trainer_name' => $s->trainer?->name ?? 'Formateur',
                    'seminar_id' => (string) $s->seminar_id,
                    'seminar_title' => $s->seminar?->title ?? 'Filière',
                    'topic_name' => $s->topic?->name ?? 'Tronc commun',
                ];
            });

        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

        return view('admin.timetables.index', compact(
            'timetables',
            'allSlots',
            'departments',
            'seminars',
            'trainers',
            'classrooms',
            'days',
            'effectiveDeptId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'seminar_id' => 'required|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',
            'trainer_id' => 'required|exists:trainers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'notes' => 'nullable|string|max:255',
        ]);

        // Calculate planned_hours automatically from start_time & end_time
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $plannedHours = round(abs($end->diffInMinutes($start)) / 60, 2);

        // 1. Check classroom capacity vs enrolled students
        $classroom = Classroom::findOrFail($request->classroom_id);
        $enrolledQuery = \App\Models\Registration::where('seminar_id', $request->seminar_id)->where('status', 'approved');
        if ($request->filled('topic_id')) {
            $enrolledQuery->where('topic_id', $request->topic_id);
        }
        $enrolledCount = $enrolledQuery->count();

        // 2. Check classroom availability conflict on the same day & time slot (Une salle occupée n'est plus disponible)
        $roomConflict = Timetable::with(['trainer', 'seminar'])
            ->where('classroom_id', $request->classroom_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->first();

        if ($roomConflict) {
            $occTrainer = $roomConflict->trainer?->name ?? 'un formateur';
            $occFiliere = $roomConflict->seminar?->title ?? 'une filière';
            $occStart = Carbon::parse($roomConflict->start_time)->format('H:i');
            $occEnd = Carbon::parse($roomConflict->end_time)->format('H:i');
            return back()->withInput()->with('error', "Salle indisponible : La salle « {$classroom->name} » est déjà occupée le {$request->day_of_week} de {$occStart} à {$occEnd} par {$occTrainer} ({$occFiliere}). Elle n'est plus disponible sur ce créneau.");
        }

        // 3. Check trainer availability conflict (Un formateur ne peut dérouler des cours que dans une seule salle à une heure déterminée)
        $trainerConflict = Timetable::with(['classroom', 'seminar'])
            ->where('trainer_id', $request->trainer_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->first();

        if ($trainerConflict) {
            $trName = $trainerConflict->trainer?->name ?? 'Ce formateur';
            $trRoom = $trainerConflict->classroom?->name ?? 'une autre salle';
            $trFiliere = $trainerConflict->seminar?->title ?? 'une autre filière';
            $trStart = Carbon::parse($trainerConflict->start_time)->format('H:i');
            $trEnd = Carbon::parse($trainerConflict->end_time)->format('H:i');
            return back()->withInput()->with('error', "Duplication de formateur bloquée : {$trName} déroule déjà un cours le {$request->day_of_week} de {$trStart} à {$trEnd} dans la salle « {$trRoom} » ({$trFiliere}). Un formateur ne peut enseigner que dans une seule salle à la fois.");
        }

        // 4. Check student class (filière) conflict on the same day & time slot
        $classConflict = Timetable::with(['classroom', 'trainer'])
            ->where('seminar_id', $request->seminar_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($q) use ($request) {
                if ($request->filled('topic_id')) {
                    $q->whereNull('topic_id')->orWhere('topic_id', $request->topic_id);
                }
            })
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->first();

        if ($classConflict) {
            $clRoom = $classConflict->classroom?->name ?? 'une salle';
            $clStart = Carbon::parse($classConflict->start_time)->format('H:i');
            $clEnd = Carbon::parse($classConflict->end_time)->format('H:i');
            return back()->withInput()->with('error', "Conflit pour les étudiants : Cette filière a déjà un cours programmé le {$request->day_of_week} de {$clStart} à {$clEnd} en salle « {$clRoom} ».");
        }

        Timetable::create([
            'department_id' => $request->department_id,
            'seminar_id' => $request->seminar_id,
            'topic_id' => $request->topic_id,
            'trainer_id' => $request->trainer_id,
            'classroom_id' => $request->classroom_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'planned_hours' => $plannedHours > 0 ? $plannedHours : 2,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        // Also ensure trainer is attached to seminar
        $trainer = Trainer::find($request->trainer_id);
        if ($trainer && !$trainer->seminars->contains('id', $request->seminar_id)) {
            $trainer->seminars()->attach($request->seminar_id, ['remuneration' => 0, 'payment_status' => 'unpaid']);
        }

        $warningMsg = null;
        if ($enrolledCount > $classroom->capacity) {
            $warningMsg = "Attention : La liste des étudiants inscrits ({$enrolledCount} étudiants) dépasse la capacité de la salle {$classroom->name} ({$classroom->capacity} places).";
        }

        $redirect = redirect()->route('admin.timetables.index')->with('success', "Créneau d'emploi du temps ajouté ({$plannedHours}h prévues).");
        if ($warningMsg) {
            $redirect->with('warning', $warningMsg);
        }
        return $redirect;
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        return redirect()->route('admin.timetables.index')->with('success', 'Créneau supprimé de l\'emploi du temps.');
    }
}
