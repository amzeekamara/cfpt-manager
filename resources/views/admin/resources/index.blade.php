@extends('layouts.admin')

@section('header_title', 'Gestion des Documents')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Documents</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Téléversez et gérez les documents visibles par les différents profils.</p>
            </div>
        </div>

    <!-- Upload Form -->
    <div class="bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm mb-8">
        <h2 class="text-base font-bold text-[#4B6A95] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">upload_file</span> Téléverser un nouveau document
        </h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            @csrf
            <div class="space-y-1.5 md:col-span-1">
                <label for="title" class="text-xs font-bold text-slate-700">Titre du document</label>
                <input type="text" name="title" required placeholder="ex: Guide 5S-KAIZEN" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
            </div>
            <div class="space-y-1.5 md:col-span-1">
                <label for="file" class="text-xs font-bold text-slate-700">Fichier</label>
                <input type="file" name="file" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
            </div>
            <div class="space-y-1.5 md:col-span-1">
                <label for="role_visibility" class="text-xs font-bold text-slate-700">Visibilité</label>
                <select name="role_visibility" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition cursor-pointer">
                    <option value="all">Tous les profils</option>
                    <option value="candidat">Candidats uniquement</option>
                    <option value="formateur">Formateurs uniquement</option>
                    <option value="coordinateur">Coordinateurs uniquement</option>
                </select>
            </div>
            <div class="space-y-1.5 md:col-span-1">
                <label for="seminar_id" class="text-xs font-bold text-slate-700">Séminaire (Optionnel)</label>
                <select name="seminar_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition cursor-pointer">
                    <option value="">Tous les séminaires (Général)</option>
                    @foreach($seminars as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="w-full py-2.5 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold transition shadow-sm">Téléverser</button>
            </div>
        </form>
    </div>

    <!-- Export Form -->
    <div x-data="{ exportType: 'candidats' }" class="bg-surface-container-lowest p-6 rounded-md border border-outline-variant shadow-sm mb-8">
        <h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">download</span> Exporter des données
        </h2>
        <form action="{{ route('admin.resources.export') }}" method="POST" target="_blank" class="flex flex-col gap-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-2 md:col-span-1">
                    <label for="type" class="text-sm font-bold text-on-surface-variant">Type de données</label>
                    <select name="type" x-model="exportType" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="candidats">Candidats</option>
                        <option value="formateurs">Formateurs / Formateurs</option>
                        <option value="depenses">Dépenses</option>
                    </select>
                </div>
                
                <div class="space-y-2 md:col-span-1" x-show="exportType === 'candidats' || exportType === 'depenses'">
                    <label for="date_from" class="text-sm font-bold text-on-surface-variant">Date de début</label>
                    <input type="date" name="date_from" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-2.5">
                </div>
                
                <div class="space-y-2 md:col-span-1" x-show="exportType === 'candidats' || exportType === 'depenses'">
                    <label for="date_to" class="text-sm font-bold text-on-surface-variant">Date de fin</label>
                    <input type="date" name="date_to" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-2.5">
                </div>

                <div class="space-y-2 md:col-span-1">
                    <label for="cohort_id" class="text-sm font-bold text-on-surface-variant">Édition</label>
                    <select name="cohort_id" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="">Toutes les éditions</option>
                        @foreach($cohorts as $cohort)
                            <option value="{{ $cohort->id }}">{{ $cohort->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 md:col-span-1">
                    <label for="seminar_id" class="text-sm font-bold text-on-surface-variant">Séminaire</label>
                    <select name="seminar_id" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="">Tous les séminaires</option>
                        @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 md:col-span-1">
                    <label for="topic_id" class="text-sm font-bold text-on-surface-variant">Thème</label>
                    <select name="topic_id" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="">Tous les thèmes</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 md:col-span-1">
                    <label for="department_id" class="text-sm font-bold text-on-surface-variant">Département</label>
                    <select name="department_id" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="">Tous les départements</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 md:col-span-1" x-show="exportType === 'formateurs'" style="display: none;">
                    <label for="payment_status" class="text-sm font-bold text-on-surface-variant">Statut de paiement</label>
                    <select name="payment_status" class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3">
                        <option value="">Tous</option>
                        <option value="pending">En attente</option>
                        <option value="paid">Payé</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-4 mt-4">
                <button type="submit" name="format" value="pdf" class="py-3 px-6 rounded-lg bg-primary text-white font-bold hover:brightness-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">picture_as_pdf</span> Exporter en PDF
                </button>
                <button type="submit" name="format" value="excel" class="py-3 px-6 rounded-lg bg-green-600 text-white font-bold hover:bg-green-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">table_view</span> Exporter en Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Resources List -->
    <div class="bg-surface-container-lowest rounded-md border border-outline-variant shadow-sm overflow-visible mb-8">
        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
            <h3 class="font-bold text-on-surface">Resources Actifs</h3>
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
                @foreach($activeResources as $doc)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-on-surface">{{ $doc->title }}</td>
                    <td class="px-6 py-4"><a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-primary hover:underline flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">download</span> Ouvrir</a></td>
                    <td class="px-6 py-4"><span class="bg-surface-variant text-on-surface-variant px-2 py-1 rounded text-xs font-bold uppercase">{{ $doc->role_visibility }}</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                            <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-40 bg-white border border-slate-100 shadow-lg rounded-md py-1 z-50">
                                <form action="{{ route('admin.resources.archive', $doc->id) }}" method="POST" class="m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-amber-600 hover:bg-amber-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">archive</span>
                                        Archiver
                                    </button>
                                </form>
                                <form action="{{ route('admin.resources.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce resource ?');" class="m-0 border-t border-slate-100">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($activeResources->isEmpty())
                <tr><td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Aucun resource actif.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Archived Resources -->
    @if($archivedResources->isNotEmpty())
    <div class="bg-surface-container-lowest opacity-75 rounded-md border border-outline-variant shadow-sm overflow-visible">
        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined">inventory_2</span>
            <h3 class="font-bold text-on-surface">Resources Archivés</h3>
        </div>
        <table class="w-full text-left text-sm">
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($archivedResources as $doc)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 text-on-surface line-through">{{ $doc->title }}</td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                            <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-40 bg-white border border-slate-100 shadow-lg rounded-md py-1 z-50">
                                <form action="{{ route('admin.resources.archive', $doc->id) }}" method="POST" class="m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">unarchive</span>
                                        Restaurer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    </div>
</div>
@endsection
