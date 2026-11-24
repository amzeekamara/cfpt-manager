<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Classroom;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Department::with(['head', 'classrooms'])->withCount(['seminars', 'classrooms']);

        if ($user && $user->isDepartmentHead() && $user->getEffectiveDepartmentId()) {
            $query->where('id', $user->getEffectiveDepartmentId());
        }

        $departments = $query->orderBy('name')->get();

        // Eligible users to be head of department (Staff & Chef de Département)
        $eligibleHeads = User::with('role')->whereHas('role', function ($q) {
            $q->whereIn('name', ['Chef de Département', 'Administrateur', 'Coordinateur', 'Admin']);
        })->orderBy('name')->get();

        return view('admin.departments.index', compact('departments', 'eligibleHeads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'head_user_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:500',
        ]);

        $dept = Department::create([
            'name' => $request->name,
            'head_user_id' => $request->head_user_id,
            'description' => $request->description,
            'is_active' => true,
        ]);

        if ($request->filled('head_user_id')) {
            User::where('id', $request->head_user_id)->update(['department_id' => $dept->id]);
        }

        return redirect()->route('admin.departments.index')->with('success', 'Département ajouté avec succès.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'head_user_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:500',
        ]);

        $department->update([
            'name' => $request->name,
            'head_user_id' => $request->head_user_id,
            'description' => $request->description,
        ]);

        if ($request->filled('head_user_id')) {
            User::where('id', $request->head_user_id)->update(['department_id' => $department->id]);
        }

        return redirect()->route('admin.departments.index')->with('success', 'Département mis à jour.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Département supprimé.');
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:cours,atelier,labo',
        ]);

        Classroom::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Salle / Atelier ajouté au département.');
    }

    public function destroyClassroom(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Salle supprimée.');
    }
}

