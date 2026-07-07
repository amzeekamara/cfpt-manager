@extends('layouts.admin')

@section('header_title', 'Dossier Candidat')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.candidates') }}" class="w-12 h-12 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-all group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-headline font-black text-on-surface tracking-tighter uppercase">Dossier de Candidature</h1>
                <p class="text-on-surface-variant font-body">Examen du profil pour : <span class="font-bold text-primary">{{ $enrollment->user->name }}</span></p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.candidates.edit', $enrollment->id) }}" class="px-6 py-2.5 bg-surface-container-highest text-on-surface rounded-xl font-bold text-sm border border-outline-variant hover:bg-surface-dim transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">edit_note</span> Modifier le dossier
            </a>
            <form action="{{ route('admin.candidates.update-status', $enrollment->id) }}" method="POST" class="flex gap-2">
                @csrf
                @method('PATCH')
                <button name="status" value="approved" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span> Approuver
                </button>
                <button name="status" value="rejected" class="px-6 py-2.5 bg-rose-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">cancel</span> Refuser
                </button>
                <button name="status" value="waiting_list" class="px-6 py-2.5 bg-blue-100 text-blue-800 border border-blue-200 rounded-xl font-bold text-sm hover:bg-blue-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">hourglass_empty</span> Liste d'attente
                </button>
            </form>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Basic Info & Profile Photo -->
        <div class="space-y-8">
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-8 shadow-sm text-center">
                <div class="w-32 h-32 rounded-full mx-auto bg-blue-50 border-4 border-white shadow-xl mb-6 overflow-hidden flex items-center justify-center text-blue-900 text-4xl font-bold">
                    @if($enrollment->user->candidateProfile && $enrollment->user->candidateProfile->photo_path)
                        <img src="{{ asset('storage/' . $enrollment->user->candidateProfile->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($enrollment->user->name, 0, 1) }}
                    @endif
                </div>
                <h2 class="text-2xl font-headline font-black text-on-surface">{{ $enrollment->user->name }}</h2>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black tracking-widest text-slate-500 uppercase mt-2">
                    Candidat #{{ str_pad($enrollment->user->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                
                <div class="mt-8 space-y-4 text-left">
                    <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-on-surface-variant">Email</div>
                            <div class="text-sm font-medium">{{ $enrollment->user->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="material-symbols-outlined text-primary">phone</span>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-on-surface-variant">Téléphone</div>
                            <div class="text-sm font-medium">{{ $enrollment->user->candidateProfile->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="material-symbols-outlined text-primary">diversity_3</span>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-on-surface-variant">Situation / Religion</div>
                            <div class="text-sm font-medium">{{ ucfirst($enrollment->user->candidateProfile->family_status ?? 'single') }} | {{ $enrollment->user->candidateProfile->religion ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="material-symbols-outlined text-slate-400">flag</span>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400">Origine</div>
                            <div class="text-sm font-medium text-slate-600">{{ $enrollment->user->candidateProfile->nationality ?? 'N/A' }} ({{ $enrollment->user->candidateProfile->residence_country ?? 'N/A' }})</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-blue-950 rounded-3xl p-8 shadow-xl text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                <h3 class="text-lg font-headline font-bold mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-400">emergency_home</span> Contact d'Urgence
                </h3>
                <div class="space-y-6 relative z-10">
                    <div>
                        <div class="text-[10px] opacity-60 uppercase font-black tracking-widest">Nom complet</div>
                        <div class="text-md font-bold text-rose-100">{{ $enrollment->user->candidateProfile->emergency_contact_name ?? 'N/A' }}</div>
                    </div>
                    <div class="flex flex-wrap gap-8">
                        <div>
                            <div class="text-[10px] opacity-60 uppercase font-black tracking-widest">Relation</div>
                            <div class="text-sm font-bold">{{ $enrollment->user->candidateProfile->emergency_contact_relation ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] opacity-60 uppercase font-black tracking-widest">Téléphone</div>
                            <div class="text-xs font-bold">{{ $enrollment->user->candidateProfile->emergency_contact_phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($enrollment->user->candidateProfile->emergency_contact_email)
                    <div>
                        <div class="text-[10px] opacity-60 uppercase font-black tracking-widest">Email</div>
                        <div class="text-xs font-medium">{{ $enrollment->user->candidateProfile->emergency_contact_email }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Detailed Info Tabs -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Academic & Pro -->
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h3 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-primary rounded-full"></span> Parcours & Expérience
                </h3>
                
                <div class="space-y-10">
                    <!-- Academic -->
                    <div>
                        <h4 class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">history_edu</span> Études & Diplômes
                        </h4>
                        @php $histories = $enrollment->user->candidateProfile->academicHistories ?? collect(); @endphp
                        @forelse($histories as $history)
                            <div class="p-4 bg-surface-container-low rounded-2xl border border-outline-variant/30 mb-3 flex justify-between items-center">
                                <div>
                                    <div class="text-sm font-bold">{{ $history->establishment_name }}</div>
                                    <div class="text-xs text-on-surface-variant">{{ $history->field_of_study }} • {{ $history->degree_obtained }}</div>
                                    @if($history->distinction)
                                        <div class="text-[10px] text-emerald-600 font-bold uppercase">{{ $history->distinction }}</div>
                                    @endif
                                </div>
                                <div class="text-sm font-black text-primary">{{ $history->graduation_year }}</div>
                            </div>
                        @empty
                            <div class="text-sm font-medium text-on-surface p-2 bg-slate-50 border rounded-lg italic text-slate-400">Détails : {{ $enrollment->user->candidateProfile->highest_degree ?? 'Non précisé' }}</div>
                        @endforelse
                    </div>

                    <!-- Professional -->
                    <div>
                        <h4 class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">work</span> Situation Professionnelle
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <div class="text-[10px] text-on-surface-variant font-bold">Profession actuelle</div>
                                <div class="text-sm font-bold">{{ $enrollment->user->candidateProfile->current_profession ?? 'N/A' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-[10px] text-on-surface-variant font-bold">Employeur (Actuel / Précédent)</div>
                                <div class="text-sm font-bold">{{ $enrollment->user->candidateProfile->current_employer ?? 'N/A' }} / <span class="text-slate-400 italic">{{ $enrollment->user->candidateProfile->previous_employer ?? 'Aucun' }}</span></div>
                            </div>
                            @if($enrollment->user->candidateProfile->job_description)
                            <div class="md:col-span-2 space-y-1">
                                <div class="text-[10px] text-on-surface-variant font-bold">Description du travail</div>
                                <div class="text-sm text-on-surface leading-relaxed">{{ $enrollment->user->candidateProfile->job_description }}</div>
                            </div>
                            @endif
                            <div class="md:col-span-2 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <div class="text-[10px] text-emerald-800 font-bold uppercase mb-2">Utilité de la formation</div>
                                <p class="text-sm text-emerald-950 leading-relaxed">{{ $enrollment->user->candidateProfile->training_utility ?? 'Aucune explication fournie.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health & Logistics -->
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h3 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-rose-500 rounded-full"></span> Santé & Logistique
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-rose-500">medical_services</span>
                            <span class="text-sm font-black uppercase text-on-surface-variant">Questionnaire & Suivi</span>
                        </div>
                        
                        <!-- Questionnaire display -->
                        @php $q = $enrollment->user->candidateProfile->health_questionnaire_json; @endphp
                        @if($q)
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-[11px] p-2 bg-slate-50 rounded-lg">
                                <span>Maladie chronique</span>
                                <span class="font-bold {{ ($q['chronic'] ?? '') == 'yes' ? 'text-rose-600' : 'text-emerald-600' }}">{{ strtoupper($q['chronic'] ?? 'N/A') }}</span>
                            </div>
                            <div class="flex justify-between text-[11px] p-2 bg-slate-50 rounded-lg">
                                <span>Traitement régulier</span>
                                <span class="font-bold {{ ($q['treatment'] ?? '') == 'yes' ? 'text-rose-600' : 'text-emerald-600' }}">{{ strtoupper($q['treatment'] ?? 'N/A') }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="text-sm space-y-4">
                            <div class="p-3 bg-rose-50 rounded-xl border border-rose-100">
                                <span class="font-bold text-rose-900 block mb-1">Observations :</span>
                                <p class="text-xs leading-relaxed text-rose-950">{{ $enrollment->user->candidateProfile->health_record_obs ?? 'Aucune observation particulière.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-blue-500">hotel</span>
                            <span class="text-sm font-black uppercase text-on-surface-variant">Logistique & Assurance</span>
                        </div>
                        <div class="space-y-3">
                            <div class="p-3 bg-blue-50 rounded-2xl border border-blue-100">
                                <div class="text-[10px] text-blue-800 font-bold uppercase mb-1">Préférence Hébergement</div>
                                <div class="text-sm font-bold text-blue-950 uppercase">
                                    {{ $enrollment->user->candidateProfile->accommodation_preference ?? 'Non spécifié' }}
                                </div>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <div class="text-[10px] text-emerald-800 font-bold uppercase mb-1">Assurance : {{ $enrollment->user->candidateProfile->insurance_details ?? 'N/A' }}</div>
                                <div class="text-xs font-bold text-emerald-950">
                                    Prime : {{ number_format($enrollment->user->candidateProfile->insurance_premium ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h3 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span> Documents & Archives Numériques
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($enrollment->user->candidateDocuments as $doc)
                        @php
                            $icon = 'description';
                            $color = 'bg-slate-100 text-slate-600';
                            if($doc->document_type == 'cv') { $icon = 'picture_as_pdf'; $color = 'bg-rose-100 text-rose-600'; }
                            if($doc->document_type == 'diploma') { $icon = 'school'; $color = 'bg-blue-100 text-blue-600'; }
                            if($doc->document_type == 'official_intro') { $icon = 'draw'; $color = 'bg-amber-100 text-amber-600'; }
                        @endphp
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-outline-variant hover:bg-surface-container transition-all group">
                            <div class="w-11 h-11 rounded-xl {{ $color }} flex items-center justify-center transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-black truncate uppercase tracking-tight">{{ str_replace('_', ' ', $doc->document_type) }}</div>
                                <div class="text-[10px] text-on-surface-variant italic truncate">{{ $doc->original_filename }}</div>
                            </div>
                            <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">download</span>
                        </a>
                    @empty
                        <p class="text-sm text-on-surface-variant italic col-span-2">Aucun document joint à cette candidature.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
