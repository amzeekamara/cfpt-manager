@extends('layouts.admin')

@section('header_title', 'Archivage et Historique')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Archivage & Historique</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Consultez l'historique des séminaires passés et l'activité du système.</p>
                </div>
            </div>
            <button class="bg-white border border-slate-200 text-slate-700 py-2.5 px-4 rounded-full shadow-2xs hover:bg-slate-50 transition font-bold text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">system_update_alt</span>
                Sauvegarde complète
            </button>
        </div>

        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#4B6A95] shrink-0">
                <span class="material-symbols-outlined text-[28px]">database</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="font-bold text-slate-900 mb-1">Base de données conforme</h3>
                <p class="text-xs text-slate-500">Dernière sauvegarde automatique effectuée aujourd'hui à {{ now()->format('H:i') }}.</p>
            </div>
            <div class="flex gap-8">
                <div class="text-right">
                    <div class="text-2xl font-black text-slate-900">{{ $archivedCohorts->count() }}</div>
                    <div class="text-xs font-semibold text-slate-400">Cycles archivés</div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-black text-slate-900">{{ $pastSeminars->count() }}</div>
                    <div class="text-xs font-semibold text-slate-400">Séminaires terminés</div>
                </div>
            </div>
        </div>

    <!-- Archived Cohorts Section -->
    @if($archivedCohorts->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-amber-600">history_edu</span>
            <h2 class="text-xl font-headline font-bold text-on-surface tracking-tight">Cycles de Formation Historiques</h2>
        </div>
        
        <style>
            .folder-wrapper {
                position: relative;
                filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.05)) drop-shadow(0 5px 10px rgba(0, 0, 0, 0.02));
                margin-top: 30px;
                transition: transform 0.2s ease, filter 0.2s ease;
            }
            .folder-wrapper:hover {
                transform: translateY(-5px);
                filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.08)) drop-shadow(0 5px 12px rgba(0, 0, 0, 0.03));
            }
            .folder {
                position: relative;
                width: 100%;
                min-height: 220px;
                background: #ffffff;
                border-radius: 0 16px 16px 16px;
                background: linear-gradient(135deg, #ffffff 0%, #f9f9fb 100%);
                box-shadow: inset 1px 2px 4px rgba(255, 255, 255, 1),
                            inset -1px -1px 3px rgba(0, 0, 0, 0.01);
                padding: 25px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border: 1px solid #f0f0f0;
                border-top: none;
            }
            .folder-tab {
                position: absolute;
                bottom: 100%;
                left: -1px;
                width: 120px;
                height: 32px;
                background: #ffffff;
                border-radius: 12px 12px 0 0;
                box-shadow: inset 1px 2px 3px rgba(255, 255, 255, 1);
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #f0f0f0;
                border-bottom: none;
                z-index: 1;
            }
            .folder-tab::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: -12px;
                width: 12px;
                height: 12px;
                background: radial-gradient(circle at 100% 0, transparent 12px, #ffffff 12.5px);
            }
            .folder-tab-border-fix {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                right: -12px;
                height: 2px;
                background: #ffffff;
                z-index: 2;
            }
            .folder-tab-border-right {
                content: '';
                position: absolute;
                bottom: 12px;
                right: -1px;
                width: 1px;
                height: 20px;
                background: #ffffff;
                z-index: 2;
            }
            .folder-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                color: #555555;
                margin-bottom: 20px;
                margin-top: 5px;
                position: relative;
                z-index: 5;
            }
            .folder-title {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 22px;
                font-weight: 800;
                color: #1a1a2e;
            }
            .folder-title svg {
                width: 22px;
                height: 22px;
                fill: none;
                stroke: #4B6A95;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
            .folder-details {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .folder-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .storage-text {
                color: #888;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .folder-value {
                font-size: 14px;
                font-weight: 800;
                color: #2d3748;
            }
            .folder-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 1px solid #f0f0f5;
            }
            .folder-actions {
                display: flex;
                gap: 8px;
            }
            .folder-action-btn {
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f1f5f9;
                color: #64748b;
                transition: all 0.2s;
                border: none;
                cursor: pointer;
                padding: 0 12px;
                font-size: 12px;
                font-weight: 700;
            }
            .folder-action-btn:hover { background: #e2e8f0; color: #0f172a; }
            .btn-budget {
                background: #eff6ff;
                color: #2563eb;
                flex: 1;
            }
            .btn-budget:hover { background: #dbeafe; color: #1d4ed8; }
            .btn-reactivate {
                background: #dcfce7;
                color: #166534;
            }
            .btn-reactivate:hover { background: #bbf7d0; color: #15803d; }
            
            .badge-status {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 4px 10px;
                border-radius: 10px;
            }
            .badge-archived { background: #f1f5f9; color: #475569; }
        </style>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-10 mt-6">
            @foreach($archivedCohorts as $cohort)
            <div class="folder-wrapper">
                <div class="folder">
                    
                    <div class="folder-tab">
                        <span class="text-xs font-black text-gray-600">{{ $cohort->start_date->format('Y') }}</span>
                        <div class="folder-tab-border-fix"></div>
                        <div class="folder-tab-border-right"></div>
                    </div>

                    <div class="folder-header">
                        <div class="folder-title">
                            <svg viewBox="0 0 24 24">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <span>{{ $cohort->title }}</span>
                        </div>
                        
                        <span class="badge-status badge-archived">Archivé</span>
                    </div>

                    <div class="folder-details">
                        <div class="folder-row">
                            <span class="storage-text">Budget Alloué</span>
                            <span class="folder-value text-primary">{{ number_format($cohort->total_budget, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="folder-row">
                            <span class="storage-text">Séminaires</span>
                            <span class="folder-value">{{ $cohort->seminars_count }} sessions</span>
                        </div>
                        <div class="folder-row">
                            <span class="storage-text">Période</span>
                            <span class="folder-value text-[13px] bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $cohort->start_date->format('d M') }} - {{ $cohort->end_date->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="folder-footer">
                        <a href="{{ route('admin.finances', ['cohort_id' => $cohort->id]) }}" class="folder-action-btn btn-budget">
                            Détails
                        </a>
                        
                        <form action="{{ route('admin.cohorts.toggle', $cohort) }}" method="POST" class="inline m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="folder-action-btn btn-reactivate">
                                Réactiver
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Archives Data Table -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-md overflow-hidden shadow-sm">
        <div class="p-4 border-b border-outline-variant flex justify-between bg-surface-container-low/30">
            <h2 class="font-bold text-lg text-on-surface">Séminaires Clôturés</h2>
            <div class="flex gap-2">
                <select class="bg-surface-container-lowest border border-outline-variant rounded-md text-sm px-3 py-1.5 focus:ring-primary focus:border-primary">
                    <option>Toutes les années</option>
                    @foreach($pastSeminars->pluck('end_date')->map->format('Y')->unique() as $year)
                        <option>Année: {{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-lowest">
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Référence</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Titre du Séminaire</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Date Fin</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant text-right">Participants</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/60">
                @forelse($pastSeminars as $seminar)
                <tr class="hover:bg-surface-container-low/50">
                    <td class="py-4 px-6 text-sm font-mono text-slate-500">SEM-{{ $seminar->id }}-{{ $seminar->end_date->format('y') }}</td>
                    <td class="py-4 px-6 font-medium text-on-surface text-sm">{{ $seminar->title }}</td>
                    <td class="py-4 px-6 text-sm text-on-surface-variant">{{ $seminar->end_date->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-sm text-right font-medium">
                        {{ $seminar->registrations()->where('status', 'approved')->count() }}/{{ $seminar->max_participants }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <button class="text-primary hover:bg-primary/10 p-1.5 rounded transition-colors text-sm font-medium">Consulter</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-20">history</span>
                        <p>Aucun séminaire archive trouvé.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
