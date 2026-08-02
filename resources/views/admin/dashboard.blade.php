@extends('layouts.admin')

@section('header_title', 'Tableau de bord')

@section('content')
<style data-purpose="custom-utilities">
    .bg-dashboard-dark { background-color: #001c37; }
    .text-brand-primary { color: #00355f; }
    .bg-brand-primary { background-color: #00355f; }
    .bg-brand-light { background-color: #d2e4ff; }
    .card-shadow { box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05); }
</style>

<div class="p-4 md:p-8">
    <div class="max-w-[1400px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- BEGIN: Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <div class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </div>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Bonjour, {{ auth()->user()->name ?? 'Administrateur' }}</h1>
                    <p class="text-gray-500 text-sm mt-1">Gérez et suivez l'activité de la plateforme PFPT CFPT.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.editions.index') }}" class="p-3 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors" title="Gérer les éditions">
                    <span class="material-symbols-outlined text-[22px] leading-none">tune</span>
                </a>
                <a href="{{ route('admin.seminars') }}" class="px-6 py-3 bg-brand-primary hover:opacity-90 text-white font-medium rounded-full flex items-center gap-2 transition shadow-lg shadow-brand-primary/30">
                    <span class="material-symbols-outlined text-[20px] leading-none">add</span>
                    Créer Séminaire
                </a>
            </div>
        </header>
        <!-- END: Header -->

        <!-- BEGIN: MetricCards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Card 1: Utilisateurs -->
            <div class="bg-white border border-gray-100 rounded-3xl p-6 card-shadow relative overflow-hidden h-48 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-on-surface-variant">Utilisateurs</span>
                    <span class="material-symbols-outlined text-blue-500" style="font-variation-settings: 'FILL' 1;">group</span>
                </div>
                <div>
                    <div class="text-3xl font-headline font-bold text-on-surface mb-2">{{ number_format($stats['users_count']) }}</div>
                    <div class="text-xs text-emerald-500 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span> Acteurs plateforme
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 text-surface-container opacity-50 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">groups</span>
                </div>
            </div>

            <!-- Card 2: Séminaires -->
            <div class="bg-white border border-gray-100 rounded-3xl p-6 card-shadow h-48 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-on-surface-variant">Séminaires Actifs</span>
                    <span class="material-symbols-outlined text-amber-500" style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
                <div>
                    <div class="text-3xl font-headline font-bold text-on-surface mb-2">{{ $stats['active_seminars_count'] }}</div>
                    <div class="text-xs text-amber-500 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[14px]">event_available</span> Pour l'édition actuelle
                    </div>
                </div>
                <div class="flex items-end gap-1 h-12 mt-4">
                    <div class="flex-1 bg-amber-100 rounded-t-sm h-1/4"></div>
                    <div class="flex-1 bg-amber-200 rounded-t-sm h-2/4"></div>
                    <div class="flex-1 bg-amber-300 rounded-t-sm h-1/3"></div>
                    <div class="flex-1 bg-amber-400 rounded-t-sm h-3/4"></div>
                    <div class="flex-1 bg-amber-500 rounded-t-sm h-2/3"></div>
                    <div class="flex-1 bg-amber-600 rounded-t-sm h-full"></div>
                </div>
            </div>

            <!-- Card 3: En attente -->
            <div class="bg-white border border-gray-100 rounded-3xl p-6 card-shadow h-48 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-on-surface-variant">Inscriptions en attente</span>
                    <span class="material-symbols-outlined text-rose-400" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                </div>
                <div>
                    <div class="text-3xl font-headline font-bold text-on-surface mb-2">{{ $stats['pending_enrollments_count'] }}</div>
                    <div class="text-xs text-rose-500 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[14px]">info</span> Demandes à traiter
                    </div>
                </div>
                <!-- Mini Chart SVG -->
                <div class="h-12 mt-4 opacity-50">
                    <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 100 40">
                        <path d="M0,35 Q10,30 20,32 T40,25 T60,20 T80,10 T100,5" fill="none" stroke="#f43f5e" stroke-width="2"></path>
                        <circle cx="100" cy="5" fill="#f43f5e" r="3"></circle>
                    </svg>
                </div>
            </div>

            <!-- Card 4: Sélectionnés -->
            <div class="bg-white border border-gray-100 rounded-3xl p-6 card-shadow h-48 relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <span class="text-sm font-medium text-on-surface-variant">Candidats Sélectionnés</span>
                    <span class="material-symbols-outlined text-emerald-500" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <div class="relative z-10">
                    <div class="text-3xl font-headline font-bold text-on-surface mb-2">{{ $stats['approved_enrollments_count'] }}</div>
                    <div class="text-xs text-on-surface-variant mb-4">Total des dossiers validés</div>
                </div>
                
                <a href="{{ route('admin.candidates') }}" class="w-full py-2.5 bg-on-surface text-white text-xs font-semibold rounded-full absolute bottom-4 left-0 right-0 mx-6 hover:bg-surface-variant hover:text-on-surface transition-colors flex justify-center items-center gap-2 z-10">
                    Voir les candidats <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-2xl z-0"></div>
            </div>
        </div>
        <!-- END: MetricCards -->

        <!-- BEGIN: Filters Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-800 flex items-center">Filtres actifs <span class="bg-on-surface text-white px-2 py-0.5 rounded-full text-[10px] ml-1.5 shadow-sm">1</span></span>
                
                <form action="{{ route('admin.dashboard') }}" method="GET" id="editionFilterForm" class="m-0">
                    <select name="edition_id" onchange="document.getElementById('editionFilterForm').submit()" 
                            class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 appearance-none min-w-[140px] focus:border-brand-primary cursor-pointer">
                        @foreach($editions as $edition)
                            <option value="{{ $edition->id }}" {{ (isset($selectedEdition) && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                                {{ $edition->title }} {{ $edition->status === 'active' ? '(Actuelle)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <select class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 appearance-none min-w-[140px] focus:border-brand-primary cursor-pointer">
                    <option>Tous les statuts</option>
                    <option>En cours</option>
                    <option>Terminé</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex border border-gray-200 rounded-full overflow-hidden bg-white shadow-sm">
                    @if(isset($selectedEdition))
                    <button class="px-4 py-2 text-sm text-gray-600 border-r border-gray-200 flex items-center gap-2 hover:bg-gray-50 transition-colors">
                        {{ $selectedEdition->start_date->format('M Y') }} 
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    </button>
                    <button class="px-4 py-2 text-sm text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors">
                        {{ $selectedEdition->end_date->format('M Y') }}
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    </button>
                    @else
                    <button class="px-4 py-2 text-sm text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors">
                        Sélectionner dates
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    </button>
                    @endif
                </div>
                
                <div class="relative shadow-sm rounded-full">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 flex items-center">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" placeholder="Rechercher ID #" class="pl-10 pr-4 py-2 border border-gray-200 rounded-full text-sm outline-none w-48 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary bg-white transition-all">
                </div>
            </div>
        </div>
        <!-- END: Filters Bar -->

        <!-- BEGIN: MainContentArea -->
        <div class="bg-dashboard-dark rounded-3xl p-6 flex flex-col lg:flex-row gap-6 relative min-h-[600px]">
            
            <!-- Left Panel: Inscriptions Récentes -->
            <div class="w-full lg:w-1/3 mt-8 text-white overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent pointer-events-none rounded-2xl"></div>
                
                <div class="flex items-center justify-between mb-8 px-2 relative z-10">
                    <h3 class="font-semibold text-lg font-headline flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-fixed">history_edu</span>
                        Inscriptions Récentes
                    </h3>
                    <a href="{{ route('admin.candidates') }}" class="text-xs font-bold text-primary-fixed hover:text-white transition-colors">Voir tout</a>
                </div>
                
                <div class="space-y-2 relative z-10">
                    @forelse($recentEnrollments as $enrollment)
                    @php
                        $statusStyles = [
                            'approved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                            'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                            'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                        ];
                        $labels = [
                            'approved' => 'Confirmé',
                            'pending' => 'En attente',
                            'rejected' => 'Refusé',
                        ];
                        $isActive = $enrollment->status === 'pending';
                    @endphp
                    
                    <div class="flex flex-col gap-3 p-4 rounded-2xl transition-all cursor-pointer group {{ $isActive ? 'bg-brand-primary shadow-lg shadow-black/20 border border-white/10' : 'hover:bg-white/5 border border-transparent' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $isActive ? 'bg-white text-brand-primary border-2 border-white/20' : 'bg-surface-tint text-white' }}">
                                    {{ substr($enrollment->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white line-clamp-1">{{ $enrollment->user->name }}</div>
                                    <div class="text-[10px] {{ $isActive ? 'text-blue-200' : 'text-gray-400' }}">{{ $enrollment->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            <span class="text-[10px] px-3 py-1 rounded-full border {{ $statusStyles[$enrollment->status] ?? $statusStyles['pending'] }}">
                                {{ $labels[$enrollment->status] ?? 'Inconnu' }}
                            </span>
                        </div>
                        <div class="text-xs font-medium {{ $isActive ? 'text-white' : 'text-gray-300' }} line-clamp-1 border-t {{ $isActive ? 'border-white/10' : 'border-white/5' }} pt-2">
                            {{ $enrollment->seminar->title }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center p-6 bg-white/5 rounded-2xl border border-white/5">
                        <span class="material-symbols-outlined text-4xl text-gray-500 mb-2">inbox</span>
                        <p class="text-sm text-gray-400">Aucune inscription récente.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Séminaires & Détails -->
            <div class="w-full lg:w-2/3 relative mt-8">
                <!-- Floating Tabs -->
                <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 flex bg-white rounded-full p-1 shadow-lg border border-gray-100 z-20">
                    <button class="px-6 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Aperçu global</button>
                    <div class="px-6 py-2 rounded-full text-sm font-medium bg-brand-primary text-white shadow-md flex items-center gap-2">
                        Séminaires <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $upcomingSeminars->count() }}</span>
                    </div>
                </div>
                
                <div class="h-full bg-brand-primary rounded-3xl p-8 border border-white/10 flex flex-col relative overflow-hidden shadow-2xl">
                    
                    @if(isset($selectedEdition))
                    <!-- Edition Focus Info -->
                    <div class="flex justify-between items-start mb-10 relative z-10">
                        <div class="space-y-4">
                            <div class="text-gray-400 text-xs uppercase tracking-wider font-bold">Édition Actuelle</div>
                            <div class="flex items-baseline gap-4">
                                <h4 class="text-white text-3xl font-headline font-bold">{{ $selectedEdition->title }}</h4>
                                @if($selectedEdition->status === 'active')
                                    <span class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[10px] px-3 py-1 rounded-full uppercase tracking-wider font-bold flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        En cours
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-gray-400 text-xs mb-2 uppercase tracking-wider font-bold">Budget Alloué</div>
                            <div class="flex items-center gap-2 justify-end">
                                <span class="text-white text-2xl font-semibold">{{ number_format($selectedEdition->total_budget, 0, ',', ' ') }} <span class="text-sm text-gray-400">FCFA</span></span>
                                <div class="w-8 h-8 bg-brand-primary rounded-md flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-[18px]">account_balance_wallet</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <h4 class="text-white text-sm font-semibold mb-4 relative z-10 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-light text-[18px]">event</span>
                        Prochains Séminaires
                    </h4>
                    
                    <!-- Seminars Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 relative z-10">
                        @forelse($upcomingSeminars->take(3) as $seminar)
                            @php
                                $enrolled = $seminar->enrollments()->where('status', 'approved')->count();
                                $isFull = $enrolled >= $seminar->max_participants;
                            @endphp
                            <div class="bg-white/5 p-5 rounded-3xl border border-white/10 flex flex-col justify-between hover:bg-white/10 transition-colors cursor-pointer group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="bg-white/10 text-white rounded-lg px-3 py-2 text-center min-w-[3.5rem] group-hover:bg-brand-primary transition-colors">
                                        <div class="text-[10px] font-bold uppercase tracking-wider">{{ $seminar->start_date->format('M') }}</div>
                                        <div class="text-xl font-headline font-black">{{ $seminar->start_date->format('d') }}</div>
                                    </div>
                                    <span class="material-symbols-outlined text-gray-500 group-hover:text-white transition-colors text-[18px]">open_in_new</span>
                                </div>
                                <div>
                                    <div class="text-white font-semibold text-sm line-clamp-2 mb-2" title="{{ $seminar->title }}">{{ $seminar->title }}</div>
                                    <div class="flex items-center justify-between mt-auto">
                                        <div class="text-gray-400 text-[11px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            {{ $seminar->start_date->format('H:i') }}
                                        </div>
                                        <div class="text-[10px] font-bold {{ $isFull ? 'text-rose-400' : 'text-emerald-400' }}">
                                            {{ $enrolled }}/{{ $seminar->max_participants }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 border-2 border-dashed border-gray-600 rounded-3xl p-6 flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-gray-500 text-3xl">event_busy</span>
                                <span class="text-gray-400 text-sm">Aucun séminaire prévu</span>
                            </div>
                        @endforelse
                        
                        @if($upcomingSeminars->count() > 0)
                        <a href="{{ route('admin.seminars') }}" class="border-2 border-dashed border-white/20 rounded-3xl flex flex-col items-center justify-center gap-3 hover:border-white/40 transition-colors cursor-pointer group min-h-[160px] bg-white/5">
                            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white group-hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">calendar_month</span>
                            </div>
                            <span class="text-white text-xs font-semibold">Calendrier complet</span>
                        </a>
                        @endif
                    </div>
                    
                    <!-- Traçabilité Staff Summary Bar -->
                    <div class="mt-auto border-t border-white/10 pt-6 relative z-10 flex flex-col md:flex-row justify-between items-end md:items-center gap-6">
                        <div class="w-full md:w-auto">
                            <h4 class="text-gray-400 text-[10px] uppercase font-bold tracking-wider mb-3">Traçabilité Staff</h4>
                            <div class="flex flex-col gap-2">
                                @forelse($recentLogs->take(2) as $log)
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-full bg-brand-primary text-white flex items-center justify-center text-[9px] font-bold">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-xs text-white font-medium">
                                                <span class="font-bold text-primary-fixed">{{ $log->action }}</span> : <span class="text-gray-300 italic">"{{ Str::limit($log->description, 40) }}"</span>
                                            </div>
                                        </div>
                                        <span class="text-[9px] text-gray-500 ml-auto whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-500 italic">Aucune activité récente.</div>
                                @endforelse
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                            <button class="p-3 bg-white/10 rounded-full text-white hover:bg-white/20 transition-colors" title="Paramètres">
                                <span class="material-symbols-outlined text-[20px]">settings</span>
                            </button>
                            <button class="p-3 bg-white/10 rounded-full text-white hover:bg-white/20 transition-colors" title="Télécharger rapport">
                                <span class="material-symbols-outlined text-[20px]">download</span>
                            </button>
                            <a href="{{ route('admin.finances') }}" class="px-6 py-2.5 bg-white text-dashboard-dark font-bold rounded-full hover:bg-gray-100 transition-colors text-sm shadow-lg whitespace-nowrap">
                                Rapport Finances
                            </a>
                        </div>
                    </div>
                    
                    <!-- Background Decor element -->
                    <div class="absolute -right-20 -top-20 w-96 h-96 bg-brand-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                </div>
            </div>
        </div>
        <!-- END: MainContentArea -->
        
    </div>
</div>
@endsection
