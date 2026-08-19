<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature Enregistrée — PFPT CFPT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
        }
        h1, h2, h3 { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 text-slate-800 antialiased">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-8 sm:p-10 max-w-md w-full text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-3xl">check</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-2">
            Candidature Transmise !
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-6 font-medium">
            Votre dossier d'inscription pour <strong>{{ $form->title }}</strong> a été enregistré avec succès.
        </p>
        
        <div class="bg-slate-50 rounded-xl p-4 text-left text-xs text-slate-600 border border-slate-200/70 mb-6 space-y-2">
            <div class="font-bold text-slate-800 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[15px] text-[#4B6A95]">info</span>
                Prochaines étapes :
            </div>
            <ul class="space-y-1.5 ml-4 list-disc text-slate-500 font-medium">
                <li>Votre statut est actuellement <em>En attente d'instruction</em>.</li>
                <li>La commission examinera la conformité de vos pièces.</li>
                <li>Vous recevrez une notification officielle par e-mail.</li>
            </ul>
        </div>

        <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-[#4B6A95] hover:bg-[#354e70] text-white font-bold text-xs rounded-xl shadow-md shadow-[#4B6A95]/20 transition-all">
            <span class="material-symbols-outlined text-[16px]">home</span>
            Retour à l'accueil
        </a>

        <div class="text-[11px] text-slate-400 mt-6 font-medium">
            CFPT Sénégal-Japon — Programme PFPT
        </div>
    </div>
</body>
</html>
