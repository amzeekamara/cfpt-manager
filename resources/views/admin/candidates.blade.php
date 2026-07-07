@extends('layouts.admin')

@section('header_title')
    Gestion des Candidatures
    @if($selectedEdition)
        <span class="text-primary-container text-sm font-bold bg-primary px-3 py-1 rounded-full ml-4 shadow-sm">
            {{ $selectedEdition->title }}
        </span>
    @endif
@endsection

@section('content')
<div class="p-8">
    <div class="mb-8 flex flex-wrap gap-4 items-center">
        <form action="{{ route('admin.candidates') }}" method="GET" class="flex flex-wrap gap-4 flex-1">
            <div class="flex-1 min-w-[200px] relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un candidat..." 
                    class="w-full bg-surface-container-low border border-outline-variant rounded-2xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
            
            <select name="edition_id" onchange="this.form.submit()" class="bg-surface-container-low border border-outline-variant rounded-2xl px-4 py-3 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer pr-10 font-bold text-primary shadow-sm">
                <option value="">Toutes les éditions</option>
                @foreach($editions as $edition)
                    <option value="{{ $edition->id }}" {{ ($selectedEdition && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                        {{ $edition->title }} {{ $edition->status == 'active' ? '(Active)' : '' }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-surface-container-low border border-outline-variant rounded-2xl px-4 py-3 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer pr-10">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                <option value="waiting_list" {{ request('status') == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
            </select>

            <select name="seminar_id" onchange="this.form.submit()" class="bg-surface-container-low border border-outline-variant rounded-2xl px-4 py-3 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer pr-10">
                <option value="">Tous les séminaires</option>
                @foreach($seminars as $seminar)
                    <option value="{{ $seminar->id }}" {{ request('seminar_id') == $seminar->id ? 'selected' : '' }}>
                        {{ Str::limit($seminar->title, 30) }}
                    </option>
                @endforeach
            </select>

            @if(request()->anyFilled(['search', 'status', 'seminar_id', 'edition_id']))
                <a href="{{ route('admin.candidates') }}" class="px-4 py-3 text-rose-600 font-bold hover:bg-rose-50 rounded-2xl transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_alt_off</span> Effacer
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.candidates.create') }}" class="px-6 py-3 bg-primary text-on-primary rounded-2xl font-bold flex items-center gap-2 shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">
            <span class="material-symbols-outlined">person_add</span> Inscrire un candidat
        </a>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant shadow-sm">
            <div class="text-xs font-bold text-on-surface-variant uppercase mb-1">Total Demandes</div>
            <div class="text-3xl font-black text-on-surface">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
            <div class="text-xs font-bold text-amber-700 uppercase mb-1">En attente</div>
            <div class="text-3xl font-black text-amber-900">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <div class="text-xs font-bold text-emerald-700 uppercase mb-1">Approuvées</div>
            <div class="text-3xl font-black text-emerald-900">{{ $stats['approved'] }}</div>
        </div>
        <div class="bg-rose-50 p-6 rounded-2xl border border-rose-200 shadow-sm">
            <div class="text-xs font-bold text-rose-700 uppercase mb-1">Refusées</div>
            <div class="text-3xl font-black text-rose-900">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    <!-- Candidates Table -->
    <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="py-5 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Candidat</th>
                        <th class="py-5 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Formation / Édition</th>
                        <th class="py-5 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Date Demande</th>
                        <th class="py-5 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Statut</th>
                        <th class="py-5 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @foreach($candidates as $enrollment)
                    <tr class="hover:bg-surface-container-low/30 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-900 font-bold overflow-hidden shadow-inner">
                                    @if($enrollment->user->candidateProfile && $enrollment->user->candidateProfile->photo_path)
                                        <img src="{{ asset('storage/' . $enrollment->user->candidateProfile->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($enrollment->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-on-surface">{{ $enrollment->user->name }}</div>
                                    <div class="text-[10px] text-on-surface-variant">{{ $enrollment->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-on-surface">{{ $enrollment->seminar->title }}</div>
                            <div class="text-[10px] font-bold text-primary uppercase">{{ $enrollment->seminar->edition?->title ?? 'Session Spéciale' }}</div>
                        </td>
                        <td class="py-4 px-6 text-xs text-on-surface-variant">{{ $enrollment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    'waiting_list' => 'bg-blue-100 text-blue-700',
                                ];
                                $statusLabels = [
                                    'pending' => 'À Examiner',
                                    'approved' => 'Sélectionné',
                                    'rejected' => 'Refusé',
                                    'waiting_list' => 'En attente',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$enrollment->status] ?? 'bg-slate-100' }}">
                                {{ $statusLabels[$enrollment->status] ?? $enrollment->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.candidates.show', $enrollment->id) }}" class="p-2 text-primary hover:bg-primary/5 rounded-lg transition-colors border border-primary/20" title="Voir le dossier">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                                <form action="{{ route('admin.candidates.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Supprimer ce dossier ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-outline-variant bg-surface-container-low/30">
            {{ $candidates->links() }}
        </div>
    </div>
</div>
@endsection
