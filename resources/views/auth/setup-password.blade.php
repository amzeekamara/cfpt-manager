<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PFPT CFPT - Définir votre mot de passe</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div>
            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="mx-auto h-24 w-auto drop-shadow-sm">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-800 tracking-tight">
                Définir votre mot de passe
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Bienvenue sur la plateforme. Veuillez choisir un mot de passe sécurisé pour activer votre compte.
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('password.setup.store') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="rounded-md space-y-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Nouveau mot de passe</label>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                        class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-lg"
                    style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                    Activer mon compte
                </button>
            </div>
        </form>
    </div>
</body>
</html>
