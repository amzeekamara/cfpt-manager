@extends('layouts.admin')

@section('header_title', 'Gestion des Candidatures')

@section('header_subtitle')
    @if($selectedEdition)
        <span class="text-white text-[10px] font-bold bg-primary px-2 py-0.5 rounded-full shadow-sm whitespace-nowrap">
            {{ $selectedEdition->title }}
        </span>
    @endif
@endsection

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-[1400px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Candidatures</h1>
                    <p class="text-gray-500 text-sm mt-1">Examinez et gérez les dossiers des candidats.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.candidates.create') }}" class="px-6 py-3 bg-brand-primary hover:opacity-90 text-white font-medium rounded-full flex items-center gap-2 transition shadow-lg shadow-brand-primary/30">
                    <span class="material-symbols-outlined text-[20px] leading-none">person_add</span>
                    Inscrire un candidat
                </a>
            </div>
        </header>

        <!-- Stats summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm relative overflow-hidden h-32 flex flex-col justify-between hover:-translate-y-1 transition-transform group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-500">Total Demandes</span>
                    <span class="material-symbols-outlined text-gray-400 text-[20px]" style="font-variation-settings: 'FILL' 1;">inbox</span>
                </div>
                <div class="text-3xl font-headline font-bold text-gray-900">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm relative overflow-hidden h-32 flex flex-col justify-between hover:-translate-y-1 transition-transform group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-500">En attente</span>
                    <span class="material-symbols-outlined text-amber-500 text-[20px]" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                </div>
                <div class="text-3xl font-headline font-bold text-gray-900">{{ $stats['pending'] }}</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm relative overflow-hidden h-32 flex flex-col justify-between hover:-translate-y-1 transition-transform group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-500">Approuvées</span>
                    <span class="material-symbols-outlined text-emerald-500 text-[20px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <div class="text-3xl font-headline font-bold text-gray-900">{{ $stats['approved'] }}</div>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm relative overflow-hidden h-32 flex flex-col justify-between hover:-translate-y-1 transition-transform group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-500">Refusées</span>
                    <span class="material-symbols-outlined text-rose-500 text-[20px]" style="font-variation-settings: 'FILL' 1;">cancel</span>
                </div>
                <div class="text-3xl font-headline font-bold text-gray-900">{{ $stats['rejected'] }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-gray-800 flex items-center mr-2">Filtres</span>
                
                <form action="{{ route('admin.candidates') }}" method="GET" id="filterForm" class="m-0 flex flex-wrap items-center gap-3">
                    <select name="edition_id" onchange="document.getElementById('filterForm').submit()" 
                            class="text-sm border border-gray-200 rounded-full px-4 py-2.5 bg-white outline-none text-gray-600 appearance-none min-w-[160px] focus:border-brand-primary focus:ring-1 focus:ring-brand-primary cursor-pointer shadow-sm">
                        <option value="">Toutes les éditions</option>
                        @foreach($editions as $edition)
                            <option value="{{ $edition->id }}" {{ ($selectedEdition && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                                {{ $edition->title }} {{ $edition->status == 'active' ? '(Active)' : '' }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="document.getElementById('filterForm').submit()" 
                            class="text-sm border border-gray-200 rounded-full px-4 py-2.5 bg-white outline-none text-gray-600 appearance-none min-w-[160px] focus:border-brand-primary focus:ring-1 focus:ring-brand-primary cursor-pointer shadow-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                        <option value="waiting_list" {{ request('status') == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
                    </select>

                    <select name="seminar_id" onchange="document.getElementById('filterForm').submit()" 
                            class="text-sm border border-gray-200 rounded-full px-4 py-2.5 bg-white outline-none text-gray-600 appearance-none min-w-[180px] max-w-[250px] focus:border-brand-primary focus:ring-1 focus:ring-brand-primary cursor-pointer shadow-sm">
                        <option value="">Tous les séminaires</option>
                        @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}" {{ request('seminar_id') == $seminar->id ? 'selected' : '' }}>
                                {{ Str::limit($seminar->title, 35) }}
                            </option>
                        @endforeach
                    </select>

                    @if(request()->anyFilled(['search', 'status', 'seminar_id', 'edition_id']))
                        <a href="{{ route('admin.candidates') }}" class="px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 rounded-full font-medium transition-colors border border-rose-100 flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                            Effacer
                        </a>
                    @endif
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative shadow-sm rounded-full">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 flex items-center">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" onblur="document.getElementById('filterForm').submit()" placeholder="Rechercher ID #" class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm outline-none w-56 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary bg-white transition-all">
                </div>
                </form>
            </div>
        </div>

        <!-- Candidates Table -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Candidat</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Formation / Édition</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Date Demande</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Statut</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($candidates as $enrollment)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center text-brand-primary font-bold overflow-hidden shadow-inner flex-shrink-0">
                                        @if($enrollment->user->candidateProfile && $enrollment->user->candidateProfile->photo_path)
                                            <img src="{{ asset('storage/' . $enrollment->user->candidateProfile->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($enrollment->user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $enrollment->user->name }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $enrollment->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-gray-800 line-clamp-1">{{ $enrollment->seminar->title }}</div>
                                <div class="text-[10px] font-bold text-brand-primary mt-0.5">{{ $enrollment->seminar->edition?->title ?? 'Session Spéciale' }}</div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                {{ $enrollment->created_at->format('d/m/Y') }} 
                                <span class="text-[11px] ml-1">{{ $enrollment->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'waiting_list' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'À Examiner',
                                        'approved' => 'Sélectionné',
                                        'rejected' => 'Refusé',
                                        'waiting_list' => 'En attente',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$enrollment->status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                    {{ $statusLabels[$enrollment->status] ?? $enrollment->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.candidates.show', $enrollment->id) }}" class="p-2 text-gray-500 hover:text-brand-primary hover:bg-brand-light rounded-full transition-colors" title="Voir le dossier">
                                        <span class="material-symbols-outlined text-[20px] leading-none">visibility</span>
                                    </a>
                                    <form action="{{ route('admin.candidates.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Supprimer ce dossier ?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-full transition-colors">
                                            <span class="material-symbols-outlined text-[20px] leading-none">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($candidates, 'hasPages') && $candidates->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $candidates->links() }}
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
