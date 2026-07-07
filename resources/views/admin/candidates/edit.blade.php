@extends('layouts.admin')

@section('header_title', 'Modifier le Dossier')

@section('content')
<div class="p-8 max-w-5xl mx-auto pb-20">
    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.candidates.show', $enrollment->id) }}" class="w-12 h-12 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-all group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-headline font-black text-on-surface tracking-tighter uppercase">Édition du Dossier</h1>
                <p class="text-on-surface-variant font-body">Candidat : <span class="font-bold text-primary">{{ $enrollment->user->name }}</span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-2xl border border-blue-100 text-xs font-bold uppercase tracking-wider">
            <span class="material-symbols-outlined text-sm">info</span>
            ID Dossier #{{ str_pad($enrollment->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-3xl flex gap-4 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-3xl">report</span>
            <div>
                <h3 class="font-bold mb-1">Des erreurs ont été détectées</h3>
                <ul class="text-sm space-y-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.candidates.update', $enrollment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- SECTION 1: Identité & Contact -->
        <div class="bg-surface-container-lowest rounded-[40px] p-10 border border-outline-variant shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 p-10 opacity-[0.03] pointer-events-none">
                <span class="material-symbols-outlined text-[120px]">badge</span>
            </div>
            
            <h2 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-primary rounded-full"></span>
                Identité & Coordonnées
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $enrollment->user->name) }}" required
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Email professionnel</label>
                    <input type="email" name="email" value="{{ old('email', $enrollment->user->email) }}" required
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $enrollment->user->candidateProfile->phone) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Religion</label>
                    <input type="text" name="religion" value="{{ old('religion', $enrollment->user->candidateProfile->religion) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Date de Naissance</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($enrollment->user->candidateProfile->birth_date)->format('Y-m-d')) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Lieu de Naissance</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $enrollment->user->candidateProfile->birth_place) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nationalité</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $enrollment->user->candidateProfile->nationality) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Situation Familiale</label>
                    <select name="family_status" class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium appearance-none">
                        <option value="single" {{ $enrollment->user->candidateProfile->family_status == 'single' ? 'selected' : '' }}>Célibataire</option>
                        <option value="married" {{ $enrollment->user->candidateProfile->family_status == 'married' ? 'selected' : '' }}>Marié(e)</option>
                        <option value="divorced" {{ $enrollment->user->candidateProfile->family_status == 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                        <option value="widowed" {{ $enrollment->user->candidateProfile->family_status == 'widowed' ? 'selected' : '' }}>Veuf/Veuve</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Parcours Professionnel -->
        <div class="bg-surface-container-lowest rounded-[40px] p-10 border border-outline-variant shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 p-10 opacity-[0.03] pointer-events-none">
                <span class="material-symbols-outlined text-[120px]">work</span>
            </div>
            
            <h2 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                Profil Professionnel
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Profession actuelle</label>
                    <input type="text" name="current_profession" value="{{ old('current_profession', $enrollment->user->candidateProfile->current_profession) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Employeur actuel</label>
                    <input type="text" name="current_employer" value="{{ old('current_employer', $enrollment->user->candidateProfile->current_employer) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Employeur précédent</label>
                    <input type="text" name="previous_employer" value="{{ old('previous_employer', $enrollment->user->candidateProfile->previous_employer) }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Description du travail</label>
                    <textarea name="job_description" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">{{ old('job_description', $enrollment->user->candidateProfile->job_description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Historique Académique Dynamique -->
        <div class="bg-surface-container-lowest rounded-[40px] p-10 border border-outline-variant shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-headline font-black text-on-surface flex items-center gap-3">
                    <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                    Diplômes & Études
                </h2>
                <button type="button" onclick="addAcademicRow()" class="flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-xl font-bold text-xs border border-amber-200 hover:bg-amber-100 transition-all">
                    <span class="material-symbols-outlined text-sm">add</span> Ajouter un diplôme
                </button>
            </div>

            <div id="academic-container" class="space-y-4">
                @php $histories = $enrollment->user->candidateProfile->academicHistories; @endphp
                @forelse($histories as $index => $history)
                    <div class="academic-row p-6 bg-surface-container-low rounded-3xl border border-outline-variant/30 grid grid-cols-1 md:grid-cols-5 gap-4 relative animate-in zoom-in-95 duration-300">
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] uppercase font-black text-on-surface-variant">Établissement</label>
                            <input type="text" name="acad_establishment[]" value="{{ $history->establishment_name }}" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-black text-on-surface-variant">Filière / Spécialité</label>
                            <input type="text" name="acad_field[]" value="{{ $history->field_of_study }}" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-black text-on-surface-variant">Diplôme / Année</label>
                            <div class="flex gap-2">
                                <input type="text" name="acad_degree[]" value="{{ $history->degree_obtained }}" placeholder="Diplôme" class="w-2/3 bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
                                <input type="text" name="acad_year[]" value="{{ $history->graduation_year }}" placeholder="Année" class="w-1/3 bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2 text-center">
                            </div>
                        </div>
                        <button type="button" onclick="this.closest('.academic-row').remove()" class="absolute -right-3 -top-3 w-8 h-8 rounded-full bg-rose-500 text-white shadow-lg flex items-center justify-center hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                        <div class="md:col-span-5 space-y-1">
                            <label class="text-[10px] uppercase font-black text-on-surface-variant">Distinction / Mention</label>
                            <input type="text" name="acad_distinction[]" value="{{ $history->distinction }}" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
                        </div>
                    </div>
                @empty
                    <p id="no-academic" class="text-sm text-center text-on-surface-variant italic py-4">Aucun diplôme enregistré.</p>
                @endforelse
            </div>
        </div>

        <!-- SECTION 4: Santé & Assurance -->
        <div class="bg-surface-container-lowest rounded-[40px] p-10 border border-outline-variant shadow-sm relative overflow-hidden">
             <div class="absolute right-0 top-0 p-10 opacity-[0.03] pointer-events-none">
                <span class="material-symbols-outlined text-[120px]">medical_services</span>
            </div>

            <h2 class="text-xl font-headline font-black text-on-surface mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-rose-500 rounded-full"></span>
                Santé, Logistique & Assurance
            </h2>

            @php $q = $enrollment->user->candidateProfile->health_questionnaire_json; @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-rose-50 rounded-2xl border border-rose-100">
                        <span class="text-sm font-bold text-rose-900">Souffrez-vous d'une affection chronique ?</span>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="health_chronic" value="yes" {{ ($q['chronic'] ?? '') == 'yes' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                <span class="text-xs font-bold uppercase">Oui</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="health_chronic" value="no" {{ ($q['chronic'] ?? '') == 'no' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                <span class="text-xs font-bold uppercase">Non</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-rose-50 rounded-2xl border border-rose-100">
                        <span class="text-sm font-bold text-rose-900">Suivez-vous un traitement régulier ?</span>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="health_treatment" value="yes" {{ ($q['treatment'] ?? '') == 'yes' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                <span class="text-xs font-bold uppercase">Oui</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="health_treatment" value="no" {{ ($q['treatment'] ?? '') == 'no' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                <span class="text-xs font-bold uppercase">Non</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Observations Médicales</label>
                        <textarea name="health_record_obs" rows="2" class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium text-sm">{{ old('health_record_obs', $enrollment->user->candidateProfile->health_record_obs) }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Préférence Hébergement</label>
                        <input type="text" name="accommodation_preference" value="{{ old('accommodation_preference', $enrollment->user->candidateProfile->accommodation_preference) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Assurance Détails</label>
                            <input type="text" name="insurance_details" value="{{ old('insurance_details', $enrollment->user->candidateProfile->insurance_details) }}"
                                class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Prime Assurance (CFA)</label>
                            <input type="number" name="insurance_premium" value="{{ old('insurance_premium', $enrollment->user->candidateProfile->insurance_premium) }}"
                                class="w-full bg-surface-container-low border border-outline-variant rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: Seminar & Status -->
        <div class="bg-blue-950 rounded-[40px] p-10 shadow-2xl text-white relative overflow-hidden">
             <div class="absolute right-0 top-0 p-10 opacity-10 pointer-events-none">
                <span class="material-symbols-outlined text-[120px]">assignment_turned_in</span>
            </div>

            <h2 class="text-xl font-headline font-black mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-blue-400 rounded-full"></span>
                Sélection & Inscription
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                <div class="space-y-2">
                    <label class="text-xs font-black opacity-60 uppercase tracking-widest px-1">Séminaire Affecté</label>
                    <select name="seminar_id" class="w-full bg-white/10 border border-white/20 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-white/20 transition-all font-bold text-blue-100 appearance-none">
                        @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}" class="text-slate-900" {{ $enrollment->seminar_id == $seminar->id ? 'selected' : '' }}>
                                {{ $seminar->title }} (Édition: {{ $seminar->edition?->title }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black opacity-60 uppercase tracking-widest px-1">Statut du Dossier</label>
                    <select name="status" class="w-full bg-white/10 border border-white/20 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-white/20 transition-all font-bold text-blue-100 appearance-none">
                        <option value="pending" class="text-slate-900" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>En attente / À examiner</option>
                        <option value="approved" class="text-slate-900" {{ $enrollment->status == 'approved' ? 'selected' : '' }}>Sélectionné / Admis</option>
                        <option value="rejected" class="text-slate-900" {{ $enrollment->status == 'rejected' ? 'selected' : '' }}>Refusé</option>
                        <option value="waiting_list" class="text-slate-900" {{ $enrollment->status == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-6 pt-10">
            <a href="{{ route('admin.candidates.show', $enrollment->id) }}" class="px-10 py-4 rounded-2xl border border-outline-variant font-bold text-on-surface-variant hover:bg-surface-container transition-all">
                Annuler
            </a>
            <button type="submit" class="px-16 py-4 rounded-2xl bg-primary text-white font-black shadow-xl shadow-primary/30 hover:shadow-2xl hover:-translate-y-1 active:translate-y-0 transition-all">
                Enregistrer les Modifications
            </button>
        </div>
    </form>
</div>

<template id="academic-template">
    <div class="academic-row p-6 bg-surface-container-low rounded-3xl border border-outline-variant/30 grid grid-cols-1 md:grid-cols-5 gap-4 relative animate-in zoom-in-95 duration-300">
        <div class="md:col-span-2 space-y-1">
            <label class="text-[10px] uppercase font-black text-on-surface-variant">Établissement</label>
            <input type="text" name="acad_establishment[]" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
        </div>
        <div class="space-y-1">
            <label class="text-[10px] uppercase font-black text-on-surface-variant">Filière / Spécialité</label>
            <input type="text" name="acad_field[]" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
        </div>
        <div class="space-y-1">
            <label class="text-[10px] uppercase font-black text-on-surface-variant">Diplôme / Année</label>
            <div class="flex gap-2">
                <input type="text" name="acad_degree[]" placeholder="Diplôme" class="w-2/3 bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
                <input type="text" name="acad_year[]" placeholder="Année" class="w-1/3 bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2 text-center">
            </div>
        </div>
        <button type="button" onclick="this.closest('.academic-row').remove()" class="absolute -right-3 -top-3 w-8 h-8 rounded-full bg-rose-500 text-white shadow-lg flex items-center justify-center hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
        <div class="md:col-span-5 space-y-1">
            <label class="text-[10px] uppercase font-black text-on-surface-variant">Distinction / Mention</label>
            <input type="text" name="acad_distinction[]" class="w-full bg-white border-outline-variant/50 rounded-xl text-sm px-4 py-2">
        </div>
    </div>
</template>

<script>
    function addAcademicRow() {
        const container = document.getElementById('academic-container');
        const noAcad = document.getElementById('no-academic');
        if (noAcad) noAcad.remove();
        
        const template = document.getElementById('academic-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }
</script>
@endsection
