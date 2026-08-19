@extends('portal.layout')
@section('title', 'Modifier mon profil - ' . $profile->first_names)

@push('styles')
<style>
    .step-active { color: #4B6A95; border-color: #4B6A95; font-weight: 800; background: #eff6ff; }
    .step-inactive { color: #94a3b8; border-color: #e2e8f0; background: white; }
    .step-completed { color: #059669; border-color: #059669; background: #ecfdf5; }
    .form-step { display: none; }
    .form-step.active { display: block; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Top Return Link -->
    <a href="{{ route('portal.candidate.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 mb-6 transition-colors bg-white px-4 py-2 rounded-full border border-slate-200 shadow-2xs">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Retour au tableau de bord</span>
    </a>

    <!-- Header Card -->
    <div class="text-center mb-8 bg-white p-6 sm:p-8 rounded-[28px] border border-slate-200/90 shadow-sm">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2 tracking-tight">Mise à jour du dossier de candidature</h1>
        <p class="text-xs text-slate-500 max-w-lg mx-auto mb-4">Veuillez renseigner avec exactitude les informations ci-dessous. Vos modifications seront soumises à la validation du comité pédagogique.</p>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-200">
            <i data-lucide="info" class="w-3.5 h-3.5 text-amber-600"></i>
            <span>Validation requise par l'administration</span>
        </div>
    </div>

    <!-- Stepper Navigation -->
    <div class="flex justify-between items-center mb-10 max-w-2xl mx-auto px-4">
        @for($i=1; $i<=6; $i++)
            <div class="flex flex-col items-center gap-1.5 relative">
                <div id="step-dot-{{$i}}" class="w-10 h-10 rounded-full border-2 flex items-center justify-center font-black text-xs transition-all duration-300 {{ $i === 1 ? 'step-active' : 'step-inactive' }}">
                    {{$i}}
                </div>
            </div>
            @if($i < 6)
                <div class="flex-1 h-[2px] bg-slate-200 mx-2"></div>
            @endif
        @endfor
    </div>

    <!-- Wizard Form Card -->
    <form action="{{ route('portal.candidate.profile.update-request') }}" method="POST" enctype="multipart/form-data" id="wizard-form" class="bg-white rounded-[28px] shadow-sm border border-slate-200/90 overflow-hidden mb-12">
        @csrf
        
        <div class="p-6 sm:p-10 md:p-12">
            
            <!-- STEP 1: IDENTITY -->
            <div class="form-step active" id="step-content-1">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="user" class="w-5 h-5 text-[#4B6A95]"></i>
                    <span>1. État Civil & Identité</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Prénoms</label>
                        <input type="text" name="first_names" value="{{ $profile->first_names }}" required id="sum-first-names" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Nom de famille</label>
                        <input type="text" name="last_name" value="{{ $profile->last_name }}" required id="sum-last-name" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Date de Naissance</label>
                        <input type="date" name="birth_date" value="{{ $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '' }}" required id="sum-birth-date" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Lieu de Naissance</label>
                        <input type="text" name="birth_place" value="{{ $profile->birth_place }}" required id="sum-birth-place" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Sexe</label>
                        <select name="gender" id="sum-gender" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                            <option value="M" {{ $profile->gender === 'M' || $profile->gender === 'male' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ $profile->gender === 'F' || $profile->gender === 'female' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Nationalité</label>
                        <input type="text" name="nationality" value="{{ $profile->nationality }}" required id="sum-nationality" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Photo récente (Optionnel pour mise à jour)</label>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#4B6A95] file:text-white hover:file:bg-[#354e70]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Situation Familiale</label>
                        <select name="family_status" id="sum-family-status" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                            <option value="single" {{ $profile->family_status == 'single' ? 'selected' : '' }}>Célibataire</option>
                            <option value="married" {{ $profile->family_status == 'married' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="divorced" {{ $profile->family_status == 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="widowed" {{ $profile->family_status == 'widowed' ? 'selected' : '' }}>Veuf/Veuve</option>
                        </select>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Religion</label>
                        <input type="text" name="religion" value="{{ $profile->religion }}" id="sum-religion" placeholder="Optionnel" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                </div>
            </div>

            <!-- STEP 2: CONTACT -->
            <div class="form-step" id="step-content-2">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="phone" class="w-5 h-5 text-[#4B6A95]"></i>
                    <span>2. Coordonnées & Urgence</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Adresse de résidence</label>
                        <input type="text" name="address" value="{{ $profile->address }}" required id="sum-address" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Téléphone Personnel</label>
                        <input type="tel" name="phone" value="{{ $profile->phone }}" required id="sum-phone" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold focus:border-[#4B6A95] focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Email (Compte Utilisateur)</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold opacity-70 cursor-not-allowed text-slate-500">
                        <p class="text-[10px] text-slate-400 mt-1">L'email officiel ne peut être modifié que par l'administration.</p>
                    </div>
                    <div class="md:col-span-2 pt-6 border-t border-slate-100 mt-2">
                        <h3 class="text-xs font-black text-rose-600 uppercase mb-4 tracking-wider flex items-center gap-1.5">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            <span>Personne à prévenir en cas d'urgence</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="emergency_name" value="{{ $profile->emergency_contact_name }}" placeholder="Nom Complet" id="sum-em-name" class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                            <input type="text" name="emergency_relation" value="{{ $profile->emergency_contact_relation }}" placeholder="Lien de parenté" id="sum-em-rel" class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                            <input type="tel" name="emergency_phone" value="{{ $profile->emergency_contact_phone }}" placeholder="Téléphone d'urgence" id="sum-em-phone" class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                            <input type="email" name="emergency_email" value="{{ $profile->emergency_contact_email }}" placeholder="Email d'urgence (Optionnel)" id="sum-em-email" class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: PROFESSIONAL & ACADEMIC -->
            <div class="form-step" id="step-content-3">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-[#4B6A95]"></i>
                    <span>3. Profil Professionnel & Académique</span>
                </h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Parcours Académique</h3>
                        <div id="academic-history-container" class="space-y-3">
                            @if($profile->academicHistories && $profile->academicHistories->count() > 0)
                                @foreach($profile->academicHistories as $edu)
                                    <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200 relative">
                                        <input type="text" name="acad_establishment[]" value="{{ $edu->establishment_name }}" placeholder="Établissement" class="md:col-span-2 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                        <input type="text" name="acad_field[]" value="{{ $edu->field_of_study }}" placeholder="Thème / Filière" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                        <input type="text" name="acad_degree[]" value="{{ $edu->degree_obtained }}" placeholder="Diplôme" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                        <div class="flex gap-2">
                                            <input type="number" name="acad_year[]" value="{{ $edu->graduation_year }}" placeholder="Année" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold w-full">
                                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200 relative">
                                    <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                    <input type="text" name="acad_field[]" placeholder="Thème / Filière" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                    <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                                    <input type="number" name="acad_year[]" placeholder="Année" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold w-full">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addAcademicRow()" class="mt-3 text-xs font-bold text-[#4B6A95] flex items-center gap-1.5 hover:underline">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Ajouter un autre diplôme</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Profession actuelle</label>
                            <input type="text" name="current_profession" value="{{ $profile->current_profession }}" id="sum-profession" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Employeur actuel</label>
                            <input type="text" name="current_employer" value="{{ $profile->current_employer }}" id="sum-employer" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Employeur précédent</label>
                            <input type="text" name="previous_employer" value="{{ $profile->previous_employer }}" id="sum-prev-employer" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Dernier diplôme obtenu</label>
                            <input type="text" name="highest_degree" value="{{ $profile->highest_degree }}" id="sum-highest-degree" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Description du poste actuel</label>
                            <textarea name="job_description" id="sum-job-desc" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">{{ $profile->job_description }}</textarea>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Utilité de cette formation pour votre service</label>
                            <textarea name="training_utility" id="sum-utility" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">{{ $profile->training_utility }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: HEALTH & LOGISTICS -->
            @php
                $health = is_array($profile->health_questionnaire_json) ? $profile->health_questionnaire_json : json_decode($profile->health_questionnaire_json, true) ?? [];
            @endphp
            <div class="form-step" id="step-content-4">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="heart-pulse" class="w-5 h-5 text-[#4B6A95]"></i>
                    <span>4. Santé & Logistique</span>
                </h2>
                <div class="space-y-6">
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-2">Questionnaire Médical</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-xs p-3.5 bg-white rounded-xl border border-slate-200">
                                <span class="font-bold text-slate-700">Souffrez-vous d'une affection ou maladie chronique ?</span>
                                <div class="flex gap-4 font-semibold">
                                    <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="health_q[chronic]" value="yes" {{ isset($health['chronic']) && $health['chronic'] == 'yes' ? 'checked' : '' }} class="text-[#4B6A95]"> Oui</label>
                                    <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="health_q[chronic]" value="no" {{ !isset($health['chronic']) || $health['chronic'] == 'no' ? 'checked' : '' }} class="text-[#4B6A95]"> Non</label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-xs p-3.5 bg-white rounded-xl border border-slate-200">
                                <span class="font-bold text-slate-700">Suivez-vous un traitement médical régulier ?</span>
                                <div class="flex gap-4 font-semibold">
                                    <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="health_q[treatment]" value="yes" {{ isset($health['treatment']) && $health['treatment'] == 'yes' ? 'checked' : '' }} class="text-[#4B6A95]"> Oui</label>
                                    <label class="flex items-center gap-1.5 cursor-pointer"><input type="radio" name="health_q[treatment]" value="no" {{ !isset($health['treatment']) || $health['treatment'] == 'no' ? 'checked' : '' }} class="text-[#4B6A95]"> Non</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Antécédents & Observations médicales</label>
                        <textarea name="health_record_obs" id="sum-health-obs" rows="2" placeholder="R.A.S si aucune observation..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">{{ $profile->health_record_obs }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Assurance Médicale</label>
                            <input type="text" name="insurance_details" value="{{ $profile->insurance_details }}" id="sum-insurance" placeholder="Nom de la compagnie / police" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Prime / Statut d'assurance</label>
                            <input type="text" name="insurance_premium_status" value="{{ $profile->insurance_premium_status }}" id="sum-premium" placeholder="À jour / En attente" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: DOCUMENTS -->
            <div class="form-step" id="step-content-5">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="paperclip" class="w-5 h-5 text-[#4B6A95]"></i>
                    <span>5. Pièces Justificatives (Téléversement)</span>
                </h2>
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-xs font-black text-slate-800 mb-1">Curriculum Vitae (CV actualisé)</label>
                        <input type="file" name="doc_cv" data-label="CV" class="doc-input w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#4B6A95] file:text-white hover:file:bg-[#354e70]">
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-xs font-black text-slate-800 mb-1">Dernier Diplôme Obtenu</label>
                        <input type="file" name="doc_diploma" data-label="Diplôme" class="doc-input w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#4B6A95] file:text-white hover:file:bg-[#354e70]">
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-xs font-black text-slate-800 mb-1">Attestation / Lettre de Recommandation</label>
                        <input type="file" name="doc_recommendation" data-label="Recommandation" class="doc-input w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#4B6A95] file:text-white hover:file:bg-[#354e70]">
                    </div>
                </div>
            </div>

            <!-- STEP 6: SUMMARY & SUBMISSION -->
            <div class="form-step" id="step-content-6">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mb-6 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <i data-lucide="check-check" class="w-5 h-5 text-emerald-600"></i>
                    <span>6. Récapitulatif & Confirmation</span>
                </h2>
                
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Identité</span>
                        <strong id="disp-fullname" class="text-slate-900 text-sm font-bold"></strong>
                        <div id="disp-birth" class="text-slate-600"></div>
                        <div class="text-slate-600"><span id="disp-gender"></span> — <span id="disp-nat"></span> — <span id="disp-rel"></span></div>
                    </div>
                    
                    <div class="border-t border-slate-200 pt-3">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Coordonnées</span>
                        <div id="disp-address" class="text-slate-800 font-bold"></div>
                        <div id="disp-phone" class="text-slate-600"></div>
                        <div class="text-rose-600 font-semibold mt-1">Urgence : <span id="disp-emergency"></span></div>
                    </div>

                    <div class="border-t border-slate-200 pt-3">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Profil Pro & Diplôme</span>
                        <div class="text-slate-800 font-bold"><span id="disp-profession"></span> chez <span id="disp-employer"></span></div>
                        <div class="text-slate-600">Diplôme : <strong id="disp-degree" class="text-[#4B6A95]"></strong></div>
                    </div>

                    <div class="border-t border-slate-200 pt-3">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block mb-1.5">Pièces Jointes Rattachées</span>
                        <div id="disp-docs" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-start gap-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                    <p class="text-xs text-emerald-900 font-medium">En soumettant cette demande, vous certifiez sur l'honneur l'exactitude des renseignements fournis. Une notification sera transmise à l'administrateur pour revue.</p>
                </div>
            </div>

        </div>

        <!-- Wizard Footer Navigation Buttons -->
        <div class="px-6 py-5 sm:px-10 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <button type="button" id="prev-btn" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition flex items-center gap-1.5" style="display: none;">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Précédent</span>
            </button>
            <div class="flex-grow"></div>
            <button type="button" id="next-btn" class="px-6 py-2.5 rounded-full bg-[#4B6A95] text-white font-bold text-xs hover:bg-[#354e70] shadow-xs transition flex items-center gap-1.5">
                <span>Suivant</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
            <button type="submit" id="submit-btn" class="px-6 py-2.5 rounded-full bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-xs transition flex items-center gap-1.5" style="display: none;">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Transmettre la mise à jour</span>
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 6;

    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    function updateStep() {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById(`step-content-${currentStep}`).classList.add('active');

        for (let i = 1; i <= totalSteps; i++) {
            const dot = document.getElementById(`step-dot-${i}`);
            dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center font-black text-xs transition-all duration-300 ';
            if (i === currentStep) {
                dot.classList.add('step-active');
            } else if (i < currentStep) {
                dot.classList.add('step-completed');
                dot.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';
            } else {
                dot.classList.add('step-inactive');
                dot.innerText = i;
            }
        }
        if (window.lucide) {
            lucide.createIcons();
        }

        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';

        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
            generateSummary();
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    function addAcademicRow() {
        const container = document.getElementById('academic-history-container');
        const row = document.createElement('div');
        row.className = 'academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200 relative';
        row.innerHTML = `
            <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
            <input type="text" name="acad_field[]" placeholder="Thème / Filière" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
            <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
            <div class="flex gap-2">
                <input type="number" name="acad_year[]" placeholder="Année" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold w-full">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    function generateSummary() {
        document.getElementById('disp-fullname').innerText = document.getElementById('sum-first-names').value + ' ' + document.getElementById('sum-last-name').value;
        document.getElementById('disp-birth').innerText = document.getElementById('sum-birth-date').value + ' à ' + document.getElementById('sum-birth-place').value;
        let genderEl = document.getElementById('sum-gender');
        document.getElementById('disp-gender').innerText = genderEl.options[genderEl.selectedIndex].text;
        document.getElementById('disp-nat').innerText = document.getElementById('sum-nationality').value;
        document.getElementById('disp-rel').innerText = document.getElementById('sum-religion').value || 'N/A';
        
        document.getElementById('disp-address').innerText = document.getElementById('sum-address').value;
        document.getElementById('disp-phone').innerText = document.getElementById('sum-phone').value;
        
        const emName = document.getElementById('sum-em-name').value;
        const emRel = document.getElementById('sum-em-rel').value;
        const emPhone = document.getElementById('sum-em-phone').value;
        document.getElementById('disp-emergency').innerText = `${emName} (${emRel}) - Tel: ${emPhone}`;
        
        document.getElementById('disp-profession').innerText = document.getElementById('sum-profession').value;
        document.getElementById('disp-employer').innerText = document.getElementById('sum-employer').value;
        document.getElementById('disp-degree').innerText = document.getElementById('sum-highest-degree').value;

        const docsContainer = document.getElementById('disp-docs');
        docsContainer.innerHTML = '';
        let hasDocs = false;
        document.querySelectorAll('.doc-input').forEach(input => {
            if(input.files.length > 0) {
                hasDocs = true;
                const span = document.createElement('span');
                span.className = 'px-3 py-1 bg-white border border-blue-200 text-[#4B6A95] rounded-full text-[10px] font-bold';
                span.innerText = input.dataset.label;
                docsContainer.appendChild(span);
            }
        });
        if(!hasDocs) {
            docsContainer.innerHTML = '<span class="text-xs text-slate-400 italic">Aucune nouvelle pièce jointe téléversée.</span>';
        }
    }

    nextBtn.addEventListener('click', () => { 
        const currentStepEl = document.getElementById(`step-content-${currentStep}`);
        const inputs = currentStepEl.querySelectorAll('input, select, textarea');
        let isValid = true;
        for(let input of inputs) {
            if(!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
                break;
            }
        }
        if(!isValid) return;

        if(currentStep < totalSteps) { 
            currentStep++; 
            updateStep(); 
        } 
    });
    
    prevBtn.addEventListener('click', () => { 
        if(currentStep > 1) { 
            currentStep--; 
            updateStep(); 
        } 
    });
</script>
@endpush
@endsection
