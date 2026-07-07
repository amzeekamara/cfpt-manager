<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature : {{ $edition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Outfit', sans-serif; }
        .step-active { color: #1e3a8a; border-color: #1e3a8a; font-weight: 800; background: #eff6ff; }
        .step-inactive { color: #94a3b8; border-color: #e2e8f0; background: white; }
        .step-completed { color: #059669; border-color: #059669; background: #ecfdf5; }
        .form-step { display: none; }
        .form-step.active { display: block; animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-headline font-black text-blue-950 mb-4 tracking-tighter uppercase">Dossier de Candidature</h1>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border border-blue-200">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                {{ $edition->title }}
            </div>
        </div>

        <!-- Stepper -->
        <div class="flex justify-between items-center mb-16 max-w-3xl mx-auto">
            @for($i=1; $i<=6; $i++)
                <div class="flex flex-col items-center gap-2 relative">
                    <div id="step-dot-{{$i}}" class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 {{ $i === 1 ? 'step-active' : 'step-inactive' }}">
                        {{$i}}
                    </div>
                </div>
                @if($i < 6)
                    <div class="flex-1 h-[2px] bg-slate-200 mx-1"></div>
                @endif
            @endfor
        </div>

        <!-- Wizard Form -->
        <form action="{{ route('public.registration.store', $edition) }}" method="POST" enctype="multipart/form-data" id="wizard-form" class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden">
            @csrf
            
            <div class="p-8 md:p-14">
                
                <!-- STEP 1: IDENTITY -->
                <div class="form-step active" id="step-content-1">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-blue-50 text-blue-900 rounded-xl material-symbols-outlined">person</span>
                        1. État Civil & Identité
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prénoms</label>
                            <input type="text" name="first_names" required id="sum-first-names" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom de famille</label>
                            <input type="text" name="last_name" required id="sum-last-name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Date de Naissance</label>
                            <input type="date" name="birth_date" required id="sum-birth-date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Lieu de Naissance</label>
                            <input type="text" name="birth_place" required id="sum-birth-place" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Sexe</label>
                            <select name="gender" id="sum-gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nationalité</label>
                            <input type="text" name="nationality" required id="sum-nationality" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Photo récente</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-xs">
                        </div>
                         <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Situation Familiale</label>
                            <select name="family_status" id="sum-family-status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <option value="single">Célibataire</option>
                                <option value="married">Marié(e)</option>
                                <option value="divorced">Divorcé(e)</option>
                                <option value="widowed">Veuf/Veuve</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Religion</label>
                            <input type="text" name="religion" id="sum-religion" placeholder="Optionnel" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                        </div>
                    </div>
                </div>

                <!-- STEP 2: CONTACT -->
                <div class="form-step" id="step-content-2">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-rose-50 text-rose-600 rounded-xl material-symbols-outlined">contact_phone</span>
                        2. Coordonnées & Urgence
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Adresse de résidence</label>
                            <input type="text" name="address" required id="sum-address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Téléphone Personnel</label>
                            <input type="tel" name="phone" required id="sum-phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Email (Identifiant Futur)</label>
                            <input type="email" name="email" required id="sum-email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2 pt-6 border-t border-slate-100 mt-4">
                            <h3 class="text-sm font-black text-rose-600 uppercase mb-4">Personne à prévenir en cas d'urgence</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="emergency_name" placeholder="Nom Complet" id="sum-em-name" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm">
                                <input type="text" name="emergency_relation" placeholder="Lien de parenté" id="sum-em-rel" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm">
                                <input type="tel" name="emergency_phone" placeholder="Téléphone" id="sum-em-phone" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm">
                                <input type="email" name="emergency_email" placeholder="Email (Optionnel)" id="sum-em-email" class="bg-slate-50 border rounded-xl px-4 py-3 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: PROFESSIONAL & ACADEMIC -->
                <div class="form-step" id="step-content-3">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl material-symbols-outlined">workspace_premium</span>
                        3. Profil Pro & Académique
                    </h2>
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Parcours Académique</h3>
                            <div id="academic-history-container" class="space-y-4">
                                <div class="academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl relative">
                                    <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_field[]" placeholder="Filière" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                    <input type="number" name="acad_year[]" placeholder="Année" class="bg-white border rounded-xl px-3 py-2 text-sm">
                                </div>
                            </div>
                            <button type="button" onclick="addAcademicRow()" class="mt-4 text-xs font-bold text-blue-600 flex items-center gap-1 hover:underline">
                                <span class="material-symbols-outlined text-[16px]">add_circle</span> Ajouter un autre diplôme
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Profession actuelle</label>
                                <input type="text" name="current_profession" id="sum-profession" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur actuel</label>
                                <input type="text" name="current_employer" id="sum-employer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Employeur précédent</label>
                                <input type="text" name="previous_employer" id="sum-prev-employer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Dernier diplôme</label>
                                <input type="text" name="highest_degree" id="sum-highest-degree" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Description du travail</label>
                                <textarea name="job_description" id="sum-job-desc" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3"></textarea>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Utilité de ce stage pour votre service</label>
                                <textarea name="training_utility" id="sum-utility" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: HEALTH & LOGISTICS -->
                <div class="form-step" id="step-content-4">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-amber-50 text-amber-600 rounded-xl material-symbols-outlined">health_and_safety</span>
                        4. Santé & Logistique
                    </h2>
                    <div class="space-y-6">
                        <div class="bg-slate-50 p-6 rounded-3xl space-y-4">
                            <h3 class="text-sm font-bold text-slate-700 mb-4">Questionnaire de Santé</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm p-3 bg-white rounded-xl border border-slate-100">
                                    <span>Souffrez-vous d'une maladie chronique ?</span>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-1"><input type="radio" name="health_q[chronic]" value="yes"> Oui</label>
                                        <label class="flex items-center gap-1"><input type="radio" name="health_q[chronic]" value="no" checked> Non</label>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-sm p-3 bg-white rounded-xl border border-slate-100">
                                    <span>Suivez-vous un traitement médical régulier ?</span>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-1"><input type="radio" name="health_q[treatment]" value="yes"> Oui</label>
                                        <label class="flex items-center gap-1"><input type="radio" name="health_q[treatment]" value="no" checked> Non</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Antécédents & Observations médicales</label>
                            <textarea name="health_record_obs" id="sum-health-obs" rows="2" placeholder="RAS si aucune observation..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Assurance Médicale</label>
                                <input type="text" name="insurance_details" id="sum-insurance" placeholder="Nom de l'assurance" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prime d'assurance</label>
                                <input type="number" name="insurance_premium" id="sum-premium" placeholder="FCFA" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Préférence Hébergement</label>
                            <select name="accommodation_preference" id="sum-accommodation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <option value="none">Pas d'hébergement</option>
                                <option value="hotel">Hôtel (selon disponibilité)</option>
                                <option value="allocation">Prime d'allocation</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: DOCUMENTS -->
                <div class="form-step" id="step-content-5">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-purple-50 text-purple-600 rounded-xl material-symbols-outlined">upload_file</span>
                        5. Documents Justificatifs
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center group hover:border-blue-400 transition-colors">
                            <label class="cursor-pointer block">
                                <span class="material-symbols-outlined text-3xl text-slate-300 group-hover:text-blue-500 mb-2">picture_as_pdf</span>
                                <div class="text-xs font-bold">Curriculum Vitae (CV)</div>
                                <input type="file" name="cv" required class="hidden doc-input" data-label="CV">
                            </label>
                        </div>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center group hover:border-blue-400 transition-colors">
                            <label class="cursor-pointer block">
                                <span class="material-symbols-outlined text-3xl text-slate-300 group-hover:text-blue-500 mb-2">draw</span>
                                <div class="text-xs font-bold">Lettre de Présentation</div>
                                <input type="file" name="official_intro" required class="hidden doc-input" data-label="Lettre Présentation">
                            </label>
                        </div>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center group hover:border-blue-400 transition-colors">
                            <label class="cursor-pointer block">
                                <span class="material-symbols-outlined text-3xl text-slate-300 group-hover:text-blue-500 mb-2">contract</span>
                                <div class="text-xs font-bold">Lettre d'Engagement</div>
                                <input type="file" name="engagement_letter" class="hidden doc-input" data-label="Lettre Engagement">
                            </label>
                        </div>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center group hover:border-blue-400 transition-colors">
                            <label class="cursor-pointer block">
                                <span class="material-symbols-outlined text-3xl text-slate-300 group-hover:text-blue-500 mb-2">school</span>
                                <div class="text-xs font-bold">Diplômes / Attestations</div>
                                <input type="file" name="diploma" class="hidden doc-input" data-label="Diplômes">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- STEP 6: SUMMARY -->
                <div class="form-step" id="step-content-6">
                    <h2 class="text-2xl font-headline font-black text-slate-800 mb-8 flex items-center gap-3">
                        <span class="p-2 bg-blue-950 text-white rounded-xl material-symbols-outlined">fact_check</span>
                        6. Récapitulatif Final
                    </h2>
                    
                    <div class="bg-blue-50/50 p-8 rounded-3xl border border-blue-100 space-y-8 max-h-[500px] overflow-y-auto custom-scrollbar">
                        <!-- Identity Summary -->
                        <div>
                            <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest mb-3 border-b border-blue-200 pb-1">Identité</h3>
                            <div class="grid grid-cols-2 text-sm gap-x-4 gap-y-2">
                                <div><span class="text-slate-400">Nom complet :</span> <span id="disp-fullname" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Naissance :</span> <span id="disp-birth" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Genre :</span> <span id="disp-gender" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Nationalité :</span> <span id="disp-nat" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Religion :</span> <span id="disp-rel" class="font-bold"></span></div>
                            </div>
                        </div>

                        <!-- Contact Summary -->
                        <div>
                            <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest mb-3 border-b border-rose-200 pb-1">Coordonnées</h3>
                            <div class="grid grid-cols-2 text-sm gap-x-4 gap-y-2">
                                <div class="col-span-2"><span class="text-slate-400">Adresse :</span> <span id="disp-address" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Tel :</span> <span id="disp-phone" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Email :</span> <span id="disp-email" class="font-bold"></span></div>
                                <div class="col-span-2 mt-2 p-2 bg-white rounded-xl border"><span class="text-rose-600 font-bold uppercase text-[10px] block">Urgence</span> <span id="disp-emergency" class="font-bold"></span></div>
                            </div>
                        </div>

                        <!-- Pro Summary -->
                        <div>
                            <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest mb-3 border-b border-emerald-200 pb-1">Profil Professionnel</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="text-slate-400">Profession :</span> <span id="disp-profession" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Employeur :</span> <span id="disp-employer" class="font-bold"></span></div>
                                <div><span class="text-slate-400">Niveau d'études :</span> <span id="disp-degree" class="font-bold"></span></div>
                            </div>
                        </div>

                        <!-- Documents Summary -->
                        <div>
                            <h3 class="text-xs font-black text-purple-900 uppercase tracking-widest mb-3 border-b border-purple-200 pb-1">Pièces Jointes</h3>
                            <div id="disp-docs" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-start gap-4">
                        <span class="material-symbols-outlined text-emerald-600">verified_user</span>
                        <div>
                            <p class="text-sm text-emerald-950 font-bold">Déclaration sur l'honneur</p>
                            <p class="text-[11px] text-emerald-800">Je certifie que toutes les informations fournies sont exactes et sincères. Toute fausse déclaration entraînera l'annulation immédiate de ma candidature.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="mt-16 flex justify-between items-center bg-slate-50 -mx-8 -mb-14 p-8 border-t border-slate-100">
                    <button type="button" id="prev-btn" class="hidden px-8 py-3 rounded-xl font-bold text-slate-500 hover:bg-white transition-all">Précédent</button>
                    <div></div>
                    <button type="button" id="next-btn" class="px-10 py-4 rounded-2xl bg-blue-950 text-white font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:-translate-y-1 transition-all">Suivant</button>
                    <button type="submit" id="submit-btn" class="hidden px-10 py-4 rounded-2xl bg-emerald-600 text-white font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:-translate-y-1 transition-all">Soumettre Candidature</button>
                </div>

            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <script>
        let currentStep = 1;
        const totalSteps = 6;

        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const submitBtn = document.getElementById('submit-btn');

        function updateStep() {
            document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
            document.getElementById(`step-content-${currentStep}`).classList.add('active');

            for(let i=1; i<=totalSteps; i++) {
                const dot = document.getElementById(`step-dot-${i}`);
                if(i < currentStep) {
                    dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-completed';
                    dot.innerHTML = '<span class="material-symbols-outlined text-sm">check</span>';
                } else if(i === currentStep) {
                    dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-active';
                    dot.innerHTML = i;
                } else {
                    dot.className = 'w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all duration-300 step-inactive';
                    dot.innerHTML = i;
                }
            }

            prevBtn.classList.toggle('hidden', currentStep === 1);
            if(currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                generateSummary();
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function addAcademicRow() {
            const container = document.getElementById('academic-history-container');
            const row = document.createElement('div');
            row.className = 'academic-row grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50 p-4 rounded-2xl relative animate-in fade-in slide-in-from-top-2 duration-300';
            row.innerHTML = `
                <input type="text" name="acad_establishment[]" placeholder="Établissement" class="md:col-span-2 bg-white border rounded-xl px-3 py-2 text-sm">
                <input type="text" name="acad_field[]" placeholder="Filière" class="bg-white border rounded-xl px-3 py-2 text-sm">
                <input type="text" name="acad_degree[]" placeholder="Diplôme" class="bg-white border rounded-xl px-3 py-2 text-sm">
                <div class="flex gap-2">
                    <input type="number" name="acad_year[]" placeholder="Année" class="bg-white border rounded-xl px-3 py-2 text-sm w-full">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 material-symbols-outlined">delete</button>
                </div>
            `;
            container.appendChild(row);
        }

        function generateSummary() {
            // Text fields
            document.getElementById('disp-fullname').innerText = document.getElementById('sum-first-names').value + ' ' + document.getElementById('sum-last-name').value;
            document.getElementById('disp-birth').innerText = document.getElementById('sum-birth-date').value + ' à ' + document.getElementById('sum-birth-place').value;
            document.getElementById('disp-gender').innerText = document.getElementById('sum-gender').value === 'M' ? 'Masculin' : 'Féminin';
            document.getElementById('disp-nat').innerText = document.getElementById('sum-nationality').value;
            document.getElementById('disp-rel').innerText = document.getElementById('sum-religion').value || 'N/A';
            
            document.getElementById('disp-address').innerText = document.getElementById('sum-address').value;
            document.getElementById('disp-phone').innerText = document.getElementById('sum-phone').value;
            document.getElementById('disp-email').innerText = document.getElementById('sum-email').value;
            
            const emName = document.getElementById('sum-em-name').value;
            const emRel = document.getElementById('sum-em-rel').value;
            const emPhone = document.getElementById('sum-em-phone').value;
            document.getElementById('disp-emergency').innerText = `${emName} (${emRel}) - Tel: ${emPhone}`;
            
            document.getElementById('disp-profession').innerText = document.getElementById('sum-profession').value;
            document.getElementById('disp-employer').innerText = document.getElementById('sum-employer').value;
            document.getElementById('disp-degree').innerText = document.getElementById('sum-highest-degree').value;

            // Docs
            const docsContainer = document.getElementById('disp-docs');
            docsContainer.innerHTML = '';
            document.querySelectorAll('.doc-input').forEach(input => {
                if(input.files.length > 0) {
                    const span = document.createElement('span');
                    span.className = 'px-3 py-1 bg-white border border-blue-200 text-blue-800 rounded-full text-[10px] font-bold';
                    span.innerText = input.dataset.label;
                    docsContainer.appendChild(span);
                }
            });
        }

        nextBtn.addEventListener('click', () => { if(currentStep < totalSteps) { currentStep++; updateStep(); } });
        prevBtn.addEventListener('click', () => { if(currentStep > 1) { currentStep--; updateStep(); } });
    </script>

</body>
</html>
