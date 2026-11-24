@extends('layouts.admin')

@section('header_title', 'Formations Continues & Attestations')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    createModalOpen: false,
    enrollModalOpen: false
}">
    <div class="w-full max-w-[1600px] mx-auto space-y-6">

        <!-- HERO BANNER : FORMATIONS CONTINUES -->
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] p-6 sm:p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-full transition text-white shrink-0 mt-1">
                        <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-300/30 text-[11px] font-extrabold uppercase tracking-wider text-amber-200 mb-2">
                            <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                            <span>Formations Courte Durée (1 à 3 mois) • Entreprises & Partenaires</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Formations Continues Professionnelles & Attestations</h1>
                        <p class="text-blue-100/85 text-xs sm:text-sm mt-1 max-w-3xl">
                            Gérez les formations continues payantes à la carte demandées par les entreprises et partenaires pour leurs employés, avec génération automatique des attestations de fin de formation.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    @if($activeTraining)
                    <button type="button" @click="enrollModalOpen = true" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 border border-white/20 text-white rounded-full text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span>+ Inscrire un Stagiaire</span>
                    </button>
                    @endif
                    <button type="button" @click="createModalOpen = true" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full text-xs font-extrabold flex items-center gap-2 shadow-md transition cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Nouvelle Formation Continue</span>
                    </button>
                </div>
            </div>

            <!-- KPIs Strip -->
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-white/15">
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Sessions Continues</span>
                    <span class="text-xl font-black">{{ $trainings->count() }} formation(s)</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Entreprises Partenaires</span>
                    <span class="text-xl font-black">{{ $trainings->pluck('partner_company')->filter()->unique()->count() }} entreprise(s)</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Professionnels Inscrits</span>
                    <span class="text-xl font-black">{{ $trainings->sum(fn($t) => $t->registrations->count()) }} stagiaire(s)</span>
                </div>
                <div class="bg-white/10 rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Recettes Formations Continues</span>
                    <span class="text-xl font-black text-emerald-300">{{ number_format($trainings->sum('fee_amount'), 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <!-- Filtres par Département et Année Académique -->
        <div class="bg-white p-4 rounded-[22px] border border-slate-200 shadow-sm">
            <form action="{{ route('admin.continuing-education.index') }}" method="GET" id="fcFilterForm" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end text-xs">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Département d'accueil</label>
                    <select name="department_id" onchange="document.getElementById('fcFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                        <option value="">Tous les 3 Départements (Informatique, Mécanique, Électrique)</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $deptId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Année Académique</label>
                    <select name="cohort_id" onchange="document.getElementById('fcFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                        <option value="">Toutes les années académiques</option>
                        @foreach($cohorts as $cohort)
                            <option value="{{ $cohort->id }}" {{ $cohortId == $cohort->id ? 'selected' : '' }}>{{ $cohort->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Formation Continue Sélectionnée</label>
                    <select name="training_id" onchange="document.getElementById('fcFilterForm').submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                        @forelse($trainings as $tr)
                            <option value="{{ $tr->id }}" {{ ($activeTraining && $activeTraining->id == $tr->id) ? 'selected' : '' }}>
                                [{{ $tr->partner_company }}] {{ $tr->title }} ({{ $tr->duration_label }})
                            </option>
                        @empty
                            <option value="">-- Aucune formation continue --</option>
                        @endforelse
                    </select>
                </div>
            </form>
        </div>

        <!-- Main Layout : Left 5 Cols (Catalogue des Formations Continues) + Right 7 Cols (Stagiaires & Attestations) -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <!-- Left 5 Cols: Liste des Formations Continues par Entreprise & Thème -->
            <div class="xl:col-span-5 space-y-3.5">
                <h2 class="text-sm font-black uppercase tracking-wider text-slate-700 px-1">
                    Catalogue des Formations Continues ({{ $trainings->count() }})
                </h2>

                @forelse($trainings as $tr)
                    @php
                        $isSelected = $activeTraining && $activeTraining->id === $tr->id;
                    @endphp
                    <div class="bg-white rounded-[24px] p-5 border-2 transition shadow-sm {{ $isSelected ? 'border-[#4B6A95] ring-2 ring-[#4B6A95]/15' : 'border-slate-200/90 hover:border-slate-300' }}">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-black uppercase">
                                    Entreprise : {{ $tr->partner_company ?: 'Partenaire' }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#4B6A95] text-[10px] font-black">
                                    Durée : {{ $tr->duration_label ?: '1 à 3 mois' }}
                                </span>
                            </div>

                            <form action="{{ route('admin.continuing-education.destroy', $tr->id) }}" method="POST" onsubmit="return confirm('Supprimer cette formation continue ?');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-600 p-1 transition cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.continuing-education.index', ['training_id' => $tr->id, 'department_id' => $deptId, 'cohort_id' => $cohortId]) }}" class="block group">
                            <h3 class="text-base font-black text-slate-900 group-hover:text-[#4B6A95] transition leading-snug">
                                {{ $tr->title }}
                            </h3>
                            <p class="text-xs font-bold text-[#4B6A95] mt-1">
                                Thème : {{ $tr->specific_theme ?: ($tr->topics->first()?->name ?: 'Perfectionnement technique') }}
                            </p>
                        </a>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="space-y-0.5">
                                <span class="text-[11px] text-slate-500 block">
                                    <strong>{{ $tr->departments->pluck('name')->join(', ') }}</strong> • Du {{ $tr->start_date?->format('d/m/Y') }} au {{ $tr->end_date?->format('d/m/Y') }}
                                </span>
                                <span class="text-xs font-black text-emerald-700">
                                    Coût : {{ number_format($tr->fee_amount, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            <a href="{{ route('admin.continuing-education.index', ['training_id' => $tr->id]) }}"
                               class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $isSelected ? 'bg-[#4B6A95] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                {{ $tr->registrations->count() }} stagiaire(s) →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[24px] p-10 text-center border border-slate-200 text-xs text-slate-400">
                        Aucune formation continue enregistrée.
                    </div>
                @endforelse
            </div>

            <!-- Right 7 Cols: Liste des Stagiaires Professionnels & Délivrance des Attestations -->
            <div class="xl:col-span-7">
                <div class="bg-white rounded-[26px] border border-slate-200 shadow-sm p-6">
                    @if($activeTraining)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100 mb-5">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase">
                                        {{ $activeTraining->partner_company }} • {{ $activeTraining->duration_label }}
                                    </span>
                                    <span class="text-xs font-black text-slate-900">
                                        {{ number_format($activeTraining->fee_amount, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <h2 class="text-xl font-black text-slate-900 mt-1">{{ $activeTraining->title }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Thème précis : <strong class="text-[#4B6A95]">{{ $activeTraining->specific_theme }}</strong> • Département : <strong>{{ $activeTraining->departments->pluck('name')->join(', ') }}</strong>
                                </p>
                            </div>

                            <button type="button" @click="enrollModalOpen = true" class="px-4 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm transition cursor-pointer shrink-0">
                                <span class="material-symbols-outlined text-[18px]">person_add</span>
                                <span>+ Inscrire un Employé / Stagiaire</span>
                            </button>
                        </div>

                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
                            Stagiaires inscrits & Attestations de fin de formation ({{ $activeTraining->registrations->count() }} / {{ $activeTraining->max_participants }})
                        </h3>

                        @if($activeTraining->registrations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400">
                                            <th class="py-3 px-3">Stagiaire Professionnel</th>
                                            <th class="py-3 px-3">Entreprise / Employeur</th>
                                            <th class="py-3 px-3">N° Attestation</th>
                                            <th class="py-3 px-3 text-right">Attestation de fin de formation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($activeTraining->registrations as $reg)
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="py-3.5 px-3">
                                                    <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400">{{ $reg->user->email }} • {{ $reg->user->candidateProfile?->phone }}</div>
                                                </td>
                                                <td class="py-3.5 px-3">
                                                    <span class="font-bold text-slate-800 block">{{ $reg->company_name ?: $activeTraining->partner_company }}</span>
                                                    <span class="text-[10px] text-slate-500">{{ $reg->user->candidateProfile?->current_profession ?: 'Employé' }}</span>
                                                </td>
                                                <td class="py-3.5 px-3 font-mono text-[11px] font-bold text-slate-600">
                                                    {{ $reg->certificate_number ?: 'ATT-CFPT-' . $reg->id }}
                                                </td>
                                                <td class="py-3.5 px-3 text-right">
                                                    <a href="{{ route('admin.continuing-education.certificate.pdf', $reg->id) }}" data-no-loader
                                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-2xs transition">
                                                        <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
                                                        <span>Attestation PDF</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-12 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                                <span class="material-symbols-outlined text-3xl opacity-40 mb-1">group_add</span>
                                <p class="font-bold text-slate-600">Aucun stagiaire inscrit dans cette session continue.</p>
                                <button type="button" @click="enrollModalOpen = true" class="mt-3 px-4 py-2 bg-[#4B6A95] text-white rounded-full font-bold cursor-pointer">
                                    + Inscrire le premier stagiaire
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="py-16 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl opacity-30 mb-2">workspace_premium</span>
                            <p class="text-sm font-bold text-slate-700">Sélectionnez ou créez une formation continue.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: Créer une Formation Continue (Courte durée, Entreprise demandeuse, Thème, Tarif) -->
    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Nouvelle Formation Continue Professionnelle</h3>
                    <p class="text-xs text-slate-500">Formation courte durée payante à la demande d'une entreprise ou d'un partenaire.</p>
                </div>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.continuing-education.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Intitulé de la Formation Continue <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Ex: Perfectionnement Automates Siemens S7-1500 & Supervision" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Entreprise / Partenaire demandeur <span class="text-rose-500">*</span></label>
                        <input type="text" name="partner_company" required placeholder="Ex: SENELEC, SONATEL, SAR, Eiffage..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Thème précis demandé <span class="text-rose-500">*</span></label>
                        <input type="text" name="specific_theme" required placeholder="Ex: Maintenance préventive API & Variateurs" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Département d'accueil (sur les 3) <span class="text-rose-500">*</span></label>
                        <select name="department_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Année Académique de rattachement</label>
                        <select name="cohort_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            @foreach($cohorts as $cohort)
                                <option value="{{ $cohort->id }}">{{ $cohort->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durée de la formation <span class="text-rose-500">*</span></label>
                        <select name="duration_label" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                            <option value="1 mois">1 mois (Courte durée)</option>
                            <option value="2 mois">2 mois (Courte durée)</option>
                            <option value="3 mois">3 mois (Courte durée)</option>
                            <option value="2 semaines intensives">2 semaines intensives</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Coût / Tarif global (FCFA) <span class="text-rose-500">*</span></label>
                        <input type="number" step="5000" min="0" name="fee_amount" value="450000" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-black outline-none focus:border-[#4B6A95]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date de début <span class="text-rose-500">*</span></label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date de fin <span class="text-rose-500">*</span></label>
                        <input type="date" name="end_date" value="{{ now()->addMonths(2)->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Effectif prévu d'employés / stagiaires</label>
                        <input type="number" name="max_participants" value="15" min="1" max="200" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold shadow-sm cursor-pointer">
                        Créer la Formation Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Inscrire un employé / stagiaire professionnel -->
    @if($activeTraining)
    <div x-show="enrollModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="enrollModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Inscrire un Stagiaire Professionnel</h3>
                    <p class="text-xs text-slate-500">{{ $activeTraining->title }} ({{ $activeTraining->partner_company }})</p>
                </div>
                <button type="button" @click="enrollModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.continuing-education.enroll', $activeTraining->id) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom & Prénom du stagiaire <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Ex: M. Pape Moussa Diouf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email professionnel <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required placeholder="pape.diouf@entreprise.sn" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" placeholder="+221 77 ..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Entreprise</label>
                        <input type="text" name="company_name" value="{{ $activeTraining->partner_company }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Poste / Fonction</label>
                        <input type="text" name="job_function" placeholder="Ex: Technicien Maintenance" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="enrollModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold cursor-pointer">Inscrire & Générer Attestation</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
