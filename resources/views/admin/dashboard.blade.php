@extends('layouts.admin')

@section('header_title', 'Tableau de bord')

@section('content')
<div class="p-8">
    <!-- Welcome section & Filter -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface mb-2 tracking-tight">Bonjour, {{ auth()->user()->name ?? 'Administrateur' }} 👋</h1>
            <p class="text-on-surface-variant font-body">Voici un aperçu de l'activité sur la plateforme PFPT CFPT aujourd'hui.</p>
        </div>
        
        <div class="bg-surface-container-low p-2 rounded-2xl border border-outline-variant flex items-center gap-3">
            <span class="material-symbols-outlined text-primary ml-2">filter_alt</span>
            <form action="{{ route('admin.dashboard') }}" method="GET" id="editionFilterForm" class="flex items-center">
                <select name="edition_id" onchange="document.getElementById('editionFilterForm').submit()" 
                    class="bg-transparent border-none focus:ring-0 text-sm font-bold text-on-surface pr-8 cursor-pointer">
                    @foreach($editions as $edition)
                        <option value="{{ $edition->id }}" {{ (isset($selectedEdition) && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                            {{ $edition->title }} {{ $edition->status === 'active' ? '(Actuelle)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant hover:shadow-md transition-shadow relative overflow-hidden group hover:-translate-y-1 duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl text-primary" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                    <span class="material-symbols-outlined pb-0.5">group</span>
                </div>
                <h3 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Utilisateurs</h3>
            </div>
            <div>
                <div class="text-3xl font-headline font-bold text-on-surface mb-1">{{ number_format($stats['users_count']) }}</div>
                <div class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">trending_up</span>
                    <span>Platform Acteurs</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant hover:shadow-md transition-shadow relative overflow-hidden group hover:-translate-y-1 duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl text-tertiary" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container">
                    <span class="material-symbols-outlined pb-0.5">school</span>
                </div>
                <h3 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Séminaires</h3>
            </div>
            <div>
                <div class="text-3xl font-headline font-bold text-on-surface mb-1">{{ $stats['active_seminars_count'] }}</div>
                <div class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">event_available</span>
                    <span>Actifs pour l'édition</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant hover:shadow-md transition-shadow relative overflow-hidden group hover:-translate-y-1 duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl text-secondary" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined pb-0.5">pending_actions</span>
                </div>
                <h3 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">En attente</h3>
            </div>
            <div>
                <div class="text-3xl font-headline font-bold text-on-surface mb-1">{{ $stats['pending_enrollments_count'] }}</div>
                <div class="text-sm font-medium text-amber-600 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">info</span>
                    <span>Demandes à traiter</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-primary/5 rounded-2xl p-6 shadow-sm border border-primary/20 hover:shadow-md transition-shadow relative overflow-hidden group hover:-translate-y-1 duration-300">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-primary/10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <span class="material-symbols-outlined pb-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider">Sélectionnés</h3>
            </div>
            <div class="relative z-10">
                <div class="text-3xl font-headline font-bold text-emerald-700 mb-1">
                    {{ $stats['approved_enrollments_count'] }}
                </div>
                <div class="text-xs font-medium text-emerald-600 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>Dossiers validés</span>
                </div>
            </div>
        </div>
    </div>

    @if(isset($selectedEdition))
    <!-- Active Edition Notice -->
    <div class="mb-8 bg-gradient-to-r from-[#0f4c81] to-[#00355f] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/3 opacity-10">
            <span class="material-symbols-outlined text-[180px] -rotate-12 translate-x-1/4">database</span>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shadow-inner relative">
                    <span class="material-symbols-outlined text-3xl">event_note</span>
                    @if($selectedEdition->status === 'active')
                    <span class="absolute -top-2 -right-2 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 border-2 border-white"></span>
                    </span>
                    @endif
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-blue-200">
                        {{ $selectedEdition->status === 'active' ? 'Cycle de formation actuel' : 'Archive du cycle' }}
                    </span>
                    <h2 class="text-2xl font-headline font-black">{{ $selectedEdition->title }}</h2>
                    <p class="text-xs text-blue-100/80 mt-1">
                        Budget : {{ number_format($selectedEdition->total_budget, 0, ',', ' ') }} FCFA · 
                        Période : {{ $selectedEdition->start_date->format('M Y') }} - {{ $selectedEdition->end_date->format('M Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <div class="text-[10px] uppercase font-bold text-blue-200">Total Éditions</div>
                    <div class="text-2xl font-black">{{ $stats['total_editions_count'] }}</div>
                </div>
                <a href="{{ route('admin.editions.index') }}" class="px-5 py-2.5 bg-white text-primary rounded-xl font-bold text-sm hover:bg-blue-50 transition-all shadow-md active:scale-95">Gérer les cycles</a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Activity Table -->
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/30 backdrop-blur-sm">
                    <h2 class="font-headline font-semibold text-lg text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">history</span>
                        Inscriptions Récentes
                    </h2>
                    <a href="{{ route('admin.candidates') }}" class="text-sm font-medium text-primary hover:text-primary-container transition-colors py-1 px-3 rounded-full hover:bg-primary/5">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest">
                                <th class="py-4 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wider border-b border-outline-variant">Utilisateur</th>
                                <th class="py-4 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wider border-b border-outline-variant">Séminaire</th>
                                <th class="py-4 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wider border-b border-outline-variant">Date</th>
                                <th class="py-4 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wider border-b border-outline-variant text-right">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/60">
                            @forelse($recentEnrollments as $enrollment)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                            {{ substr($enrollment->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-on-surface text-sm">{{ $enrollment->user->name }}</div>
                                            <div class="text-xs text-on-surface-variant">{{ $enrollment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-on-surface font-medium">{{ $enrollment->seminar->title }}</td>
                                <td class="py-4 px-6 text-sm text-on-surface-variant">{{ $enrollment->created_at->format('d M, H:i') }}</td>
                                <td class="py-4 px-6 text-right">
                                    @php
                                        $statusStyles = [
                                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $dotStyles = [
                                            'approved' => 'bg-emerald-500',
                                            'pending' => 'bg-amber-500',
                                            'rejected' => 'bg-rose-500',
                                        ];
                                        $labels = [
                                            'approved' => 'Confirmé',
                                            'pending' => 'En attente',
                                            'rejected' => 'Refusé',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusStyles[$enrollment->status] ?? $statusStyles['pending'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotStyles[$enrollment->status] ?? $dotStyles['pending'] }}"></span>
                                        {{ $labels[$enrollment->status] ?? 'Inconnu' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-on-surface-variant">Aucune inscription récente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.seminars') }}" class="bg-primary hover:opacity-90 text-on-primary p-4 rounded-2xl shadow-sm hover:shadow transition-all group flex flex-col items-center justify-center gap-2 text-center h-28" style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                    <span class="material-symbols-outlined text-3xl group-hover:scale-110 transition-transform">add_circle</span>
                    <span class="text-sm font-medium">Créer Séminaire</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-surface-container-highest hover:bg-surface-dim text-on-surface p-4 rounded-2xl shadow-sm hover:shadow transition-all group flex flex-col items-center justify-center gap-2 text-center h-28 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-3xl text-secondary group-hover:scale-110 transition-transform">group_add</span>
                    <span class="text-sm font-medium">Gestion Utilisateurs</span>
                </a>
                <a href="{{ route('admin.finances') }}" class="bg-surface-container-highest hover:bg-surface-dim text-on-surface p-4 rounded-2xl shadow-sm hover:shadow transition-all group flex flex-col items-center justify-center gap-2 text-center h-28 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-3xl text-tertiary group-hover:scale-110 transition-transform">download</span>
                    <span class="text-sm font-medium">Rapports Finances</span>
                </a>
                <a href="#" class="bg-surface-container-highest hover:bg-surface-dim text-on-surface p-4 rounded-2xl shadow-sm hover:shadow transition-all group flex flex-col items-center justify-center gap-2 text-center h-28 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-3xl text-primary group-hover:scale-110 transition-transform">settings</span>
                    <span class="text-sm font-medium">Paramètres</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-8">
            <!-- Upcoming Seminars -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant">
                <h2 class="font-headline font-semibold text-lg text-on-surface flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-tertiary">event_upcoming</span>
                    Séminaires à venir
                </h2>
                
                <div class="space-y-4">
                    @forelse($upcomingSeminars as $seminar)
                    @php
                        $enrolled = $seminar->enrollments()->where('status', 'approved')->count();
                        $isFull = $enrolled >= $seminar->max_participants;
                    @endphp
                    <div class="flex gap-4 items-start p-3 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant/50">
                        <div class="bg-tertiary-container text-on-tertiary-container rounded-lg px-3 py-2 text-center min-w-[3.5rem] shadow-sm">
                            <div class="text-xs font-bold uppercase tracking-wider">{{ $seminar->start_date->format('M') }}</div>
                            <div class="text-xl font-headline font-black">{{ $seminar->start_date->format('d') }}</div>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-on-surface leading-tight mb-1">{{ $seminar->title }}</h4>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                {{ $seminar->start_date->format('H:i') }} - {{ $seminar->end_date->format('H:i') }}
                            </div>
                            <div class="text-xs font-medium {{ $isFull ? 'text-rose-600 bg-rose-50' : 'text-emerald-600 bg-emerald-50' }} inline-block px-2 py-0.5 rounded">
                                {{ $isFull ? 'Complet' : $enrolled . '/' . $seminar->max_participants . ' Inscrits' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-on-surface-variant text-center py-4">Aucun séminaire prévu.</p>
                    @endforelse
                </div>
                
                <a href="{{ route('admin.seminars') }}" class="block w-full text-center mt-4 py-2 border border-outline hover:bg-surface-container text-on-surface font-medium text-sm rounded-lg transition-colors">
                    Calendrier complet
                </a>
            </div>

            <!-- Activity Log / Audit Trail -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant">
                <h2 class="font-headline font-semibold text-base text-on-surface mb-6 flex items-center justify-between">
                    Traçabilité Staff
                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] uppercase font-bold tracking-widest">Audit Trail</span>
                </h2>
                
                <div class="space-y-6 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-outline-variant before:h-full pb-2">
                    @forelse($recentLogs as $log)
                    <div class="relative pl-8 group">
                        <!-- Dot -->
                        <div class="absolute left-1.5 top-1 w-3.5 h-3.5 rounded-full bg-surface-container-lowest border-2 border-primary group-hover:scale-125 transition-transform z-10"></div>
                        
                        <div class="flex flex-col">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">{{ $log->action }}</span>
                                <span class="text-[10px] text-on-surface-variant font-medium">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant line-clamp-1 italic mb-1">"{{ $log->description }}"</p>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-full bg-primary/20 text-primary flex items-center justify-center text-[8px] font-bold">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <span class="text-[10px] font-bold text-on-surface-variant">{{ $log->user->name }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-on-surface-variant text-center py-4 italic">Aucune action enregistrée pour le moment.</p>
                    @endforelse
                </div>
                
                <button class="w-full mt-6 py-2 border border-outline hover:bg-surface-container text-on-surface font-medium text-sm rounded-lg transition-colors">
                    Journal complet
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
