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
        return view('admin.documents.index', compact('activeDocuments', 'archivedDocuments'));
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
}
