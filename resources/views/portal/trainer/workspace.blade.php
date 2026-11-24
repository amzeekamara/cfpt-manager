@extends('portal.layout')
@section('title', 'Espace Cours & Évaluations (Style OpenClassrooms) — Formateur')

@section('content')
<div x-data="{
    activeTab: '{{ request('tab', request()->filled('evaluation_id') ? 'evaluations' : 'chapters') }}',
    chapterModalOpen: false,
    evalModalOpen: false,
    selectedSeminarId: '{{ $activeSeminar?->id ?? '' }}',
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
}" class="space-y-6">

    <!-- HERO BANNER STYLE OPENCLASSROOMS -->
    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] p-6 sm:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-wider text-blue-100 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Studio Pédagogique Interactif • Style OpenClassrooms</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                    {{ $activeSeminar?->title ?? 'Mes Cours & Évaluations' }}
                </h1>
                <p class="text-blue-100/85 text-xs sm:text-sm mt-1 max-w-2xl">
                    Publiez vos chapitres de cours structurés, créez des évaluations et projets pratiques, réceptionnez et corrigez les livrables des étudiants, prenez les présences et répondez à leurs questions en direct.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                @if($activeSeminar)
                    <a href="{{ route('portal.trainer.attendances', ['seminar_id' => $activeSeminar->id]) }}"
                       class="px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white rounded-full text-xs font-extrabold flex items-center gap-2 transition">
                        <i data-lucide="check-square" class="w-4 h-4 text-emerald-300"></i>
                        <span>Prendre les Présences du Cours</span>
                    </a>
                @endif
                <button type="button" @click="chapterModalOpen = true"
                        class="px-4 py-2.5 bg-white text-slate-900 hover:bg-slate-100 rounded-full text-xs font-extrabold flex items-center gap-1.5 shadow-sm transition cursor-pointer">
                    <i data-lucide="book-open" class="w-4 h-4 text-[#4B6A95]"></i>
                    <span>+ Nouveau Chapitre</span>
                </button>
                <button type="button" @click="evalModalOpen = true"
                        class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full text-xs font-extrabold flex items-center gap-1.5 shadow-md transition cursor-pointer">
                    <i data-lucide="award" class="w-4 h-4"></i>
                    <span>+ Nouvelle Évaluation</span>
                </button>
            </div>
        </div>

        <!-- Sélecteur rapide de Filière / Cours du formateur -->
        <div class="relative z-10 mt-6 pt-5 border-t border-white/15 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-200 mr-1">Mes Filières :</span>
                @foreach($trainer->seminars as $sem)
                    <a href="{{ route('portal.trainer.workspace', ['seminar_id' => $sem->id]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ ($activeSeminar && $activeSeminar->id == $sem->id) ? 'bg-white text-slate-900 shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $sem->title }}
                    </a>
                @endforeach
            </div>

            <!-- Onglets de l'espace OpenClassrooms -->
            <div class="bg-slate-900/40 p-1 rounded-full border border-white/15 flex items-center text-xs font-bold">
                <button type="button" @click="activeTab = 'chapters'" :class="activeTab === 'chapters' ? 'bg-white text-slate-900' : 'text-white/80 hover:text-white'" class="px-4 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                    <span>1. Chapitres du Cours ({{ $chapters->count() }})</span>
                </button>
                <button type="button" @click="activeTab = 'evaluations'" :class="activeTab === 'evaluations' ? 'bg-white text-slate-900' : 'text-white/80 hover:text-white'" class="px-4 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="file-check-2" class="w-3.5 h-3.5"></i>
                    <span>2. Évaluations & Livrables ({{ $evaluations->count() }})</span>
                </button>
                <button type="button" @click="activeTab = 'forum'" :class="activeTab === 'forum' ? 'bg-white text-slate-900' : 'text-white/80 hover:text-white'" class="px-4 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                    <span>3. Forum Q&R ({{ $discussions->count() }})</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- LAYOUT PRINCIPAL STYLE OPENCLASSROOMS (SIDEBAR SOMMAIRE + CONTENU) -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- SIDEBAR GAUCHE (4 COLS) : PLAN DU COURS & ÉVALUATIONS -->
        <div class="lg:col-span-4 space-y-5">
            <!-- Sommaire des Chapitres -->
            <div class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i data-lucide="list-Filter" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Sommaire du Cours ({{ $chapters->count() }})</span>
                    </h3>
                    <button type="button" @click="chapterModalOpen = true" class="text-xs font-bold text-[#4B6A95] hover:underline cursor-pointer">+ Ajouter</button>
                </div>

                <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                    @forelse($chapters as $index => $chap)
                        <a href="{{ route('portal.trainer.workspace', ['seminar_id' => $activeSeminar->id, 'chapter_id' => $chap->id, 'tab' => 'chapters']) }}"
                           class="block p-3 rounded-2xl border transition {{ ($activeChapter && $activeChapter->id == $chap->id && request('tab', 'chapters') === 'chapters') ? 'bg-[#4B6A95] text-white border-[#4B6A95] shadow-xs' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-800 border-slate-200/80' }}">
                            <div class="flex items-center justify-between text-[10px] font-black uppercase mb-0.5 {{ ($activeChapter && $activeChapter->id == $chap->id && request('tab', 'chapters') === 'chapters') ? 'text-blue-200' : 'text-[#4B6A95]' }}">
                                <span>Chapitre {{ $index + 1 }} • {{ $chap->topic?->name ?? 'Tronc commun' }}</span>
                                <span>{{ $chap->duration_minutes }} min</span>
                            </div>
                            <div class="text-xs font-bold leading-snug">{{ $chap->title }}</div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 italic py-4 text-center">Aucun chapitre publié dans ce cours.</p>
                    @endforelse
                </div>
            </div>

            <!-- Liste des Évaluations & Projets -->
            <div class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4 text-emerald-600"></i>
                        <span>Évaluations & Projets ({{ $evaluations->count() }})</span>
                    </h3>
                    <button type="button" @click="evalModalOpen = true" class="text-xs font-bold text-emerald-600 hover:underline cursor-pointer">+ Créer</button>
                </div>

                <div class="space-y-2 max-h-[280px] overflow-y-auto pr-1">
                    @forelse($evaluations as $ev)
                        <a href="{{ route('portal.trainer.workspace', ['seminar_id' => $activeSeminar->id, 'evaluation_id' => $ev->id, 'tab' => 'evaluations']) }}"
                           class="block p-3 rounded-2xl border transition {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-800 border-slate-200/80' }}">
                            <div class="flex items-center justify-between text-[10px] font-black uppercase mb-0.5 {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'text-emerald-300' : 'text-emerald-700' }}">
                                <span>{{ strtoupper($ev->type) }} • Coef {{ $ev->coefficient }}</span>
                                <span>{{ $ev->submissions->count() }} livrable(s)</span>
                            </div>
                            <div class="text-xs font-bold leading-snug">{{ $ev->title }}</div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 italic py-4 text-center">Aucune évaluation créée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ZONE CENTRALE INTERACTIVE (8 COLS) -->
        <div class="lg:col-span-8">

            <!-- ============================================================== -->
            <!-- ONGLET 1 : LECTEUR & ÉDITEUR DE CHAPITRE DE COURS -->
            <!-- ============================================================== -->
            <div x-show="activeTab === 'chapters'" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                @if($activeChapter)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-[#4B6A95] border border-blue-200 text-[11px] font-black uppercase">
                                    {{ $activeChapter->topic?->name ?? 'Tronc commun' }}
                                </span>
                                <span class="text-xs text-slate-400 font-bold">• Durée estimée : {{ $activeChapter->duration_minutes }} min</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $activeChapter->title }}</h2>
                        </div>
                        <form action="{{ route('portal.trainer.chapters.destroy', $activeChapter->id) }}" method="POST" onsubmit="return confirm('Supprimer ce chapitre ?');" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white text-xs font-bold transition cursor-pointer">
                                Supprimer le chapitre
                            </button>
                        </form>
                    </div>

                    @if($activeChapter->video_url)
                        <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-amber-300">
                                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                                </div>
                                <div class="text-xs">
                                    <span class="text-[10px] font-black uppercase text-blue-200 block">Ressource Vidéo / Capsule Interactive</span>
                                    <span class="font-bold">{{ $activeChapter->video_url }}</span>
                                </div>
                            </div>
                            <a href="{{ $activeChapter->video_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-white text-slate-900 text-xs font-extrabold hover:bg-blue-50 transition">
                                Ouvrir la capsule ↗
                            </a>
                        </div>
                    @endif

                    <!-- Contenu du Chapitre style OpenClassrooms -->
                    <div class="prose max-w-none bg-slate-50/70 rounded-2xl p-6 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">{{ $activeChapter->content }}</div>

                    @if($activeChapter->attachment_path)
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-text" class="w-5 h-5 text-[#4B6A95]"></i>
                                <div class="text-xs">
                                    <span class="font-extrabold text-slate-900 block">Support de cours téléchargeable joint à ce chapitre</span>
                                    <span class="text-slate-500">Accessible par tous les étudiants inscrits</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $activeChapter->attachment_path) }}" target="_blank" download class="px-4 py-2 rounded-xl bg-[#4B6A95] text-white text-xs font-bold">
                                Télécharger le support
                            </a>
                        </div>
                    @endif
                @else
                    <div class="py-16 text-center text-slate-400">
                        <i data-lucide="book-open" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                        <p class="text-base font-bold text-slate-700">Aucun chapitre de cours publié pour cette filière.</p>
                        <p class="text-xs mt-1">Créez votre premier chapitre structuré (leçon, schéma, vidéo ou PDF) pour vos étudiants.</p>
                        <button type="button" @click="chapterModalOpen = true" class="mt-4 px-6 py-2.5 bg-[#4B6A95] text-white rounded-full text-xs font-bold cursor-pointer">
                            + Publier un premier chapitre
                        </button>
                    </div>
                @endif
            </div>

            <!-- ============================================================== -->
            <!-- ONGLET 2 : ÉVALUATIONS, LIVRABLES ÉTUDIANTS & NOTATION -->
            <!-- ============================================================== -->
            <div x-show="activeTab === 'evaluations'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                @if($activeEvaluation)
                    <div class="pb-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black uppercase">
                                    {{ strtoupper($activeEvaluation->type) }} • Coefficient {{ $activeEvaluation->coefficient }} • Barème / {{ number_format($activeEvaluation->max_score, 0) }}
                                </span>
                                <span class="text-xs text-slate-500 font-bold">Date : {{ $activeEvaluation->evaluation_date->format('d/m/Y') }}</span>
                            </div>
                            <h2 class="text-xl font-black text-slate-900">{{ $activeEvaluation->title }}</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Matière : <strong class="text-[#4B6A95]">{{ $activeEvaluation->topic?->name ?? 'Tronc commun' }}</strong></p>
                        </div>
                    </div>

                    <!-- Consignes & Énoncé de l'évaluation -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#4B6A95] block">Consignes & Énoncé Pédagogique pour les Étudiants</span>
                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $activeEvaluation->instructions ?: 'Réalisez le travail demandé et déposez votre livrable ou participez à l\'évaluation en salle.' }}</p>
                        @if($activeEvaluation->attachment_path)
                            <div class="pt-2">
                                <a href="{{ asset('storage/' . $activeEvaluation->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#4B6A95] hover:underline">
                                    <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                                    <span>Télécharger le sujet / énoncé joint</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Livrables déposés par les étudiants & Notation interactive -->
                    <div>
                        <h3 class="text-sm font-black text-slate-900 mb-3 flex items-center justify-between">
                            <span>Correction des Livrables & Saisie des Notes ({{ $registrations->count() }} étudiants)</span>
                            <span class="text-xs font-bold text-emerald-600">{{ $activeEvaluation->submissions->count() }} livrable(s) remis en ligne</span>
                        </h3>

                        <form action="{{ route('portal.trainer.evaluations.grades.store', $activeEvaluation->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-900 text-white uppercase text-[10px] font-black">
                                        <tr>
                                            <th class="py-3 px-4">Étudiant</th>
                                            <th class="py-3 px-4">Travail / Livrable Étudiant</th>
                                            <th class="py-3 px-4 w-32 text-center">Note / {{ number_format($activeEvaluation->max_score, 0) }}</th>
                                            <th class="py-3 px-4">Feedback & Appréciation du Formateur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($registrations as $reg)
                                            @php
                                                $grade = $activeEvaluation->grades->firstWhere('registration_id', $reg->id);
                                                $submission = $activeEvaluation->submissions->firstWhere('registration_id', $reg->id);
                                            @endphp
                                            <tr class="hover:bg-slate-50/90 align-top">
                                                <td class="py-3.5 px-4">
                                                    <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400">{{ $reg->user->candidateProfile?->matricule ?? $reg->user->email }}</div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    @if($submission)
                                                        <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200 space-y-1">
                                                            <div class="text-[10px] font-black text-emerald-800 uppercase">
                                                                ✓ Remis le {{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '' }}
                                                            </div>
                                                            @if($submission->student_comment)
                                                                <p class="text-[11px] text-slate-700 italic">« {{ $submission->student_comment }} »</p>
                                                            @endif
                                                            @if($submission->file_path)
                                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4B6A95] hover:underline">
                                                                    <i data-lucide="download" class="w-3 h-3"></i>
                                                                    <span>Télécharger le livrable</span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-[11px] text-slate-400 italic">Aucun fichier déposé en ligne</span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    <input type="number" step="0.25" min="0" max="{{ $activeEvaluation->max_score }}"
                                                           name="scores[{{ $reg->id }}]"
                                                           value="{{ $grade?->score }}"
                                                           placeholder="0 - {{ number_format($activeEvaluation->max_score, 0) }}"
                                                           class="w-24 px-2.5 py-2 bg-slate-50 border-2 border-slate-300 rounded-xl text-center font-black text-slate-900 focus:bg-white focus:border-[#4B6A95] outline-none">
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <input type="text"
                                                           name="appreciations[{{ $reg->id }}]"
                                                           value="{{ $grade?->appreciation }}"
                                                           placeholder="Commentaire de correction pour l'étudiant..."
                                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:bg-white focus:border-[#4B6A95] outline-none">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-extrabold shadow-sm transition cursor-pointer">
                                    Enregistrer et restituer les notes & feedbacks aux étudiants
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="py-16 text-center text-slate-400">
                        <i data-lucide="award" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                        <p class="text-base font-bold text-slate-700">Aucune évaluation sélectionnée.</p>
                        <button type="button" @click="evalModalOpen = true" class="mt-4 px-6 py-2.5 bg-emerald-600 text-white rounded-full text-xs font-bold cursor-pointer">
                            + Créer une évaluation
                        </button>
                    </div>
                @endif
            </div>

            <!-- ============================================================== -->
            <!-- ONGLET 3 : FORUM INTERACTIF QUESTIONS / RÉPONSES DU COURS -->
            <!-- ============================================================== -->
            <div x-show="activeTab === 'forum'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="pb-4 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-900">Forum d'Entraide & Questions/Réponses du Cours</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Interagissez directement avec vos étudiants sur les chapitres du cours et les évaluations.</p>
                </div>

                @if($activeSeminar)
                    <form action="{{ route('portal.trainer.discussions.store') }}" method="POST" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        @csrf
                        <input type="hidden" name="seminar_id" value="{{ $activeSeminar->id }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Matière concernée (optionnel)</label>
                                <select name="topic_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
                                    <option value="">-- Général / Tout le cours --</option>
                                    @foreach($activeSeminar->topics as $top)
                                        <option value="{{ $top->id }}">{{ $top->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Évaluation liée (optionnel)</label>
                                <select name="evaluation_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
                                    <option value="">-- Discussion sur le cours --</option>
                                    @foreach($evaluations as $ev)
                                        <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <textarea name="body" rows="3" required placeholder="Publier une explication, une consigne ou répondre aux questions des étudiants..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 outline-none focus:border-[#4B6A95]"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-extrabold cursor-pointer">
                                Publier dans le cours
                            </button>
                        </div>
                    </form>
                @endif

                <div class="space-y-3">
                    @forelse($discussions as $disc)
                        <div class="p-4 rounded-2xl border {{ $disc->author_role === 'Formateur' ? 'bg-blue-50/50 border-blue-200' : 'bg-white border-slate-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $disc->author_role === 'Formateur' ? 'bg-[#4B6A95] text-white' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $disc->author_role }}
                                    </span>
                                    <strong class="text-xs font-black text-slate-900">{{ $disc->author_name }}</strong>
                                    @if($disc->topic)
                                        <span class="text-[11px] font-bold text-[#4B6A95]">• {{ $disc->topic->name }}</span>
                                    @endif
                                    @if($disc->evaluation)
                                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold">
                                            Éval : {{ $disc->evaluation->title }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400">{{ $disc->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $disc->body }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-8">Aucune discussion dans ce cours pour le moment.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL 1 : PUBLIER UN NOUVEAU CHAPITRE DE COURS -->
    <div x-show="chapterModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="chapterModalOpen = false" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Publier un Chapitre de Cours (Style OpenClassrooms)</h3>
                <button type="button" @click="chapterModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
            </div>
            <form action="{{ route('portal.trainer.chapters.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Filière <span class="text-rose-500">*</span></label>
                        <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-semibold text-slate-800">
                            @foreach($trainer->seminars as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Matière / Module</label>
                        <select name="topic_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-semibold text-slate-800">
                            <option value="">-- Tronc commun --</option>
                            <template x-for="top in currentTopics" :key="top.id">
                                <option :value="top.id" x-text="top.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Titre du chapitre <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Ex: Chapitre 1 — Architecture des réseaux VLAN & Routage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durée (minutes)</label>
                        <input type="number" name="duration_minutes" value="45" min="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contenu pédagogique du chapitre <span class="text-rose-500">*</span></label>
                    <textarea name="content" rows="6" required placeholder="Rédigez le contenu du cours, les objectifs pédagogiques, les étapes techniques et les exercices d'application..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lien Vidéo / Capsule (optionnel)</label>
                        <input type="url" name="video_url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-slate-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Support PDF / Document joint</label>
                        <input type="file" name="attachment" class="w-full text-xs text-slate-500">
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="chapterModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#4B6A95] text-white font-bold cursor-pointer">Publier le chapitre</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2 : CRÉER UNE ÉVALUATION AVEC CONSIGNES & SUJET JOINT -->
    <div x-show="evalModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="evalModalOpen = false" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Créer une Évaluation / Projet Pratique</h3>
                <button type="button" @click="evalModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
            </div>
            <form action="{{ route('portal.trainer.evaluations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="from_workspace" value="1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Filière <span class="text-rose-500">*</span></label>
                        <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-semibold text-slate-800">
                            @foreach($trainer->seminars as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Matière / Module</label>
                        <select name="topic_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-semibold text-slate-800">
                            <option value="">-- Tronc commun --</option>
                            <template x-for="top in currentTopics" :key="top.id">
                                <option :value="top.id" x-text="top.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Titre de l'évaluation <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Ex: Projet Pratique — Configuration d'un automate Siemens S7" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Consignes détaillées & Livrables attendus</label>
                    <textarea name="instructions" rows="4" placeholder="Expliquez aux étudiants le travail à réaliser, les critères d'évaluation et le format du livrable attendu..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800"></textarea>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Type</label>
                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-800">
                            <option value="devoir">Devoir</option>
                            <option value="tp">TP / Projet</option>
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
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fichier Sujet / Énoncé PDF (optionnel)</label>
                    <input type="file" name="attachment" class="w-full text-xs text-slate-500">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="evalModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-emerald-600 text-white font-bold cursor-pointer">Créer l'évaluation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
