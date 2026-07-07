<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire soumis — PFPT CFPT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-10 max-w-md w-full text-center">
        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-emerald-500 text-5xl" style="font-variation-settings:'FILL' 1">check_circle</span>
        </div>
        <h1 class="text-2xl font-headline font-bold text-slate-800 mb-3" style="font-family:'Manrope',sans-serif">
            Formulaire soumis !
        </h1>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Merci pour votre inscription à <strong>{{ $form->title }}</strong>.<br>
            Votre dossier est en cours de traitement. Vous serez contacté(e) prochainement.
        </p>
        <div class="bg-slate-50 rounded-xl p-4 text-left text-xs text-slate-600 border border-slate-100 mb-6">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <span class="material-symbols-outlined text-[16px] text-[#00355f]">info</span>
                Prochaines étapes
            </div>
            <ul class="space-y-1 ml-5 list-disc">
                <li>Votre candidature a été enregistrée avec le statut <em>En attente</em>.</li>
                <li>Un administrateur examinera votre dossier.</li>
                <li>Vous recevrez une confirmation par e-mail.</li>
            </ul>
        </div>
        <div class="text-xs text-slate-400">PFPT CFPT Saint-Jean de Dieu · Portail de candidature</div>
    </div>
</body>
</html>
