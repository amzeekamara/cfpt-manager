<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class EditionController extends Controller
{
    public function index()
    {
        $editions = Edition::where('status', '!=', 'completed')
            ->withCount('seminars')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.editions.index', compact('editions'));
    }
    public function create()
    {
        $themes = \App\Models\Theme::where('is_active', true)->orderBy('name')->get();
        return view('admin.editions.create', compact('themes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'total_budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'themes' => 'nullable|string',
        ]);

        if ($request->filled('themes')) {
            \App\Models\Theme::firstOrCreate(['name' => $request->themes], ['is_active' => true]);
        }

        $edition = Edition::create($validated);

        ActivityLogger::log(
            'Création d\'Édition',
            "L'édition '{$edition->title}' avec un budget de {$edition->total_budget} FCFA a été créée.",
            $edition
        );

        return redirect()->route('admin.editions.index')->with('success', 'Édition créée avec succès !');
    }


    public function edit(Edition $edition)
    {
        $themes = \App\Models\Theme::where('is_active', true)->orderBy('name')->get();
        return view('admin.editions.edit', compact('edition', 'themes'));
    }

    public function update(Request $request, Edition $edition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'total_budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'themes' => 'nullable|string',
        ]);

        if ($request->filled('themes')) {
            \App\Models\Theme::firstOrCreate(['name' => $request->themes], ['is_active' => true]);
        }

        $edition->update($validated);

        ActivityLogger::log(
            'Modification d\'Édition',
            "L'édition '{$edition->title}' a été mise à jour.",
            $edition
        );

        return redirect()->route('admin.editions.index')->with('success', 'Édition mise à jour avec succès !');
    }

    public function destroy(Edition $edition)
    {
        if ($edition->seminars()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une édition qui contient des séminaires.');
        }

        $title = $edition->title;
        $edition->delete();

        ActivityLogger::log(
            'Suppression d\'Édition',
            "L'édition '{$title}' a été supprimée.",
            null
        );

        return redirect()->route('admin.editions.index')->with('success', 'Édition supprimée.');
    }

    public function toggleStatus(Edition $edition)
    {
        // Si on active cette édition, on désactive les autres
        if ($edition->status !== 'active') {
            Edition::where('status', 'active')->update(['status' => 'completed']);
            $edition->update(['status' => 'active']);
            $action = "activée";
        } else {
            $edition->update(['status' => 'completed']);
            $action = "archivée";
        }

        ActivityLogger::log(
            'Changement de Statut d\'Édition',
            "L'édition '{$edition->title}' a été {$action}.",
            $edition
        );

        return back()->with('success', "L'édition a été {$action}.");
    }
}
