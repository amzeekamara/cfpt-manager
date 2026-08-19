@extends('layouts.admin')

@section('header_title', 'Modifier le Dossier')

@push('styles')
<style>
    .step-active { color: #1e3a8a; border-color: #1e3a8a; font-weight: 800; background: #eff6ff; }
    .step-inactive { color: #94a3b8; border-color: #e2e8f0; background: white; }
    .step-completed { color: #059669; border-color: #059669; background: #ecfdf5; }
    .form-step { display: none; }
    .form-step.active { display: block; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 pb-24">
    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.candidates.show', $registration->id) }}" class="w-12 h-12 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-all group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-headline font-black text-primary tracking-tighter uppercase">Édition du Dossier</h1>
                <p class="text-slate-500 font-medium">Candidat : <span class="font-bold text-primary">{{ $registration->user->name }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-primary rounded-full border border-blue-100 text-xs font-bold uppercase tracking-wider">
            <span class="material-symbols-outlined text-sm">info</span>
            ID Dossier #{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg flex gap-4 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-3xl">report</span>
            <div>
                <h3 class="font-bold mb-1">Des erreurs ont été détectées</h3>
                <ul class="text-sm space-y-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Stepper -->
    <div class="flex justify-between items-center mb-12 max-w-2xl mx-auto relative z-10">
        @for($i=1; $i<=5; $i++)
            <div class="flex flex-col items-center gap-2 relative z-20">
                <div id="step-dot-{{$i}}" class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 {{ $i === 1 ? 'step-active' : 'step-inactive' }} bg-white">
                    {{$i}}
                </div>
            </div>
            @if($i < 5)
                <div class="flex-1 h-[2px] bg-slate-200 mx-1 z-10"></div>
            @endif
        @endfor
    </div>

    <!-- Wizard Form -->
    <form action="{{ route('admin.candidates.update', $registration->id) }}" method="POST" enctype="multipart/form-data" id="wizard-form" class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-8 md:p-14">
            
            <!-- STEP 1: IDENTITY -->
            <div class="form-step active" id="step-content-1">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">person</span>
                    1. Identité & Coordonnées
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Photo d'Identité / Avatar -->
                    <div class="md:col-span-2 p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-50 border-2 border-blue-200 flex items-center justify-center text-[#4B6A95] font-bold text-xl overflow-hidden shrink-0">
                            @if(optional($registration->user->candidateProfile)->photo_path)
                                <img src="{{ Storage::url(optional($registration->user->candidateProfile)->photo_path) }}" alt="Photo Candidat" class="w-full h-full object-cover">
                            @elseif($registration->user->avatar)
                                <img src="{{ Storage::url($registration->user->avatar) }}" alt="Photo Candidat" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-3xl">account_circle</span>
                            @endif
                        </div>
                        <div class="flex-1 w-full space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest block">Photo d'Identité / Avatar du Candidat</label>
                            <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png,image/jpg,image/webp" 
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#4B6A95] file:text-white hover:file:bg-[#354e70] transition cursor-pointer">
                            <p class="text-[10px] text-slate-400">Formats acceptés : JPG, PNG, WEBP. Taille max : 5 Mo.</p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Matricule</label>
                        <input type="text" name="matricule" value="{{ old('matricule', optional($registration->user->candidateProfile)->matricule) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom complet *</label>
                        <input type="text" name="name" value="{{ old('name', $registration->user->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Sexe</label>
                        <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                            <option value="">-- Sélectionner --</option>
                            <option value="M" {{ old('gender', optional($registration->user->candidateProfile)->gender) == 'M' ? 'selected' : '' }}>Masculin (M)</option>
                            <option value="F" {{ old('gender', optional($registration->user->candidateProfile)->gender) == 'F' ? 'selected' : '' }}>Féminin (F)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Date de Naissance</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional(optional($registration->user->candidateProfile)->birth_date)->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Lieu de Naissance</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', optional($registration->user->candidateProfile)->birth_place) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nationalité</label>
                        <input type="text" name="nationality" value="{{ old('nationality', optional($registration->user->candidateProfile)->nationality) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Situation Familiale</label>
                        <select name="family_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                            <option value="single" {{ optional($registration->user->candidateProfile)->family_status == 'single' ? 'selected' : '' }}>Célibataire</option>
                            <option value="married" {{ optional($registration->user->candidateProfile)->family_status == 'married' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="divorced" {{ optional($registration->user->candidateProfile)->family_status == 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="widowed" {{ optional($registration->user->candidateProfile)->family_status == 'widowed' ? 'selected' : '' }}>Veuf/Veuve</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion', optional($registration->user->candidateProfile)->religion) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                    </div>
                </div>
            </div>

            <!-- STEP 2: CONTACT -->
            <div class="form-step" id="step-content-2">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">contact_phone</span>
                    2. Coordonnées & Contact
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Email professionnel</label>
                        <input type="email" name="email" value="{{ old('email', $registration->user->email) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', optional($registration->user->candidateProfile)->phone) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    
                    <div class="md:col-span-2 pt-6 border-t border-slate-100 mt-4">
                        <h3 class="text-sm font-black text-rose-600 uppercase mb-4">Personne à prévenir en cas d'urgence</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', optional($registration->user->candidateProfile)->emergency_contact_name) }}" placeholder="Nom Complet" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', optional($registration->user->candidateProfile)->emergency_contact_relation) }}" placeholder="Lien de parenté" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', optional($registration->user->candidateProfile)->emergency_contact_phone) }}" placeholder="Téléphone" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="email" name="emergency_contact_email" value="{{ old('emergency_contact_email', optional($registration->user->candidateProfile)->emergency_contact_email) }}" placeholder="Email (Optionnel)" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: PROFESSIONAL & ACADEMIC -->
            <div class="form-step" id="step-content-3">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">workspace_premium</span>
                    3. Profil Professionnel
                </h2>
                
                <div class="space-y-8">
                    <!-- Professional -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Grade</label>
                            <input type="text" name="grade" value="{{ old('grade', optional($registration->user->candidateProfile)->grade) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Fonction</label>
                            <input type="text" name="job_function" value="{{ old('job_function', optional($registration->user->candidateProfile)->job_function) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Emploi / Spécialité</label>
                            <input type="text" name="current_profession" value="{{ old('current_profession', optional($registration->user->candidateProfile)->current_profession) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur actuel</label>
                            <input type="text" name="current_employer" value="{{ old('current_employer', optional($registration->user->candidateProfile)->current_employer) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur précédent</label>
                            <input type="text" name="previous_employer" value="{{ old('previous_employer', optional($registration->user->candidateProfile)->previous_employer) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Description du travail</label>
                            <textarea name="job_description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">{{ old('job_description', optional($registration->user->candidateProfile)->job_description) }}</textarea>
                        </div>
                    </div>

                    <!-- Academic -->
                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Parcours Académique</h3>
                        <div id="academic-container" class="space-y-4">
                            @php $histories = optional($registration->user->candidateProfile)->academicHistories ?? collect(); @endphp
                            @forelse($histories as $index => $history)
                                <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl relative">
                                    <input type="text" name="acad_establishment[]" value="{{ $history->establishment_name }}" placeholder="Établissement" class="md:col-span-2 bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_field[]" value="{{ $history->field_of_study }}" placeholder="Thème" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_degree[]" value="{{ $history->degree_obtained }}" placeholder="Diplôme" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <div class="flex gap-2">
                                        <input type="text" name="acad_year[]" value="{{ $history->graduation_year }}" placeholder="Année" class="bg-white border rounded-xl px-3 py-2 text-sm w-full">
                                        <button type="button" onclick="this.closest('.academic-row').remove()" class="text-rose-500 material-symbols-outlined hover:bg-rose-50 rounded-full p-1 transition-colors">delete</button>
                                    </div>
                                    <div class="md:col-span-5">
                                        <input type="text" name="acad_distinction[]" value="{{ $history->distinction }}" placeholder="Distinction / Mention" class="w-full bg-white border rounded-xl px-3 py-2 text-sm">
                                    </div>
                                </div>
                            @empty
                                <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl relative">
                                    <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_field[]" placeholder="Thème" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <div class="flex gap-2">
                                        <input type="text" name="acad_year[]" placeholder="Année" class="bg-white border rounded-xl px-3 py-2 text-sm w-full">
                                        <button type="button" onclick="this.closest('.academic-row').remove()" class="text-rose-500 material-symbols-outlined hover:bg-rose-50 rounded-full p-1 transition-colors">delete</button>
                                    </div>
                                    <div class="md:col-span-5">
                                        <input type="text" name="acad_distinction[]" placeholder="Distinction / Mention" class="w-full bg-white border rounded-xl px-3 py-2 text-sm">
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" onclick="addAcademicRow()" class="mt-4 text-xs font-bold text-primary flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span> Ajouter un autre diplôme
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 4: HEALTH & LOGISTICS -->
            @php $q = optional($registration->user->candidateProfile)->health_questionnaire_json; $q = is_string($q) ? json_decode($q, true) : $q; $q = $q ?? []; @endphp
            <div class="form-step" id="step-content-4">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">health_and_safety</span>
                    4. Santé & Logistique
                </h2>
                <div class="space-y-6">
                    <div class="bg-slate-50 p-6 rounded-xl space-y-4 border border-slate-100">
                        <h3 class="text-sm font-bold text-slate-700 mb-4">Questionnaire de Santé</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm p-3 bg-white rounded-xl border border-slate-200">
                                <span class="font-medium text-slate-700">Souffrez-vous d'une affection chronique ?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_chronic" value="yes" {{ ($q['chronic'] ?? '') == 'yes' ? 'checked' : '' }} class="text-primary"> Oui</label>
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_chronic" value="no" {{ ($q['chronic'] ?? '') == 'no' ? 'checked' : '' }} class="text-primary"> Non</label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-sm p-3 bg-white rounded-xl border border-slate-200">
                                <span class="font-medium text-slate-700">Suivez-vous un traitement régulier ?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_treatment" value="yes" {{ ($q['treatment'] ?? '') == 'yes' ? 'checked' : '' }} class="text-primary"> Oui</label>
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_treatment" value="no" {{ ($q['treatment'] ?? '') == 'no' ? 'checked' : '' }} class="text-primary"> Non</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Observations Médicales</label>
                        <textarea name="health_record_obs" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">{{ old('health_record_obs', optional($registration->user->candidateProfile)->health_record_obs) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Préférence Hébergement</label>
                            <input type="text" name="accommodation_preference" value="{{ old('accommodation_preference', optional($registration->user->candidateProfile)->accommodation_preference) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1"></div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Assurance Détails</label>
                            <input type="text" name="insurance_details" value="{{ old('insurance_details', optional($registration->user->candidateProfile)->insurance_details) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prime Assurance (CFA)</label>
                            <input type="number" name="insurance_premium" value="{{ old('insurance_premium', optional($registration->user->candidateProfile)->insurance_premium) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: ASSIGNMENT -->
            <div class="form-step" id="step-content-5">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">assignment_turned_in</span>
                    5. Sélection & Affectation
                </h2>
                
                <!-- Sélection Séminaire, Thème et Statut (Cadre Bleu) -->
                <div class="bg-[#00355f] p-6 sm:p-8 rounded-2xl space-y-6 text-white shadow-md relative">
                    <div class="space-y-6">
                        <!-- Séminaire -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-blue-200 block">Séminaire de Formation *</label>
                            <select id="seminar_select" name="seminar_id" required 
                                    class="w-full bg-white/10 hover:bg-white/15 border border-white/25 focus:border-white focus:bg-white/20 text-white rounded-xl px-4 py-3 font-semibold text-sm transition outline-none">
                                @foreach($seminars as $seminar)
                                    <option value="{{ $seminar->id }}" class="text-slate-900" {{ $registration->seminar_id == $seminar->id ? 'selected' : '' }}>
                                        {{ $seminar->title }} (Édition: {{ $seminar->cohort?->title }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Thème affecté -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-blue-200 block">Thème d'Atelier / Spécialité</label>
                                <select id="topic_select" name="topic_id" 
                                        class="w-full bg-white/10 hover:bg-white/15 border border-white/25 focus:border-white focus:bg-white/20 text-white rounded-xl px-4 py-3 font-semibold text-sm transition outline-none">
                                    <option value="" class="text-slate-900">-- Choisir d'abord un séminaire --</option>
                                </select>
                            </div>

                            <!-- Statut du dossier -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-blue-200 block">Statut du Dossier *</label>
                                <select name="status" required 
                                        class="w-full bg-white/10 hover:bg-white/15 border border-white/25 focus:border-white focus:bg-white/20 text-white rounded-xl px-4 py-3 font-semibold text-sm transition outline-none">
                                    <option value="pending" class="text-slate-900" {{ $registration->status == 'pending' ? 'selected' : '' }}>En attente / À examiner</option>
                                    <option value="approved" class="text-slate-900" {{ $registration->status == 'approved' ? 'selected' : '' }}>Sélectionné / Admis</option>
                                    <option value="waiting_list" class="text-slate-900" {{ $registration->status == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
                                    <option value="rejected" class="text-slate-900" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Refusé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION PIÈCES JUSTIFICATIVES & DOCUMENTS -->
                <div class="mt-8 pt-6 border-t border-slate-200 space-y-6">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-[#4B6A95] text-[20px]">folder_open</span>
                            Pièces Justificatives & Documents du Candidat
                        </h3>
                        <p class="text-xs text-slate-500">Ajoutez de nouvelles pièces jointes (PDF, Images, Word, jusqu'à 10 Mo par fichier).</p>
                    </div>

                    @if($registration->user->candidateResources && $registration->user->candidateResources->count() > 0)
                        <div class="space-y-2 pb-2">
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block">Documents déjà enregistrés :</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($registration->user->candidateResources as $doc)
                                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="material-symbols-outlined text-[#4B6A95] text-[18px]">description</span>
                                            <span class="font-medium text-slate-800 truncate">{{ $doc->resource_type }} ({{ $doc->original_filename }})</span>
                                        </div>
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-[#4B6A95] hover:underline font-bold shrink-0 ml-2">Voir</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 4 Documents Principaux -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Diplôme -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Nouveau Diplôme / Attestation</label>
                            <input type="file" name="document_diploma" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-[#4B6A95] hover:file:bg-blue-50 transition cursor-pointer">
                        </div>

                        <!-- Lettre de motivation -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Nouvelle Lettre de Motivation</label>
                            <input type="file" name="document_motivation" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-[#4B6A95] hover:file:bg-blue-50 transition cursor-pointer">
                        </div>

                        <!-- CV -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Nouveau Curriculum Vitae (CV)</label>
                            <input type="file" name="document_cv" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-[#4B6A95] hover:file:bg-blue-50 transition cursor-pointer">
                        </div>

                        <!-- Passeport / CNI -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Pièce d'Identité / Passeport</label>
                            <input type="file" name="document_passport" accept=".pdf,.png,.jpg,.jpeg"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-[#4B6A95] hover:file:bg-blue-50 transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Documents complémentaires dynamiques -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <label class="text-xs font-bold text-slate-700 block">Autres Documents & Justificatifs Complémentaires</label>
                        
                        <div id="other-docs-container" class="space-y-3">
                            <!-- Lignes ajoutées dynamiquement via le bouton ci-dessous -->
                        </div>

                        <button type="button" onclick="addOtherDocRow()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs">
                            <span class="material-symbols-outlined text-[18px] text-[#4B6A95]">add_circle</span>
                            <span>Ajouter un fichier</span>
                        </button>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200 flex items-start gap-4">
                    <span class="material-symbols-outlined text-slate-400">info</span>
                    <div>
                        <p class="text-sm text-slate-700 font-bold">Enregistrement final</p>
                        <p class="text-[11px] text-slate-500">En soumettant ce formulaire, les modifications seront enregistrées immédiatement. S'il s'agit d'un changement de statut, le candidat sera notifié.</p>
                    </div>
                </div>
            </div>

            <!-- Form Controls -->
            <div class="mt-16 flex justify-between items-center bg-slate-50 -mx-8 -mb-14 p-8 border-t border-slate-100">
                <button type="button" id="prev-btn" class="hidden px-8 py-3 rounded-md font-bold text-slate-500 hover:bg-white transition-all border border-slate-200 shadow-sm">Précédent</button>
                <div></div>
                <button type="button" id="next-btn" class="px-10 py-4 rounded-md bg-primary text-white font-black uppercase text-xs tracking-[0.2em] shadow-md hover:-translate-y-1 transition-all">Suivant</button>
                <button type="submit" id="submit-btn" class="hidden px-10 py-4 rounded-md bg-emerald-600 text-white font-black uppercase text-xs tracking-[0.2em] shadow-md hover:-translate-y-1 transition-all">Enregistrer</button>
            </div>

        </div>
    </form>
</div>

<template id="academic-template">
    <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl relative animate-in fade-in slide-in-from-top-2 duration-300">
        <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border rounded-xl px-3 py-2 text-sm">
        <input type="text" name="acad_field[]" placeholder="Thème" class="bg-white border rounded-xl px-3 py-2 text-sm">
        <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border rounded-xl px-3 py-2 text-sm">
        <div class="flex gap-2">
            <input type="text" name="acad_year[]" placeholder="Année" class="bg-white border rounded-xl px-3 py-2 text-sm w-full">
            <button type="button" onclick="this.closest('.academic-row').remove()" class="text-rose-500 material-symbols-outlined hover:bg-rose-50 rounded-full p-1 transition-colors">delete</button>
        </div>
        <div class="md:col-span-5">
            <input type="text" name="acad_distinction[]" placeholder="Distinction / Mention" class="w-full bg-white border rounded-xl px-3 py-2 text-sm">
        </div>
    </div>
</template>

<template id="other-doc-template">
    <div class="other-doc-row flex flex-col sm:flex-row items-stretch sm:items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl animate-in fade-in slide-in-from-top-2 duration-200">
        <input type="text" name="other_doc_titles[]" placeholder="Intitulé du document (ex: Attestation de travail, Certificat...)" class="flex-1 bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-xs font-medium focus:border-[#4B6A95] outline-none">
        <input type="file" name="other_documents[]" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#4B6A95] cursor-pointer">
        <button type="button" onclick="this.closest('.other-doc-row').remove()" class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition self-end sm:self-center" title="Supprimer ce fichier">
            <span class="material-symbols-outlined text-[18px]">delete</span>
        </button>
    </div>
</template>

@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 5;

    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');

    function updateStep() {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById(`step-content-${currentStep}`).classList.add('active');

        for(let i=1; i<=totalSteps; i++) {
            const dot = document.getElementById(`step-dot-${i}`);
            if(i < currentStep) {
                dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-completed bg-white';
                dot.innerHTML = '<span class="material-symbols-outlined text-sm">check</span>';
            } else if(i === currentStep) {
                dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-active';
                dot.innerHTML = i;
            } else {
                dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-inactive bg-white';
                dot.innerHTML = i;
            }
        }

        prevBtn.classList.toggle('hidden', currentStep === 1);
        if(currentStep === totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function addAcademicRow() {
        const container = document.getElementById('academic-container');
        const template = document.getElementById('academic-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    function addOtherDocRow() {
        const container = document.getElementById('other-docs-container');
        const template = document.getElementById('other-doc-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    nextBtn.addEventListener('click', () => { if(currentStep < totalSteps) { currentStep++; updateStep(); } });
    prevBtn.addEventListener('click', () => { if(currentStep > 1) { currentStep--; updateStep(); } });

    // Script pour les thèmes
    const topicsBySeminar = @json($topicsBySeminar);
    const oldTopicId = "{{ old('topic_id', $registration->topic_id) }}";
    
    const seminarSelect = document.getElementById('seminar_select');
    const topicSelect = document.getElementById('topic_select');
    
    function updateTopics() {
        const seminarId = seminarSelect.value;
        topicSelect.innerHTML = '<option value="" class="text-slate-900">-- Choisir un thème (Optionnel) --</option>';
        
        if (seminarId && topicsBySeminar[seminarId]) {
            topicsBySeminar[seminarId].forEach(topic => {
                const option = document.createElement('option');
                option.value = topic.id;
                option.className = 'text-slate-900';
                option.textContent = topic.name;
                if (oldTopicId == topic.id) {
                    option.selected = true;
                }
                topicSelect.appendChild(option);
            });
        }
    }
    
    seminarSelect.addEventListener('change', updateTopics);
    // Init on load
    updateTopics();
</script>
@endpush
