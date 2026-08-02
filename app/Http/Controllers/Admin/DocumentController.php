<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $activeDocuments = Document::where('is_archived', false)->latest()->get();
        $archivedDocuments = Document::where('is_archived', true)->latest()->get();
        
        $editions = \App\Models\Edition::orderBy('created_at', 'desc')->get();
        $seminars = \App\Models\Seminar::all();

        return view('admin.documents.index', compact('activeDocuments', 'archivedDocuments', 'editions', 'seminars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'role_visibility' => 'required|in:all,formateur,candidat,coordinateur',
        ]);

        $path = $request->file('file')->store('ged_documents', 'public');

        Document::create([
            'title' => $request->title,
            'file_path' => $path,
            'role_visibility' => $request->role_visibility,
            'is_archived' => false,
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document téléversé avec succès.');
    }

    public function archive(Document $document)
    {
        $document->update(['is_archived' => !$document->is_archived]);
        $status = $document->is_archived ? 'archivé' : 'restauré';
        return redirect()->route('admin.documents.index')->with('success', "Le document a été $status.");
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->route('admin.documents.index')->with('success', 'Document supprimé définitivement.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:candidats,formateurs,depenses',
            'format' => 'required|in:pdf,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'payment_status' => 'nullable|string',
            'edition_id' => 'nullable|exists:editions,id',
            'seminar_id' => 'nullable|exists:seminars,id'
        ]);

        $type = $request->type;
        $format = $request->format;
        $filters = $request->only(['date_from', 'date_to', 'payment_status', 'edition_id', 'seminar_id']);

        $filename = "export_{$type}_" . date('Ymd_His');

        if ($format === 'excel') {
            $exportClass = match ($type) {
                'candidats' => new \App\Exports\CandidatesExport($filters),
                'formateurs' => new \App\Exports\IntervenantsExport($filters),
                'depenses' => new \App\Exports\ExpensesExport($filters),
            };
            return \Maatwebsite\Excel\Facades\Excel::download($exportClass, $filename . '.xlsx');
        }

        if ($format === 'pdf') {
            $items = match ($type) {
                'candidats' => (new \App\Exports\CandidatesExport($filters))->collection(),
                'formateurs' => (new \App\Exports\IntervenantsExport($filters))->collection(),
                'depenses' => (new \App\Exports\ExpensesExport($filters))->collection(),
            };

            $view = match ($type) {
                'candidats' => 'exports.pdf.candidates',
                'formateurs' => 'exports.pdf.intervenants',
                'depenses' => 'exports.pdf.expenses',
            };

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('items'));
            return $pdf->download($filename . '.pdf');
        }

        return redirect()->back();
    }
}
