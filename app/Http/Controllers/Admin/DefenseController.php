<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Defense;
use App\Models\Enrollment;

class DefenseController extends Controller
{
    public function index()
    {
        $defenses = Defense::with(['enrollment.user', 'enrollment.seminar'])->latest()->get();
        
        // Obtenir les inscriptions approuvées qui n'ont pas encore de soutenance
        $enrollments = Enrollment::with(['user', 'seminar'])
            ->where('status', 'approved')
            ->whereDoesntHave('defense')
            ->get();

        return view('admin.defenses.index', compact('defenses', 'enrollments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'defense_date' => 'required|date',
        ]);

        Defense::create([
            'enrollment_id' => $request->enrollment_id,
            'defense_date' => $request->defense_date,
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.defenses.index')->with('success', 'Soutenance planifiée.');
    }

    public function update(Request $request, Defense $defense)
    {
        $request->validate([
            'defense_date' => 'required|date',
            'grade' => 'nullable|numeric|min:0|max:20',
            'jury_comments' => 'nullable|string',
            'status' => 'required|in:scheduled,passed,failed',
        ]);

        $defense->update($request->all());

        return redirect()->route('admin.defenses.index')->with('success', 'Soutenance mise à jour.');
    }

    public function destroy(Defense $defense)
    {
        $defense->delete();
        return redirect()->route('admin.defenses.index')->with('success', 'Soutenance supprimée.');
    }
}
