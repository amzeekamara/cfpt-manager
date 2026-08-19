<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }} — PFPT CFPT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Polices officielles de la plateforme : Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <meta name="description" content="{{ $form->description }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        pfpt: {
                            50: '#f3f6fb',
                            100: '#e4edf8',
                            200: '#d2e4ff',
                            500: '#4B6A95',
                            600: '#3d587c',
                            700: '#354e70',
                            800: '#263a54',
                            900: '#162536',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            color: #1e293b;
        }
        h1, h2, h3, h4 { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            letter-spacing: -0.02em;
        }
        .form-card { 
            box-shadow: 0 4px 20px -2px rgba(22, 37, 54, 0.06), 0 2px 6px -1px rgba(22, 37, 54, 0.04); 
        }
        .input-field {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-field:focus {
            background-color: #ffffff;
            border-color: #4B6A95;
            box-shadow: 0 0 0 3px rgba(75, 106, 149, 0.15);
        }
        select.input-field {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234B6A95' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50/60 pb-20 text-slate-800 antialiased">

    <div class="relative min-h-screen flex flex-col">

        {{-- Barre Supérieure Épurée --}}
        <header class="sticky top-0 z-30 py-3.5 px-6 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
            <div class="max-w-5xl mx-auto flex items-center justify-between">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo-blue.png') }}" alt="CFPT Sénégal-Japon" class="h-10 w-auto object-contain">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900 leading-tight group-hover:text-pfpt-500 transition-colors">CFPT SÉNÉGAL-JAPON</div>
                        <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Portail Officiel PFPT</div>
                    </div>
                </a>
                <a href="{{ route('welcome') }}" class="text-xs font-bold text-slate-600 hover:text-pfpt-500 flex items-center gap-1 transition-colors px-3 py-1.5 rounded-lg hover:bg-slate-100">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Retour à l'accueil
                </a>
            </div>
        </header>

        {{-- Contenu Principal --}}
        <main class="flex-1 flex items-start justify-center py-8 px-4 sm:px-6">
            <div class="w-full max-w-4xl space-y-6">

                {{-- En-tête du Formulaire (Épuré, sans icône carrée superflue) --}}
                <div class="bg-gradient-to-br from-pfpt-900 via-pfpt-800 to-pfpt-700 rounded-2xl p-6 sm:p-8 text-white form-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-white leading-tight">
                                {{ $form->title }}
                            </h1>
                            @if($form->description)
                                <p class="text-slate-200 text-xs sm:text-sm leading-relaxed max-w-2xl font-normal">
                                    {{ $form->description }}
                                </p>
                            @endif
                            @if($form->seminar)
                                <div class="pt-0.5">
                                    <span class="text-xs font-semibold text-slate-200">
                                        {{ $form->seminar->title }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Logo Institutionnel Discret --}}
                        <div class="hidden md:flex flex-shrink-0 bg-white/95 p-3 rounded-xl shadow-sm border border-white/20 items-center justify-center">
                            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo" class="h-14 w-auto object-contain">
                        </div>
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-white/15 flex items-center gap-1.5 text-slate-300 text-xs">
                        <span>Les champs marqués d'un</span>
                        <span class="text-rose-300 font-bold">*</span>
                        <span>sont obligatoires.</span>
                    </div>
                </div>

                {{-- Alertes Erreurs --}}
                @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-2 text-sm text-rose-900">
                        <span class="material-symbols-outlined text-rose-500 text-[18px]">error</span>
                        Veuillez corriger les erreurs suivantes :
                    </div>
                    <ul class="text-xs space-y-1 list-disc list-inside text-rose-700 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Formulaire Multi-Sections --}}
                <form action="{{ route('public.form.store', $form->share_token) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- SECTION 1: Identité & Coordonnées -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 form-card space-y-6">
                        <div class="pb-4 border-b border-slate-100">
                            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Identité & Coordonnées</h2>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Informations personnelles relatives au candidat</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                            {{-- Matricule --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Matricule</label>
                                <input type="text" name="answers[matricule]" value="{{ old('answers.matricule') }}" placeholder="Ex: MAT-2026-001"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>
                            
                            {{-- Photo de profil --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Photo de profil (Optionnelle)</label>
                                <input type="file" name="answers[photo]" accept="image/jpeg,image/png,image/jpg"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 input-field file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-pfpt-500 file:text-white hover:file:bg-pfpt-700 file:transition-colors">
                                <p class="text-[11px] text-slate-400">Format passeport recommandé (ex: 400x400 px), max 2 Mo.</p>
                            </div>

                            {{-- Nom --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                    Nom <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="answers[nom]" value="{{ old('answers.nom') }}" required placeholder="Votre nom de famille"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Prénom --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                    Prénom <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="answers[prenom]" value="{{ old('answers.prenom') }}" required placeholder="Vos prénoms"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Sexe --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                    Sexe <span class="text-rose-500">*</span>
                                </label>
                                <select name="answers[sexe]" required class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field appearance-none">
                                    <option value="">Sélectionner</option>
                                    <option value="Masculin" {{ old('answers.sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                    <option value="Féminin" {{ old('answers.sexe') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>

                            {{-- Email --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                    Email <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="answers[email]" value="{{ old('answers.email') }}" required placeholder="nom@exemple.com"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Téléphone WhatsApp --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Téléphone (WhatsApp)</label>
                                <input type="text" name="answers[whatsapp]" value="{{ old('answers.whatsapp') }}" placeholder="+221 ..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Religion --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Religion</label>
                                <input type="text" name="answers[religion]" value="{{ old('answers.religion') }}" placeholder="Facultatif"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Date de Naissance --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Date de Naissance</label>
                                <input type="date" name="answers[date_naissance]" value="{{ old('answers.date_naissance') }}"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field">
                            </div>

                            {{-- Lieu de Naissance --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Lieu de Naissance</label>
                                <input type="text" name="answers[lieu_naissance]" value="{{ old('answers.lieu_naissance') }}" placeholder="Ville, Pays"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Nationalité --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Nationalité</label>
                                <input type="text" name="answers[nationalite]" value="{{ old('answers.nationalite') }}" placeholder="Ex: Sénégalaise, Ivoirienne..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Pays de résidence --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Pays de résidence</label>
                                <select name="answers[pays]" class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field appearance-none">
                                    <option value="">Sélectionner un pays</option>
                                    @php
                                        $countries = [
                                            'Sénégal','Mali','Guinée','Côte d\'Ivoire','Burkina Faso','Mauritanie',
                                            'Niger','Togo','Bénin','Cameroun','Gabon','Congo','République Démocratique du Congo'
                                        ];
                                    @endphp
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}" {{ old('answers.pays') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Adresse --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Adresse</label>
                                <input type="text" name="answers[adresse]" value="{{ old('answers.adresse') }}" placeholder="Quartier, Rue..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Situation Familiale --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Situation Familiale</label>
                                <select name="answers[situation_fam]" class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field appearance-none">
                                    <option value="">Sélectionner</option>
                                    <option value="Célibataire" {{ old('answers.situation_fam') == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                                    <option value="Marié(e)" {{ old('answers.situation_fam') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                                    <option value="Divorcé(e)" {{ old('answers.situation_fam') == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                                    <option value="Veuf/Veuve" {{ old('answers.situation_fam') == 'Veuf/Veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                                </select>
                            </div>

                            {{-- Contact d'urgence (Nom) --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Contact d'urgence (Nom complet)</label>
                                <input type="text" name="answers[urgence_nom]" value="{{ old('answers.urgence_nom') }}" placeholder="Nom d'un proche"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Contact d'urgence (Téléphone) --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Contact d'urgence (Téléphone)</label>
                                <input type="text" name="answers[urgence]" value="{{ old('answers.urgence') }}" placeholder="+221 ..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Profil Professionnel -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 form-card space-y-6">
                        <div class="pb-4 border-b border-slate-100">
                            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Profil Professionnel</h2>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Poste, institution et responsabilités actuelles</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                            {{-- Grade --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Grade</label>
                                <input type="text" name="answers[grade]" value="{{ old('answers.grade') }}" placeholder="Ex: Ingénieur, Formateur, Technicien..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Fonction --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Fonction</label>
                                <input type="text" name="answers[fonction]" value="{{ old('answers.fonction') }}" placeholder="Ex: Responsable atelier, Enseignant..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Emploi / Profession --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Emploi / Spécialité</label>
                                <input type="text" name="answers[profession]" value="{{ old('answers.profession') }}" placeholder="Ex: Électrotechnique, Mécanique..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Structure --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Structure (Employeur actuel)</label>
                                <input type="text" name="answers[employeur]" value="{{ old('answers.employeur') }}" placeholder="Nom de l'institut / ministère / entreprise"
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Employeur précédent --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Employeur précédent (le cas échéant)</label>
                                <input type="text" name="answers[emp_precedent]" value="{{ old('answers.emp_precedent') }}" placeholder="Structure antérieure..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                            </div>

                            {{-- Description du travail --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Description synthétique des missions & tâches</label>
                                <textarea name="answers[desc_travail]" rows="3" placeholder="Décrivez brièvement vos activités au quotidien..."
                                    class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">{{ old('answers.desc_travail') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Diplômes & Études -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 form-card space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Diplômes & Formations</h2>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Parcours académique et certifications</p>
                            </div>
                            <button type="button" onclick="addDiplomaRow()" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-pfpt-50 text-pfpt-700 hover:bg-pfpt-100 rounded-lg font-bold text-xs border border-pfpt-200 transition-colors self-start sm:self-auto">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                Ajouter un diplôme
                            </button>
                        </div>

                        <div id="diplomas-container" class="space-y-4">
                            <!-- Ligne par défaut -->
                            <div class="diploma-row p-5 bg-slate-50/60 rounded-xl border border-slate-200/70 grid grid-cols-1 md:grid-cols-2 gap-4 relative">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Diplôme Obtenu</label>
                                    <input type="text" name="answers[diplomas][0][nom]" required placeholder="Ex: Licence, BTS, Diplôme d'Ingénieur..."
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 input-field placeholder:text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Établissement / Université</label>
                                    <input type="text" name="answers[diplomas][0][etab]" required placeholder="Nom de l'établissement"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 input-field placeholder:text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Année d'obtention</label>
                                    <input type="text" name="answers[diplomas][0][annee]" required placeholder="Ex: 2022"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 input-field placeholder:text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Fichier justificatif (PDF, JPG, PNG)</label>
                                    <input type="file" name="answers[diplomas][0][file]" accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-700 input-field file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-pfpt-500 file:text-white hover:file:bg-pfpt-700 file:transition-colors">
                                </div>
                                <button type="button" onclick="removeDiplomaRow(this)" class="remove-btn hidden absolute -right-2 -top-2 w-7 h-7 rounded-full bg-rose-500 text-white shadow-md flex items-center justify-center hover:bg-rose-600 transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Santé, Logistique & Assurance -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 form-card space-y-6">
                        <div class="pb-4 border-b border-slate-100">
                            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Santé, Logistique & Assurance</h2>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Informations requises pour la prise en charge durant le séminaire</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/70 space-y-2">
                                    <span class="text-xs font-bold text-slate-800 block">Souffrez-vous d'une affection chronique ?</span>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="answers[sante_chronique]" value="Oui" {{ old('answers.sante_chronique') == 'Oui' ? 'checked' : '' }} class="w-4 h-4 text-pfpt-500 focus:ring-pfpt-500">
                                            <span class="text-xs font-bold text-slate-700">Oui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="answers[sante_chronique]" value="Non" {{ old('answers.sante_chronique') == 'Non' ? 'checked' : '' }} class="w-4 h-4 text-pfpt-500 focus:ring-pfpt-500">
                                            <span class="text-xs font-bold text-slate-700">Non</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/70 space-y-2">
                                    <span class="text-xs font-bold text-slate-800 block">Suivez-vous un traitement médical régulier ?</span>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="answers[sante_traitement]" value="Oui" {{ old('answers.sante_traitement') == 'Oui' ? 'checked' : '' }} class="w-4 h-4 text-pfpt-500 focus:ring-pfpt-500">
                                            <span class="text-xs font-bold text-slate-700">Oui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="answers[sante_traitement]" value="Non" {{ old('answers.sante_traitement') == 'Non' ? 'checked' : '' }} class="w-4 h-4 text-pfpt-500 focus:ring-pfpt-500">
                                            <span class="text-xs font-bold text-slate-700">Non</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Observations Médicales Particulières</label>
                                    <textarea name="answers[sante_obs]" rows="2" placeholder="Régime alimentaire, allergies, etc."
                                        class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 input-field placeholder:text-slate-400">{{ old('answers.sante_obs') }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Préférence Hébergement</label>
                                    <input type="text" name="answers[hebergement]" value="{{ old('answers.hebergement') }}" placeholder="Précisions éventuelles"
                                        class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Détails Assurance Voyage / Maladie</label>
                                    <input type="text" name="answers[assurance]" value="{{ old('answers.assurance') }}" placeholder="Compagnie, numéro de police..."
                                        class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 input-field placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de Soumission -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-slate-500 font-medium">
                            En soumettant ce formulaire, vous attestez de l'exactitude des informations fournies.
                        </span>
                        <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3.5 bg-pfpt-500 hover:bg-pfpt-700 text-white font-bold text-sm rounded-xl shadow-md shadow-pfpt-500/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                            <span>Soumettre ma candidature</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </form>

                {{-- Pied de page sécurisé --}}
                <div class="text-center text-xs text-slate-400 pt-4 pb-8">
                    CFPT Sénégal-Japon — Programme de Formation en Pays Tiers (PFPT)
                </div>
            </div>
        </main>
    </div>

    <script>
        let diplomaCount = 1;
        function addDiplomaRow() {
            const container = document.getElementById('diplomas-container');
            const firstRow = container.querySelector('.diploma-row');
            const newRow = firstRow.cloneNode(true);
            
            // Update names and clear values
            const inputs = newRow.querySelectorAll('input');
            inputs.forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, '[' + diplomaCount + ']');
                if (input.type !== 'radio' && input.type !== 'checkbox') {
                    input.value = '';
                }
            });
            
            // Show remove button
            const removeBtn = newRow.querySelector('.remove-btn');
            if (removeBtn) removeBtn.classList.remove('hidden');
            
            container.appendChild(newRow);
            diplomaCount++;
        }

        function removeDiplomaRow(btn) {
            btn.closest('.diploma-row').remove();
        }
    </script>
</body>
</html>
