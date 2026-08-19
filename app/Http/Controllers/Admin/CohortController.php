<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use App\Http\Requests\Admin\CohortRequest;

class CohortController extends Controller
{
    public function index()
    {
        $cohorts = Cohort::where('status', '!=', 'completed')
            ->withCount('seminars')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.cohorts.index', compact('cohorts'));
    }
    public function create()
    {
        $topics = \App\Models\Topic::where('is_active', true)->orderBy('name')->get();
        return view('admin.cohorts.create', compact('topics'));
    }

    public function store(CohortRequest $request)
    {
        $validated = $request->validated();

        $cohort = Cohort::create($validated);

        ActivityLogger::log(
            'Création d\'Édition',
            "L'édition '{$cohort->title}' avec un budget de {$cohort->total_budget} FCFA a été créée.",
            $cohort
        );

        return redirect()->route('admin.cohorts.index')->with('success', 'Édition créée avec succès !');
    }


    public function edit(Cohort $cohort)
    {
        $topics = \App\Models\Topic::where('is_active', true)->orderBy('name')->get();
        return view('admin.cohorts.edit', compact('cohort', 'topics'));
    }

    public function update(CohortRequest $request, Cohort $cohort)
    {
        $validated = $request->validated();

        $cohort->update($validated);

        ActivityLogger::log(
            'Modification d\'Édition',
            "L'édition '{$cohort->title}' a été mise à jour.",
            $cohort
        );

        return redirect()->route('admin.cohorts.index')->with('success', 'Édition mise à jour avec succès !');
    }

    public function destroy(Cohort $cohort)
    {
        if ($cohort->seminars()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une édition qui contient des séminaires.');
        }

        $title = $cohort->title;
        $cohort->delete();

        ActivityLogger::log(
            'Suppression d\'Édition',
            "L'édition '{$title}' a été supprimée.",
            null
        );

        return redirect()->route('admin.cohorts.index')->with('success', 'Édition supprimée.');
    }

    public function toggleStatus(Cohort $cohort)
    {
        // Si on active cette édition, on désactive les autres
        if ($cohort->status !== 'active') {
            Cohort::where('status', 'active')->update(['status' => 'completed']);
            $cohort->update(['status' => 'active']);
            $action = "activée";
        } else {
            $cohort->update(['status' => 'completed']);
            $action = "archivée";
        }

        ActivityLogger::log(
            'Changement de Statut d\'Édition',
            "L'édition '{$cohort->title}' a été {$action}.",
            $cohort
        );

        return back()->with('success', "L'édition a été {$action}.");
    }
}
