<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Cohort;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\SeminarRequest;

class SeminarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $deptId = ($user && $user->isDepartmentHead()) ? $user->getEffectiveDepartmentId() : null;

        $activeSeminars = Seminar::where('status', 'active')
            ->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->with(['cohort', 'departments', 'topics', 'trainers', 'registrations' => function($q) { $q->where('status', 'approved'); }])
            ->orderBy('start_date', 'asc')
            ->get();
        $draftSeminars = Seminar::where('status', 'draft')
            ->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->with(['cohort', 'departments', 'topics', 'trainers', 'registrations' => function($q) { $q->where('status', 'approved'); }])
            ->get();
        $pastSeminars = Seminar::where('status', 'completed')
            ->when($deptId, fn($q) => $q->whereHas('departments', fn($dq) => $dq->where('departments.id', $deptId)))
            ->with(['cohort', 'departments', 'topics', 'trainers', 'registrations' => function($q) { $q->where('status', 'approved'); }])
            ->orderBy('end_date', 'desc')
            ->get();

        return view('admin.seminars', compact('activeSeminars', 'draftSeminars', 'pastSeminars'));
    }

    public function create()
    {
        $cohorts = Cohort::orderBy('start_date', 'desc')->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.seminars.create', compact('cohorts', 'departments'));
    }

    public function store(SeminarRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('seminars', 'public');
            $validated['image'] = $path;
        }

        $seminar = Seminar::create($validated);
        $seminar->departments()->attach($request->departments);

        if (!empty($request->topics) && is_array($request->topics)) {
            $topicsData = collect($request->topics)
                ->filter()
                ->map(fn($name) => ['name' => $name, 'is_active' => true])
                ->all();
            $seminar->topics()->createMany($topicsData);
        }

        ActivityLogger::log('Création de Séminaire', "Le séminaire '{$seminar->title}' a été créé.", $seminar);

        return redirect()->route('admin.seminars')->with('success', 'Séminaire créé avec succès !');
    }

    public function edit(Seminar $seminar)
    {
        $cohorts = Cohort::orderBy('start_date', 'desc')->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.seminars.edit', compact('seminar', 'cohorts', 'departments'));
    }

    public function update(SeminarRequest $request, Seminar $seminar)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($seminar->image) {
                Storage::disk('public')->delete($seminar->image);
            }
            $validated['image'] = $request->file('image')->store('seminars', 'public');
        }

        $seminar->update($validated);
        $seminar->departments()->sync($request->departments);

        $seminar->topics()->delete();
        if (!empty($request->topics) && is_array($request->topics)) {
            $topicsData = collect($request->topics)
                ->filter()
                ->map(fn($name) => ['name' => $name, 'is_active' => true])
                ->all();
            $seminar->topics()->createMany($topicsData);
        }

        ActivityLogger::log(
            'Modification de Séminaire',
            "Le séminaire '{$seminar->title}' a été modifié.",
            $seminar
        );

        return redirect()->route('admin.seminars')->with('success', 'Séminaire mis à jour avec succès !');
    }

    public function destroy(Seminar $seminar)
    {
        if ($seminar->image) {
            Storage::disk('public')->delete($seminar->image);
        }
        $seminar->delete();

        ActivityLogger::log(
            'Suppression de Séminaire',
            "Le séminaire '{$seminar->title}' a été supprimé.",
            null
        );

        return redirect()->route('admin.seminars')->with('success', 'Séminaire supprimé avec succès !');
    }

    public function toggleStatus(\App\Models\Seminar $seminar)
    {
        $seminar->update(['status' => $seminar->status === 'active' ? 'completed' : 'active']);
        \App\Helpers\ActivityLogger::log(
            'Modification de statut',
            "Le statut du séminaire '{$seminar->title}' a été modifié.",
            $seminar
        );
        return back()->with('success', 'Statut du séminaire mis à jour !');
    }
}