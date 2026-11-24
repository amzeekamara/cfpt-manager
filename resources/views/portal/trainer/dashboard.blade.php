@extends('portal.layout')
@section('title', 'Espace Formateur')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- =========================================
     STYLES SPÉCIFIQUES AU DASHBOARD
     ========================================= --}}
    <style>
        :root {
            --brand: #4B6A95;
            --brand-light: #d2e4ff;
            --brand-dark: #354e70;
            --bg-card: #ffffff;
            --bg-page: #F8FAFC;
            --text-main: #1f2937;
            --text-muted: #9ca3af;
            --border: #e5e7eb;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --accent-red: #ef4444;
        }

        .dash-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
        }

        .dash-card.dark {
            background: #2d3f5a;
            color: white;
            border-color: #2d3f5a;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ---- KPI COMPACT (horizontal, B&W icon, sparkline) ---- */
        .kpi-grid {
            gap: 12px;
        }

        .kpi-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transition: box-shadow .25s, transform .25s;
            cursor: default;
            backdrop-filter: blur(6px);
        }

        .kpi-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            transform: translateY(-2px);
        }

        .kpi-card.kpi-dark {
            background: rgba(30, 45, 61, 0.75);
            border-color: rgba(255, 255, 255, 0.12);
            color: white;
        }

        /* Icône B&W */
        .kpi-bw-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f3f4f6;
            color: #111827;
        }

        .kpi-dark .kpi-bw-icon {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .kpi-body {
            flex: 1;
            min-width: 0;
        }

        .kpi-lbl {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 2px;
        }

        .kpi-dark .kpi-lbl {
            color: rgba(255, 255, 255, .5);
        }

        .kpi-val {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            line-height: 1.1;
        }

        .kpi-dark .kpi-val {
            color: #fff;
        }

        .kpi-sub {
            font-size: 10px;
            color: #d1d5db;
            margin-top: 2px;
        }

        .kpi-dark .kpi-sub {
            color: rgba(255, 255, 255, .4);
        }

        /* Sparkline SVG */
        .kpi-spark {
            flex-shrink: 0;
            opacity: .55;
        }

        .kpi-card:hover .kpi-spark {
            opacity: 1;
        }

        /* Badge tendance */
        .kpi-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 5px;
            background: #f3f4f6;
            color: #374151;
            margin-top: 4px;
        }

        .kpi-dark .kpi-badge {
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .8);
        }

        .badge-up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .badge-down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        .badge-neu {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-amber);
        }

        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Table styles */
        .dash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dash-table th {
            text-align: left;
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .dash-table td {
            padding: 11px 0;
            font-size: 13px;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        /* Nav tabs */
        .tab-btn {
            font-size: 12px;
            padding: 5px 14px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: none;
            color: var(--text-muted);
            transition: all .2s;
        }

        .tab-btn.active {
            background: #2d3f5a;
            color: white;
        }

        /* Message card */
        .msg-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            transition: all .2s;
            background: #fafafa;
        }

        .msg-card:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        /* Section header */
        .section-label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Status pill */
        .pill {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .pill-green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .pill-amber {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-amber);
        }

        .pill-red {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        .pill-blue {
            background: rgba(75, 106, 149, 0.1);
            color: var(--brand);
        }

        /* Btn */
        .btn-brand {
            background: #2d3f5a;
            color: white;
            border: none;
            padding: 9px 0;
            border-radius: 9px;
            width: 100%;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s;
        }

        .btn-brand:hover {
            opacity: .85;
        }

        .btn-outline-brand {
            background: white;
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 9px 0;
            border-radius: 9px;
            width: 100%;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-outline-brand:hover {
            border-color: #2d3f5a;
        }
    </style>


    {{-- =========================================
     HERO BLOCK : Header Profil + KPIs (with shared background)
     ========================================= --}}
    <div class="relative overflow-hidden rounded-2xl mb-5" style="background: #2d3f5a;">
        {{-- Background image --}}
        <div class="absolute inset-0"
            style="background-image: url('{{ asset('images/centre.png') }}'); background-size: cover; background-position: center; opacity: 0.25;">
        </div>
        {{-- Gradient overlay covering full hero --}}
        <div class="absolute inset-0"
            style="background: linear-gradient(135deg, rgba(45,63,90,0.90) 0%, rgba(53,78,112,0.80) 60%, rgba(45,63,90,0.75) 100%);">
        </div>

        {{-- Inner content --}}
        <div class="relative z-10">

            {{-- HEADER : Profil --}}
            <div class="p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    {{-- Avatar + Nom --}}
                    <div class="flex items-center gap-5">
                        @if (isset($trainer) && $trainer->avatar)
                            <img src="{{ asset('storage/' . $trainer->avatar) }}" alt="{{ $trainer->name }}"
                                class="w-20 h-20 rounded-full border-4 border-white/30 shadow-xl object-cover shrink-0">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($trainer->name) }}&background=4B6A95&color=fff&size=200"
                                alt="{{ $trainer->name }}"
                                class="w-20 h-20 rounded-full border-4 border-white/30 shadow-xl object-cover shrink-0">
                        @endif
                        <div>
                            <p class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-1">Espace Formateur</p>
                            <h1 class="text-2xl md:text-3xl font-headline font-bold text-white leading-tight">
                                {{ $trainer->name }}</h1>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="flex items-center gap-1 text-blue-200 text-xs">
                                    <span class="material-symbols-outlined text-[14px]">mail</span>
                                    {{ $trainer->email ?? 'Non renseigné' }}
                                </span>
                                <span class="flex items-center gap-1 text-blue-200 text-xs">
                                    <span class="material-symbols-outlined text-[14px]">call</span>
                                    {{ $trainer->phone ?? 'Non renseigné' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons accès rapide Studio de Cours & Présences --}}
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        <a href="{{ route('portal.trainer.workspace') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition-all shadow-md">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                            Cours & Évaluations (OpenClassrooms)
                        </a>
                        <a href="{{ route('portal.trainer.attendances') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white rounded-xl font-bold text-sm transition-all backdrop-blur-sm">
                            <span class="material-symbols-outlined text-[18px]">checklist</span>
                            Gérer les Présences
                        </a>
                    </div>
                </div>
            </div>

            {{-- Separator line --}}
            <div class="border-t border-white/10 mx-5"></div>

            {{-- KPI GRID inside the hero block --}}
            <div class="kpi-grid p-5">


                {{-- KPI 1 : Filières — dark --}}
                <div class="kpi-card kpi-dark">
                    <div class="kpi-bw-icon">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-lbl">Filières & Cours</div>
                        <div class="kpi-val">{{ $trainer->seminars->count() }}</div>
                        <span class="kpi-badge">
                            <span class="material-symbols-outlined text-[11px]">trending_up</span> Actif cette année
                        </span>
                    </div>
                    <svg class="kpi-spark" width="52" height="28" viewBox="0 0 52 28" fill="none">
                        <polyline points="0,24 8,18 16,20 24,10 32,14 40,6 52,2" stroke="rgba(255,255,255,.5)"
                            stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="52" cy="2" r="3" fill="rgba(255,255,255,.7)" />
                    </svg>
                </div>

                {{-- KPI 2 : Étudiants --}}
                <div class="kpi-card">
                    <div class="kpi-bw-icon">
                        <span class="material-symbols-outlined text-[20px]">group</span>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-lbl">Étudiants</div>
                        <div class="kpi-val">{{ $totalCandidates }}</div>
                        <span class="kpi-badge">
                            <span class="material-symbols-outlined text-[11px]">verified</span> Inscrits
                        </span>
                    </div>
                    <svg class="kpi-spark" width="52" height="28" viewBox="0 0 52 28" fill="none">
                        <polyline points="0,22 10,16 20,18 30,8 40,12 52,4" stroke="#1e2d3d" stroke-width="1.8"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="52" cy="4" r="3" fill="#1e2d3d" />
                    </svg>
                </div>

                {{-- KPI 3 : Heures de cours dispensées --}}
                <div class="kpi-card">
                    <div class="kpi-bw-icon">
                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-lbl">Volume Horaire</div>
                        <div class="kpi-val">{{ $totalHoursTaught ?? 0 }} <span
                                class="text-xs font-bold text-slate-400">heures</span></div>
                        <span class="kpi-badge">
                            <span class="material-symbols-outlined text-[11px]">history_edu</span> Effectué
                        </span>
                    </div>
                    <svg class="kpi-spark" width="52" height="28" viewBox="0 0 52 28" fill="none">
                        <polyline points="0,20 12,20 20,12 28,16 36,8 44,10 52,4" stroke="#1e2d3d" stroke-width="1.8"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="52" cy="4" r="3" fill="#1e2d3d" />
                    </svg>
                </div>

                {{-- KPI 4 : Séances Effectuées --}}
                <div class="kpi-card">
                    <div class="kpi-bw-icon">
                        <span class="material-symbols-outlined text-[20px]">event_available</span>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-lbl">Séances Saisies</div>
                        <div class="kpi-val">{{ $trainerSessions->count() }}</div>
                        <span class="kpi-badge">
                            <span class="material-symbols-outlined text-[11px]">fact_check</span> Émargées
                        </span>
                    </div>
                    <svg class="kpi-spark" width="52" height="28" viewBox="0 0 52 28" fill="none">
                        <polyline points="0,26 10,22 20,24 30,14 40,18 52,8" stroke="#1e2d3d" stroke-width="1.8"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="52" cy="8" r="3" fill="#1e2d3d" />
                    </svg>
                </div>

            </div>{{-- end kpi-grid --}}
        </div>{{-- end inner content --}}
    </div>{{-- end hero block --}}

    {{-- =========================================
     GRILLE PRINCIPALE
     ========================================= --}}
    <div class="main-grid">

        {{-- COLONNE GAUCHE : Tableau étudiants & Suivi des Présences --}}
        <div class="dash-card">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <p class="section-label">Suivi Pédagogique</p>
                    <h2 class="section-title">Liste des Étudiants, Emploi du Temps & Heures</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('portal.trainer.attendances') }}"
                        class="flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-xl bg-[#4B6A95] text-white hover:bg-[#354e70] transition-colors shadow-2xs">
                        <span class="material-symbols-outlined text-[16px]">checklist</span> Émargement & Filtres
                    </a>
                </div>
            </div>

            {{-- Onglets de bascule --}}
            <div class="flex flex-wrap items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                <button type="button" id="tab-btn-candidates" onclick="switchTrainerTab('candidates')"
                    class="tab-btn active text-xs font-bold">
                    Étudiants & Présences ({{ $recentCandidates->count() }})
                </button>
                <button type="button" id="tab-btn-timetable" onclick="switchTrainerTab('timetable')"
                    class="tab-btn text-xs font-bold">
                    Mon Emploi du Temps ({{ isset($trainerTimetables) ? $trainerTimetables->count() : 0 }})
                </button>
                <button type="button" id="tab-btn-sessions" onclick="switchTrainerTab('sessions')"
                    class="tab-btn text-xs font-bold">
                    Heures Effectuées ({{ $trainerSessions->count() }})
                </button>
                <button type="button" id="tab-btn-topics" onclick="switchTrainerTab('topics')"
                    class="tab-btn text-xs font-bold">
                    Par Matière ({{ $topicsWithCounts->count() }})
                </button>
            </div>

            {{-- Contenu Onglet 1 : Liste directe des candidats et statuts --}}
            <div id="tab-content-candidates">
                @if ($recentCandidates->count() > 0)
                    <div
                        class="flex items-center justify-between gap-2 p-2.5 mb-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-500 font-bold">Séance du
                            {{ \Carbon\Carbon::parse($todayDate)->locale('fr')->isoFormat('D MMMM YYYY') }} :</span>
                        <div class="flex items-center gap-3 font-bold">
                            <span class="text-emerald-600">{{ $todayPresentCount }} Présent(s)</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-rose-600">{{ $todayAbsentCount }} Absent(s)</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                        <table class="dash-table w-full">
                            <thead>
                                <tr>
                                    <th style="width: 42%;">Candidat</th>
                                    <th style="width: 38%;">Thème</th>
                                    <th style="width: 20%; text-align: right;">Statut Séance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentCandidates as $candidate)
                                    @php
                                        $att = $candidate->attendances->first();
                                        $attStatus = $att ? $att->status : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-2.5">
                                                @if ($candidate->user->avatar && Storage::disk('public')->exists($candidate->user->avatar))
                                                    <img src="{{ Storage::url($candidate->user->avatar) }}"
                                                        alt="Avatar" class="w-7 h-7 rounded-full object-cover shrink-0">
                                                @else
                                                    <div
                                                        class="w-7 h-7 rounded-full bg-[#4B6A95]/15 text-[#4B6A95] font-black text-[11px] flex items-center justify-center shrink-0">
                                                        {{ strtoupper(substr($candidate->user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <div class="font-bold text-slate-900 text-xs leading-tight truncate">
                                                        {{ $candidate->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-medium truncate">
                                                        {{ $candidate->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-xs text-slate-600 font-medium line-clamp-1"
                                                title="{{ $candidate->topic?->name ?? 'Général' }}">
                                                {{ $candidate->topic?->name ?? 'Général' }}
                                            </span>
                                        </td>
                                        <td class="text-right whitespace-nowrap">
                                            @if ($attStatus === 'present')
                                                <span class="text-xs font-bold text-emerald-600">Présent</span>
                                            @elseif($attStatus === 'absent')
                                                <span class="text-xs font-bold text-rose-600">Absent</span>
                                            @else
                                                <span class="text-xs font-bold text-amber-500">Non émargé</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <span class="material-symbols-outlined text-[40px] mb-2">person_off</span>
                        <p class="text-sm font-bold text-gray-600">Aucun candidat approuvé.</p>
                    </div>
                @endif
            </div>

            {{-- Contenu Onglet Emploi du Temps Formateur --}}
            <div id="tab-content-timetable" class="hidden">
                @if (isset($trainerTimetables) && $trainerTimetables->count() > 0)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 mb-3 rounded-2xl bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] text-white text-xs shadow-xs gap-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-amber-300">calendar_clock</span>
                            <span class="font-black text-sm">Mon Planning Hebdomadaire des Cours</span>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 font-black text-xs">
                            {{ $trainerTimetables->sum('planned_hours') }} heures programmées / semaine
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($trainerTimetables as $slot)
                            @php
                                $palette = match($slot->id % 4) {
                                    0 => 'bg-[#e0f2fe] border-[#bae6fd] text-[#0369a1]',
                                    1 => 'bg-[#dcfce7] border-[#bbf7d0] text-[#15803d]',
                                    2 => 'bg-[#f3e8ff] border-[#e9d5ff] text-[#7e22ce]',
                                    default => 'bg-[#ffedd5] border-[#fed7aa] text-[#c2410c]',
                                };
                                $bullet = match($slot->id % 4) {
                                    0 => 'bg-sky-500',
                                    1 => 'bg-emerald-500',
                                    2 => 'bg-purple-500',
                                    default => 'bg-amber-500',
                                };
                            @endphp
                            <div class="p-3.5 rounded-2xl border {{ $palette }} hover:shadow-md transition flex flex-col justify-between gap-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-black text-[11px] uppercase tracking-wider">
                                        {{ $slot->day_of_week }}
                                    </span>
                                    <span class="text-xs font-black flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $bullet }}"></span>
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} –
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                        ({{ $slot->planned_hours }}h)
                                    </span>
                                </div>
                                <div>
                                    <div class="font-black text-slate-900 text-xs leading-snug line-clamp-2">
                                        {{ $slot->topic?->name ?? 'Tronc commun' }}
                                    </div>
                                    <div class="text-[11px] font-bold opacity-80 line-clamp-1 mt-0.5">
                                        {{ $slot->seminar?->title ?? 'Filière' }}
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-black/5 flex items-center justify-between text-[11px]">
                                    <span class="font-extrabold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">meeting_room</span>
                                        {{ $slot->classroom?->name ?? 'Salle assignée' }}
                                    </span>
                                    <a href="{{ route('portal.trainer.attendances', ['seminar_id' => $slot->seminar_id, 'topic_id' => $slot->topic_id]) }}"
                                       class="px-2.5 py-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] flex items-center gap-1 transition shadow-2xs">
                                        <span class="material-symbols-outlined text-[13px]">fact_check</span>
                                        <span>Faire l'appel</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <span class="material-symbols-outlined text-[40px] mb-2">calendar_month</span>
                        <p class="text-sm font-bold text-gray-600">Aucun créneau d'emploi du temps programmé.</p>
                    </div>
                @endif
            </div>

            {{-- Contenu Onglet 2 : Relevé des Séances & Heures dispensées --}}
            <div id="tab-content-sessions" class="hidden">
                @if ($trainerSessions->count() > 0)
                    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                        <table class="dash-table w-full">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Date Séance</th>
                                    <th style="width: 45%;">Séminaire / Thème</th>
                                    <th style="width: 30%; text-align: right;">Heures Dispensées</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainerSessions as $session)
                                    <tr>
                                        <td>
                                            <div class="font-bold text-slate-800 text-xs">
                                                {{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="text-xs font-bold text-slate-800 line-clamp-1">
                                                {{ $session->seminar->title ?? 'Séminaire' }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium">
                                                {{ $session->topic->name ?? 'Général / Atelier' }}</div>
                                        </td>
                                        <td class="text-right whitespace-nowrap">
                                            <span class="font-black text-slate-900 text-xs">{{ $session->hours_taught }}
                                                h</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <span class="material-symbols-outlined text-[40px] mb-2">history_edu</span>
                        <p class="text-sm font-bold text-gray-600">Aucune séance enregistrée pour le moment.</p>
                        <p class="text-xs text-gray-400 mt-1">Enregistrez un émargement pour comptabiliser vos heures.</p>
                    </div>
                @endif
            </div>

            {{-- Contenu Onglet 3 : Par Thème --}}
            <div id="tab-content-topics" class="hidden">
                @if ($topicsWithCounts->count() > 0)
                    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Thème / Séminaire</th>
                                    <th>Candidats</th>
                                    <th>Statut</th>
                                    <th>Export</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topicsWithCounts as $topic)
                                    <tr>
                                        <td>
                                            <div style="font-size:13px; font-weight:700; color:var(--text-main);">
                                                {{ $topic->name }}</div>
                                            <div style="font-size:11px; color:var(--text-muted); margin-top:1px;">
                                                {{ $topic->seminar->title ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span
                                                style="font-size:18px; font-weight:800; color:var(--text-main);">{{ $topic->registrations_count }}</span>
                                        </td>
                                        <td>
                                            @if ($topic->registrations_count > 0)
                                                <span class="pill pill-green">Actif</span>
                                            @else
                                                <span class="pill pill-amber">Vide</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($topic->registrations_count > 0)
                                                <a href="{{ route('portal.trainer.topics.export', $topic->id) }}"
                                                    class="flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-brand-primary transition-colors"
                                                    title="Télécharger CSV">
                                                    <span
                                                        class="material-symbols-outlined text-[18px]">file_download</span>
                                                    CSV
                                                </a>
                                            @else
                                                <span
                                                    class="material-symbols-outlined text-gray-300 text-[18px]">block</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <span class="material-symbols-outlined text-[40px] mb-2">topic</span>
                        <p class="text-sm">Aucun thème disponible dans vos séminaires.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- COLONNE DROITE : Nouveau Message --}}
        <div class="dash-card">
            <div class="mb-5">
                <p class="section-label">Communication</p>
                <h2 class="section-title">Envoyer un Message</h2>
            </div>

            @if (session('success'))
                <div
                    class="mb-4 flex items-center gap-2 px-3 py-2.5 bg-green-50 text-green-700 rounded-lg text-sm border border-green-100">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('portal.trainer.messages.send') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-3 flex-grow">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Filière / Classe
                        concernée</label>
                    <select id="seminar_select" name="seminar_id"
                        class="w-full text-sm py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-brand-primary/30 transition-all"
                        required>
                        <option value="">-- Sélectionnez une filière --</option>
                        @foreach ($trainer->seminars as $seminar)
                            <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="topics_container" class="hidden">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Matières ciblées
                        <span class="font-normal normal-case text-gray-400">(optionnel)</span></label>
                    <div id="topics_list"
                        class="flex flex-col gap-1.5 max-h-28 overflow-y-auto p-2 bg-gray-50 rounded-lg border border-gray-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Sujet</label>
                    <input type="text" name="subject" required
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-brand-primary/30 text-sm transition-all"
                        placeholder="Objet du message">
                </div>

                <div class="flex-grow">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Message</label>
                    <textarea name="body" required rows="4"
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-brand-primary/30 text-sm resize-none transition-all"
                        placeholder="Tapez votre message..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Fichier joint <span
                            class="font-normal normal-case text-gray-400">(optionnel)</span></label>
                    <input type="file" name="attachment"
                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-brand-primary hover:file:bg-blue-100">
                </div>

                <div class="flex gap-3 mt-1">
                    <button type="submit" class="btn-brand flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span> Envoyer
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- =========================================
     GRILLE BAS : Séminaires détail + Historique messages
     ========================================= --}}
    <div class="bottom-grid">

        {{-- Historique des messages --}}
        <div class="dash-card">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <p class="section-label">Activité</p>
                    <h2 class="section-title">Historique des Messages</h2>
                </div>
                <span
                    style="font-size:12px; color:var(--text-muted); font-weight:600; background:#f3f4f6; padding:4px 10px; border-radius:8px;">
                    {{ $messages->count() }} au total
                </span>
            </div>

            <div class="flex flex-col gap-3 overflow-y-auto" style="max-height: 420px;">
                @forelse($messages as $msg)
                    <div class="msg-card">
                        <div class="flex justify-between items-start mb-2">
                            <h4 style="font-size:13px; font-weight:700; color:var(--text-main);">{{ $msg->subject }}</h4>
                            <span style="font-size:10px; color:var(--text-muted); white-space:nowrap; margin-left:8px;">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            <span class="pill pill-blue">{{ $msg->seminar->title ?? 'N/A' }}</span>
                        </div>
                        <p style="font-size:12px; color:#6b7280; line-height:1.5;">{{ Str::limit($msg->body, 120) }}</p>
                        @if ($msg->attachment_path)
                            <div class="mt-2 pt-2" style="border-top: 1px solid var(--border);">
                                <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-primary hover:underline">
                                    <span class="material-symbols-outlined text-[15px]">attachment</span>
                                    Document joint
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <span class="material-symbols-outlined text-[44px] mb-3">speaker_notes_off</span>
                        <p class="text-sm">Aucun message envoyé pour l'instant.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Filières assignées + infos --}}
        <div class="dash-card">
            <div class="mb-5">
                <p class="section-label">Programme</p>
                <h2 class="section-title">Filières Assignées</h2>
            </div>

            <div class="flex flex-col gap-3 mb-5">
                @forelse($trainer->seminars as $seminar)
                    <div style="border:1px solid var(--border); border-radius:12px; padding:14px; transition: all .2s;"
                        onmouseover="this.style.borderColor='#4B6A95'"
                        onmouseout="this.style.borderColor='var(--border)'">
                        <div class="flex justify-between items-start">
                            <div>
                                <p style="font-size:13px; font-weight:700; color:var(--text-main);">{{ $seminar->title }}
                                </p>
                                @if ($seminar->cohort)
                                    <p style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                                        <span class="material-symbols-outlined text-[13px]"
                                            style="vertical-align:middle;">calendar_month</span>
                                        {{ $seminar->cohort->title ?? '' }}
                                    </p>
                                @endif
                            </div>
                            <span
                                class="pill pill-blue">{{ $seminar->registrations()->where('status', 'approved')->count() }}
                                étudiants</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                        <span class="material-symbols-outlined text-[36px] mb-2">school</span>
                        <p class="text-sm">Aucune filière assignée.</p>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('portal.trainer.attendances') }}"
                class="btn-brand flex items-center justify-center gap-2 no-underline">
                <span class="material-symbols-outlined text-[18px]">checklist</span>
                Gestion des Présences
            </a>
        </div>

    </div>

    @push('scripts')
        <script>
            function switchTrainerTab(tab) {
                const btnCandidates = document.getElementById('tab-btn-candidates');
                const btnTimetable = document.getElementById('tab-btn-timetable');
                const btnSessions = document.getElementById('tab-btn-sessions');
                const btnTopics = document.getElementById('tab-btn-topics');

                const contentCandidates = document.getElementById('tab-content-candidates');
                const contentTimetable = document.getElementById('tab-content-timetable');
                const contentSessions = document.getElementById('tab-content-sessions');
                const contentTopics = document.getElementById('tab-content-topics');

                // Reset all tabs
                [btnCandidates, btnTimetable, btnSessions, btnTopics].forEach(btn => btn && btn.classList.remove('active'));
                [contentCandidates, contentTimetable, contentSessions, contentTopics].forEach(c => c && c.classList.add(
                    'hidden'));

                if (tab === 'candidates') {
                    if (btnCandidates) btnCandidates.classList.add('active');
                    if (contentCandidates) contentCandidates.classList.remove('hidden');
                } else if (tab === 'timetable') {
                    if (btnTimetable) btnTimetable.classList.add('active');
                    if (contentTimetable) contentTimetable.classList.remove('hidden');
                } else if (tab === 'sessions') {
                    if (btnSessions) btnSessions.classList.add('active');
                    if (contentSessions) contentSessions.classList.remove('hidden');
                } else if (tab === 'topics') {
                    if (btnTopics) btnTopics.classList.add('active');
                    if (contentTopics) contentTopics.classList.remove('hidden');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const topicsBySeminar = @json($topicsBySeminar);
                const seminarSelect = document.getElementById('seminar_select');
                const topicsContainer = document.getElementById('topics_container');
                const topicsList = document.getElementById('topics_list');

                if (seminarSelect) {
                    seminarSelect.addEventListener('change', function() {
                        const seminarId = this.value;
                        topicsList.innerHTML = '';
                        if (seminarId && topicsBySeminar[seminarId] && topicsBySeminar[seminarId].length > 0) {
                            topicsContainer.classList.remove('hidden');
                            topicsBySeminar[seminarId].forEach(topic => {
                                const label = document.createElement('label');
                                label.className = 'flex items-center gap-2 cursor-pointer';
                                label.innerHTML = `
                            <input type="checkbox" name="topic_ids[]" value="${topic.id}"
                                   class="text-brand-primary rounded border-gray-300 focus:ring-brand-primary">
                            <span class="text-sm text-gray-700">${topic.name}</span>
                        `;
                                topicsList.appendChild(label);
                            });
                        } else {
                            topicsContainer.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
