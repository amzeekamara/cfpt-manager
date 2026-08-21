<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        $activeResources = Resource::where('is_archived', false)->latest()->get();
        $archivedResources = Resource::where('is_archived', true)->latest()->get();
        
        $cohorts = \App\Models\Cohort::orderBy('created_at', 'desc')->get();
        $seminars = \App\Models\Seminar::all();
        $topics = \App\Models\Topic::where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();

        return view('admin.resources.index', compact('activeResources', 'archivedResources', 'cohorts', 'seminars', 'topics', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'role_visibility' => 'required|in:all,formateur,candidat,coordinateur',
            'seminar_id' => 'nullable|exists:seminars,id',
        ]);

        $path = $request->file('file')->store('ged_resources', 'public');

        Resource::create([
            'title' => $request->title,
            'file_path' => $path,
            'role_visibility' => $request->role_visibility,
            'seminar_id' => $request->seminar_id ?: null,
            'is_archived' => false,
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Document téléversé avec succès.');
    }

    public function archive(Resource $resource)
    {
        $resource->update(['is_archived' => !$resource->is_archived]);
        $status = $resource->is_archived ? 'archivé' : 'restauré';
        return redirect()->route('admin.resources.index')->with('success', "Le resource a été $status.");
    }

    public function destroy(Resource $resource)
    {
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('success', 'Resource supprimé définitivement.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:candidats,formateurs,depenses',
            'format' => 'required|in:pdf,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'payment_status' => 'nullable|string',
            'cohort_id' => 'nullable|exists:cohorts,id',
            'seminar_id' => 'nullable|exists:seminars,id',
            'topic_id' => 'nullable|exists:topics,id',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $type = $request->type;
        $format = $request->format;
        $filters = $request->only(['date_from', 'date_to', 'payment_status', 'cohort_id', 'seminar_id', 'topic_id', 'department_id']);

        $filename = "export_{$type}_" . date('Ymd_His');

        if ($format === 'excel') {
            $exportClass = match ($type) {
                'candidats' => new \App\Exports\CandidatesExport($filters),
                'formateurs' => new \App\Exports\TrainersExport($filters),
                'depenses' => new \App\Exports\ExpensesExport($filters),
            };
            return \Maatwebsite\Excel\Facades\Excel::download($exportClass, $filename . '.xlsx');
        }

        if ($format === 'pdf') {
            $items = match ($type) {
                'candidats' => (new \App\Exports\CandidatesExport($filters))->collection(),
                'formateurs' => (new \App\Exports\TrainersExport($filters))->collection(),
                'depenses' => (new \App\Exports\ExpensesExport($filters))->collection(),
            };

            $view = match ($type) {
                'candidats' => 'exports.pdf.candidates',
                'formateurs' => 'exports.pdf.trainers',
                'depenses' => 'exports.pdf.expenses',
            };

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('items'));
            return $pdf->download($filename . '.pdf');
        }

        return redirect()->back();
    }
}
