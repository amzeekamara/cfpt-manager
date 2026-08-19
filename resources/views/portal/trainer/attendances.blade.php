@extends('portal.layout')
@section('title', 'Gestion des présences')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <a href="{{ route('portal.trainer.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 mb-2 transition-colors bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-2xs">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Retour au tableau de bord</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Feuille d'émargement & Présences</h1>
        <p class="text-xs text-slate-500 mt-1">Saisie et suivi journalier des présences des participants par séminaire.</p>
    </div>
</div>

<!-- Filter Box -->
<div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-200/90 shadow-sm mb-6">
    <form action="{{ route('portal.trainer.attendances') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end" id="filterForm">
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Séminaire</label>
            <select name="seminar_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800" onchange="document.getElementById('filterForm').submit()">
                <option value="">-- Choisir un séminaire --</option>
                @foreach($trainer->seminars as $seminar)
                    <option value="{{ $seminar->id }}" {{ $seminarId == $seminar->id ? 'selected' : '' }}>{{ $seminar->title }}</option>
                @endforeach
            </select>
        </div>
        
        @if($seminarId)
        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Thème (Optionnel)</label>
            <select name="topic_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800" onchange="document.getElementById('filterForm').submit()">
                <option value="">-- Aucun thème spécifique --</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-[11px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Date de la séance</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:border-[#4B6A95] focus:bg-white outline-none text-xs font-bold text-slate-800" required>
        </div>

        <div>
            <button type="submit" class="w-full px-5 py-2.5 bg-[#4B6A95] text-white rounded-full text-xs font-bold hover:bg-[#354e70] shadow-xs transition-all flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Charger la liste</span>
            </button>
        </div>
    </form>
</div>

<!-- Candidates Attendance Table Card -->
<div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-200/90 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 pb-4 border-b border-slate-100">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i data-lucide="clipboard-check" class="w-5 h-5 text-[#4B6A95]"></i>
            <span>Séance du {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
        </h2>
        <span class="text-xs font-bold text-slate-400">{{ $registrations->count() }} participant(s) inscrit(s)</span>
    </div>

    @if($registrations->isEmpty())
        <div class="py-12 text-center text-slate-400 text-xs">
            <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
            <p class="font-bold text-slate-700 text-sm">Aucun candidat approuvé pour ce séminaire.</p>
            <p class="mt-1">Veuillez sélectionner un séminaire avec des inscriptions validées.</p>
        </div>
    @else
        <form action="{{ route('portal.trainer.attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="seminar_id" value="{{ $seminarId }}">
            <input type="hidden" name="topic_id" value="{{ request('topic_id') }}">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                            <th class="px-4 py-3">Candidat</th>
                            <th class="px-4 py-3 text-center">Émargement</th>
                            <th class="px-4 py-3 text-right">Statut Enregistré</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold">
                        @foreach($registrations as $registration)
                            @php
                                $attendance = $registration->attendances->first();
                                $status = $attendance ? $attendance->status : null;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($registration->user->avatar)
                                            <img src="{{ Storage::url($registration->user->avatar) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-[#4B6A95]/10 flex items-center justify-center text-[#4B6A95] font-black text-xs">
                                                {{ substr($registration->user->name, 0, 1) }}{{ substr($registration->user->firstname ?? '', 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs">{{ $registration->user->name }} {{ $registration->user->firstname ?? '' }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $registration->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="inline-flex bg-slate-100 rounded-full p-1 border border-slate-200">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $registration->id }}]" value="present" class="peer sr-only" {{ $status === 'present' ? 'checked' : '' }} required>
                                            <div class="px-4 py-1.5 rounded-full text-xs font-bold text-slate-500 peer-checked:bg-emerald-600 peer-checked:text-white transition-all">
                                                Présent
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $registration->id }}]" value="absent" class="peer sr-only" {{ $status === 'absent' ? 'checked' : '' }}>
                                            <div class="px-4 py-1.5 rounded-full text-xs font-bold text-slate-500 peer-checked:bg-rose-600 peer-checked:text-white transition-all">
                                                Absent
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    @if($status === 'present')
                                        <span class="text-xs font-extrabold text-emerald-600">
                                            Présent
                                        </span>
                                    @elseif($status === 'absent')
                                        <span class="text-xs font-extrabold text-rose-600">
                                            Absent
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-slate-400">
                                            Non défini
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                    <label class="text-xs font-black text-slate-700">Volume d'heures dispensées :</label>
                    <input type="number" name="hours_taught" min="0" max="24" class="w-20 px-3 py-1.5 bg-white border border-slate-300 rounded-xl focus:border-[#4B6A95] text-xs font-black text-center outline-none" placeholder="Ex: 4" required>
                </div>
                <button type="submit" class="px-8 py-3 bg-[#4B6A95] text-white rounded-full text-xs font-bold shadow-xs hover:bg-[#354e70] transition-all flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Enregistrer la feuille de présence</span>
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
