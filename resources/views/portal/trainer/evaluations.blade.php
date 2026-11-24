@extends('portal.layout')
@section('title', 'Évaluations & Notes — Espace Formateur')

@section('content')
<div x-data="{
    createModalOpen: false,
    selectedSeminarId: '{{ $selectedSeminarId }}',
    seminarsTopicsMap: {
        @foreach($trainer->seminars as $sem)
            '{{ $sem->id }}': [
                @foreach($sem->topics as $top)
                    { id: '{{ $top->id }}', name: '{{ addslashes($top->name) }}' },
                @endforeach
            ],
        @endforeach
    },
    get currentTopics() {
        return this.seminarsTopicsMap[this.selectedSeminarId] || [];
    }
}">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('portal.trainer.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 mb-2 transition-colors bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Retour au tableau de bord</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Gestion des Évaluations & Saisie des Notes</h1>
            <p class="text-xs text-slate-500 mt-1">Créez vos évaluations (Devoirs, TP, Examens) et saisissez les notes des étudiants inscrits à vos cours.</p>
        </div>

        <button type="button" @click="createModalOpen = true" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-bold flex items-center gap-2 shadow-sm transition cursor-pointer">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>+ Nouvelle Évaluation</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/90 shadow-sm mb-6">
        <form action="{{ route('portal.trainer.evaluations') }}" method="GET" id="trainerEvalFilter" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end text-xs">
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Filière / Cours assigné</label>
                <select name="seminar_id" onchange="document.getElementById('trainerEvalFilter').submit()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                    @foreach($trainer->seminars as $seminar)
                        <option value="{{ $seminar->id }}" {{ $selectedSeminarId == $seminar->id ? 'selected' : '' }}>{{ $seminar->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Évaluation à noter</label>
                <select name="evaluation_id" onchange="document.getElementById('trainerEvalFilter').submit()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                    @forelse($evaluations as $ev)
                        <option value="{{ $ev->id }}" {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'selected' : '' }}>
                            [{{ strtoupper($ev->type) }} - Coef {{ $ev->coefficient }}] {{ $ev->title }} ({{ $ev->evaluation_date->format('d/m/Y') }})
                        </option>
                    @empty
                        <option value="">-- Aucune évaluation créée --</option>
                    @endforelse
                </select>
            </div>

            <div>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold cursor-pointer">
                    Charger la grille
                </button>
            </div>
        </form>
    </div>

    <!-- Main Grade Entry Card -->
    <div class="bg-white rounded-[24px] p-6 sm:p-8 border border-slate-200/90 shadow-sm">
        @if($activeEvaluation)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#4B6A95] text-[10px] font-black uppercase tracking-wider">
                        {{ strtoupper($activeEvaluation->type) }} • Coefficient {{ $activeEvaluation->coefficient }}
                    </span>
                    <h2 class="text-lg font-black text-slate-900 mt-1">{{ $activeEvaluation->title }}</h2>
                    <p class="text-xs text-slate-500">
                        Matière : <strong>{{ $activeEvaluation->topic?->name ?? 'Tronc commun' }}</strong> • Date : {{ $activeEvaluation->evaluation_date->format('d/m/Y') }}
                    </p>
                </div>
                <div class="text-xs font-bold text-emerald-600">
                    ● Restitution active aux étudiants
                </div>
            </div>

            @if($registrations->count() > 0)
                <form action="{{ route('portal.trainer.evaluations.grades.store', $activeEvaluation->id) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400">
                                    <th class="py-3 px-4">Étudiant</th>
                                    <th class="py-3 px-4">Module</th>
                                    <th class="py-3 px-4 w-36 text-center">Note / {{ number_format($activeEvaluation->max_score, 0) }}</th>
                                    <th class="py-3 px-4">Appréciation du formateur</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($registrations as $reg)
                                    @php
                                        $existingGrade = $activeEvaluation->grades->firstWhere('registration_id', $reg->id);
                                    @endphp
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $reg->user->email }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 font-semibold">
                                            {{ $reg->topic?->name ?? 'Général' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <input type="number" step="0.25" min="0" max="{{ $activeEvaluation->max_score }}"
                                                   name="scores[{{ $reg->id }}]"
                                                   value="{{ $existingGrade?->score }}"
                                                   placeholder="0 - {{ number_format($activeEvaluation->max_score, 0) }}"
                                                   class="w-24 px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-center font-black text-slate-900 focus:bg-white focus:border-[#4B6A95] outline-none">
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <input type="text"
                                                   name="appreciations[{{ $reg->id }}]"
                                                   value="{{ $existingGrade?->appreciation }}"
                                                   placeholder="Observation ou appréciation automatique"
                                                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:bg-white focus:border-[#4B6A95] outline-none">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Enregistrer et restituer les notes</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="py-10 text-center text-slate-400 text-xs">
                    Aucun étudiant inscrit dans ce cours.
                </div>
            @endif
        @else
            <div class="py-12 text-center text-slate-400 text-xs">
                <i data-lucide="award" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                <p class="font-bold text-slate-700 text-sm">Aucune évaluation créée pour ce cours.</p>
                <button type="button" @click="createModalOpen = true" class="mt-4 px-5 py-2.5 bg-[#4B6A95] text-white rounded-full text-xs font-bold cursor-pointer">
                    + Créer une évaluation
                </button>
            </div>
        @endif
    </div>

    <!-- Modal: Nouvelle Évaluation -->
    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Créer une Évaluation (Devoir / TP / Examen)</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('portal.trainer.evaluations.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Filière / Cours <span class="text-rose-500">*</span></label>
                    <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                        @foreach($trainer->seminars as $sem)
                            <option value="{{ $sem->id }}">{{ $sem->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Matière / Module</label>
                    <select name="topic_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                        <option value="">-- Tronc commun --</option>
                        <template x-for="top in currentTopics" :key="top.id">
                            <option :value="top.id" x-text="top.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Titre de l'évaluation <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Ex: Devoir N°1, TP Atelier, Examen Final..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Type</label>
                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-800">
                            <option value="devoir">Devoir</option>
                            <option value="tp">TP / Atelier</option>
                            <option value="examen">Examen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Coef.</label>
                        <input type="number" step="0.5" min="0.5" max="20" name="coefficient" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Barème</label>
                        <input type="number" name="max_score" value="20" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date</label>
                        <input type="date" name="evaluation_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-800">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold">Créer l'évaluation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
