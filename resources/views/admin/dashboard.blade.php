@extends('layouts.admin')

@section('header_title', 'Tableau de Bord')

@section('content')
<style>
    .font-dashboard { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Textures hachurées modernes */
    .striped-red-bar {
        background-image: repeating-linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.25),
            rgba(255, 255, 255, 0.25) 2px,
            transparent 2px,
            transparent 5px
        );
        background-color: #dc2626;
    }
    .striped-brand-bar {
        background-image: repeating-linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.2),
            rgba(255, 255, 255, 0.2) 2px,
            transparent 2px,
            transparent 5px
        );
        background-color: #1e3a8a;
    }
    .striped-gray {
        background-image: repeating-linear-gradient(
            -45deg,
            #e2e8f0,
            #e2e8f0 2px,
            #f1f5f9 2px,
            #f1f5f9 5px
        );
    }
    /* Sleek Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="w-full min-h-screen bg-[#e9ebf0] p-3.5 lg:p-4.5 font-dashboard text-slate-800" 
     x-data="{
        selectedCohortId: '{{ $selectedCohort->id ?? '' }}',
        searchCandidate: '',
        filterSeminar: '',
        filterTopic: '',
        filterStatus: '',
        // Format countdown
        daysLeft: 0,
        hoursLeft: 0,
        progressPercent: 0,
        initCountdown() {
            @if(isset($selectedCohort) && $selectedCohort->start_date && $selectedCohort->end_date)
                const start = new Date('{{ $selectedCohort->start_date->toIso8601String() }}').getTime();
                const end = new Date('{{ $selectedCohort->end_date->toIso8601String() }}').getTime();
                const now = new Date().getTime();
                const total = end - start;
                const elapsed = Math.max(0, now - start);
                this.progressPercent = Math.min(100, Math.max(0, Math.round((elapsed / total) * 100)));
                
                const diff = end - now;
                if (diff > 0) {
                    this.daysLeft = Math.floor(diff / (1000 * 60 * 60 * 24));
                    this.hoursLeft = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                } else {
                    this.daysLeft = 0;
                    this.hoursLeft = 0;
                }
            @endif
        }
     }" 
     x-init="initCountdown(); $nextTick(() => { if (window.lucide) { lucide.createIcons(); } })">

    <!-- Top Container -->
    <div class="max-w-[1600px] mx-auto flex flex-col gap-3.5">

        <!-- Header / Welcome Banner with Cohort Switcher -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>Bonjour, {{ auth()->user()->name ?? 'Administrateur' }}</span>
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Supervision globale de l'édition, gestion des séminaires et suivi des candidatures.
                </p>
            </div>

            <!-- Cohort Switcher Form -->
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.dashboard') }}" method="GET" id="cohortSelectForm" class="m-0 flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    <span class="text-xs font-bold text-slate-700 hidden sm:inline">Édition :</span>
                    <select name="cohort_id" onchange="document.getElementById('cohortSelectForm').submit()" 
                            class="text-xs font-bold bg-transparent text-slate-800 outline-none border-none py-0.5 pr-6 cursor-pointer focus:ring-0">
                        @foreach($cohorts as $cohort)
                            <option value="{{ $cohort->id }}" {{ (isset($selectedCohort) && $selectedCohort->id == $cohort->id) ? 'selected' : '' }}>
                                {{ $cohort->title }} {{ $cohort->status === 'active' ? '(En cours)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('admin.cohorts.index') }}" class="p-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg text-slate-700 shadow-2xs transition flex items-center justify-center" title="Gérer les éditions">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- Dashboard Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3.5">

            <!-- ============================================================== -->
            <!-- COLUMN 1: ZONE GAUCHE (ÉDITION EN COURS & SÉMINAIRES) + TIMER + NOTIFS (4 Cols) -->
            <!-- ============================================================== -->
            <div class="xl:col-span-4 flex flex-col gap-3.5">

                <!-- Édition en cours, Budget & Séminaires Card -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex flex-col gap-4 relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Édition en cours</span>
                                @if(isset($selectedCohort) && $selectedCohort->status === 'active')
                                    <span class="text-xs font-extrabold text-emerald-600">Active</span>
                                @endif
                            </div>
                            <div class="text-2xl font-black text-slate-900 mt-1 tracking-tight">
                                {{ $selectedCohort->title ?? 'Session Spéciale' }}
                            </div>
                            <div class="text-xs text-slate-500 font-medium mt-1">
                                Budget Alloué : <strong class="text-slate-900 font-extrabold">{{ number_format($selectedCohort->budget ?? 0, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>

                        <!-- Badge Partenariat (Bleu primaire) -->
                        <span class="text-xs font-black text-[#4B6A95]">PFPT - CFPT</span>
                    </div>

                    <!-- Quick Navigation / Management Buttons -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="{{ route('admin.cohorts.index') }}" class="bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold py-2.5 px-3 rounded-lg flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span>Gérer Édition</span>
                        </a>
                        <a href="{{ route('admin.seminars.create') }}" class="bg-[#f4f5f7] hover:bg-slate-200 text-slate-700 text-xs font-bold py-2.5 px-3 rounded-lg flex items-center justify-center gap-1.5 transition">
                            <span>+ Séminaire</span>
                        </a>
                    </div>

                    <!-- Séminaires de l'édition -->
                    <div>
                        <div class="text-xs font-bold text-slate-800 mb-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span>Séminaires</span>
                                <span class="text-slate-300 font-light">|</span>
                                <span class="text-slate-400 font-semibold">Total {{ $allSeminars->count() }} séminaires</span>
                            </div>
                            <a href="{{ route('admin.seminars') }}" class="text-[11px] font-bold text-[#4B6A95] hover:underline flex items-center gap-1">
                                <span>Voir tous</span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>

                        <!-- Mini Seminars Grid / Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-2.5 max-h-72 overflow-y-auto pr-1 custom-scrollbar">
                            @forelse($allSeminars->take(4) as $sem)
                                <div class="border border-slate-200 bg-[#fafbfc] hover:bg-white hover:border-[#4B6A95]/30 rounded-lg p-3.5 flex flex-col justify-between transition group shadow-2xs">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-900 group-hover:text-[#4B6A95] transition line-clamp-1">
                                            {{ $sem->title }}
                                        </span>
                                        <span class="text-[11px] font-bold {{ $sem->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }} shrink-0 ml-2">
                                            {{ $sem->status === 'active' ? 'Ouvert' : 'Prévu' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-[10px] text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                                            <span>Du {{ \Carbon\Carbon::parse($sem->start_date)->format('d/m') }} au {{ \Carbon\Carbon::parse($sem->end_date)->format('d/m/Y') }}</span>
                                        </span>
                                        <span class="font-bold text-slate-700">{{ $sem->max_participants ?? 20 }} places</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3.5 text-center text-xs text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                    Aucun séminaire configuré pour cette édition.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- [ZONE TIMER] Compte à rebours & Progression temporelle de l'édition -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                            <i data-lucide="hourglass" class="w-4 h-4 text-[#4B6A95]"></i>
                            Compte à Rebours de l'Édition
                        </span>
                        <span class="text-[11px] font-extrabold text-[#4B6A95]" x-text="daysLeft + ' jours ' + hoursLeft + 'h restants'"></span>
                    </div>

                    <!-- Progress Bar Hachurée Bleue -->
                    <div class="w-full h-3 rounded-full bg-slate-100 flex overflow-hidden p-0.5 border border-slate-200">
                        <div class="h-full bg-[#4B6A95] rounded-full transition-all duration-500" :style="'width: ' + progressPercent + '%'"></div>
                        <div class="h-full striped-gray flex-1"></div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                        <span>Début : <strong class="text-slate-800">{{ $selectedCohort?->start_date ? $selectedCohort->start_date->format('d M Y') : 'N/A' }}</strong></span>
                        <span>Fin : <strong class="text-slate-800">{{ $selectedCohort?->end_date ? $selectedCohort->end_date->format('d M Y') : 'N/A' }}</strong></span>
                    </div>
                </div>

                <!-- [ZONE NOTIFICATIONS & ACTIVITÉS RÉCENTES] -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 font-bold text-xs text-slate-900">
                            <i data-lucide="activity" class="w-4 h-4 text-[#4B6A95]"></i>
                            <span>Activités & Historique Récent</span>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                            {{ $recentLogs->count() }} actions
                        </span>
                    </div>

                    <!-- Activities Stream -->
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                        @forelse($recentLogs as $log)
                            <div class="p-2.5 bg-slate-50 hover:bg-slate-100/70 rounded-lg border border-slate-100 flex items-start gap-2.5 transition">
                                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[#4B6A95] font-bold text-xs shrink-0 shadow-2xs">
                                    <i data-lucide="check" class="w-3 h-3 text-[#4B6A95]"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        {{ $log->action ?? 'Action effectuée' }}
                                    </p>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-0.5">
                                        <span>{{ $log->user->name ?? 'Système' }}</span>
                                        <span>{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-xs text-slate-400 bg-slate-50 rounded-lg">
                                Aucune activité récente enregistrée.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- COLUMN 2 & 3: ZONE BLEUE (4 STATS) + GRAPHIQUE + ZONE JAUNE (TABLE CANDIDATS) (8 Cols) -->
            <!-- ============================================================== -->
            <div class="xl:col-span-8 flex flex-col gap-3.5">

                <!-- Top Sub-Row: 4 Metrics (Zone Bleue) & Graphic Chart -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">

                    <!-- 4 Metric Cards (6 Cols) -->
                    <div class="lg:col-span-6 grid grid-cols-2 gap-2.5">
                        <!-- Card 1: Total Demandes / Inscrits (Bleu marque) -->
                        <div class="bg-gradient-to-br from-[#4B6A95] to-[#344d70] rounded-xl p-3.5 text-white flex flex-col justify-between shadow-sm h-32 relative overflow-hidden">
                            <div class="flex justify-between items-center relative z-10">
                                <span class="text-xs font-bold opacity-90">Total Inscrits</span>
                                <i data-lucide="users" class="w-5 h-5 text-white/90"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="text-2xl sm:text-3xl font-black tracking-tight">{{ $stats['total_registrations'] ?? ($stats['pending_registrations_count'] + $stats['approved_registrations_count'] + $stats['rejected_registrations_count']) }}</div>
                                <div class="text-[10px] font-semibold opacity-90">Demandes reçues au total</div>
                            </div>
                        </div>

                        <!-- Card 2: Candidats À Examiner (En attente) -->
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 flex flex-col justify-between shadow-sm h-32">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-700">À Examiner</span>
                                <i data-lucide="hourglass" class="w-5 h-5 text-amber-500"></i>
                            </div>
                            <div>
                                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $stats['pending_registrations_count'] }}</div>
                                <div class="text-[10px] font-semibold text-amber-600">
                                    En attente de décision
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Candidats Sélectionnés (Approuvés) -->
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 flex flex-col justify-between shadow-sm h-32">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-700">Sélectionnés</span>
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                            </div>
                            <div>
                                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $stats['approved_registrations_count'] }}</div>
                                <div class="text-[10px] font-semibold text-emerald-600">
                                    Retenus pour la formation
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Candidats Non Retenus (Refusés) -->
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 flex flex-col justify-between shadow-sm h-32">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-700">Non Retenus</span>
                                <i data-lucide="x-circle" class="w-5 h-5 text-rose-500"></i>
                            </div>
                            <div>
                                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $stats['rejected_registrations_count'] }}</div>
                                <div class="text-[10px] font-semibold text-rose-500">
                                    Dossiers rejetés
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Représentation Graphique des Candidatures par Séminaire avec Repères et Graduations Automatisées (6 Cols) -->
                    <div class="lg:col-span-6 bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-xs font-extrabold text-slate-900">Répartition par Séminaire</h3>
                                <p class="text-[10px] text-slate-400 font-medium">Nombre de candidatures enregistrées</p>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] font-bold text-slate-600">
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-sm bg-[#4B6A95]"></span> Inscrits
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-sm bg-[#8ebdf9]"></span> Retenus
                                </span>
                            </div>
                        </div>

                        @php
                            $topSeminars = $allSeminars->take(6);
                            $maxVal = 0;
                            foreach ($topSeminars as $semItem) {
                                $count = $semItem->registrations()->count();
                                if ($count > $maxVal) {
                                    $maxVal = $count;
                                }
                            }
                            // Graduation automatique de l'axe Y
                            $yMax = max(10, (int)(ceil(max(1, $maxVal) / 5) * 5));
                            $yStep = (int)($yMax / 4);
                            $yTicks = [$yMax, $yStep * 3, $yStep * 2, $yStep, 0];
                        @endphp

                        <!-- Zone du Graphique avec Axes X & Y Gradués -->
                        <div class="relative flex items-stretch gap-2 h-36 pt-2">
                            <!-- Axe Y (Graduations numériques) -->
                            <div class="flex flex-col justify-between text-[9px] font-bold text-slate-400 pr-1 select-none border-r border-slate-200">
                                @foreach($yTicks as $tick)
                                    <span class="text-right w-5 leading-none">{{ $tick }}</span>
                                @endforeach
                            </div>

                            <!-- Conteneur des Barres avec lignes de repère horizontales -->
                            <div class="flex-1 relative flex flex-col justify-between">
                                <!-- Lignes de repère horizontales en arrière-plan -->
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                                    <div class="border-b border-slate-100 border-dashed w-full"></div>
                                    <div class="border-b border-slate-100 border-dashed w-full"></div>
                                    <div class="border-b border-slate-100 border-dashed w-full"></div>
                                    <div class="border-b border-slate-100 border-dashed w-full"></div>
                                    <div class="border-b border-slate-300 w-full"></div>
                                </div>

                                <!-- Colonnes de barres par Séminaire -->
                                <div class="relative z-10 h-full flex items-end justify-around gap-2 px-2">
                                    @forelse($topSeminars as $index => $s)
                                        @php
                                            $sTotal = $s->registrations()->count();
                                            $sApproved = $s->registrations()->where('status', 'approved')->count();
                                            
                                            $totalPct = min(100, max(6, ($sTotal / $yMax) * 100));
                                            $approvedPct = min(100, max(4, ($sApproved / $yMax) * 100));
                                        @endphp
                                        <div class="flex flex-col items-center gap-1.5 flex-1 group">
                                            <!-- Colonnes groupées : Inscrits & Retenus -->
                                            <div class="w-full flex items-end justify-center gap-1 h-24 relative" title="{{ $s->title }} : {{ $sTotal }} inscrits ({{ $sApproved }} retenus)">
                                                <!-- Barre Inscrits -->
                                                <div class="w-3.5 bg-[#4B6A95] rounded-t-sm transition-all duration-300 group-hover:bg-[#354e70] relative flex items-start justify-center" 
                                                     style="height: {{ $totalPct }}%;">
                                                    @if($sTotal > 0)
                                                        <span class="absolute -top-3.5 text-[8px] font-bold text-[#4B6A95] opacity-0 group-hover:opacity-100 transition">{{ $sTotal }}</span>
                                                    @endif
                                                </div>
                                                <!-- Barre Retenus -->
                                                <div class="w-3.5 bg-[#8ebdf9] rounded-t-sm transition-all duration-300 group-hover:bg-[#6baaf5] relative flex items-start justify-center" 
                                                     style="height: {{ $approvedPct }}%;">
                                                    @if($sApproved > 0)
                                                        <span class="absolute -top-3.5 text-[8px] font-bold text-sky-600 opacity-0 group-hover:opacity-100 transition">{{ $sApproved }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Label Axe X (S1, S2, S3...) -->
                                            <span class="text-[10px] font-extrabold text-slate-600 truncate max-w-[50px] group-hover:text-[#4B6A95] transition" title="{{ $s->title }}">
                                                S{{ $index + 1 }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="w-full text-center text-xs text-slate-400 py-6">
                                            Aucune donnée disponible pour cette édition.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ============================================================== -->
                <!-- [TABLEAU DES CANDIDATS AVEC FOND BLANC ET FILTRES PAR THÈME] (8 Cols) -->
                <!-- ============================================================== -->
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col gap-3.5">
                    
                    <!-- Table Header & Controls -->
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3">
                        <div class="shrink-0">
                            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                <i data-lucide="user-check" class="w-4.5 h-4.5 text-[#4B6A95]"></i>
                                <span>Candidats Récents & Décisions</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Filtrage direct par séminaire, thème et statut d'admission.</p>
                        </div>

                        <!-- Filters & Search Bar -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Search Input -->
                            <div class="relative">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
                                <input type="text" x-model="searchCandidate" placeholder="Rechercher..." 
                                       class="bg-slate-50 border border-slate-200 rounded-lg pl-7 pr-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#4B6A95] w-32 sm:w-40">
                            </div>

                            <!-- Filter by Seminar -->
                            <select x-model="filterSeminar" class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:border-[#4B6A95] cursor-pointer max-w-[130px]">
                                <option value="">Tous séminaires</option>
                                @foreach($allSeminars as $seminar)
                                    <option value="{{ $seminar->title }}">{{ Str::limit($seminar->title, 20) }}</option>
                                @endforeach
                            </select>

                            <!-- Filter by Topic / Thème -->
                            <select x-model="filterTopic" class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:border-[#4B6A95] cursor-pointer max-w-[130px]">
                                <option value="">Tous les thèmes</option>
                                @foreach($allTopics as $top)
                                    <option value="{{ $top->name }}">{{ Str::limit($top->name, 20) }}</option>
                                @endforeach
                            </select>

                            <!-- Filter by Status -->
                            <select x-model="filterStatus" class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:border-[#4B6A95] cursor-pointer">
                                <option value="">Tous statuts</option>
                                <option value="pending">À Examiner</option>
                                <option value="approved">Sélectionné</option>
                                <option value="rejected">Refusé</option>
                            </select>

                            <a href="{{ route('admin.candidates') }}" class="px-3.5 py-1.5 bg-[#4B6A95] text-white rounded-lg text-xs font-bold hover:bg-[#3c5678] transition shadow-2xs flex items-center gap-1 shrink-0 whitespace-nowrap">
                                <span>Gérer tout</span>
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Scrollable Candidates Table Container (Fond blanc propre) -->
                    <div class="overflow-x-auto max-h-[420px] overflow-y-auto custom-scrollbar border border-slate-100 rounded-lg bg-white">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-slate-50/95 backdrop-blur-xs z-10 border-b border-slate-200">
                                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="py-3 px-3.5 w-10 text-center"><input type="checkbox" class="rounded border-slate-300"></th>
                                    <th class="py-3 px-3.5 font-semibold">Candidat</th>
                                    <th class="py-3 px-3.5 font-semibold">Séminaire / Thème</th>
                                    <th class="py-3 px-3.5 font-semibold">Nationalité</th>
                                    <th class="py-3 px-3.5 font-semibold">Statut</th>
                                    <th class="py-3 px-3.5 font-semibold">Date Demande</th>
                                    <th class="py-3 px-3.5 text-right font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100 font-medium text-slate-700 bg-white">
                                @forelse($recentRegistrations as $reg)
                                    @php
                                        $cProfile = $reg->user->candidateProfile;
                                        $statusClass = match($reg->status) {
                                             'approved' => 'text-emerald-600',
                                             'rejected' => 'text-rose-600',
                                             'waiting_list' => 'text-blue-600',
                                             default => 'text-amber-600',
                                        };
                                        $statusDot = match($reg->status) {
                                             'approved' => 'bg-emerald-500',
                                             'rejected' => 'bg-rose-500',
                                             'waiting_list' => 'bg-blue-500',
                                             default => 'bg-amber-500',
                                        };
                                        $statusLabel = match($reg->status) {
                                             'approved' => 'Sélectionné',
                                             'rejected' => 'Refusé',
                                             'waiting_list' => 'En attente',
                                             default => 'À Examiner',
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition" 
                                        x-show="(
                                            ('{{ strtolower($reg->user->name) }}'.includes(searchCandidate.toLowerCase()) || '{{ strtolower($reg->user->email) }}'.includes(searchCandidate.toLowerCase())) &&
                                            (filterSeminar === '' || '{{ $reg->seminar->title ?? '' }}'.includes(filterSeminar)) &&
                                            (filterTopic === '' || '{{ $reg->topic->name ?? '' }}'.includes(filterTopic)) &&
                                            (filterStatus === '' || '{{ $reg->status }}' === filterStatus)
                                        )">
                                        <td class="py-3 px-3.5 text-center"><input type="checkbox" class="rounded border-slate-300"></td>
                                        
                                        <!-- Candidate Avatar + Name -->
                                        <td class="py-3 px-3.5">
                                            <a href="{{ route('admin.candidates.show', $reg->id) }}" class="flex items-center gap-2.5 hover:text-[#4B6A95] transition">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-[#4B6A95] text-xs shrink-0 overflow-hidden">
                                                    @if($cProfile && $cProfile->photo_path)
                                                        <img src="{{ asset('storage/' . $cProfile->photo_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($reg->user->name, 0, 1) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-800 block">{{ $reg->user->name }}</span>
                                                    <span class="text-[10px] text-slate-400">{{ $reg->user->email }}</span>
                                                </div>
                                            </a>
                                        </td>

                                        <!-- Seminar & Topic -->
                                        <td class="py-3 px-3.5">
                                            <div class="font-semibold text-slate-800 line-clamp-1">{{ $reg->seminar->title ?? 'Séminaire' }}</div>
                                            @if($reg->topic)
                                                <div class="text-[10px] text-[#4B6A95] font-semibold line-clamp-1">{{ $reg->topic->name }}</div>
                                            @endif
                                        </td>

                                        <!-- Country / Nationality -->
                                        <td class="py-3 px-3.5 font-semibold text-slate-700">
                                            {{ $cProfile->nationality ?? 'Sénégalaise' }}
                                        </td>

                                        <!-- Status Text -->
                                        <td class="py-3 px-3.5">
                                            <span class="text-[11px] font-bold {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <!-- Date -->
                                        <td class="py-3 px-3.5 text-slate-500 font-medium text-[11px]">
                                            {{ $reg->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3 px-3.5 text-right">
                                            <a href="{{ route('admin.candidates.show', $reg->id) }}" class="p-1.5 text-slate-400 hover:text-red-600 inline-flex items-center" title="Consulter le dossier">
                                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                            Aucune candidature enregistrée pour cette session.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endsection

