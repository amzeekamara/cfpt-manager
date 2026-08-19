@php
    $currentCohort = $cohort ?? null;
    $cohortTitle = $currentCohort ? $currentCohort->title : 'Formulaire d\'Inscription';
@endphp
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier de Candidature — Programme PFPT CFPT Sénégal-Japon & JICA</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f3f6fb',
                            100: '#e4edf8',
                            200: '#d2e4ff',
                            300: '#94b9e6',
                            400: '#6b90c0',
                            500: '#4B6A95',
                            600: '#354e70',
                            700: '#263a54',
                            800: '#162536',
                            900: '#0c1622',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        headline: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-[#4B6A95] selection:text-white">

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4B6A95] rounded-lg p-1">
                <img src="{{ asset('images/LOGO PFPT.png') }}" alt="Logo PFPT" class="h-9 w-auto">
                <div class="border-l border-slate-200 pl-3">
                    <span class="text-[11px] font-bold text-slate-500 block uppercase tracking-wider">CFPT Sénégal-Japon — JICA</span>
                    <span class="text-sm font-extrabold text-[#4B6A95] font-headline leading-none">Programme PFPT</span>
                </div>
            </a>
            
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#4B6A95] bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-lg transition">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Retour au Portail</span>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 md:py-12">
        
        <!-- Header Title & Program Badge -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center mb-2">
                <span class="text-xs font-black uppercase tracking-wider text-[#4B6A95]">Candidatures Ouvertes — Sessions Officielles</span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 font-headline tracking-tight">
                Dossier de Candidature
            </h1>
            <p class="text-slate-600 text-sm sm:text-base mt-2 max-w-xl mx-auto">
                Remplissez directement votre formulaire en sélectionnant votre séminaire de formation et votre spécialité.
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-600 rounded-r-xl text-rose-800 text-sm flex items-start gap-3 shadow-xs">
                <span class="material-symbols-outlined text-rose-600 mt-0.5">error</span>
                <div>
                    <strong class="font-bold">Attention :</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-[#f3f6fb] border-l-4 border-[#4B6A95] rounded-r-xl text-[#162536] text-sm flex items-start gap-3 shadow-xs">
                <span class="material-symbols-outlined text-[#4B6A95] mt-0.5">check_circle</span>
                <div>
                    <strong class="font-bold">Succès :</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl text-amber-900 text-sm shadow-xs">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-600 text-[20px]">warning</span>
                    Veuillez corriger les erreurs suivantes :
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-amber-800 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stepper (3 Steps) -->
        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-xs mb-8">
            <div class="relative flex items-center justify-between">
                <!-- Progress Line Background -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 w-full z-0 rounded-full"></div>
                <!-- Active Progress Line -->
                <div id="stepper-progress" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-[#4B6A95] z-0 rounded-full transition-all duration-300 w-0"></div>

                <!-- Step 1 Indicator -->
                <div class="relative z-10 flex flex-col items-center group cursor-pointer" onclick="goToStep(1)">
                    <div id="step-badge-1" class="w-10 h-10 rounded-full bg-[#4B6A95] text-white font-bold flex items-center justify-center text-sm shadow-xs ring-4 ring-blue-50 transition-all">
                        1
                    </div>
                    <span id="step-label-1" class="mt-2 text-xs font-bold text-slate-900 font-headline hidden sm:block">1. Séminaire & Identité</span>
                </div>

                <!-- Step 2 Indicator -->
                <div class="relative z-10 flex flex-col items-center group cursor-pointer" onclick="goToStep(2)">
                    <div id="step-badge-2" class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-sm border-2 border-slate-200 transition-all">
                        2
                    </div>
                    <span id="step-label-2" class="mt-2 text-xs font-medium text-slate-500 font-headline hidden sm:block">2. Profil & Logistique</span>
                </div>

                <!-- Step 3 Indicator -->
                <div class="relative z-10 flex flex-col items-center group cursor-pointer" onclick="goToStep(3)">
                    <div id="step-badge-3" class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-sm border-2 border-slate-200 transition-all">
                        3
                    </div>
                    <span id="step-label-3" class="mt-2 text-xs font-medium text-slate-500 font-headline hidden sm:block">3. Pièces & Validation</span>
                </div>
            </div>
            
            <!-- Mobile Step Title Subtitle -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 sm:hidden font-medium">
                <span id="mobile-step-title">Étape 1 sur 3 : Séminaire & Identité</span>
                <span class="text-[#4B6A95] font-semibold">~2 min</span>
            </div>
        </div>

        <!-- Wizard Form -->
        <form action="{{ route('public.registration.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="wizard-form" 
              class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
            @csrf

            <div class="p-6 sm:p-8 md:p-10">

                <!-- ============================================================== -->
                <!-- STEP 1 : SÉMINAIRE, IDENTITÉ, CONTACT & URGENCE -->
                <!-- ============================================================== -->
                <div class="form-step active" id="step-1">
                    
                    <!-- Section A : Choix de la Formation & Spécialité -->
                    <div class="mb-8 pb-6 border-b border-slate-100">
                        <div class="mb-4">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 font-headline">Choix du Séminaire & Spécialisation</h3>
                            <p class="text-xs sm:text-sm text-slate-500">Sélectionnez la session de formation et votre thème ciblé</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Séminaire Selector -->
                            <div class="sm:col-span-2">
                                <label for="seminar_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Séminaire de formation <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="seminar_id" id="seminar_id" required onchange="onSeminarChange(this.value)"
                                            class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-xs sm:text-sm font-medium text-slate-800 outline-none transition cursor-pointer @error('seminar_id') border-rose-500 @enderror">
                                        <option value="" disabled {{ empty($selectedSeminarId) ? 'selected' : '' }}>-- Choisissez un séminaire de formation --</option>
                                        @foreach($seminars as $sem)
                                            @if($sem->isOpenForApplication())
                                                <option value="{{ $sem->id }}" {{ (old('seminar_id', $selectedSeminarId ?? '') == $sem->id) ? 'selected' : '' }} data-open="true">
                                                    {{ $sem->title }} (Du {{ \Carbon\Carbon::parse($sem->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($sem->end_date)->format('d/m/Y') }}) — Inscriptions Ouvertes
                                                </option>
                                            @elseif($sem->isOngoing())
                                                <option value="{{ $sem->id }}" disabled data-open="false">
                                                    {{ $sem->title }} (Du {{ \Carbon\Carbon::parse($sem->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($sem->end_date)->format('d/m/Y') }}) — [En cours - Inscriptions Closes]
                                                </option>
                                            @else
                                                <option value="{{ $sem->id }}" disabled data-open="false">
                                                    {{ $sem->title }} — [Session Clôturée]
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @error('seminar_id')
                                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-[#4B6A95]">info</span>
                                    <span>Conformément aux directives JICA, seules les sessions à venir acceptent les candidatures.</span>
                                </p>
                            </div>

                            <!-- Thème / Topic Selector -->
                            <div class="sm:col-span-2">
                                <label for="topic_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Thème / Module de spécialisation <span class="text-slate-400 font-normal">(Optionnel)</span>
                                </label>
                                <div class="relative">
                                    <select name="topic_id" id="topic_id"
                                            class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-xs sm:text-sm font-medium text-slate-800 outline-none transition cursor-pointer @error('topic_id') border-rose-500 @enderror">
                                        <option value="">Sélectionnez d'abord un séminaire</option>
                                    </select>
                                </div>
                                @error('topic_id')
                                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section B : Identité & Photo -->
                    <div class="pb-4 mb-6 border-b border-slate-100">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 font-headline">Identité & Coordonnées</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Renseignez vos informations d'état civil et de contact</p>
                    </div>

                    <!-- Photo Upload Preview -->
                    <div class="mb-8 flex flex-col sm:flex-row items-center gap-5">
                        <div class="w-16 h-16 rounded-full border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-slate-700 shrink-0" id="photo-preview-container">
                            <span class="material-symbols-outlined text-3xl text-slate-400" id="photo-placeholder-icon">account_circle</span>
                            <img id="photo-preview-img" class="w-full h-full object-cover hidden rounded-full" alt="Aperçu photo">
                        </div>
                        <div class="text-center sm:text-left flex-1">
                            <label for="photo-input" class="block text-sm font-semibold text-slate-800 mb-1 cursor-pointer">
                                Photo d'identité récente <span class="text-xs font-normal text-slate-500">(Optionnel)</span>
                            </label>
                            <p class="text-xs text-slate-500 mb-2">Format JPG, PNG max 2 Mo.</p>
                            <label for="photo-input" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-800 text-xs font-semibold rounded-lg border border-slate-300 shadow-2xs cursor-pointer transition">
                                <span class="material-symbols-outlined text-[16px] text-[#4B6A95]">upload</span>
                                <span>Choisir une photo</span>
                            </label>
                            <input type="file" name="photo" id="photo-input" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                        </div>
                    </div>

                    <!-- Identity Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                        <div>
                            <label for="first_names" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Prénoms <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="first_names" id="first_names" value="{{ old('first_names') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Babacar">
                        </div>

                        <div>
                            <label for="last_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nom de famille <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Ndiaye">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Date de naissance <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition">
                        </div>

                        <div>
                            <label for="birth_place" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Lieu de naissance <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Dakar">
                        </div>

                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Sexe <span class="text-rose-500">*</span>
                            </label>
                            <select name="gender" id="gender" required
                                    class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition">
                                <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div>
                            <label for="nationality" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nationalité <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nationality" id="nationality" value="{{ old('nationality', 'Sénégalaise') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Sénégalaise">
                        </div>

                        <div>
                            <label for="residence_country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pays de résidence <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="residence_country" id="residence_country" value="{{ old('residence_country', 'Sénégal') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Sénégal">
                        </div>

                        <div>
                            <label for="family_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Situation familiale <span class="text-rose-500">*</span>
                            </label>
                            <select name="family_status" id="family_status" required
                                    class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition">
                                <option value="single" {{ old('family_status') === 'single' ? 'selected' : '' }}>Célibataire</option>
                                <option value="married" {{ old('family_status') === 'married' ? 'selected' : '' }}>Marié(e)</option>
                                <option value="divorced" {{ old('family_status') === 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="widowed" {{ old('family_status') === 'widowed' ? 'selected' : '' }}>Veuf / Veuve</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contact & Emergency Section -->
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">
                            Coordonnées & Contact d'urgence
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Adresse de résidence complète <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                       class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                       placeholder="Ex: Villa 124, Sacré-Cœur 3, Dakar">
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Téléphone portable <span class="text-rose-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                       class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                       placeholder="+221 77 000 00 00">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Email de contact (Identifiant de connexion) <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                       placeholder="nom.prenom@domaine.com">
                            </div>
                        </div>

                        <!-- Emergency Box -->
                        <div class="p-4 sm:p-5 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">
                                Personne à contacter en cas d'urgence
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="emergency_name" class="block text-[11px] font-semibold text-slate-700 mb-1">Nom complet <span class="text-rose-500">*</span></label>
                                    <input type="text" name="emergency_name" id="emergency_name" value="{{ old('emergency_name') }}" required
                                           class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-lg text-sm outline-none">
                                </div>
                                <div>
                                    <label for="emergency_relation" class="block text-[11px] font-semibold text-slate-700 mb-1">Lien de parenté <span class="text-rose-500">*</span></label>
                                    <input type="text" name="emergency_relation" id="emergency_relation" value="{{ old('emergency_relation') }}" required placeholder="Ex: Conjoint, Frère"
                                           class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-lg text-sm outline-none">
                                </div>
                                <div>
                                    <label for="emergency_phone" class="block text-[11px] font-semibold text-slate-700 mb-1">Téléphone <span class="text-rose-500">*</span></label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone') }}" required
                                           class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-lg text-sm outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- STEP 2 : PROFIL PRO & LOGISTIQUE -->
                <!-- ============================================================== -->
                <div class="form-step" id="step-2">
                    <div class="pb-4 mb-6 border-b border-slate-100">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 font-headline">Profil Professionnel & Logistique</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Parcours académique, poste actuel et préférences logistiques</p>
                    </div>

                    <!-- Professional Information -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                        <div>
                            <label for="current_profession" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Profession actuelle <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="current_profession" id="current_profession" value="{{ old('current_profession') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Formateur en Électrotechnique">
                        </div>

                        <div>
                            <label for="current_employer" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Employeur actuel <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="current_employer" id="current_employer" value="{{ old('current_employer') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Lycée Technique de Dakar">
                        </div>

                        <div>
                            <label for="highest_degree" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Dernier diplôme obtenu <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="highest_degree" id="highest_degree" value="{{ old('highest_degree') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: Licence Professionnelle ou Master">
                        </div>

                        <div>
                            <label for="previous_employer" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Employeur précédent <span class="text-xs font-normal text-slate-400">(Optionnel)</span>
                            </label>
                            <input type="text" name="previous_employer" id="previous_employer" value="{{ old('previous_employer') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                   placeholder="Ex: SENELEC">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="job_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Description synthétique de vos responsabilités
                            </label>
                            <textarea name="job_description" id="job_description" rows="2"
                                      class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                      placeholder="Décrivez brièvement vos missions principales...">{{ old('job_description') }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="training_utility" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                En quoi cette formation sera-t-elle utile à votre structure ? <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="training_utility" id="training_utility" rows="2" required
                                      class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                      placeholder="Impact attendu sur votre travail ou celui de votre établissement...">{{ old('training_utility') }}</textarea>
                        </div>
                    </div>

                    <!-- Dynamic Academic History -->
                    <div class="pt-6 border-t border-slate-200 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                                Historique des Diplômes / Formations
                            </h3>
                            <button type="button" onclick="addAcademicRow()" 
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-slate-800 hover:text-black bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition">
                                <span class="material-symbols-outlined text-[16px] text-[#4B6A95]">add</span>
                                <span>Ajouter un diplôme</span>
                            </button>
                        </div>

                        <div id="academic-history-container" class="space-y-3">
                            <div class="academic-row grid grid-cols-1 sm:grid-cols-12 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="sm:col-span-4">
                                    <input type="text" name="acad_establishment[]" placeholder="Établissement / Université" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                                </div>
                                <div class="sm:col-span-3">
                                    <input type="text" name="acad_field[]" placeholder="Filière / Spécialité" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                                </div>
                                <div class="sm:col-span-3">
                                    <input type="text" name="acad_degree[]" placeholder="Diplôme obtenu" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                                </div>
                                <div class="sm:col-span-2 flex items-center gap-2">
                                    <input type="number" name="acad_year[]" placeholder="Année" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logistics & Health -->
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">
                            Logistique & Hébergement
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="accommodation_preference" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Option d'hébergement souhaitée <span class="text-rose-500">*</span>
                                </label>
                                <select name="accommodation_preference" id="accommodation_preference" required
                                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition">
                                    <option value="hotel" {{ old('accommodation_preference') === 'hotel' ? 'selected' : '' }}>Hébergement pris en charge (Hôtel / Résidence partenaire)</option>
                                    <option value="allocation" {{ old('accommodation_preference') === 'allocation' ? 'selected' : '' }}>Indemnité forfaitaire de séjour</option>
                                    <option value="none" {{ old('accommodation_preference') === 'none' ? 'selected' : '' }}>Aucun (Résident local à Dakar)</option>
                                </select>
                            </div>

                            <div>
                                <label for="religion" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Régime alimentaire particulier <span class="text-xs font-normal text-slate-400">(Optionnel)</span>
                                </label>
                                <input type="text" name="religion" id="religion" value="{{ old('religion') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-xl text-sm outline-none transition"
                                       placeholder="Ex: Végétarien, Halal, Sans sel">
                            </div>
                        </div>

                        <!-- Health & Observations -->
                        <div class="p-4 sm:p-5 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">
                                Informations Médicales & Particularités
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label for="health_record_obs" class="block text-[11px] font-semibold text-slate-700 mb-1">Observations médicales, allergies ou traitements en cours <span class="text-slate-400 font-normal">(Optionnel)</span></label>
                                    <textarea name="health_record_obs" id="health_record_obs" rows="2"
                                              class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#4B6A95] focus:ring-2 focus:ring-[#d2e4ff] rounded-lg text-sm outline-none"
                                              placeholder="Précisez ici toute condition médicale nécessitant une attention particulière...">{{ old('health_record_obs') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- STEP 3 : PIÈCES JOINTES & VALIDATION -->
                <!-- ============================================================== -->
                <div class="form-step" id="step-3">
                    <div class="pb-4 mb-6 border-b border-slate-100">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 font-headline">Pièces Justificatives & Confirmation</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Téléversez les documents requis pour l'instruction de votre dossier</p>
                    </div>

                    <!-- Upload Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <!-- CV -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="mb-1.5">
                                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Curriculum Vitae (CV) <span class="text-rose-500">*</span></span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Format PDF, DOC max 5 Mo.</p>
                            </div>
                            <label class="doc-upload-label flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-300 hover:border-[#4B6A95] bg-white rounded-lg cursor-pointer transition">
                                <span class="material-symbols-outlined text-slate-400 text-2xl mb-1">cloud_upload</span>
                                <span class="text-xs font-semibold text-slate-700 text-center" id="cv-status">
                                    Parcourir le fichier
                                </span>
                                <input type="file" name="cv" id="doc-cv" required accept=".pdf,.doc,.docx" class="hidden doc-file-input" data-target="cv-status" onchange="handleFileSelect(this)">
                            </label>
                        </div>

                        <!-- Official Intro / Lettre d'appui -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="mb-1.5">
                                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Lettre d'Appui / Introduction <span class="text-rose-500">*</span></span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Signée par votre hiérarchie / structure (PDF max 5 Mo).</p>
                            </div>
                            <label class="doc-upload-label flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-300 hover:border-[#4B6A95] bg-white rounded-lg cursor-pointer transition">
                                <span class="material-symbols-outlined text-slate-400 text-2xl mb-1">cloud_upload</span>
                                <span class="text-xs font-semibold text-slate-700 text-center" id="official-intro-status">
                                    Parcourir le fichier
                                </span>
                                <input type="file" name="official_intro" id="doc-official-intro" required accept=".pdf,.doc,.docx" class="hidden doc-file-input" data-target="official-intro-status" onchange="handleFileSelect(this)">
                            </label>
                        </div>

                        <!-- Engagement Letter -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="mb-1.5">
                                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Lettre d'Engagement <span class="text-xs font-normal text-slate-400">(Optionnel)</span></span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Engagement de participation continue (PDF max 5 Mo).</p>
                            </div>
                            <label class="doc-upload-label flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-300 hover:border-[#4B6A95] bg-white rounded-lg cursor-pointer transition">
                                <span class="material-symbols-outlined text-slate-400 text-2xl mb-1">cloud_upload</span>
                                <span class="text-xs font-semibold text-slate-700 text-center" id="engagement-status">
                                    Parcourir le fichier
                                </span>
                                <input type="file" name="engagement_letter" id="doc-engagement" accept=".pdf,.doc,.docx" class="hidden doc-file-input" data-target="engagement-status" onchange="handleFileSelect(this)">
                            </label>
                        </div>

                        <!-- Diplôme -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="mb-1.5">
                                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Copie du Dernier Diplôme <span class="text-xs font-normal text-slate-400">(Optionnel)</span></span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Format PDF, DOC ou Image max 10 Mo.</p>
                            </div>
                            <label class="doc-upload-label flex flex-col items-center justify-center p-3 border-2 border-dashed border-slate-300 hover:border-[#4B6A95] bg-white rounded-lg cursor-pointer transition">
                                <span class="material-symbols-outlined text-slate-400 text-2xl mb-1">cloud_upload</span>
                                <span class="text-xs font-semibold text-slate-700 text-center" id="diploma-status">
                                    Parcourir le fichier
                                </span>
                                <input type="file" name="diploma" id="doc-diploma" accept=".pdf,.doc,.docx,image/*" class="hidden doc-file-input" data-target="diploma-status" onchange="handleFileSelect(this)">
                            </label>
                        </div>
                    </div>

                    <!-- Live Summary Preview -->
                    <div class="p-5 bg-[#f3f6fb] rounded-2xl border border-[#d2e4ff] mb-6">
                        <div class="flex items-center justify-between mb-3 border-b border-[#e4edf8] pb-2">
                            <span class="text-xs font-bold text-[#4B6A95] uppercase tracking-wider">
                                Récapitulatif de votre candidature
                            </span>
                            <span class="text-xs text-[#4B6A95] font-semibold cursor-pointer hover:underline" onclick="goToStep(1)">Modifier</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2.5 gap-x-4 text-xs">
                            <div class="sm:col-span-2"><span class="text-slate-500">Séminaire :</span> <strong id="sum-seminar" class="text-[#4B6A95]">-</strong></div>
                            <div class="sm:col-span-2"><span class="text-slate-500">Thème / Spécialisation :</span> <strong id="sum-topic" class="text-slate-800">-</strong></div>
                            <div><span class="text-slate-500">Candidat :</span> <strong id="sum-name" class="text-slate-800">-</strong></div>
                            <div><span class="text-slate-500">Email :</span> <strong id="sum-email" class="text-slate-800">-</strong></div>
                            <div><span class="text-slate-500">Téléphone :</span> <strong id="sum-phone" class="text-slate-800">-</strong></div>
                            <div><span class="text-slate-500">Profession :</span> <strong id="sum-prof" class="text-slate-800">-</strong></div>
                        </div>
                    </div>

                    <!-- Honor Certification Box -->
                    <div class="p-4 sm:p-5 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Déclaration sur l'honneur</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mt-0.5">
                                Je certifie sur l'honneur l'exactitude de toutes les informations et pièces jointes fournies dans ce dossier. Toute fausse déclaration entraîne le rejet immédiat de la candidature.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- NAVIGATION BUTTONS -->
                <!-- ============================================================== -->
                <div class="mt-10 pt-6 border-t border-slate-200 flex items-center justify-between">
                    <button type="button" id="prev-btn" onclick="previousStep()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-full shadow-2xs transition hidden">
                        <span class="material-symbols-outlined text-[18px] text-slate-700">arrow_back</span>
                        <span>Précédent</span>
                    </button>

                    <div class="ml-auto flex items-center gap-3">
                        <button type="button" id="next-btn" onclick="nextStep()"
                                class="inline-flex items-center gap-2 px-7 py-3 text-sm font-bold text-white bg-[#4B6A95] hover:bg-[#354e70] rounded-full shadow-xs transition">
                            <span>Continuer</span>
                            <span class="material-symbols-outlined text-[18px] text-white">arrow_forward</span>
                        </button>

                        <button type="submit" id="submit-btn"
                                class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-[#4B6A95] hover:bg-[#354e70] rounded-full shadow-xs transition hidden">
                            <span class="material-symbols-outlined text-[20px] text-white">send</span>
                            <span>Soumettre ma candidature</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} CFPT Sénégal-Japon - Programme de Formation en Pays Tiers (PFPT / JICA).</p>
    </footer>

    <!-- Wizard Interaction Scripts -->
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        const titles = {
            1: "Étape 1 sur 3 : Séminaire & Identité",
            2: "Étape 2 sur 3 : Profil & Logistique",
            3: "Étape 3 sur 3 : Pièces & Validation"
        };

        const seminarTopicsMap = {
            @foreach($seminars as $sem)
                "{{ $sem->id }}": [
                    @foreach($sem->topics as $topic)
                        { id: "{{ $topic->id }}", name: "{{ addslashes($topic->name) }}" },
                    @endforeach
                ],
            @endforeach
        };

        function onSeminarChange(seminarId) {
            const topicSelect = document.getElementById('topic_id');
            if (!topicSelect) return;
            
            topicSelect.innerHTML = '';
            
            const topics = seminarTopicsMap[seminarId] || [];
            
            if (topics.length > 0) {
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = '-- Choisissez un thème / module spécialisé --';
                topicSelect.appendChild(defaultOpt);
                
                const oldTopicId = "{{ old('topic_id') }}";
                topics.forEach(topic => {
                    const opt = document.createElement('option');
                    opt.value = topic.id;
                    opt.textContent = topic.name;
                    if (oldTopicId && oldTopicId == topic.id) {
                        opt.selected = true;
                    }
                    topicSelect.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Module Général / Tronc Commun';
                topicSelect.appendChild(opt);
            }

            refreshSummary();
        }

        function updateStepUI() {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
            document.getElementById(`step-${currentStep}`).classList.add('active');

            // Update Progress Bar
            const progressPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('stepper-progress').style.width = `${progressPercent}%`;

            // Update Stepper Badges
            for (let i = 1; i <= totalSteps; i++) {
                const badge = document.getElementById(`step-badge-${i}`);
                const label = document.getElementById(`step-label-${i}`);
                
                if (i < currentStep) {
                    badge.className = 'w-10 h-10 rounded-full bg-[#354e70] text-white font-bold flex items-center justify-center text-sm shadow-xs transition-all';
                    badge.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span>';
                    if (label) label.className = 'mt-2 text-xs font-semibold text-slate-900 font-headline hidden sm:block';
                } else if (i === currentStep) {
                    badge.className = 'w-10 h-10 rounded-full bg-[#4B6A95] text-white font-bold flex items-center justify-center text-sm shadow-xs ring-4 ring-blue-50 transition-all';
                    badge.innerHTML = i;
                    if (label) label.className = 'mt-2 text-xs font-bold text-slate-900 font-headline hidden sm:block';
                } else {
                    badge.className = 'w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-sm border-2 border-slate-200 transition-all';
                    badge.innerHTML = i;
                    if (label) label.className = 'mt-2 text-xs font-medium text-slate-500 font-headline hidden sm:block';
                }
            }

            // Mobile Title
            document.getElementById('mobile-step-title').innerText = titles[currentStep];

            // Buttons
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            if (currentStep === 1) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }

            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                refreshSummary();
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            const stepEl = document.getElementById(`step-${step}`);
            const requiredInputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            requiredInputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files.length === 0) {
                        isValid = false;
                        input.closest('.doc-upload-label')?.classList.add('border-rose-500', 'bg-rose-50/50');
                    } else {
                        input.closest('.doc-upload-label')?.classList.remove('border-rose-500', 'bg-rose-50/50');
                    }
                } else if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-rose-500', 'bg-rose-50/30');
                } else {
                    input.classList.remove('border-rose-500', 'bg-rose-50/30');
                }
            });

            if (!isValid) {
                alert('Veuillez renseigner tous les champs obligatoires avant de poursuivre.');
            }
            return isValid;
        }

        function nextStep() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepUI();
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepUI();
            }
        }

        function goToStep(step) {
            if (step < currentStep) {
                currentStep = step;
                updateStepUI();
            } else if (step > currentStep) {
                if (validateStep(currentStep)) {
                    currentStep = step;
                    updateStepUI();
                }
            }
        }

        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-placeholder-icon').classList.add('hidden');
                    const previewImg = document.getElementById('photo-preview-img');
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleFileSelect(input) {
            const statusTargetId = input.dataset.target;
            const statusEl = document.getElementById(statusTargetId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeKb = (file.size / 1024).toFixed(0);
                statusEl.innerHTML = `<span class="text-[#4B6A95] font-semibold">✓ ${file.name}</span> (${sizeKb} Ko)`;
                input.closest('.doc-upload-label')?.classList.remove('border-rose-500', 'bg-rose-50/50');
            }
        }

        function addAcademicRow() {
            const container = document.getElementById('academic-history-container');
            const row = document.createElement('div');
            row.className = 'academic-row grid grid-cols-1 sm:grid-cols-12 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-200';
            row.innerHTML = `
                <div class="sm:col-span-4">
                    <input type="text" name="acad_establishment[]" placeholder="Établissement / Université" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                </div>
                <div class="sm:col-span-3">
                    <input type="text" name="acad_field[]" placeholder="Filière / Spécialité" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                </div>
                <div class="sm:col-span-3">
                    <input type="text" name="acad_degree[]" placeholder="Diplôme obtenu" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="number" name="acad_year[]" placeholder="Année" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-[#4B6A95]">
                    <button type="button" onclick="this.closest('.academic-row').remove()" class="p-1 text-slate-400 hover:text-rose-500 rounded transition" title="Supprimer">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            `;
            container.appendChild(row);
        }

        function refreshSummary() {
            const firstNames = document.getElementById('first_names')?.value || '';
            const lastName = document.getElementById('last_name')?.value || '';
            const email = document.getElementById('email')?.value || '-';
            const phone = document.getElementById('phone')?.value || '-';
            const prof = document.getElementById('current_profession')?.value || '-';

            const semSelect = document.getElementById('seminar_id');
            const semText = semSelect && semSelect.selectedIndex >= 0 ? semSelect.options[semSelect.selectedIndex].text : '-';
            const topSelect = document.getElementById('topic_id');
            const topText = topSelect && topSelect.selectedIndex >= 0 ? topSelect.options[topSelect.selectedIndex].text : 'Module Général';

            document.getElementById('sum-name').innerText = `${firstNames} ${lastName}`.trim() || '-';
            document.getElementById('sum-email').innerText = email;
            document.getElementById('sum-phone').innerText = phone;
            document.getElementById('sum-prof').innerText = prof;
            if (document.getElementById('sum-seminar')) document.getElementById('sum-seminar').innerText = semText;
            if (document.getElementById('sum-topic')) document.getElementById('sum-topic').innerText = topText;
        }

        // Initialize UI & dynamic seminar topics on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateStepUI();
            const initialSeminar = document.getElementById('seminar_id')?.value;
            if (initialSeminar) {
                onSeminarChange(initialSeminar);
            }
        });
    </script>
</body>
</html>
