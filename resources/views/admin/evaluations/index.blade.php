@extends('layouts.admin')

@section('header_title', 'Évaluations, Notes & Bulletins')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    createModalOpen: false,
    activeTab: 'saisie',
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
    <div class="w-full max-w-[1600px] mx-auto space-y-6">

        <!-- HERO BANNER : ÉVALUATIONS, NOTES & BULLETINS -->
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] p-6 sm:p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-full transition text-white shrink-0 mt-1">
                        <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-wider text-blue-100 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Gestion Pédagogique & Restitution • CFPT Sénégal-Japon</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Évaluations, Saisie des Notes & Bulletins PDF</h1>
                        <p class="text-blue-100/85 text-xs sm:text-sm mt-1 max-w-2xl">
                            Gérez les devoirs, travaux pratiques (TP) et examens finaux par filière et matière, avec calcul automatique des moyennes pondérées (/20) et édition des bulletins.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <!-- Switch Onglets Pleine Largeur -->
                    <div class="bg-slate-900/40 backdrop-blur-xs p-1 rounded-full border border-white/15 flex items-center text-xs font-bold">
                        <button type="button" @click="activeTab = 'saisie'" :class="activeTab === 'saisie' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-4 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">edit_note</span>
                            <span>1. Notes par Évaluation</span>
                        </button>
                        <button type="button" @click="activeTab = 'bulletins'" :class="activeTab === 'bulletins' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-4 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                            <span>2. Classement & Bulletins ({{ $registrations->count() }})</span>
                        </button>
                    </div>

                    @if(auth()->user() && auth()->user()->isDepartmentHead())
                        <button type="button" @click="createModalOpen = true" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full text-xs font-extrabold flex items-center gap-2 shadow-md transition cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            <span>+ Nouvelle Évaluation</span>
                        </button>
                    @else
                        <div class="px-4 py-2 rounded-full bg-white/10 border border-white/20 text-[11px] font-bold text-blue-100 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-amber-300">verified_user</span>
                            <span>Création réservée aux Formateurs & Chefs de Département</span>
                        </div>
                    @endif
                </div>
            </div>

            @php
                $totalGradesCount = $evaluations->sum(fn($ev) => $ev->grades->whereNotNull('score')->count());
            @endphp
            <!-- KPIs Strip -->
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-white/15">
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Évaluations de la Filière</span>
                    <span class="text-xl font-black">{{ $evaluations->count() }} épreuve(s)</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Étudiants Inscrits</span>
                    <span class="text-xl font-black">{{ $registrations->count() }} étudiant(s)</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Total Coefficients</span>
                    <span class="text-xl font-black">{{ $evaluations->sum('coefficient') }}</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Notes Saisies</span>
                    <span class="text-xl font-black text-emerald-300">{{ $totalGradesCount }} note(s)</span>
                </div>
            </div>
        </div>

        <!-- BARRE DE SÉLECTION DE LA FILIÈRE & DES ÉPREUVES -->
        <div class="bg-white p-5 rounded-[24px] border border-slate-200 shadow-sm space-y-4">
            <form action="{{ route('admin.evaluations.index') }}" method="GET" id="evalFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end text-xs">
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">1. Choisir la Filière</label>
                    <select name="seminar_id" onchange="document.getElementById('evalFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        @foreach($seminars as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSeminarId == $sem->id ? 'selected' : '' }}>
                                {{ $sem->title }} ({{ $sem->departments->pluck('name')->join(', ') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">2. Choisir l'Évaluation à noter ({{ $evaluations->count() }} disponibles)</label>
                    <div class="flex flex-wrap items-center gap-2">
                        @forelse($evaluations as $ev)
                            <a href="{{ route('admin.evaluations.index', ['seminar_id' => $selectedSeminarId, 'evaluation_id' => $ev->id]) }}"
                               class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-2 {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'bg-[#4B6A95] text-white border-[#4B6A95] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'bg-white/20 text-white' : 'bg-blue-100 text-[#4B6A95]' }}">
                                    {{ $ev->type }} (Coef {{ $ev->coefficient }})
                                </span>
                                <span>{{ $ev->title }}</span>
                            </a>
                        @empty
                            <span class="text-xs text-slate-400 italic py-2">Aucune évaluation créée pour cette filière.</span>
                        @endforelse
                    </div>
                </div>
            </form>
        </div>

        <!-- ============================================================== -->
        <!-- ONGLET 1 : GRILLE PLEINE LARGEUR DE SAISIE DES NOTES -->
        <!-- ============================================================== -->
        <div x-show="activeTab === 'saisie'" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8">
            @if($activeEvaluation)
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-blue-50 text-[#4B6A95] border border-blue-200">
                                {{ strtoupper($activeEvaluation->type) }} • Coefficient {{ $activeEvaluation->coefficient }} • Barème / {{ number_format($activeEvaluation->max_score, 0) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $activeEvaluation->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ $activeEvaluation->is_published ? '● Notes restituées aux étudiants' : '● Brouillon (masqué aux étudiants)' }}
                            </span>
                        </div>
                        <h2 class="text-xl font-black text-slate-900">{{ $activeEvaluation->title }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Matière : <strong class="text-[#4B6A95]">{{ $activeEvaluation->topic?->name ?? 'Tronc commun' }}</strong> • Date de l'épreuve : <strong>{{ $activeEvaluation->evaluation_date->format('d/m/Y') }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <form action="{{ route('admin.evaluations.toggle-publish', $activeEvaluation->id) }}" method="POST" class="m-0">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold hover:bg-slate-50 transition cursor-pointer">
                                {{ $activeEvaluation->is_published ? 'Masquer aux étudiants' : 'Publier / Restituer' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.evaluations.destroy', $activeEvaluation->id) }}" method="POST" onsubmit="return confirm('Supprimer cette évaluation et ses notes ?');" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer" title="Supprimer l'évaluation">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                @if($registrations->count() > 0)
                    <form action="{{ route('admin.evaluations.grades.store', $activeEvaluation->id) }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-900 text-white uppercase text-[10px] font-black tracking-wider">
                                    <tr>
                                        <th class="py-3.5 px-5 w-14">#</th>
                                        <th class="py-3.5 px-5">Étudiant & Matricule</th>
                                        <th class="py-3.5 px-5">Matière / Module inscrit</th>
                                        <th class="py-3.5 px-5 w-44 text-center">Note / {{ number_format($activeEvaluation->max_score, 0) }}</th>
                                        <th class="py-3.5 px-5">Appréciation Pédagogique</th>
                                        <th class="py-3.5 px-5 text-right">Bulletin Individuel</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($registrations as $idx => $reg)
                                        @php
                                            $existingGrade = $activeEvaluation->grades->firstWhere('registration_id', $reg->id);
                                        @endphp
                                        <tr class="hover:bg-slate-50/90 transition">
                                            <td class="py-3.5 px-5 font-black text-slate-400">{{ $idx + 1 }}</td>
                                            <td class="py-3.5 px-5">
                                                <div class="font-bold text-slate-900 text-sm">{{ $reg->user->name }}</div>
                                                <div class="text-[11px] text-slate-400">
                                                    {{ $reg->user->candidateProfile?->matricule ?? 'ETU-' . $reg->id }} • {{ $reg->user->email }}
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-5 font-semibold text-slate-700">
                                                {{ $reg->topic?->name ?? 'Tronc commun' }}
                                            </td>
                                            <td class="py-3.5 px-5 text-center">
                                                <input type="number" step="0.25" min="0" max="{{ $activeEvaluation->max_score }}"
                                                       name="scores[{{ $reg->id }}]"
                                                       value="{{ $existingGrade?->score }}"
                                                       placeholder="0 - {{ number_format($activeEvaluation->max_score, 0) }}"
                                                       class="w-28 px-3 py-2 bg-slate-50 border-2 border-slate-300 rounded-xl text-center font-black text-sm text-slate-900 focus:bg-white focus:border-[#4B6A95] outline-none">
                                            </td>
                                            <td class="py-3.5 px-5">
                                                <input type="text"
                                                       name="appreciations[{{ $reg->id }}]"
                                                       value="{{ $existingGrade?->appreciation }}"
                                                       placeholder="Calcul automatique (Très Bien, Bien, Passable...)"
                                                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-medium focus:bg-white focus:border-[#4B6A95] outline-none">
                                            </td>
                                            <td class="py-3.5 px-5 text-right">
                                                <a href="{{ route('admin.evaluations.report-card.pdf', $reg->id) }}" data-no-loader
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#4B6A95] text-slate-700 hover:text-white text-xs font-bold transition">
                                                    <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                                                    <span>Bulletin</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" {{ $activeEvaluation->is_published ? 'checked' : '' }} class="w-4 h-4 rounded text-[#4B6A95]">
                                <span>Restituer immédiatement ces notes dans le portail personnel des étudiants</span>
                            </label>
                            <button type="submit" class="px-8 py-3 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-black text-xs rounded-full shadow-md transition cursor-pointer flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                <span>Enregistrer toutes les notes de l'évaluation</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="py-12 text-center text-slate-400 text-xs">
                        Aucun étudiant inscrit dans cette filière.
                    </div>
                @endif
            @else
                <div class="py-16 text-center text-slate-400">
                    <span class="material-symbols-outlined text-5xl opacity-30 mb-2">grading</span>
                    <p class="text-base font-bold text-slate-700">Aucune évaluation créée pour cette filière.</p>
                    <p class="text-xs mt-1">Les évaluations (devoirs, TP, examens) sont créées par les Formateurs et les Chefs de Département.</p>
                    @if(auth()->user() && auth()->user()->isDepartmentHead())
                        <button type="button" @click="createModalOpen = true" class="mt-4 px-6 py-2.5 bg-[#4B6A95] text-white rounded-full text-xs font-bold cursor-pointer">
                            + Créer une première évaluation
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- ============================================================== -->
        <!-- ONGLET 2 : TABLEAU RÉCAPITULATIF DES MOYENNES & BULLETINS PDF -->
        <!-- ============================================================== -->
        <div x-show="activeTab === 'bulletins'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="pb-5 border-b border-slate-100 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Procès-Verbal des Moyennes & Restitution des Bulletins</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Synthèse complète de toutes les évaluations de la filière avec moyenne générale pondérée (/20) et mention.</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-white uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Étudiant</th>
                            @foreach($evaluations as $ev)
                                <th class="py-3.5 px-4 text-center">
                                    {{ Str::limit($ev->title, 18) }}<br>
                                    <span class="text-[9px] text-blue-200">({{ strtoupper($ev->type) }} - Coef {{ $ev->coefficient }})</span>
                                </th>
                            @endforeach
                            <th class="py-3.5 px-4 text-center">Moyenne Générale / 20</th>
                            <th class="py-3.5 px-4 text-center">Mention</th>
                            <th class="py-3.5 px-4 text-right">Bulletin Officiel PDF</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
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
                                $mention = match (true) {
                                    $avg === null => '-',
                                    $avg >= 16 => 'Très Bien',
                                    $avg >= 14 => 'Bien',
                                    $avg >= 12 => 'Assez Bien',
                                    $avg >= 10 => 'Passable',
                                    default => 'Insuffisant',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $reg->topic?->name ?? 'Tronc commun' }}</div>
                                </td>
                                @foreach($evaluations as $ev)
                                    @php
                                        $g = $reg->grades->firstWhere('evaluation_id', $ev->id);
                                    @endphp
                                    <td class="py-3.5 px-4 text-center font-bold">
                                        @if($g && $g->score !== null)
                                            <span class="text-slate-900">{{ number_format($g->score, 2) }}</span>
                                            <span class="text-[10px] text-slate-400">/{{ number_format($ev->max_score, 0) }}</span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="py-3.5 px-4 text-center">
                                    @if($avg !== null)
                                        <span class="px-3 py-1 rounded-full font-black text-xs {{ $avg >= 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ number_format($avg, 2) }} / 20
                                        </span>
                                    @else
                                        <span class="text-slate-400">Non noté</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center font-extrabold text-slate-700">{{ $mention }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.evaluations.report-card.pdf', $reg->id) }}" data-no-loader
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold transition shadow-2xs">
                                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                        <span>Télécharger Bulletin</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + $evaluations->count() }}" class="py-10 text-center text-slate-400">Aucun étudiant inscrit dans cette filière.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Créer une Évaluation -->
    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Nouvelle Évaluation Pédagogique</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
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
                            <option value="">-- Direction des Études / Jury --</option>
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
                        Créer et saisir les notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
