@extends('layouts.admin')

@section('header_title', 'Nouvelle Inscription')

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
            <a href="{{ route('admin.candidates') }}" class="w-12 h-12 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-all group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-headline font-black text-primary tracking-tighter uppercase">Inscrire un Candidat</h1>
                <p class="text-slate-500 font-medium">Créer manuellement un nouveau dossier de candidature.</p>
            </div>
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
    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" id="wizard-form" class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        @csrf
        
        <div class="p-8 md:p-14">
            
            <!-- STEP 1: IDENTITY -->
            <div class="form-step active" id="step-content-1">
                <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-500">person</span>
                    1. Identité & Coordonnées
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Matricule</label>
                        <input type="text" name="matricule" value="{{ old('matricule') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Sexe</label>
                        <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                            <option value="">-- Sélectionner --</option>
                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculin (M)</option>
                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Féminin (F)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Date de Naissance</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Lieu de Naissance</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nationalité</label>
                        <input type="text" name="nationality" value="{{ old('nationality') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Situation Familiale</label>
                        <select name="family_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                            <option value="single" {{ old('family_status') == 'single' ? 'selected' : '' }}>Célibataire</option>
                            <option value="married" {{ old('family_status') == 'married' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="divorced" {{ old('family_status') == 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="widowed" {{ old('family_status') == 'widowed' ? 'selected' : '' }}>Veuf/Veuve</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
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
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 font-medium">
                    </div>
                    
                    <div class="md:col-span-2 pt-6 border-t border-slate-100 mt-4">
                        <h3 class="text-sm font-black text-rose-600 uppercase mb-4">Personne à prévenir en cas d'urgence</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Nom Complet" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" placeholder="Lien de parenté" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Téléphone" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
                            <input type="email" name="emergency_contact_email" value="{{ old('emergency_contact_email') }}" placeholder="Email (Optionnel)" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm font-medium">
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
                            <input type="text" name="grade" value="{{ old('grade') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Fonction</label>
                            <input type="text" name="job_function" value="{{ old('job_function') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Emploi / Spécialité</label>
                            <input type="text" name="current_profession" value="{{ old('current_profession') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur actuel</label>
                            <input type="text" name="current_employer" value="{{ old('current_employer') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur précédent</label>
                            <input type="text" name="previous_employer" value="{{ old('previous_employer') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Description du travail</label>
                            <textarea name="job_description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">{{ old('job_description') }}</textarea>
                        </div>
                    </div>

                    <!-- Academic -->
                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Parcours Académique</h3>
                        <div id="academic-container" class="space-y-4">
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
                        </div>
                        <button type="button" onclick="addAcademicRow()" class="mt-4 text-xs font-bold text-primary flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span> Ajouter un autre diplôme
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 4: HEALTH & LOGISTICS -->
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
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_chronic" value="yes" {{ old('health_chronic') == 'yes' ? 'checked' : '' }} class="text-primary"> Oui</label>
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_chronic" value="no" {{ old('health_chronic') == 'no' ? 'checked' : '' }} class="text-primary"> Non</label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-sm p-3 bg-white rounded-xl border border-slate-200">
                                <span class="font-medium text-slate-700">Suivez-vous un traitement régulier ?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_treatment" value="yes" {{ old('health_treatment') == 'yes' ? 'checked' : '' }} class="text-primary"> Oui</label>
                                    <label class="flex items-center gap-1 cursor-pointer font-bold"><input type="radio" name="health_treatment" value="no" {{ old('health_treatment') == 'no' ? 'checked' : '' }} class="text-primary"> Non</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Observations Médicales</label>
                        <textarea name="health_record_obs" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">{{ old('health_record_obs') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Préférence Hébergement</label>
                            <input type="text" name="accommodation_preference" value="{{ old('accommodation_preference') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1"></div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Assurance Détails</label>
                            <input type="text" name="insurance_details" value="{{ old('insurance_details') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prime Assurance (CFA)</label>
                            <input type="number" name="insurance_premium" value="{{ old('insurance_premium') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium">
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
                
                <div class="bg-primary p-8 rounded-xl space-y-6 text-white shadow-md relative overflow-hidden">
                    <div class="absolute right-0 top-0 p-8 opacity-5 pointer-events-none">
                        <span class="material-symbols-outlined text-[150px]">gavel</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        <div class="space-y-2">
                            <select id="seminar_select" name="seminar_id" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 focus:ring-4 focus:ring-white/20 transition-all font-bold text-blue-100 appearance-none outline-none">
                                <option value="" class="text-slate-900">-- Choisir un séminaire --</option>
                                @foreach($seminars as $seminar)
                                    <option value="{{ $seminar->id }}" class="text-slate-900" {{ old('seminar_id') == $seminar->id ? 'selected' : '' }}>
                                        {{ $seminar->title }} (Édition: {{ $seminar->cohort?->title }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black opacity-60 uppercase tracking-widest">Thème Affecté</label>
                            <select id="topic_select" name="topic_id" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 focus:ring-4 focus:ring-white/20 transition-all font-bold text-blue-100 appearance-none outline-none">
                                <option value="" class="text-slate-900">-- Choisir d'abord un séminaire --</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black opacity-60 uppercase tracking-widest">Statut du Dossier *</label>
                            <select name="status" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 focus:ring-4 focus:ring-white/20 transition-all font-bold text-blue-100 appearance-none outline-none">
                                <option value="pending" class="text-slate-900" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>En attente / À examiner</option>
                                <option value="approved" class="text-slate-900" {{ old('status') == 'approved' ? 'selected' : '' }}>Sélectionné / Admis</option>
                                <option value="rejected" class="text-slate-900" {{ old('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                                <option value="waiting_list" class="text-slate-900" {{ old('status') == 'waiting_list' ? 'selected' : '' }}>Liste d'attente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200 flex items-start gap-4">
                    <span class="material-symbols-outlined text-slate-400">info</span>
                    <div>
                        <p class="text-sm text-slate-700 font-bold">Création du dossier</p>
                        <p class="text-[11px] text-slate-500">Un compte utilisateur sera automatiquement généré (ou mis à jour s'il existe déjà) et le candidat recevra une notification selon le statut choisi.</p>
                    </div>
                </div>
            </div>

            <!-- Form Controls -->
            <div class="mt-16 flex justify-between items-center bg-slate-50 -mx-8 -mb-14 p-8 border-t border-slate-100">
                <button type="button" id="prev-btn" class="hidden px-8 py-3 rounded-md font-bold text-slate-500 hover:bg-white transition-all border border-slate-200 shadow-sm">Précédent</button>
                <div></div>
                <button type="button" id="next-btn" class="px-10 py-4 rounded-md bg-primary text-white font-black uppercase text-xs tracking-[0.2em] shadow-md hover:-translate-y-1 transition-all">Suivant</button>
                <button type="submit" id="submit-btn" class="hidden px-10 py-4 rounded-md bg-emerald-600 text-white font-black uppercase text-xs tracking-[0.2em] shadow-md hover:-translate-y-1 transition-all">Créer le Dossier</button>
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

<script>
    const topicsBySeminar = @json($topicsBySeminar);
    const oldTopicId = "{{ old('topic_id') }}";
    
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
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

    nextBtn.addEventListener('click', () => { if(currentStep < totalSteps) { currentStep++; updateStep(); } });
    prevBtn.addEventListener('click', () => { if(currentStep > 1) { currentStep--; updateStep(); } });
</script>
@endpush
