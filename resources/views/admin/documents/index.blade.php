@extends('layouts.admin')

@section('header_title', 'Gestion Documentaire Globale (GED)')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Documents</h1>
        <p class="text-on-surface-variant font-body">Téléversez et gérez les documents visibles par les différents profils.</p>
    </div>

    <!-- Upload Form -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm mb-8">
        <h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">upload_file</span> Téléverser un nouveau document
        </h2>
        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div class="space-y-2 md:col-span-1">
                <label for="title" class="text-sm font-bold text-on-surface-variant">Titre du document</label>
                <input type="text" name="title" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3">
            </div>
            <div class="space-y-2 md:col-span-1">
                <label for="file" class="text-sm font-bold text-on-surface-variant">Fichier</label>
                <input type="file" name="file" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-2.5">
            </div>
            <div class="space-y-2 md:col-span-1">
                <label for="role_visibility" class="text-sm font-bold text-on-surface-variant">Visibilité</label>
                <select name="role_visibility" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3">
                    <option value="all">Tous</option>
                    <option value="coordinateur">Coordinateurs uniquement</option>
                    <option value="formateur">Formateurs uniquement</option>
                    <option value="candidat">Candidats uniquement</option>
                </select>
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold hover:shadow-lg transition-all">Téléverser</button>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
            <h3 class="font-bold text-on-surface">Documents Actifs</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-surface-container-low/50 text-on-surface-variant font-bold border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4">Titre</th>
                    <th class="px-6 py-4">Fichier</th>
                    <th class="px-6 py-4">Visibilité</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($activeDocuments as $doc)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-on-surface">{{ $doc->title }}</td>
                    <td class="px-6 py-4"><a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-primary hover:underline flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">download</span> Ouvrir</a></td>
                    <td class="px-6 py-4"><span class="bg-surface-variant text-on-surface-variant px-2 py-1 rounded text-xs font-bold uppercase">{{ $doc->role_visibility }}</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <form action="{{ route('admin.documents.archive', $doc->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-amber-600 hover:text-amber-800 font-bold px-2 py-1">Archiver</button>
                        </form>
                        <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce document ?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-bold px-2 py-1">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($activeDocuments->isEmpty())
                <tr><td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Aucun document actif.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Archived Documents -->
    @if($archivedDocuments->isNotEmpty())
    <div class="bg-surface-container-lowest opacity-75 rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined">inventory_2</span>
            <h3 class="font-bold text-on-surface">Documents Archivés</h3>
        </div>
        <table class="w-full text-left text-sm">
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($archivedDocuments as $doc)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 text-on-surface line-through">{{ $doc->title }}</td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <form action="{{ route('admin.documents.archive', $doc->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-emerald-600 hover:text-emerald-800 font-bold px-2 py-1">Restaurer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
