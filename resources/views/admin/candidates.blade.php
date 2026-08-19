@extends('layouts.admin')

@section('header_title', 'Gestion des Candidatures')

@section('header_subtitle')
    @if($selectedCohort)
        <span class="text-white text-[10px] font-bold bg-primary px-2.5 py-0.5 rounded-full shadow-sm whitespace-nowrap">
            {{ $selectedCohort->title }}
        </span>
    @endif
@endsection

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{ 
    drawerOpen: false, 
    candidate: null,
    openDrawer(data) {
        this.candidate = data;
        this.drawerOpen = true;
    },
    closeDrawer() {
        this.drawerOpen = false;
    }
}">
    <div class="w-full max-w-[1600px] mx-auto">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Candidatures</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Examinez, filtrez et validez les dossiers des candidats en quelques clics.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.candidates.create') }}" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-full flex items-center gap-2 transition shadow-sm">
                    <span class="material-symbols-outlined text-[18px] leading-none">person_add</span>
                    Inscrire un candidat
                </a>
            </div>
        </header>

        <!-- Stats summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <x-stat-card 
                title="Total Demandes" icon="folder" :showProgress="false"
                value="{{ $stats['total'] }}" 
                total="{{ $stats['total'] }}" 
                unit=""
                description="Dossiers de candidatures enregistrés."
                color="blue">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-40"><span class="material-symbols-outlined text-8xl text-gray-300" style="font-variation-settings: 'FILL' 1;">folder_open</span></div>
                </x-slot>
            </x-stat-card>
            
            <x-stat-card 
                title="À Examiner" icon="hourglass_empty" :showProgress="true"
                value="{{ $stats['pending'] }}" 
                total="{{ $stats['total'] }}" 
                unit=""
                description="Dossiers nécessitant une décision."
                color="yellow">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl text-yellow-300" style="font-variation-settings: 'FILL' 1;">hourglass_empty</span></div>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Approuvées" icon="check_circle" :showProgress="true"
                value="{{ $stats['approved'] }}" 
                total="{{ $stats['total'] }}" 
                unit=""
                description="Candidats retenus pour la formation."
                color="green">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl text-green-300" style="font-variation-settings: 'FILL' 1;">check_circle</span></div>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Refusées" icon="cancel" :showProgress="true"
                value="{{ $stats['rejected'] }}" 
                total="{{ $stats['total'] }}" 
                unit=""
                description="Dossiers non retenus pour cette session."
                color="red">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl text-red-300" style="font-variation-settings: 'FILL' 1;">cancel</span></div>
                </x-slot>
            </x-stat-card>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4">
            <form action="{{ route('admin.candidates') }}" method="GET" id="filterForm" class="m-0 flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-brand-primary text-[18px]">filter_list</span>
                    <span>Filtres :</span>
                </div>
                
                <select name="cohort_id" onchange="document.getElementById('filterForm').submit()" 
                        class="text-xs border border-gray-200 rounded-xl px-3.5 py-2 bg-slate-50 outline-none text-gray-700 focus:border-brand-primary focus:ring-2 focus:ring-brand-light cursor-pointer">
                    <option value="">Toutes les cohortes</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" {{ ($selectedCohort && $selectedCohort->id == $cohort->id) ? 'selected' : '' }}>
                            {{ $cohort->title }} {{ $cohort->status == 'active' ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>

                <select name="status" onchange="document.getElementById('filterForm').submit()" 
                        class="text-xs border border-gray-200 rounded-xl px-3.5 py-2 bg-slate-50 outline-none text-gray-700 focus:border-brand-primary focus:ring-2 focus:ring-brand-light cursor-pointer">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>À Examiner</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                    <option value="waiting_list" {{ request('status') == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
                </select>

                <select name="seminar_id" onchange="document.getElementById('filterForm').submit()" 
                        class="text-xs border border-gray-200 rounded-xl px-3.5 py-2 bg-slate-50 outline-none text-gray-700 max-w-[220px] focus:border-brand-primary focus:ring-2 focus:ring-brand-light cursor-pointer">
                    <option value="">Tous les séminaires</option>
                    @foreach($seminars as $seminar)
                        <option value="{{ $seminar->id }}" {{ request('seminar_id') == $seminar->id ? 'selected' : '' }}>
                            {{ Str::limit($seminar->title, 30) }}
                        </option>
                    @endforeach
                </select>

                <select name="topic_id" onchange="document.getElementById('filterForm').submit()" 
                        class="text-xs border border-gray-200 rounded-xl px-3.5 py-2 bg-slate-50 outline-none text-gray-700 max-w-[200px] focus:border-brand-primary focus:ring-2 focus:ring-brand-light cursor-pointer">
                    <option value="">Tous les thèmes</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                            {{ Str::limit($topic->name, 25) }}
                        </option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['search', 'status', 'seminar_id', 'topic_id', 'cohort_id']))
                    <a href="{{ route('admin.candidates') }}" class="px-3 py-1.5 text-xs text-rose-600 hover:bg-rose-50 rounded-lg font-semibold transition border border-rose-100 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                        Réinitialiser
                    </a>
                @endif
            </form>
            
            <div class="w-full lg:w-auto flex items-center justify-end">
                <div class="relative w-full sm:w-64">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 flex items-center">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" form="filterForm" name="search" value="{{ request('search') }}" onblur="document.getElementById('filterForm').submit()" placeholder="Rechercher candidat / email..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-gray-200 rounded-xl text-xs outline-none focus:border-brand-primary focus:bg-white focus:ring-2 focus:ring-brand-light transition">
                </div>
            </div>
        </div>

        <!-- Candidates Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-200 text-gray-600">
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Candidat</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Formation</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Date & Pays</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Statut</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-right">Actions Express</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-slate-800">
                        @forelse($candidates as $registration)
                        @php
                            $profile = $registration->user->candidateProfile;
                            $candidateData = [
                                'id' => $registration->id,
                                'name' => $registration->user->name,
                                'email' => $registration->user->email,
                                'status' => $registration->status,
                                'seminar_title' => $registration->seminar?->title ?? '-',
                                'cohort_title' => $registration->seminar?->cohort?->title ?? '-',
                                'created_at' => $registration->created_at->format('d/m/Y H:i'),
                                'phone' => $profile?->phone ?? 'Non renseigné',
                                'nationality' => $profile?->nationality ?? 'N/A',
                                'profession' => $profile?->current_profession ?? 'N/A',
                                'employer' => $profile?->current_employer ?? 'N/A',
                                'highest_degree' => $profile?->highest_degree ?? 'N/A',
                                'training_utility' => $profile?->training_utility ?? 'Non renseigné',
                                'accommodation' => $profile?->accommodation_preference ?? 'none',
                                'photo_url' => $profile && $profile->photo_path ? asset('storage/' . $profile->photo_path) : null,
                                'show_url' => route('admin.candidates.show', $registration->id),
                                'status_url' => route('admin.candidates.update-status', $registration->id),
                                'documents' => ($registration->user->candidateResources ?? $registration->user->documents ?? collect([]))->map(function($doc) {
                                    return [
                                        'type' => $doc->resource_type,
                                        'filename' => $doc->original_filename,
                                        'url' => asset('storage/' . $doc->file_path)
                                    ];
                                })
                            ];
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition-colors group">
                            <!-- Candidate Avatar & Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3 cursor-pointer" @click="openDrawer(@js($candidateData))">
                                    <div class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center text-brand-primary font-bold overflow-hidden shadow-inner flex-shrink-0 border border-gray-200">
                                        @if($profile && $profile->photo_path)
                                            <img src="{{ asset('storage/' . $profile->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($registration->user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-brand-primary transition">{{ $registration->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $registration->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Seminar & Cohort -->
                            <td class="py-4 px-6">
                                <div class="text-xs font-semibold text-gray-800 line-clamp-1">{{ $registration->seminar->title ?? 'Séminaire' }}</div>
                                <div class="text-[11px] font-bold text-brand-primary mt-0.5">{{ $registration->seminar->cohort?->title ?? 'Session' }}</div>
                            </td>

                            <!-- Date & Country -->
                            <td class="py-4 px-6 text-xs text-gray-600">
                                <div>{{ $registration->created_at->format('d/m/Y') }}</div>
                                <div class="text-[11px] text-gray-400 font-medium">{{ $profile?->nationality ?? 'Sénégalaise' }}</div>
                            </td>

                            <!-- Status Text -->
                            <td class="py-4 px-6">
                                @if($registration->status === 'approved')
                                    <span class="text-xs font-bold text-emerald-600">
                                        Sélectionné
                                    </span>
                                @elseif($registration->status === 'rejected')
                                    <span class="text-xs font-bold text-rose-600">
                                        Refusé
                                    </span>
                                @elseif($registration->status === 'waiting_list')
                                    <span class="text-xs font-bold text-blue-600">
                                        Liste d'attente
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-amber-600">
                                        À Examiner
                                    </span>
                                @endif
                            </td>

                            <!-- Quick Action Buttons -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Express Quick Preview Drawer Button -->
                                    <button type="button" @click="openDrawer(@js($candidateData))" 
                                            class="px-3 py-1.5 bg-slate-100 hover:bg-brand-primary hover:text-white text-slate-700 text-xs font-semibold rounded-lg transition flex items-center gap-1.5 shadow-sm"
                                            title="Aperçu rapide du dossier">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        <span>Examiner</span>
                                    </button>

                                    <!-- Direct Approve Button (if pending) -->
                                    @if($registration->status === 'pending' && auth()->user()->role->name !== 'Agent JICA')
                                        <form action="{{ route('admin.candidates.update-status', $registration->id) }}" method="POST" class="m-0 inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="status" value="approved" 
                                                    class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg transition" 
                                                    title="Approuver immédiatement">
                                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 text-sm">
                                Aucun dossier de candidature ne correspond à vos filtres.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($candidates, 'hasPages') && $candidates->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $candidates->links() }}
                </div>
            @endif
        </div>
        
    </div>

    <!-- ============================================================== -->
    <!-- SLIDE-OVER QUICK PREVIEW DRAWER (TIROIR D'EXAMEN EXPRESS) -->
    <!-- ============================================================== -->
    <div x-show="drawerOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-hidden" 
         aria-labelledby="slide-over-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Overlay -->
        <div x-show="drawerOpen" 
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" 
             @click="closeDrawer()"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="drawerOpen" 
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
                
                <!-- Drawer Header -->
                <div class="p-6 border-b border-gray-100 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <template x-if="candidate && candidate.photo_url">
                            <img :src="candidate.photo_url" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                        </template>
                        <template x-if="candidate && !candidate.photo_url">
                            <div class="w-12 h-12 rounded-full bg-brand-light text-brand-primary font-bold flex items-center justify-center text-base shadow-sm">
                                <span x-text="candidate ? candidate.name.charAt(0) : 'C'"></span>
                            </div>
                        </template>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 font-headline" x-text="candidate ? candidate.name : ''"></h3>
                            <p class="text-xs text-gray-500" x-text="candidate ? candidate.email : ''"></p>
                        </div>
                    </div>

                    <button type="button" @click="closeDrawer()" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Drawer Body Scrollable Content -->
                <div class="p-6 flex-1 overflow-y-auto space-y-6 text-xs text-slate-700" x-if="candidate">
                    
                    <!-- Session & Status -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 uppercase font-bold text-[10px]">Session / Cohorte</span>
                            <span class="font-bold text-brand-primary" x-text="candidate ? candidate.cohort_title : ''"></span>
                        </div>
                        <div class="text-sm font-semibold text-slate-800" x-text="candidate ? candidate.seminar_title : ''"></div>
                    </div>

                    <!-- Contact & Identity -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-brand-primary text-[16px]">person</span>
                            Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-2 gap-3 bg-white p-3.5 rounded-xl border border-gray-200">
                            <div><span class="text-gray-400 block text-[10px]">Téléphone</span> <strong x-text="candidate ? candidate.phone : '-'"></strong></div>
                            <div><span class="text-gray-400 block text-[10px]">Nationalité</span> <strong x-text="candidate ? candidate.nationality : '-'"></strong></div>
                            <div><span class="text-gray-400 block text-[10px]">Profession</span> <strong x-text="candidate ? candidate.profession : '-'"></strong></div>
                            <div><span class="text-gray-400 block text-[10px]">Employeur</span> <strong x-text="candidate ? candidate.employer : '-'"></strong></div>
                            <div class="col-span-2"><span class="text-gray-400 block text-[10px]">Dernier Diplôme</span> <strong x-text="candidate ? candidate.highest_degree : '-'"></strong></div>
                        </div>
                    </div>

                    <!-- Motivation / Training Utility -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-brand-primary text-[16px]">psychology</span>
                            Utilité & Motivation
                        </h4>
                        <p class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs leading-relaxed text-slate-600 italic" x-text="candidate ? candidate.training_utility : '-'"></p>
                    </div>

                    <!-- Documents Attached -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-brand-primary text-[16px]">folder</span>
                            Pièces Jointes
                        </h4>
                        <div class="space-y-2">
                            <template x-if="candidate && candidate.documents && candidate.documents.length > 0">
                                <template x-for="doc in candidate.documents" :key="doc.filename">
                                    <a :href="doc.url" target="_blank" class="flex items-center justify-between p-2.5 bg-slate-50 hover:bg-brand-light/30 border border-slate-200 rounded-xl transition group">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-brand-primary text-[18px]">description</span>
                                            <span class="font-semibold text-slate-800 text-xs group-hover:text-brand-primary" x-text="doc.filename"></span>
                                        </div>
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-brand-primary text-[16px]">download</span>
                                    </a>
                                </template>
                            </template>
                            <template x-if="candidate && (!candidate.documents || candidate.documents.length === 0)">
                                <p class="text-xs text-gray-400">Aucun document téléversé.</p>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Drawer Footer : Instant 1-Click Status Actions -->
                <div class="p-5 border-t border-gray-200 bg-slate-50 space-y-3" x-if="candidate">
                    <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider block">Décision d'admission :</span>
                    
                    @if(auth()->user()->role->name !== 'Agent JICA')
                    <div class="grid grid-cols-3 gap-2">
                        <form :action="candidate ? candidate.status_url : '#'" method="POST" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="approved" 
                                    class="w-full py-2.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">check</span>
                                <span>Approuver</span>
                            </button>
                        </form>

                        <form :action="candidate ? candidate.status_url : '#'" method="POST" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="waiting_list" 
                                    class="w-full py-2.5 px-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">hourglass_empty</span>
                                <span>Attente</span>
                            </button>
                        </form>

                        <form :action="candidate ? candidate.status_url : '#'" method="POST" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="rejected" 
                                    class="w-full py-2.5 px-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                                <span>Refuser</span>
                            </button>
                        </form>
                    </div>
                    @endif

                    <div class="pt-2">
                        <a :href="candidate ? candidate.show_url : '#'" 
                           class="w-full block text-center py-2 text-xs font-semibold text-brand-primary hover:underline">
                            Ouvrir la fiche complète détaillée →
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
