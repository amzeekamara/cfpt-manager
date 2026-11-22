<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler aux Séminaires — PFPT CFPT Sénégal-Japon</title>
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
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a'
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
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-brand-500 selection:text-white">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 rounded-lg p-1">
                <img src="{{ asset('images/LOGO PFPT.png') }}" alt="Logo PFPT" class="h-9 w-auto">
                <div class="border-l border-slate-200 pl-3">
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">CFPT Sénégal-Japon</span>
                    <span class="text-sm font-bold text-brand-900 font-headline leading-none">Programme PFPT</span>
                </div>
            </a>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-900 px-3 py-2 transition hidden sm:inline-block">
                    Déjà inscrit ? Se connecter
                </a>
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-brand-700 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-lg transition">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 py-10 md:py-14">
        
        <!-- Header Banner -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 mb-2">
                <span class="text-xs font-black uppercase tracking-wider text-[#4B6A95]">Sessions & Séminaires Disponibles</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-headline tracking-tight">
                Choisissez votre Formation & Postulez
            </h1>
            <p class="text-slate-600 text-sm sm:text-base mt-2.5 leading-relaxed">
                Sélectionnez le séminaire de formation correspondant à votre spécialité pour démarrer votre dossier de candidature officiel en ligne.
            </p>
        </div>

        <!-- Official Ambassador Alert (Clear & Reassuring) -->
        <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-2xl mb-10 shadow-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#4B6A95] text-white flex items-center justify-center shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-2xl">info</span>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-headline mb-1">
                    Modalités d'admission & Validation institutionnelle
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Votre pré-inscription en ligne permet au CFPT Sénégal-Japon et à la JICA d'instruire votre dossier technique. Pour les candidats internationaux, la participation définitive sera entérinée par votre Ministère de tutelle et l'Ambassade du Sénégal/Japon.
                </p>
            </div>
        </div>

        <!-- Flash Alert Message -->
        @if(session('error'))
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-2xl mb-8 flex items-start gap-3 shadow-xs">
                <span class="material-symbols-outlined text-amber-600 shrink-0">warning</span>
                <p class="text-xs sm:text-sm text-amber-800 font-medium leading-relaxed">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl mb-8 flex items-start gap-3 shadow-xs">
                <span class="material-symbols-outlined text-emerald-600 shrink-0">check_circle</span>
                <p class="text-xs sm:text-sm text-emerald-800 font-medium leading-relaxed">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Active Seminars Grid -->
        <div class="mb-12">
            <h2 class="text-lg font-bold text-slate-900 font-headline mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-800">event_available</span>
                Catalogue des Séminaires & Sessions de Formation
            </h2>

            @if($seminars->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($seminars as $seminar)
                        <div class="bg-white rounded-2xl border {{ $seminar->isOpenForApplication() ? 'border-slate-200 hover:border-[#4B6A95]/50' : 'border-slate-200 bg-slate-50/50' }} p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                            <div>
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    @if($seminar->isOpenForApplication())
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Inscriptions Ouvertes
                                        </span>
                                    @elseif($seminar->isOngoing())
                                        <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            En cours — Inscriptions Closes
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full">
                                            Session Terminée
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">group</span>
                                        {{ $seminar->max_participants ?? 20 }} places
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 font-headline {{ $seminar->isOpenForApplication() ? 'group-hover:text-[#4B6A95]' : '' }} transition mb-2">
                                    {{ $seminar->title }}
                                </h3>

                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4">
                                    {{ $seminar->description ?? 'Formation technique approfondie dispensée au CFPT Sénégal-Japon.' }}
                                </p>

                                <div class="space-y-1.5 text-xs text-slate-600 bg-slate-50 p-3.5 rounded-xl border border-slate-100 mb-6">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4B6A95] text-[16px]">calendar_today</span>
                                        <span>Du <strong>{{ \Carbon\Carbon::parse($seminar->start_date)->translatedFormat('d F Y') }}</strong> au <strong>{{ \Carbon\Carbon::parse($seminar->end_date)->translatedFormat('d F Y') }}</strong></span>
                                    </div>
                                    @if($seminar->cohort)
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[#4B6A95] text-[16px]">school</span>
                                            <span>Cohorte : <strong>{{ $seminar->cohort->title }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                @if($seminar->isOpenForApplication())
                                    @if($seminar->cohort)
                                        <a href="{{ route('public.registration.show', $seminar->cohort) }}" 
                                           class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold rounded-full shadow-xs transition text-xs sm:text-sm">
                                            <span>Remplir le dossier de candidature</span>
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    @else
                                        <a href="{{ route('public.registration.show', $seminars->first()?->cohort_id ?? 1) }}" 
                                           class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold rounded-full shadow-xs transition text-xs sm:text-sm">
                                            <span>Postuler à ce séminaire</span>
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    @endif
                                @elseif($seminar->isOngoing())
                                    <div class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-amber-50 border border-amber-200 text-amber-800 font-semibold rounded-full text-xs sm:text-sm cursor-not-allowed">
                                        <span class="material-symbols-outlined text-[18px] text-amber-600">lock</span>
                                        <span>Inscriptions closes (Session en cours)</span>
                                    </div>
                                @else
                                    <div class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-slate-100 border border-slate-200 text-slate-500 font-semibold rounded-full text-xs sm:text-sm cursor-not-allowed">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">event_busy</span>
                                        <span>Session Clôturée</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white p-10 rounded-2xl border border-slate-200 text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-2xl">event_busy</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 font-headline mb-1">Aucune session ouverte actuellement</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                        Les prochaines cohortes seront publiées prochainement. Vous pouvez contacter le centre pour plus de renseignements.
                    </p>
                    <a href="{{ route('welcome') }}#contact" class="inline-flex items-center gap-2 text-xs font-semibold text-[#4B6A95] hover:underline">
                        <span>Contacter le CFPT Sénégal-Japon</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            @endif
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-auto">
        <p>&copy; {{ date('Y') }} CFPT Sénégal-Japon - Programme de Formation en Pays Tiers (PFPT / JICA).</p>
    </footer>

    <!-- Widget Chatbot Flottant n8n -->
    <x-n8n-chat />
</body>
</html>
