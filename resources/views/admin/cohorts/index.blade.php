@extends('layouts.admin')

@section('header_title', 'Éditions & Budgets')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Cycles de Formation</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                        Gérez les éditions du programme. Les cycles terminés sont consultables dans 
                        <a href="{{ route('admin.archives') }}" class="text-[#4B6A95] font-bold hover:underline inline-flex items-center gap-1">
                            l'archivage <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </p>
                </div>
            </div>
            @if(auth()->user()->role->name !== 'Agent JICA')
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cohorts.create') }}" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-full flex items-center gap-2 transition shadow-sm">
                    <span class="material-symbols-outlined text-[18px] leading-none">add_circle</span>
                    Nouvelle Édition
                </a>
            </div>
            @endif
        </header>

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
                width: 32px;
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
            }
            .folder-action-btn:hover { background: #e2e8f0; color: #0f172a; }
            .btn-budget {
                background: #eff6ff;
                color: #2563eb;
                padding: 0 12px;
                width: auto;
                font-weight: 700;
                font-size: 12px;
            }
            .btn-budget:hover { background: #dbeafe; color: #1d4ed8; }
            .btn-archive:hover { background: #fef3c7; color: #d97706; }
            .btn-delete:hover { background: #fee2e2; color: #dc2626; }
            
            .badge-status {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 4px 10px;
                border-radius: 10px;
            }
            .badge-active { background: #dcfce7; color: #166534; }
            .badge-completed { background: #f1f5f9; color: #475569; }
            .badge-draft { background: #fef3c7; color: #b45309; }
        </style>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-10 mt-6">
            @forelse($cohorts as $cohort)
            <div class="folder-wrapper" x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'">
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
                        
                        @if($cohort->status === 'active')
                            <span class="badge-status badge-active">Actif</span>
                        @elseif($cohort->status === 'completed')
                            <span class="badge-status badge-completed">Terminé</span>
                        @else
                            <span class="badge-status badge-draft">Brouillon</span>
                        @endif
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
                            Détails Budget
                        </a>
                        @if(auth()->user()->role->name !== 'Agent JICA')
                        <div class="folder-actions relative">
                            <button @click="open = !open" @click.outside="open = false" class="folder-action-btn flex items-center justify-center p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-40 bg-white border border-slate-100 shadow-lg rounded-md py-1 z-50">
                                <a href="{{ route('admin.cohorts.edit', $cohort) }}" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    Modifier
                                </a>
                                
                                <form action="{{ route('admin.cohorts.toggle', $cohort) }}" method="POST" class="m-0 border-t border-slate-100">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-amber-600 hover:bg-amber-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">{{ $cohort->status === 'active' ? 'archive' : 'unarchive' }}</span>
                                        {{ $cohort->status === 'active' ? 'Archiver' : 'Activer' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.cohorts.destroy', $cohort) }}" method="POST" class="m-0 border-t border-slate-100" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette édition ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="text-xs font-semibold text-gray-400">Lecture seule</div>
                        @endif
                    </div>

                </div>
            </div>
            @empty
            <div class="lg:col-span-3 py-20 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">event_busy</span>
                <p class="font-bold text-gray-500">Aucune édition n'a été créée pour le moment.</p>
                @if(auth()->user()->role->name !== 'Agent JICA')
                <a href="{{ route('admin.cohorts.create') }}" class="inline-block mt-4 text-brand-primary font-bold hover:underline">Initialiser le premier cycle</a>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
