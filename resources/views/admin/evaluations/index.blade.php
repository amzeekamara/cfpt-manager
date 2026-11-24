@extends('layouts.admin')

@section('header_title', 'Évaluations, Notes & Restitution')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    createModalOpen: false,
    selectedSeminarId: '{{ $selectedSeminarId }}',
    seminarsTopicsMap: {
        @foreach($seminars as $sem)
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
    <div class="w-full max-w-[1600px] mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Évaluations, Notes & Restitution</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                        Saisissez les notes (Devoirs, TP, Examens), calculez les moyennes pondérées et générez les bulletins officiels PDF.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" @click="createModalOpen = true" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white rounded-full text-xs font-bold flex items-center gap-2 shadow-sm transition cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add_chart</span>
                    + Créer une Évaluation (Devoir / TP / Examen)
                </button>
            </div>
        </div>

        <!-- Seminar & Module Selector Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <form action="{{ route('admin.evaluations.index') }}" method="GET" id="evalFilterForm" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end text-xs">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Filière / Formation</label>
                    <select name="seminar_id" onchange="document.getElementById('evalFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                        @foreach($seminars as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSeminarId == $sem->id ? 'selected' : '' }}>
                                {{ $sem->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Évaluation active pour saisie des notes</label>
                    <select name="evaluation_id" onchange="document.getElementById('evalFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                        @forelse($evaluations as $ev)
                            <option value="{{ $ev->id }}" {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'selected' : '' }}>
                                [{{ strtoupper($ev->type) }} - Coef {{ $ev->coefficient }}] {{ $ev->title }} ({{ $ev->evaluation_date->format('d/m/Y') }})
                            </option>
                        @empty
                            <option value="">-- Aucune évaluation créée pour cette filière --</option>
                        @endforelse
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition cursor-pointer">
                        Actualiser
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <!-- Left 7 Cols: Grade Entry for Active Evaluation -->
            <div class="xl:col-span-7">
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
                    @if($activeEvaluation)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-5">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-[#4B6A95]">
                                        {{ strtoupper($activeEvaluation->type) }} • Coef. {{ $activeEvaluation->coefficient }}
                                    </span>
                                    <span class="text-xs font-bold {{ $activeEvaluation->is_published ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $activeEvaluation->is_published ? '● Notes restituées aux étudiants' : '● Brouillon (masqué)' }}
                                    </span>
                                </div>
                                <h2 class="text-lg font-black text-slate-900 mt-1">{{ $activeEvaluation->title }}</h2>
                                <p class="text-xs text-slate-500">
                                    Matière : <strong>{{ $activeEvaluation->topic?->name ?? 'Tronc commun' }}</strong> • Barème sur <strong>{{ number_format($activeEvaluation->max_score, 0) }} pts</strong>
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.evaluations.toggle-publish', $activeEvaluation->id) }}" method="POST" class="m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold hover:bg-slate-50 transition cursor-pointer">
                                        {{ $activeEvaluation->is_published ? 'Masquer notes' : 'Publier / Restituer' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.evaluations.destroy', $activeEvaluation->id) }}" method="POST" onsubmit="return confirm('Supprimer cette évaluation et ses notes ?');" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition cursor-pointer" title="Supprimer">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($registrations->count() > 0)
                            <form action="{{ route('admin.evaluations.grades.store', $activeEvaluation->id) }}" method="POST">
                                @csrf
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead>
                                            <tr class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400">
                                                <th class="py-3 px-3">Étudiant</th>
                                                <th class="py-3 px-3">Spécialité / Module</th>
                                                <th class="py-3 px-3 w-32 text-center">Note / {{ number_format($activeEvaluation->max_score, 0) }}</th>
                                                <th class="py-3 px-3">Appréciation pédagogique</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($registrations as $reg)
                                                @php
                                                    $existingGrade = $activeEvaluation->grades->firstWhere('registration_id', $reg->id);
                                                @endphp
                                                <tr class="hover:bg-slate-50/80">
                                                    <td class="py-3 px-3">
                                                        <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                                                        <div class="text-[10px] text-slate-400">{{ $reg->user->email }}</div>
                                                    </td>
                                                    <td class="py-3 px-3 text-slate-600 font-medium">
                                                        {{ $reg->topic?->name ?? 'Tronc commun' }}
                                                    </td>
                                                    <td class="py-3 px-3 text-center">
                                                        <input type="number" step="0.25" min="0" max="{{ $activeEvaluation->max_score }}"
                                                               name="scores[{{ $reg->id }}]"
                                                               value="{{ $existingGrade?->score }}"
                                                               placeholder="0 - {{ number_format($activeEvaluation->max_score, 0) }}"
                                                               class="w-24 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-center font-black text-slate-900 focus:bg-white focus:border-[#4B6A95] outline-none">
                                                    </td>
                                                    <td class="py-3 px-3">
                                                        <input type="text"
                                                               name="appreciations[{{ $reg->id }}]"
                                                               value="{{ $existingGrade?->appreciation }}"
                                                               placeholder="Auto (Très bien, Bien, Passable...)"
                                                               class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:bg-white focus:border-[#4B6A95] outline-none">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="is_published" value="1" {{ $activeEvaluation->is_published ? 'checked' : '' }} class="rounded text-[#4B6A95]">
                                        <span>Restituer immédiatement ces notes dans l'espace étudiant</span>
                                    </label>
                                    <button type="submit" class="px-6 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold text-xs rounded-full shadow-sm transition cursor-pointer flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">save</span>
                                        Enregistrer les notes
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="py-10 text-center text-slate-400 text-xs">
                                Aucun étudiant inscrit dans cette filière.
                            </div>
                        @endif
                    @else
                        <div class="py-14 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl opacity-40 mb-2">grading</span>
                            <p class="text-sm font-bold text-slate-700">Aucune évaluation sélectionnée.</p>
                            <p class="text-xs mt-1">Créez un devoir, un TP ou un examen pour saisir et restituer les notes des étudiants.</p>
                            <button type="button" @click="createModalOpen = true" class="mt-4 px-5 py-2.5 bg-[#4B6A95] text-white rounded-full text-xs font-bold cursor-pointer">
                                + Créer une première évaluation
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 5 Cols: Synthèse des Moyennes & Restitution des Bulletins PDF -->
            <div class="xl:col-span-5">
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
                    <div class="pb-4 border-b border-slate-100 mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black text-slate-900">Restitution & Bulletins de Notes</h3>
                            <p class="text-xs text-slate-500">Moyenne générale pondérée (/20) et export PDF officiel.</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            {{ $evaluations->count() }} éval.
                        </span>
                    </div>

                    <div class="space-y-2.5 max-h-[540px] overflow-y-auto pr-1">
                        @forelse($registrations as $reg)
                            @php
                                $weightedSum = 0;
                                $coefSum = 0;
                                foreach ($evaluations as $ev) {
                                    $g = $reg->grades->firstWhere('evaluation_id', $ev->id);
                                    if ($g && $g->score !== null) {
                                        $s20 = ($g->score / max(1, $ev->max_score)) * 20;
                                        $weightedSum += $s20 * $ev->coefficient;
                                        $coefSum += $ev->coefficient;
                                    }
                                }
                                $avg = $coefSum > 0 ? round($weightedSum / $coefSum, 2) : null;
                            @endphp
                            <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/60 hover:bg-white transition flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-900 truncate">{{ $reg->user->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $reg->topic?->name ?? 'Tronc commun' }}</div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold uppercase text-slate-400 block">Moyenne</span>
                                        @if($avg !== null)
                                            <span class="text-sm font-black {{ $avg >= 10 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ number_format($avg, 2) }} / 20
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">N/A</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('admin.evaluations.report-card.pdf', $reg->id) }}" data-no-loader
                                       class="px-3 py-2 rounded-xl bg-white hover:bg-[#4B6A95] text-[#4B6A95] hover:text-white border border-slate-200 text-xs font-bold transition flex items-center gap-1 shadow-2xs"
                                       title="Télécharger le bulletin de notes PDF">
                                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                        <span>Bulletin</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">
                                Aucun étudiant inscrit.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Créer une Évaluation -->
    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Nouvelle Évaluation Pédagogique</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.evaluations.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Filière / Formation <span class="text-rose-500">*</span></label>
                    <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                        @foreach($seminars as $sem)
                            <option value="{{ $sem->id }}">{{ $sem->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
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
                        <label class="block font-bold text-slate-700 mb-1">Formateur évaluateur</label>
                        <select name="trainer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            <option value="">-- Administration / Jury --</option>
                            @foreach($trainers as $tr)
                                <option value="{{ $tr->id }}">{{ $tr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Intitulé de l'évaluation <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Ex: Devoir N°1 Automatisme, TP Électronique, Examen Semestriel..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Type <span class="text-rose-500">*</span></label>
                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                            <option value="devoir">Devoir</option>
                            <option value="tp">TP / Atelier</option>
                            <option value="examen">Examen Final</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Coefficient <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.5" min="0.5" max="20" name="coefficient" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Barème <span class="text-rose-500">*</span></label>
                        <input type="number" name="max_score" value="20" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="evaluation_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold shadow-sm cursor-pointer">
                        Créer et passer à la saisie des notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
