<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\Edition;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Storage;

class SeminarController extends Controller
{
    public function index()
    {
        $activeSeminars = Seminar::where('status', 'active')->with('edition')->orderBy('start_date', 'asc')->get();
        $draftSeminars = Seminar::where('status', 'draft')->with('edition')->get();
        $pastSeminars = Seminar::where('status', 'completed')->with('edition')->orderBy('end_date', 'desc')->get();

        return view('admin.seminars', compact('activeSeminars', 'draftSeminars', 'pastSeminars'));
    }

    public function create()
    {
        $editions = Edition::orderBy('start_date', 'desc')->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.seminars.create', compact('editions', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'edition_id' => 'nullable|exists:editions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,active,completed',
        ]);

        if ($request->filled('department_name')) {
            \App\Models\Department::firstOrCreate(['name' => $request->department_name], ['is_active' => true]);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('seminars', 'public');
            $validated['image'] = $path;
        }

        $seminar = Seminar::create($validated);

        ActivityLogger::log(
            'Création de Séminaire',
            "Le séminaire '{$seminar->title}' (Dépt: {$seminar->department_name}) a été créé.",
            $seminar
        );

        return redirect()->route('admin.seminars')->with('success', 'Séminaire créé avec succès !');
    }

    public function edit(Seminar $seminar)
    {
        $editions = Edition::orderBy('start_date', 'desc')->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.seminars.edit', compact('seminar', 'editions', 'departments'));
    }

    public function update(Request $request, Seminar $seminar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'edition_id' => 'nullable|exists:editions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,active,completed',
        ]);

        if ($request->filled('department_name')) {
            \App\Models\Department::firstOrCreate(['name' => $request->department_name], ['is_active' => true]);
        }

        if ($request->hasFile('image')) {
            if ($seminar->image) {
                Storage::disk('public')->delete($seminar->image);
            }
            $path = $request->file('image')->store('seminars', 'public');
            $validated['image'] = $path;
        }

        $seminar->update($validated);

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
}
