<!DOCTYPE html>
<html class="light" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>PFPT CFPT - Connexion</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#00355f",
                        "primary-container": "#0f4c81",
                        "on-surface": "#191c1e",
                        "surface": "#f7f9fb",
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .no-line-input {
            border: none;
            border-bottom: 1px solid #c2c7d1;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .no-line-input:focus {
            box-shadow: none;
            border-bottom: 2px solid #00355f;
            outline: none;
        }
    </style>
</head>

<body class="bg-surface font-body text-on-surface antialiased overflow-hidden">
    <main class="flex min-h-screen w-full">

        <!-- Left Form Panel -->
        <section class="w-full md:w-1/2 flex flex-col justify-center items-center bg-[#F8FAFC] px-8 lg:px-24">
            <nav class="fixed top-0 left-0 w-full md:w-1/2 flex justify-between items-center px-12 py-8 z-50">
                <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="h-14 w-auto">
            </nav>

            <div class="w-full max-w-md space-y-12">
                <div class="space-y-4">
                    <h1 class="font-headline text-3xl font-extrabold tracking-tight text-primary leading-tight">
                        Portail d'Administration PFPT
                    </h1>
                    <p class="text-slate-500 font-body">
                        Accédez à votre espace institutionnel pour la gestion des séminaires d'appui.
                    </p>

                    @if ($errors->any())
                        <div class="p-4 bg-red-100 text-red-700 rounded-lg text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>

                <form class="space-y-8" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-6">
                        <div class="relative">
                            <label class="block font-label text-[10px] tracking-wider uppercase text-slate-400 mb-1"
                                for="email">Adresse Émail</label>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-slate-400 absolute left-0">mail</span>
                                <input class="w-full bg-transparent no-line-input pl-8 py-3 text-on-surface" id="email"
                                    name="email" value="{{ old('email') }}" required autofocus type="email" />
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block font-label text-[10px] tracking-wider uppercase text-slate-400 mb-1"
                                for="password">Mot de passe</label>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-slate-400 absolute left-0">lock</span>
                                <input class="w-full bg-transparent no-line-input pl-8 py-3 text-on-surface"
                                    id="password" name="password" required type="password" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input class="h-4 w-4 rounded border-slate-300 text-blue-900" id="remember" name="remember"
                                type="checkbox" />
                            <label class="ml-2 block text-sm text-slate-600 font-body" for="remember">Se souvenir de
                                moi</label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 px-6 rounded-lg text-white font-headline font-bold tracking-wide hover:opacity-90 transition-all shadow-lg"
                        style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                        Se Connecter
                    </button>
                </form>
            </div>
        </section>

        <!-- Right Branding Panel -->
        <section class="hidden md:flex w-1/2 bg-primary relative overflow-hidden flex-col justify-center items-center px-12">
            <div class="relative z-10 text-center space-y-12">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo PFPT"
                    class="h-72 w-auto mx-auto drop-shadow-xl">
                <div class="space-y-4">
                    <h2 class="font-headline text-4xl font-black text-white tracking-tight uppercase leading-tight">
                        Programme de Formation<br>en Pays Tiers
                    </h2>
                    <div class="w-20 h-1.5 bg-white/30 mx-auto rounded-full"></div>
                    <p class="text-blue-100 font-body max-w-sm mx-auto leading-relaxed text-lg">
                        Une synergie éducative d'excellence pour le renforcement technique de l'Afrique.
                    </p>
                </div>
            </div>
            
            <!-- Abstract background shape for subtle texture -->
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        </section>

    </main>
</body>

</html>