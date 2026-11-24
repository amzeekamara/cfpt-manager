@extends('portal.layout')
@section('title', 'Mes Cours & Évaluations (Style OpenClassrooms) — Espace Étudiant')

@section('content')
<div x-data="{
    activeTab: '{{ request('tab', request()->filled('evaluation_id') ? 'evaluations' : 'chapters') }}'
}" class="space-y-6">

    <!-- HERO BANNER STYLE OPENCLASSROOMS ÉTUDIANT -->
    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] p-6 sm:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-wider text-blue-100 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Parcours d'Apprentissage Interactif • CFPT Sénégal-Japon</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                    {{ $registration?->seminar?->title ?? 'Ma Filière de Formation' }}
                </h1>
                <p class="text-blue-100/85 text-xs sm:text-sm mt-1 max-w-2xl">
                    Suivez les chapitres de cours publiés par vos formateurs, consultez les énoncés d'évaluations, déposez vos livrables de TP/Projets et posez vos questions dans le forum du cours.
                </p>
            </div>

            <!-- Switcher Onglets -->
            <div class="bg-slate-900/40 p-1.5 rounded-full border border-white/15 flex flex-wrap items-center text-xs font-bold shrink-0">
                <button type="button" @click="activeTab = 'chapters'" :class="activeTab === 'chapters' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-4 py-2 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                    <span>1. Chapitres du Cours ({{ $chapters->count() }})</span>
                </button>
                <button type="button" @click="activeTab = 'evaluations'" :class="activeTab === 'evaluations' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-4 py-2 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    <span>2. Évaluations & Dépôt Livrables ({{ $evaluations->count() }})</span>
                </button>
                <button type="button" @click="activeTab = 'forum'" :class="activeTab === 'forum' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-4 py-2 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>3. Questions / Réponses ({{ $discussions->count() }})</span>
                </button>
            </div>
        </div>
    </div>

    @if($registration && $registration->seminar)
        <!-- ============================================================== -->
        <!-- LAYOUT STYLE OPENCLASSROOMS (SIDEBAR SOMMAIRE + ZONE CENTRALE) -->
        <!-- ============================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- SIDEBAR GAUCHE (4 COLS) : SOMMAIRE DES CHAPITRES & ÉVALUATIONS -->
            <div class="lg:col-span-4 space-y-5">
                <!-- Sommaire des Chapitres -->
                <div class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <i data-lucide="book-open" class="w-4 h-4 text-[#4B6A95]"></i>
                            <span>Chapitres du Cours ({{ $chapters->count() }})</span>
                        </h3>
                    </div>

                    <div class="space-y-2 max-h-[320px] overflow-y-auto pr-1">
                        @forelse($chapters as $index => $chap)
                            <a href="{{ route('portal.candidate.workspace', ['chapter_id' => $chap->id, 'tab' => 'chapters']) }}"
                               class="block p-3.5 rounded-2xl border transition {{ ($activeChapter && $activeChapter->id == $chap->id && request('tab', 'chapters') === 'chapters') ? 'bg-[#4B6A95] text-white border-[#4B6A95] shadow-xs' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-800 border-slate-200/80' }}">
                                <div class="flex items-center justify-between text-[10px] font-black uppercase mb-0.5 {{ ($activeChapter && $activeChapter->id == $chap->id && request('tab', 'chapters') === 'chapters') ? 'text-blue-200' : 'text-[#4B6A95]' }}">
                                    <span>Chapitre {{ $index + 1 }} • {{ $chap->topic?->name ?? 'Tronc commun' }}</span>
                                    <span>{{ $chap->duration_minutes }} min</span>
                                </div>
                                <div class="text-xs font-bold leading-snug">{{ $chap->title }}</div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 italic py-4 text-center">Aucun chapitre publié pour le moment.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Évaluations & Projets de la Filière -->
                <div class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <i data-lucide="award" class="w-4 h-4 text-emerald-600"></i>
                            <span>Mes Évaluations & TP ({{ $evaluations->count() }})</span>
                        </h3>
                    </div>

                    <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($evaluations as $ev)
                            @php
                                $myGrade = $ev->grades->firstWhere('registration_id', $registration->id);
                                $mySub = $ev->submissions->firstWhere('registration_id', $registration->id);
                            @endphp
                            <a href="{{ route('portal.candidate.workspace', ['evaluation_id' => $ev->id, 'tab' => 'evaluations']) }}"
                               class="block p-3.5 rounded-2xl border transition {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-800 border-slate-200/80' }}">
                                <div class="flex items-center justify-between text-[10px] font-black uppercase mb-1 {{ ($activeEvaluation && $activeEvaluation->id == $ev->id) ? 'text-emerald-300' : 'text-emerald-700' }}">
                                    <span>{{ strtoupper($ev->type) }} • Coef {{ $ev->coefficient }}</span>
                                    @if($myGrade && $myGrade->score !== null)
                                        <span class="px-2 py-0.5 rounded bg-emerald-500 text-white">{{ number_format($myGrade->score, 1) }}/{{ number_format($ev->max_score, 0) }}</span>
                                    @elseif($mySub)
                                        <span class="px-2 py-0.5 rounded bg-blue-500 text-white">Livrable remis</span>
                                    @else
                                        <span>À réaliser</span>
                                    @endif
                                </div>
                                <div class="text-xs font-bold leading-snug">{{ $ev->title }}</div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 italic py-4 text-center">Aucune évaluation programmée.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ZONE CENTRALE (8 COLS) -->
            <div class="lg:col-span-8">

                <!-- ============================================================== -->
                <!-- ONGLET 1 : LECTURE DU CHAPITRE DE COURS -->
                <!-- ============================================================== -->
                <div x-show="activeTab === 'chapters'" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    @if($activeChapter)
                        <div class="pb-5 border-b border-slate-100">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-[#4B6A95] border border-blue-200 text-[11px] font-black uppercase">
                                    {{ $activeChapter->topic?->name ?? 'Tronc commun' }}
                                </span>
                                <span class="text-xs text-slate-500 font-bold">
                                    Formateur : <strong class="text-slate-800">{{ $activeChapter->trainer?->name ?? 'Équipe Pédagogique CFPT' }}</strong>
                                </span>
                                <span class="text-xs text-slate-400">• {{ $activeChapter->duration_minutes }} min de lecture</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $activeChapter->title }}</h2>
                        </div>

                        @if($activeChapter->video_url)
                            <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-amber-300">
                                        <i data-lucide="play-circle" class="w-5 h-5"></i>
                                    </div>
                                    <div class="text-xs">
                                        <span class="text-[10px] font-black uppercase text-blue-200 block">Capsule Vidéo / Démonstration Technique</span>
                                        <span class="font-bold">Support multimédia associé à ce chapitre</span>
                                    </div>
                                </div>
                                <a href="{{ $activeChapter->video_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-white text-slate-900 text-xs font-extrabold hover:bg-blue-50 transition">
                                    Regarder la vidéo ↗
                                </a>
                            </div>
                        @endif

                        <div class="prose max-w-none bg-slate-50/70 rounded-2xl p-6 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">{{ $activeChapter->content }}</div>

                        @if($activeChapter->attachment_path)
                            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="file-text" class="w-5 h-5 text-[#4B6A95]"></i>
                                    <div class="text-xs">
                                        <span class="font-extrabold text-slate-900 block">Support PDF officiel du chapitre</span>
                                        <span class="text-slate-500">Téléchargez ce document pour vos révisions et travaux pratiques</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $activeChapter->attachment_path) }}" target="_blank" download class="px-4 py-2 rounded-xl bg-[#4B6A95] text-white text-xs font-bold">
                                    Télécharger le PDF
                                </a>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-semibold">Une question sur ce chapitre ?</span>
                            <button type="button" @click="activeTab = 'forum'" class="px-4 py-2 rounded-full bg-slate-100 hover:bg-[#4B6A95] text-slate-700 hover:text-white text-xs font-bold transition cursor-pointer">
                                Poser une question au formateur →
                            </button>
                        </div>
                    @else
                        <div class="py-16 text-center text-slate-400">
                            <i data-lucide="book-open" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                            <p class="text-base font-bold text-slate-700">Les chapitres de cours seront bientôt publiés par vos formateurs.</p>
                        </div>
                    @endif
                </div>

                <!-- ============================================================== -->
                <!-- ONGLET 2 : ÉVALUATION, DÉPÔT DE LIVRABLE & FEEDBACK FORMATEUR -->
                <!-- ============================================================== -->
                <div x-show="activeTab === 'evaluations'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    @if($activeEvaluation)
                        @php
                            $myGrade = $activeEvaluation->grades->firstWhere('registration_id', $registration->id);
                            $mySub = $activeEvaluation->submissions->firstWhere('registration_id', $registration->id);
                        @endphp
                        <div class="pb-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black uppercase">
                                        {{ strtoupper($activeEvaluation->type) }} • Coefficient {{ $activeEvaluation->coefficient }} • Barème / {{ number_format($activeEvaluation->max_score, 0) }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-bold">Date : {{ $activeEvaluation->evaluation_date->format('d/m/Y') }}</span>
                                </div>
                                <h2 class="text-xl font-black text-slate-900">{{ $activeEvaluation->title }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Matière : <strong class="text-[#4B6A95]">{{ $activeEvaluation->topic?->name ?? 'Tronc commun' }}</strong> • Formateur : <strong>{{ $activeEvaluation->trainer?->name ?? 'Formateur CFPT' }}</strong>
                                </p>
                            </div>

                            <!-- Affichage de la Note obtenue si déjà corrigée -->
                            @if($myGrade && $myGrade->score !== null)
                                <div class="p-4 rounded-2xl bg-slate-900 text-white text-center shrink-0">
                                    <span class="text-[10px] font-black uppercase text-emerald-300 block">Note Obtenue</span>
                                    <span class="text-2xl font-black">{{ number_format($myGrade->score, 2) }} / {{ number_format($activeEvaluation->max_score, 0) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Énoncé & Consignes du formateur -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#4B6A95] block">Énoncé & Consignes de l'Évaluation</span>
                            <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $activeEvaluation->instructions ?: 'Suivez les instructions données par votre formateur et déposez votre compte-rendu ou livrable ci-dessous.' }}</p>
                            @if($activeEvaluation->attachment_path)
                                <div class="pt-2">
                                    <a href="{{ asset('storage/' . $activeEvaluation->attachment_path) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#4B6A95] text-white text-xs font-bold">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        <span>Télécharger le sujet / énoncé PDF</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Feedback / Appréciation du formateur -->
                        @if($myGrade && ($myGrade->appreciation || $mySub?->trainer_feedback))
                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 block">Correction & Appréciation du Formateur</span>
                                <p class="text-xs font-bold text-emerald-950">{{ $mySub?->trainer_feedback ?: $myGrade->appreciation }}</p>
                            </div>
                        @endif

                        <!-- Formulaire de dépôt de livrable / devoir par l'étudiant -->
                        <div class="p-5 rounded-2xl border-2 border-dashed border-slate-200 bg-white space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-black text-slate-900">Mon Espace de Remise de Livrable (Devoir / Compte-rendu TP)</h3>
                                @if($mySub)
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black">
                                        ✓ Remis le {{ $mySub->submitted_at?->format('d/m/Y à H:i') }}
                                    </span>
                                @endif
                            </div>

                            <form action="{{ route('portal.candidate.evaluations.submit', $activeEvaluation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                                @csrf
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Commentaire ou réponse écrite pour le formateur</label>
                                    <textarea name="student_comment" rows="3" placeholder="Présentez votre travail, vos résultats ou vos remarques..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95]">{{ old('student_comment', $mySub?->student_comment) }}</textarea>
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Fichier Livrable (PDF, ZIP, Word, Image — Max 10 Mo)</label>
                                    <input type="file" name="livrable" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#4B6A95]">
                                    @if($mySub && $mySub->file_path)
                                        <p class="mt-1.5 text-[11px] text-emerald-700 font-bold">
                                            Fichier actuel déposé : <a href="{{ asset('storage/' . $mySub->file_path) }}" target="_blank" class="underline">Consulter mon fichier envoyé</a>
                                        </p>
                                    @endif
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-extrabold shadow-xs transition cursor-pointer">
                                        {{ $mySub ? 'Mettre à jour mon livrable' : 'Envoyer mon livrable au formateur' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="py-16 text-center text-slate-400">
                            <i data-lucide="award" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                            <p class="text-base font-bold text-slate-700">Aucune évaluation publiée pour le moment.</p>
                        </div>
                    @endif
                </div>

                <!-- ============================================================== -->
                <!-- ONGLET 3 : FORUM INTERACTIF QUESTIONS / RÉPONSES -->
                <!-- ============================================================== -->
                <div x-show="activeTab === 'forum'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-900">Poser une Question au Formateur & Forum du Cours</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Échangez avec votre formateur et les étudiants de votre filière sur les cours et les évaluations.</p>
                    </div>

                    <form action="{{ route('portal.candidate.discussions.store') }}" method="POST" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        @csrf
                        <input type="hidden" name="seminar_id" value="{{ $registration->seminar_id }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Matière concernée</label>
                                <select name="topic_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
                                    <option value="">-- Général / Tout le cours --</option>
                                    @foreach($registration->seminar->topics as $top)
                                        <option value="{{ $top->id }}">{{ $top->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Évaluation concernée (optionnel)</label>
                                <select name="evaluation_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
                                    <option value="">-- Question sur le cours --</option>
                                    @foreach($evaluations as $ev)
                                        <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <textarea name="body" rows="3" required placeholder="Posez votre question technique ou demandez une précision sur une évaluation..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 outline-none focus:border-[#4B6A95]"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-[#4B6A95] hover:bg-[#354e70] text-white rounded-full text-xs font-extrabold cursor-pointer">
                                Publier ma question
                            </button>
                        </div>
                    </form>

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
                            <p class="text-xs text-slate-400 italic text-center py-8">Aucune question posée pour le moment. Soyez le premier à échanger avec le formateur !</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
@endsection
