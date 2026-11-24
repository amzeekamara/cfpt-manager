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
            ->withCount(['seminars' => fn($q) => $q->where('training_type', '!=', 'continue')])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.cohorts.index', compact('cohorts'));
    }

    public function create()
    {
        $previousCohorts = Cohort::with(['seminars' => function ($q) {
            $q->where('training_type', '!=', 'continue')->with(['departments', 'topics']);
        }])->orderByDesc('start_date')->get();

        return view('admin.cohorts.create', compact('previousCohorts'));
    }

    public function store(CohortRequest $request)
    {
        $validated = $request->validated();

        $cohort = Cohort::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        // Reconduire les filières, leurs départements et leurs matières depuis une année académique passée
        $clonedCount = 0;
        if (!empty($request->selected_seminar_ids) && is_array($request->selected_seminar_ids)) {
            $sourceSeminars = \App\Models\Seminar::with(['departments', 'topics'])
                ->whereIn('id', $request->selected_seminar_ids)
                ->get();

            foreach ($sourceSeminars as $src) {
                $newFiliere = \App\Models\Seminar::create([
                    'cohort_id' => $cohort->id,
                    'title' => $src->title,
                    'description' => $src->description,
                    'image' => $src->image,
                    'start_date' => $cohort->start_date,
                    'end_date' => $cohort->end_date,
                    'max_participants' => $src->max_participants,
                    'status' => 'active',
                    'training_type' => $src->training_type ?? 'initiale',
                ]);

                // Conserver les départements rattachés
                $newFiliere->departments()->sync($src->departments->pluck('id')->toArray());

                // Conserver les matières / modules
                foreach ($src->topics as $topic) {
                    $newFiliere->topics()->create([
                        'name' => $topic->name,
                        'is_active' => true,
                    ]);
                }
                $clonedCount++;
            }
        }

        ActivityLogger::log(
            'Création d\'Année Académique',
            "L'année académique '{$cohort->title}' a été créée avec {$clonedCount} filière(s) reconduite(s).",
            $cohort
        );

        return redirect()->route('admin.cohorts.index')->with('success', "Année académique créée avec succès ({$clonedCount} filière(s) reconduite(s) que vous pouvez modifier au besoin) !");
    }


    public function edit(Cohort $cohort)
    {
        $topics = \App\Models\Topic::where('is_active', true)->orderBy('name')->get();
        return view('admin.cohorts.edit', compact('cohort', 'topics'));
    }

    public function update(CohortRequest $request, Cohort $cohort)
    {
        $validated = $request->validated();

        $cohort->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        ActivityLogger::log(
            'Modification d\'Année Académique',
            "L'année académique '{$cohort->title}' a été mise à jour.",
            $cohort
        );

        return redirect()->route('admin.cohorts.index')->with('success', 'Année académique mise à jour avec succès !');
    }

    public function destroy(Cohort $cohort)
    {
        if ($cohort->seminars()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une année académique qui contient des filières.');
        }

        $title = $cohort->title;
        $cohort->delete();

        ActivityLogger::log(
            'Suppression d\'Année Académique',
            "L'année académique '{$title}' a été supprimée.",
            null
        );

        return redirect()->route('admin.cohorts.index')->with('success', 'Année académique supprimée.');
    }

    public function toggleStatus(Cohort $cohort)
    {
        $wasCompleted = ($cohort->status === 'completed');

        // Si on active cette édition, on désactive les autres
        if ($cohort->status !== 'active') {
            Cohort::where('status', 'active')->update(['status' => 'completed']);
            $cohort->update(['status' => 'active']);
            $action = "activée";
            $message = "L'édition '{$cohort->title}' a été réactivée avec succès et est désormais active dans vos cycles en cours.";
        } else {
            $cohort->update(['status' => 'completed']);
            $action = "archivée";
            $message = "L'édition '{$cohort->title}' a été archivée et déplacée dans l'historique.";
        }

        ActivityLogger::log(
            'Changement de Statut d\'Édition',
            "L'édition '{$cohort->title}' a été {$action}.",
            $cohort
        );

        if ($wasCompleted) {
            return redirect()->route('admin.cohorts.index')->with('success', $message);
        }

        return back()->with('success', $message);
    }
}
