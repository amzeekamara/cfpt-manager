@extends('layouts.admin')

@section('header_title', 'Dossier Candidat')

@section('content')
<style>
    .zendenta-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    }
    .timeline-line {
        position: absolute;
        left: 15px;
        top: 24px;
        bottom: 24px;
        width: 2px;
        background: #e5e7eb;
    }
</style>

<div class="p-6 md:p-8 bg-[#f5f6f8] min-h-screen">
    
    <!-- Top Header Navigation (Zendenta Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.candidates') }}" class="flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-brand-primary transition-colors bg-white px-3 py-2 rounded-xl border border-gray-200 shadow-sm">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Retour
            </a>
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                <a href="{{ route('admin.candidates') }}" class="hover:text-gray-600">Liste des Candidats</a>
                <span>&rsaquo;</span>
                <span class="text-gray-800 font-bold">{{ $registration->user->name }}</span>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2.5 bg-white text-gray-700 font-bold border border-gray-200 rounded-xl hover:bg-gray-50 text-xs flex items-center gap-2 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Imprimer
            </button>
            <a href="{{ route('admin.candidates.edit', $registration->id) }}" class="px-4 py-2.5 bg-[#00355f] text-white font-bold rounded-xl hover:opacity-90 text-xs flex items-center gap-2 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Modifier Candidat
            </a>
        </div>
    </div>

    <!-- MAIN GRID TOP: Profile Summary (Left 2 cols) + Notes Card (Right 1 col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- LEFT 2 COLS: Combined Profile & Details Card -->
        <div class="lg:col-span-2 zendenta-card p-6 flex flex-col md:flex-row gap-6">
            
            <!-- Left Sub-Box: Avatar, Name, Email, Stats & Actions -->
            <div class="md:w-1/3 flex flex-col items-center text-center pr-0 md:pr-6 border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 justify-between">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-brand-light flex items-center justify-center text-brand-primary text-3xl font-bold mb-3 shrink-0">
                        @if($registration->user->candidateProfile && $registration->user->candidateProfile->photo_path)
                            <img src="{{ asset('storage/' . $registration->user->candidateProfile->photo_path) }}" class="w-full h-full object-cover">
                        @elseif($registration->user->avatar)
                            <img src="{{ asset('storage/' . $registration->user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($registration->user->name, 0, 2) }}
                        @endif
                    </div>

                    <h2 class="text-xl font-headline font-bold text-gray-900 leading-tight mb-1">{{ $registration->user->name }}</h2>
                    <p class="text-xs text-gray-400 font-medium break-all mb-4">{{ $registration->user->email }}</p>

                    <!-- Stats Counter (Zendenta 15 Past | 2 Upcoming style) -->
                    @php
                        $pastCount = $registration->user->registrations()->where('status', 'approved')->count();
                    @endphp
                    <div class="flex items-center justify-center gap-4 py-2.5 px-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs w-full mb-4">
                        <div class="text-center">
                            <span class="text-lg font-black text-gray-900 block leading-tight">{{ $pastCount }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Formations</span>
                        </div>
                        <div class="w-px h-7 bg-gray-200"></div>
                        <div class="text-center">
                            <span class="text-lg font-black text-emerald-600 block leading-tight">1</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actuelle</span>
                        </div>
                    </div>
                </div>

                <!-- Status Action / Badges -->
                <div class="w-full">
                    @if($registration->status === 'pending')
                        <form action="{{ route('admin.candidates.update-status', $registration->id) }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            @method('PATCH')
                            <button name="status" value="approved" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span> Approuver
                            </button>
                            <div class="grid grid-cols-2 gap-2">
                                <button name="status" value="rejected" class="py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-xs hover:bg-rose-100 transition-colors">
                                    Refuser
                                </button>
                                <button name="status" value="waiting_list" class="py-2 rounded-lg bg-amber-50 text-amber-600 font-bold text-xs hover:bg-amber-100 transition-colors">
                                    Attente
                                </button>
                            </div>
                        </form>
                    @else
                        @if($registration->status === 'approved')
                            <div class="w-full py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 border border-emerald-100">
                                <span class="material-symbols-outlined text-[18px]">verified</span> Candidat Approuvé
                            </div>
                        @elseif($registration->status === 'rejected')
                            <div class="w-full py-2.5 bg-rose-50 text-rose-700 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 border border-rose-100">
                                <span class="material-symbols-outlined text-[18px]">cancel</span> Candidature Refusée
                            </div>
                        @else
                            <div class="w-full py-2.5 bg-amber-50 text-amber-700 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 border border-amber-100">
                                <span class="material-symbols-outlined text-[18px]">hourglass_empty</span> Liste d'attente
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Right Sub-Box: Personal Details Grid (3 cols x 3 rows) -->
            <div class="md:w-2/3 flex flex-col justify-between pl-0 md:pl-2">
                @php
                    $profile = $registration->user->candidateProfile;
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-4 gap-x-4 text-xs">
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Genre</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ ucfirst($profile->gender ?? 'Non renseigné') }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Date de Naissance</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile && $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('d M Y') : 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Téléphone</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile->phone ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Profession</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile->current_profession ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Employeur</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile->current_employer ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Niveau Diplôme</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile->highest_degree ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Nationalité</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $profile->nationality ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Statut Dossier</span>
                        <strong class="text-emerald-600 font-bold text-sm uppercase">{{ $registration->status }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium block mb-1">Date d'Inscription</span>
                        <strong class="text-gray-800 font-bold text-sm">{{ $registration->created_at->format('d M Y') }}</strong>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">domain</span>
                        Séminaire : <strong class="text-gray-800">{{ $registration->seminar->title }}</strong>
                    </span>
                    @if($registration->topic)
                    <span class="bg-blue-50 text-brand-primary font-bold px-2.5 py-1 rounded-lg">
                        {{ $registration->topic->name }}
                    </span>
                    @endif
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: Notes / Remarques Box (Zendenta style) -->
        <div class="lg:col-span-1 zendenta-card p-6 flex flex-col justify-between" x-data="{ note: '' }">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-primary text-[20px]">sticky_note_2</span>
                        Notes & Remarques
                    </h3>
                    <span class="text-xs text-brand-primary font-bold cursor-pointer hover:underline">Tout voir</span>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-xs text-gray-600 leading-relaxed mb-4">
                    <ul class="space-y-2 list-disc list-inside">
                        <li>Dossier de candidature reçu le {{ $registration->created_at->format('d/m/Y') }}.</li>
                        @if($profile && $profile->health_questionnaire_json)
                        <li>Questionnaire de santé : renseigné.</li>
                        @endif
                        <li>Séminaire cible : {{ $registration->seminar->title }}.</li>
                    </ul>
                </div>

                <textarea x-model="note" rows="3" class="w-full text-xs p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-1 focus:ring-brand-primary resize-none" placeholder="Ajouter une note administrative..."></textarea>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">Admin — {{ date('d M Y') }}</span>
                <button type="button" class="px-4 py-2 bg-[#00355f] text-white text-xs font-bold rounded-xl hover:opacity-90 shadow-sm transition-all">
                    Enregistrer la note
                </button>
            </div>
        </div>

    </div>

    <!-- BOTTOM GRID: Tabs & Timeline (Left 2 cols) + Attached Files (Right 1 col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ activeTab: 'upcoming' }">
        
        <!-- LEFT 2 COLS: Tabs & Timeline Section (Zendenta style) -->
        <div class="lg:col-span-2 zendenta-card p-6 flex flex-col justify-between">
            <div>
                <!-- Tab Navigation Pills -->
                <div class="flex items-center gap-2 border-b border-gray-100 pb-4 mb-6 overflow-x-auto">
                    <button @click="activeTab = 'upcoming'" 
                            :class="{ 'bg-brand-primary text-white shadow-sm': activeTab === 'upcoming', 'bg-gray-50 text-gray-600 hover:bg-gray-100': activeTab !== 'upcoming' }"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all whitespace-nowrap">
                        Séminaire Actuel & À venir
                    </button>
                    <button @click="activeTab = 'history'" 
                            :class="{ 'bg-brand-primary text-white shadow-sm': activeTab === 'history', 'bg-gray-50 text-gray-600 hover:bg-gray-100': activeTab !== 'history' }"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all whitespace-nowrap">
                        Historique des Candidatures
                    </button>
                </div>

                <!-- Tab Content 1: Upcoming & Current Seminar Timeline -->
                <div x-show="activeTab === 'upcoming'" class="space-y-4 relative pl-8">
                    <div class="timeline-line"></div>

                    <!-- Current Active Seminar Item -->
                    <div class="relative bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="absolute -left-[25px] top-6 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white ring-4 ring-emerald-100"></div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Session en cours</span>
                                <span class="text-xs font-semibold text-gray-400">{{ $registration->seminar->start_date->format('d M Y') }} — {{ $registration->seminar->start_date->format('H:i') }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $registration->seminar->title }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Formateur / Thème : {{ $registration->topic->name ?? 'Général' }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-gray-700 text-xs font-bold shadow-sm shrink-0 self-start sm:self-center">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>

                </div>

                <!-- Tab Content 2: Past History -->
                <div x-show="activeTab === 'history'" class="space-y-3">
                    @php
                        $pastRegistrations = $registration->user->registrations()->where('id', '!=', $registration->id)->with('seminar')->orderBy('created_at', 'desc')->get();
                    @endphp
                    @forelse($pastRegistrations as $past)
                        <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-brand-primary flex items-center justify-center font-bold shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">school</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $past->seminar->title }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $past->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <span class="font-bold uppercase text-[11px] {{ $past->status === 'approved' ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $past->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 py-6 text-center">Aucun historique de candidature passée.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- RIGHT 1 COL: Files / Attached Documents (Zendenta style) -->
        <div class="lg:col-span-1 zendenta-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-primary text-[20px]">folder_open</span>
                        Fichiers / Documents
                    </h3>
                    <a href="{{ route('admin.candidates.edit', $registration->id) }}" class="text-xs text-brand-primary font-bold hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">add</span> Ajouter
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($registration->user->candidateResources as $doc)
                        @php
                            $icon = 'description';
                            $bgColor = 'bg-blue-50 text-brand-primary';
                            if($doc->resource_type == 'cv') { $icon = 'picture_as_pdf'; $bgColor = 'bg-rose-50 text-rose-600'; }
                            if($doc->resource_type == 'diploma') { $icon = 'workspace_premium'; $bgColor = 'bg-amber-50 text-amber-600'; }
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl {{ $bgColor }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-bold text-gray-800 truncate">{{ str_replace('_', ' ', ucfirst($doc->resource_type)) }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $doc->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-brand-primary transition-colors shrink-0" title="Télécharger / Voir">
                                <span class="material-symbols-outlined text-[18px]">download</span>
                            </a>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-xs">
                            <span class="material-symbols-outlined text-gray-300 text-3xl mb-1">folder_off</span>
                            <p>Aucun document attaché.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
