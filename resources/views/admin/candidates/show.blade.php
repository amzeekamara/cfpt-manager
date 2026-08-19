@extends('layouts.admin')

@section('header_title', 'Dossier Candidat — ' . $registration->user->name)

@section('content')
<style>
    .info-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        margin-bottom: 3px;
        display: block;
    }
    .info-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    .info-section-title {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #4B6A95;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    @media print {
        header, .no-print, nav, aside {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            padding: 0 !important;
        }
        .info-card {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            break-inside: avoid;
            margin-bottom: 16px !important;
        }
        .print-header {
            display: block !important;
        }
    }
</style>

@php
    $user = $registration->user;
    $profile = $user->candidateProfile;
    $health = $profile && is_array($profile->health_questionnaire_json) ? $profile->health_questionnaire_json : [];
    
    // Calcul de l'âge
    $age = null;
    if ($profile && $profile->birth_date) {
        $age = \Carbon\Carbon::parse($profile->birth_date)->age;
    }
@endphp

<div class="p-4 sm:p-6 md:p-8 bg-[#f8fafc] min-h-screen">
    
    <!-- Top Header Navigation & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.candidates') }}" class="flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#4B6A95] transition-colors bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Retour</span>
            </a>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <a href="{{ route('admin.candidates') }}" class="hover:text-slate-600">Liste des Candidats</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-bold">{{ $user->name }}</span>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-bold border border-slate-200 rounded-xl text-xs flex items-center gap-2 shadow-2xs transition">
                <span class="material-symbols-outlined text-[18px] text-slate-500">print</span>
                <span>Imprimer la Fiche</span>
            </button>
            <a href="{{ route('admin.candidates.edit', $registration->id) }}" class="px-4 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-2xs transition">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                <span>Modifier le Dossier</span>
            </a>
        </div>
    </div>

    <!-- Print Header (Visible only on print) -->
    <div class="hidden print-header mb-6 pb-4 border-b-2 border-slate-800">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase">CFPT Sénégal-Japon & JICA</h1>
                <p class="text-xs font-bold text-slate-600">Programme de Formation en Pays Tiers (PFPT) — Fiche Individuelle de Candidature</p>
            </div>
            <div class="text-right text-xs">
                <span class="font-bold text-slate-800">Dossier #{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</span><br>
                <span class="text-slate-500">Date d'impression : {{ date('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- ============================================================== -->
        <!-- LEFT COLUMN: Profile Header Card, Status & Notes (4 Cols) -->
        <!-- ============================================================== -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Candidate Summary Card -->
            <div class="info-card p-6 flex flex-col items-center text-center relative overflow-hidden">
                <!-- Avatar photo -->
                <div class="relative w-28 h-28 rounded-full border-4 border-white shadow-md overflow-hidden bg-blue-50 flex items-center justify-center text-[#4B6A95] text-3xl font-extrabold mb-3 shrink-0">
                    @if($profile && $profile->photo_path && Storage::disk('public')->exists($profile->photo_path))
                        <img src="{{ Storage::url($profile->photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @elseif($user->avatar && Storage::disk('public')->exists($user->avatar))
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                </div>

                <h2 class="text-lg font-extrabold text-slate-900 leading-tight mb-1">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500 font-medium break-all mb-2">{{ $user->email }}</p>

                @if($profile && $profile->matricule)
                    <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full text-[11px] font-mono font-bold mb-4">
                        Matricule : {{ $profile->matricule }}
                    </span>
                @endif

                <!-- Status Badge and Decision Action -->
                <div class="w-full pt-4 border-t border-slate-100 mt-2">
                    <div class="mb-3">
                        <span class="info-label text-center">Statut Actuel du Dossier</span>
                        @if($registration->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-extrabold">
                                <span class="material-symbols-outlined text-[16px]">verified</span> Dossier Admis / Sélectionné
                            </span>
                        @elseif($registration->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-extrabold">
                                <span class="material-symbols-outlined text-[16px]">cancel</span> Candidature Refusée
                            </span>
                        @elseif($registration->status === 'waiting_list')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-extrabold">
                                <span class="material-symbols-outlined text-[16px]">hourglass_empty</span> Liste d'Attente
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#4B6A95] border border-blue-200 rounded-xl text-xs font-extrabold">
                                <span class="material-symbols-outlined text-[16px]">pending</span> En Attente d'Examen
                            </span>
                        @endif
                    </div>

                    <!-- Quick Action buttons (if pending or change required) -->
                    <form action="{{ route('admin.candidates.update-status', $registration->id) }}" method="POST" class="flex flex-col gap-2 no-print">
                        @csrf
                        @method('PATCH')
                        @if($registration->status !== 'approved')
                            <button name="status" value="approved" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-2xs transition flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span> Approuver la Candidature
                            </button>
                        @endif
                        <div class="grid grid-cols-2 gap-2">
                            @if($registration->status !== 'waiting_list')
                                <button name="status" value="waiting_list" class="py-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xs hover:bg-amber-100 transition">
                                    Mettre en Attente
                                </button>
                            @endif
                            @if($registration->status !== 'rejected')
                                <button name="status" value="rejected" class="py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs hover:bg-rose-100 transition">
                                    Refuser
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Seminar & Training Details Card -->
            <div class="info-card p-5 space-y-4">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                    Séminaire & Formation
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="info-label">Séminaire Cible</span>
                        <div class="font-bold text-slate-800 text-sm flex items-start gap-1.5">
                            <span class="material-symbols-outlined text-slate-400 text-[18px] shrink-0 mt-0.5">apartment</span>
                            <span>{{ $registration->seminar->title }}</span>
                        </div>
                    </div>

                    @if($registration->topic)
                        <div class="pt-2 border-t border-slate-100">
                            <span class="info-label">Thème d'Atelier / Projet Pratique</span>
                            <div class="font-bold text-[#4B6A95] bg-blue-50/70 border border-blue-100 rounded-lg p-2.5">
                                {{ $registration->topic->name }}
                            </div>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2">
                        <div>
                            <span class="info-label">Édition / Cohorte</span>
                            <strong class="info-value text-xs">{{ $registration->seminar->cohort->title ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="info-label">Date Candidature</span>
                            <strong class="info-value text-xs">{{ $registration->created_at->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes & Remarques Box -->
            <div class="info-card p-5 flex flex-col justify-between" x-data="{ note: '' }">
                <div>
                    <div class="info-section-title">
                        <span class="material-symbols-outlined text-[18px]">sticky_note_2</span>
                        Notes Administratives
                    </div>

                    <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100 text-xs text-slate-600 leading-relaxed mb-3">
                        <ul class="space-y-1.5 list-disc list-inside">
                            <li>Dossier enregistré le {{ $registration->created_at->format('d/m/Y à H:i') }}.</li>
                            @if($profile && !empty($health))
                                <li>Questionnaire de santé : renseigné.</li>
                            @endif
                            <li>Séminaire : {{ $registration->seminar->title }}.</li>
                        </ul>
                    </div>

                    <textarea x-model="note" rows="2" class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-[#4B6A95] resize-none no-print" placeholder="Ajouter une note de suivi..."></textarea>
                </div>

                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between no-print">
                    <span class="text-[11px] text-slate-400">Admin — {{ date('d M Y') }}</span>
                    <button type="button" class="px-3 py-1.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-lg shadow-2xs transition">
                        Enregistrer
                    </button>
                </div>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- RIGHT COLUMN: ALL STRUCTURED FORM FIELDS WITHOUT EXCEPTION (8 Cols) -->
        <!-- ============================================================== -->
        <div class="lg:col-span-8 flex flex-col gap-6">

            <!-- 1. IDENTITÉ & ÉTAT CIVIL COMPLET -->
            <div class="info-card p-6">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    1. Identité & État Civil
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <div>
                        <span class="info-label">Nom Complet</span>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                    <div>
                        <span class="info-label">Sexe / Genre</span>
                        <div class="info-value">
                            @if(strtoupper($profile->gender ?? '') === 'M') Masculin (M)
                            @elseif(strtoupper($profile->gender ?? '') === 'F') Féminin (F)
                            @else {{ $profile->gender ?? 'N/A' }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Date de Naissance</span>
                        <div class="info-value">
                            {{ $profile && $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('d/m/Y') : 'N/A' }}
                            @if($age) <span class="text-slate-400 text-xs font-normal">({{ $age }} ans)</span> @endif
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Lieu de Naissance</span>
                        <div class="info-value">{{ $profile->birth_place ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <span class="info-label">Nationalité</span>
                        <div class="info-value">{{ $profile->nationality ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Pays de Résidence</span>
                        <div class="info-value">{{ $profile->residence_country ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Situation Familiale</span>
                        <div class="info-value">
                            @php
                                $statusMap = ['single' => 'Célibataire', 'married' => 'Marié(e)', 'divorced' => 'Divorcé(e)', 'widowed' => 'Veuf/Veuve'];
                                $fs = strtolower($profile->family_status ?? '');
                            @endphp
                            {{ $statusMap[$fs] ?? ($profile->family_status ?? 'N/A') }}
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Religion / Culte</span>
                        <div class="info-value">{{ $profile->religion ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- 2. COORDONNÉES & CONTACTS D'URGENCE -->
            <div class="info-card p-6">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[20px]">contact_phone</span>
                    2. Coordonnées & Contacts
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <span class="info-label">Email Principal</span>
                        <div class="info-value text-slate-800">{{ $user->email }}</div>
                    </div>
                    <div>
                        <span class="info-label">Téléphone / WhatsApp</span>
                        <div class="info-value">{{ $profile->phone ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Adresse de Résidence</span>
                        <div class="info-value">{{ $profile->address ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Contact d'urgence -->
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-xl">
                    <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider block mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-rose-500 text-[16px]">emergency</span>
                        Personne à contacter en cas d'urgence
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Nom complet :</span>
                            <strong class="text-slate-800">{{ $profile->emergency_contact_name ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Lien de parenté :</span>
                            <strong class="text-slate-800">{{ $profile->emergency_contact_relation ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Téléphone :</span>
                            <strong class="text-slate-800">{{ $profile->emergency_contact_phone ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Email :</span>
                            <strong class="text-slate-800">{{ $profile->emergency_contact_email ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PROFIL PROFESSIONNEL & EMPLOI -->
            <div class="info-card p-6">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[20px]">work</span>
                    3. Profil Professionnel & Emploi
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <span class="info-label">Profession / Métier</span>
                        <div class="info-value">{{ $profile->current_profession ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Grade / Titre</span>
                        <div class="info-value">{{ $profile->grade ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Fonction occupée</span>
                        <div class="info-value">{{ $profile->job_function ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Employeur Actuel</span>
                        <div class="info-value">{{ $profile->current_employer ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Employeur Précédent</span>
                        <div class="info-value">{{ $profile->previous_employer ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Plus Haut Diplôme</span>
                        <div class="info-value">{{ $profile->highest_degree ?? 'N/A' }}</div>
                    </div>
                </div>

                @if($profile && ($profile->job_description || $profile->training_utility))
                    <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                        @if($profile->job_description)
                            <div>
                                <span class="info-label">Description du Poste & Responsabilités</span>
                                <p class="text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100 leading-relaxed">{{ $profile->job_description }}</p>
                            </div>
                        @endif
                        @if($profile->training_utility)
                            <div>
                                <span class="info-label">Utilité / Impact attendu de la formation au CFPT</span>
                                <p class="text-slate-700 bg-blue-50/50 p-3 rounded-lg border border-blue-100 leading-relaxed">{{ $profile->training_utility }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 4. PARCOURS ACADÉMIQUE & DIPLÔMES -->
            <div class="info-card p-6">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[20px]">school</span>
                    4. Parcours Académique & Formations
                </div>

                @if($profile && $profile->academicHistories && $profile->academicHistories->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase text-[10px]">
                                <tr>
                                    <th class="p-3">Établissement</th>
                                    <th class="p-3">Filière / Spécialité</th>
                                    <th class="p-3">Diplôme Obtenu</th>
                                    <th class="p-3">Année</th>
                                    <th class="p-3">Mention / Distinction</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($profile->academicHistories as $edu)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="p-3 font-semibold text-slate-800">{{ $edu->establishment_name }}</td>
                                        <td class="p-3 text-slate-600">{{ $edu->field_of_study ?? '—' }}</td>
                                        <td class="p-3 font-bold text-[#4B6A95]">{{ $edu->degree_obtained ?? '—' }}</td>
                                        <td class="p-3 font-mono text-slate-600">{{ $edu->graduation_year ?? '—' }}</td>
                                        <td class="p-3 text-slate-600">{{ $edu->distinction ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Aucun diplôme spécifique enregistré dans l'historique académique.</p>
                @endif
            </div>

            <!-- 5. SANTÉ, ASSURANCE & LOGISTIQUE -->
            <div class="info-card p-6">
                <div class="info-section-title">
                    <span class="material-symbols-outlined text-[20px]">health_and_safety</span>
                    5. Santé, Assurance & Logistique
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <div>
                        <span class="info-label">Affection Chronique</span>
                        <div class="info-value">
                            @if(isset($health['chronic']) && $health['chronic'] === 'yes')
                                <span class="text-rose-600 font-bold">Oui</span>
                            @else
                                <span class="text-emerald-600 font-bold">Non</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Traitement Régulier</span>
                        <div class="info-value">
                            @if(isset($health['treatment']) && $health['treatment'] === 'yes')
                                <span class="text-rose-600 font-bold">Oui</span>
                            @else
                                <span class="text-emerald-600 font-bold">Non</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Préférence Hébergement</span>
                        <div class="info-value">{{ $profile->accommodation_preference ?? 'Non spécifié' }}</div>
                    </div>
                    <div>
                        <span class="info-label">Prime Assurance</span>
                        <div class="info-value font-mono">
                            {{ $profile && $profile->insurance_premium ? number_format($profile->insurance_premium, 0, ',', ' ') . ' FCFA' : '0 FCFA' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <span class="info-label">Observations Médicales</span>
                        <p class="text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ $profile->health_record_obs ?? 'Aucune observation particulière.' }}</p>
                    </div>
                    <div>
                        <span class="info-label">Détails Assurance</span>
                        <p class="text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ $profile->insurance_details ?? 'Non renseigné.' }}</p>
                    </div>
                </div>
            </div>

            <!-- 6. PIÈCES JOINTES & DOCUMENTS JUSTIFICATIFS -->
            <div class="info-card p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="info-section-title !p-0 !m-0 !border-0">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        6. Pièces Justificatives & Documents GED
                    </div>
                    <a href="{{ route('admin.candidates.edit', $registration->id) }}" class="text-xs text-[#4B6A95] font-bold hover:underline flex items-center gap-1 no-print">
                        <span class="material-symbols-outlined text-[16px]">add</span> Ajouter des documents
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($user->candidateResources as $doc)
                        @php
                            $icon = 'description';
                            $bgColor = 'bg-blue-50 text-[#4B6A95]';
                            $type = strtolower($doc->resource_type ?? '');
                            if(str_contains($type, 'cv')) { $icon = 'picture_as_pdf'; $bgColor = 'bg-rose-50 text-rose-600'; }
                            elseif(str_contains($type, 'dipl')) { $icon = 'workspace_premium'; $bgColor = 'bg-amber-50 text-amber-600'; }
                            elseif(str_contains($type, 'pass') || str_contains($type, 'ident')) { $icon = 'badge'; $bgColor = 'bg-emerald-50 text-emerald-600'; }
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition bg-white shadow-2xs">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl {{ $bgColor }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-bold text-slate-800 truncate">{{ $doc->resource_type ?? 'Document' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate">{{ $doc->original_filename ?? 'Fichier joint' }}</div>
                                </div>
                            </div>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="p-2 text-slate-400 hover:text-[#4B6A95] hover:bg-blue-50 rounded-lg transition shrink-0 ml-2" title="Consulter / Télécharger">
                                <span class="material-symbols-outlined text-[20px]">download</span>
                            </a>
                        </div>
                    @empty
                        <div class="sm:col-span-2 py-8 text-center text-slate-400 text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">folder_off</span>
                            <p>Aucun document justificatif attaché à ce dossier.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
