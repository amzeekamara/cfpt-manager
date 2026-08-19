@extends('portal.layout')
@section('title', 'Gestion des présences — Espace Formateur')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <a href="{{ route('portal.trainer.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 mb-2 transition-colors bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-2xs">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Retour au tableau de bord</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Liste des Candidats & Suivi des Présences</h1>
        <p class="text-xs text-slate-500 mt-1">Consultez et filtrez en temps réel les présences et absences de vos candidats par date.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 text-emerald-800 rounded-2xl border border-emerald-200 text-xs font-bold shadow-2xs">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Barre de Filtres Complète (Date, Séminaire, Thème, Recherche) -->
<div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm mb-6">
    <form action="{{ route('portal.trainer.attendances') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end" id="attendanceFilterForm">
        <!-- 1. Séminaire -->
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Séminaire</label>
            <select name="seminar_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800 cursor-pointer" onchange="document.getElementById('attendanceFilterForm').submit()">
                @foreach($trainer->seminars as $seminar)
                    <option value="{{ $seminar->id }}" {{ $seminarId == $seminar->id ? 'selected' : '' }}>{{ $seminar->title }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- 2. Thème (Optionnel) -->
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Thème d'atelier / Projet</label>
            <select name="topic_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800 cursor-pointer" onchange="document.getElementById('attendanceFilterForm').submit()">
                <option value="">-- Tous les thèmes --</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ ($topicId ?? '') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 3. Date de la séance -->
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Date de la séance *</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800 cursor-pointer" required onchange="document.getElementById('attendanceFilterForm').submit()">
        </div>

        <!-- 4. Recherche par mot-clé -->
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Rechercher un candidat</label>
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nom, matricule, email..." class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-medium text-slate-800">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
            </div>
        </div>

        <input type="hidden" name="status" id="status_filter_input" value="{{ $statusFilter ?? 'all' }}">
    </form>
</div>

<!-- Quick Presence/Absence Tabs / Counters Filter -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <!-- Tous -->
    <button type="button" onclick="setStatusFilter('all')" class="p-4 rounded-2xl border transition-all text-left flex items-center justify-between cursor-pointer {{ ($statusFilter ?? 'all') === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:bg-slate-50' }}">
        <div>
            <span class="text-[10px] font-black uppercase tracking-wider block opacity-75">Tous les candidats</span>
            <span class="text-xl font-black">{{ $totalCandidatesCount }}</span>
        </div>
        <i data-lucide="users" class="w-5 h-5 opacity-80"></i>
    </button>

    <!-- Présents -->
    <button type="button" onclick="setStatusFilter('present')" class="p-4 rounded-2xl border transition-all text-left flex items-center justify-between cursor-pointer {{ ($statusFilter ?? '') === 'present' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:bg-emerald-50/50' }}">
        <div>
            <span class="text-[10px] font-black uppercase tracking-wider block text-emerald-600 {{ ($statusFilter ?? '') === 'present' ? '!text-emerald-100' : '' }}">Candidats Présents</span>
            <span class="text-xl font-black text-emerald-700 {{ ($statusFilter ?? '') === 'present' ? '!text-white' : '' }}">{{ $presentCount }}</span>
        </div>
        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 {{ ($statusFilter ?? '') === 'present' ? '!text-white' : '' }}"></i>
    </button>

    <!-- Absents -->
    <button type="button" onclick="setStatusFilter('absent')" class="p-4 rounded-2xl border transition-all text-left flex items-center justify-between cursor-pointer {{ ($statusFilter ?? '') === 'absent' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:bg-rose-50/50' }}">
        <div>
            <span class="text-[10px] font-black uppercase tracking-wider block text-rose-600 {{ ($statusFilter ?? '') === 'absent' ? '!text-rose-100' : '' }}">Candidats Absents</span>
            <span class="text-xl font-black text-rose-700 {{ ($statusFilter ?? '') === 'absent' ? '!text-white' : '' }}">{{ $absentCount }}</span>
        </div>
        <i data-lucide="x-circle" class="w-5 h-5 text-rose-500 {{ ($statusFilter ?? '') === 'absent' ? '!text-white' : '' }}"></i>
    </button>

    <!-- Non émargés -->
    <button type="button" onclick="setStatusFilter('unmarked')" class="p-4 rounded-2xl border transition-all text-left flex items-center justify-between cursor-pointer {{ ($statusFilter ?? '') === 'unmarked' ? 'bg-amber-500 text-white border-amber-500 shadow-md' : 'bg-white text-slate-800 border-slate-200 hover:bg-amber-50/50' }}">
        <div>
            <span class="text-[10px] font-black uppercase tracking-wider block text-amber-600 {{ ($statusFilter ?? '') === 'unmarked' ? '!text-amber-100' : '' }}">Non Émargés</span>
            <span class="text-xl font-black text-amber-700 {{ ($statusFilter ?? '') === 'unmarked' ? '!text-white' : '' }}">{{ $unmarkedCount }}</span>
        </div>
        <i data-lucide="clock" class="w-5 h-5 text-amber-500 {{ ($statusFilter ?? '') === 'unmarked' ? '!text-white' : '' }}"></i>
    </button>
</div>

<!-- Table Card -->
<div class="bg-white rounded-[24px] p-6 sm:p-8 border border-slate-200/90 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-[#4B6A95]"></i>
                <span>Feuille de présence — Séance du {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Affichage de <strong class="text-slate-700">{{ $registrations->count() }}</strong> candidat(s) 
                @if(($statusFilter ?? 'all') !== 'all')
                    (Filtre : <span class="uppercase font-bold text-[#4B6A95]">{{ $statusFilter }}</span>)
                @endif
            </p>
        </div>

        @if($totalCandidatesCount > 0)
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase text-slate-400 block">Taux d'émargement</span>
                    <span class="text-xs font-black text-slate-800">
                        {{ $totalCandidatesCount > 0 ? round((($presentCount + $absentCount) / $totalCandidatesCount) * 100) : 0 }}%
                    </span>
                </div>
                <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-[#4B6A95]" style="width: {{ $totalCandidatesCount > 0 ? (($presentCount + $absentCount) / $totalCandidatesCount) * 100 : 0 }}%"></div>
                </div>
            </div>
        @endif
    </div>

    @if($registrations->isEmpty())
        <div class="py-12 text-center text-slate-400 text-xs">
            <i data-lucide="user-x" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
            <p class="font-bold text-slate-700 text-sm">Aucun candidat correspondant au filtre sélectionné.</p>
            <p class="mt-1">Modifiez vos critères de filtre ou la date pour afficher les candidats.</p>
            @if(($statusFilter ?? 'all') !== 'all' || $search)
                <button type="button" onclick="resetFilters()" class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    Réinitialiser les filtres
                </button>
            @endif
        </div>
    @else
        <form action="{{ route('portal.trainer.attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="seminar_id" value="{{ $seminarId }}">
            <input type="hidden" name="topic_id" value="{{ $topicId }}">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                            <th class="px-4 py-3">Candidat</th>
                            <th class="px-4 py-3">Thème / Spécialité</th>
                            <th class="px-4 py-3 text-center">Statut Actuel</th>
                            <th class="px-4 py-3 text-right">Modifier l'Émargement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold">
                        @foreach($registrations as $registration)
                            @php
                                $attendance = $registration->attendances->first();
                                $status = $attendance ? $attendance->status : null;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Candidat -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($registration->user->avatar && Storage::disk('public')->exists($registration->user->avatar))
                                            <img src="{{ Storage::url($registration->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                        @elseif($registration->user->candidateProfile?->photo_path && Storage::disk('public')->exists($registration->user->candidateProfile->photo_path))
                                            <img src="{{ Storage::url($registration->user->candidateProfile->photo_path) }}" alt="Photo" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#4B6A95]/10 flex items-center justify-center text-[#4B6A95] font-black text-xs shrink-0">
                                                {{ strtoupper(substr($registration->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs">{{ $registration->user->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium flex items-center gap-1.5">
                                                <span>{{ $registration->user->email }}</span>
                                                @if($registration->user->candidateProfile?->matricule)
                                                    <span>&bull;</span>
                                                    <span class="font-mono font-bold text-slate-600">{{ $registration->user->candidateProfile->matricule }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Thème -->
                                <td class="px-4 py-3.5">
                                    <span class="text-xs font-semibold text-slate-700">
                                        {{ $registration->topic?->name ?? 'Général / Non assigné' }}
                                    </span>
                                </td>

                                <!-- Statut Actuel pour la date -->
                                <td class="px-4 py-3.5 text-center">
                                    @if($status === 'present')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[11px] font-black">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Présent</span>
                                        </span>
                                    @elseif($status === 'absent')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-[11px] font-black">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            <span>Absent</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-full text-[11px] font-semibold">
                                            <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                                            <span>Non émargé</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions d'émargement direct -->
                                <td class="px-4 py-3.5 text-right">
                                    <div class="inline-flex bg-slate-100 rounded-full p-1 border border-slate-200">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $registration->id }}]" value="present" class="peer sr-only" {{ $status === 'present' ? 'checked' : '' }}>
                                            <div class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-500 peer-checked:bg-emerald-600 peer-checked:text-white transition-all flex items-center gap-1">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                <span>Présent</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $registration->id }}]" value="absent" class="peer sr-only" {{ $status === 'absent' ? 'checked' : '' }}>
                                            <div class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-500 peer-checked:bg-rose-600 peer-checked:text-white transition-all flex items-center gap-1">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                <span>Absent</span>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                    <label class="text-xs font-black text-slate-700">Volume d'heures dispensées :</label>
                    <input type="number" name="hours_taught" min="1" max="24" class="w-20 px-3 py-1.5 bg-white border border-slate-300 rounded-xl focus:border-[#4B6A95] text-xs font-black text-center outline-none" placeholder="Ex: 4" value="4" required>
                </div>
                <button type="submit" class="px-8 py-3 bg-[#4B6A95] text-white rounded-full text-xs font-bold shadow-xs hover:bg-[#354e70] transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Enregistrer la feuille de présence</span>
                </button>
            </div>
        </form>
    @endif
</div>

<script>
    function setStatusFilter(status) {
        document.getElementById('status_filter_input').value = status;
        document.getElementById('attendanceFilterForm').submit();
    }

    function resetFilters() {
        document.getElementById('status_filter_input').value = 'all';
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) searchInput.value = '';
        document.getElementById('attendanceFilterForm').submit();
    }
</script>
@endsection
