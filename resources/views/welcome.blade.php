<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PFPT CFPT S-J | Portail</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#00355f",
                        "primary-container": "#0f4c81"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F8FAFC] font-body text-slate-800 antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Navbar -->
    <header class="w-full py-6 px-8 md:px-16 flex justify-between items-center relative z-20">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="h-16 w-auto">
        </div>
        <div>
            @auth
                <a href="{{ route('admin.users.index') }}" class="font-bold text-blue-900 hover:text-blue-700">Aller au Tableau de Bord &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="font-bold text-blue-900 hover:text-blue-700">S'identifier</a>
            @endauth
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="flex-1 flex flex-col justify-center items-center text-center px-4 relative z-20">
        
        <span class="inline-block py-1 px-3 rounded-full bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-widest mb-6 border border-blue-200">
            Programme de Formation
        </span>
        
        <h1 class="font-headline text-5xl md:text-7xl font-extrabold text-blue-900 tracking-tight max-w-4xl leading-tight mb-8">
            L'Excellence Technique en Pays Tiers
        </h1>
        
        <p class="text-lg md:text-xl text-slate-500 max-w-2xl mb-12 font-body leading-relaxed">
            Bienvenue sur la plateforme officielle de gestion des séminaires d'appui PFPT. Une initiative conjointe du Centre de Formation Professionnelle et Technique Sénégal-Japon et de la JICA.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            @auth
                <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-primary text-white rounded-lg font-bold font-headline transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1" style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                    Accéder à mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 bg-primary text-white rounded-lg font-bold font-headline transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1" style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                    S'identifier (Administration)
                </a>
            @endauth
        </div>
    </main>

    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-100/50 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full bg-blue-200/30 blur-[100px]"></div>
    </div>

</body>
</html>
