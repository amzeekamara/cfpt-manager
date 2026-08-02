<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PFPT CFPT - Connexion</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        /* Motif à points pour l'arrière-plan principal (bleu très foncé) */
        .bg-dots {
            background-color: #0A1128;
            background-image: radial-gradient(#1E293B 1.5px, transparent 1.5px);
            background-size: 20px 20px;
        }
        /* Motif quadrillage pour la section formulaire (blanc) */
        .bg-grid {
            background-color: #ffffff;
            background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px), linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
            background-size: 40px 40px;
        }
        /* Effet d'ombre pour simuler le globe terrestre */
        .globe-shadow { 
            box-shadow: inset -15px -15px 40px rgba(0,0,0,0.15); 
        }
    </style>
</head>
<body class="bg-dots min-h-screen flex items-center justify-center p-4 md:p-8">

    <!-- Conteneur Principal de la Modal -->
    <div class="max-w-[1000px] w-full bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[700px]">
        
        <!-- Panneau Gauche (Branding & Globe) -->
        <div class="w-full md:w-[45%] bg-white relative p-8 flex flex-col justify-between overflow-hidden rounded-l-[2rem]">
            <!-- Logo et En-tête -->
            <div class="relative z-10 flex items-center gap-2 text-gray-900 font-bold text-lg">
                <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="h-8 w-auto">
                <span class="ml-2">PFPT CFPT</span>
            </div>

            <!-- Illustration du Globe -->
            <div class="relative z-10 flex-1 flex items-center justify-center mt-12 mb-32">
                <div class="w-56 h-56 bg-blue-50 rounded-full globe-shadow relative flex items-center justify-center border border-blue-100">
                    <!-- Silhouette approximative des continents (Remplacée par la carte de l'Afrique) -->
                    <img src="{{ asset('images/africa_map.svg') }}" class="absolute inset-0 w-[70%] h-[70%] m-auto opacity-70 object-contain" alt="Carte Afrique" style="filter: invert(47%) sepia(85%) saturate(3025%) hue-rotate(204deg) brightness(98%) contrast(92%);">

                    <!-- Lignes de connexion simulées en arrière plan -->
                    <svg class="absolute inset-[-50px] w-[calc(100%+100px)] h-[calc(100%+100px)] pointer-events-none" style="z-index:-1;">
                        <path d="M 150 150 Q 80 80 50 20" stroke="rgba(59,130,246,0.3)" fill="none" stroke-width="1.5"/>
                        <path d="M 150 150 Q 220 80 250 50" stroke="rgba(59,130,246,0.3)" fill="none" stroke-width="1.5"/>
                        <path d="M 150 150 Q 50 150 20 180" stroke="rgba(59,130,246,0.3)" fill="none" stroke-width="1.5"/>
                    </svg>

                    <!-- Avatars Flottants (Africains professionnels) -->
                    <div class="absolute -top-8 left-6 w-12 h-12 bg-white rounded-2xl p-1 shadow-lg transform -rotate-6 z-20">
                        <img src="{{ asset('images/avatars/engineer.png') }}" class="w-full h-full rounded-xl object-cover" alt="Ingénieur">
                    </div>
                    <div class="absolute top-2 -right-10 w-12 h-12 bg-white rounded-2xl p-1 shadow-lg transform rotate-12 z-20">
                        <img src="{{ asset('images/avatars/developer.png') }}" class="w-full h-full rounded-xl object-cover" alt="Développeuse">
                    </div>
                    <div class="absolute top-1/2 -left-12 w-10 h-10 bg-white rounded-2xl p-1 shadow-lg z-20 transform -translate-y-1/2">
                        <img src="{{ asset('images/avatars/technician.png') }}" class="w-full h-full rounded-xl object-cover" alt="Technicien">
                    </div>
                    <div class="absolute bottom-6 right-2 w-10 h-10 bg-white rounded-2xl p-1 shadow-lg transform rotate-6 z-20">
                        <img src="{{ asset('images/avatars/architect.png') }}" class="w-full h-full rounded-xl object-cover" alt="Architecte">
                    </div>
                    <div class="absolute -bottom-8 left-16 w-8 h-8 bg-white rounded-xl p-0.5 shadow-lg transform -rotate-12 z-20">
                        <img src="{{ asset('images/avatars/scientist.png') }}" class="w-full h-full rounded-lg object-cover" alt="Scientifique">
                    </div>
                </div>
            </div>

            <!-- Carte Témoignage en bas -->
            <div class="absolute bottom-0 left-0 w-full bg-blue-50/80 backdrop-blur-md p-8 border-t border-gray-100 z-20">
                <p class="text-gray-800 text-xl font-semibold leading-snug mb-6">"Le Programme de Formation en Pays Tiers renforce l'excellence technique à travers les continents."</p>
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-gray-900 font-bold text-sm">PFPT Admin</h4>
                        <p class="text-gray-500 text-xs mt-0.5">Sénégal-Japon</p>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="flex text-blue-500 text-sm gap-0.5">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panneau Droit (Formulaire) -->
        <div class="w-full md:w-[55%] bg-grid relative p-8 md:p-12 flex flex-col items-center overflow-y-auto">
            <div class="w-full max-w-[380px]">
                
                <!-- Icône Avatar Formulaire -->
                <div class="w-16 h-16 mx-auto mb-6 rounded-full border border-gray-100 bg-gray-50 flex items-center justify-center relative">
                    <div class="absolute inset-0 m-1.5 rounded-full border border-gray-200"></div>
                    <svg class="w-5 h-5 text-gray-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>

                <!-- Textes d'en-tête -->
                <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Connexion</h2>
                <p class="text-xs text-gray-500 mb-8 leading-relaxed text-center">Accédez à votre portail d'administration<br>PFPT CFPT S-J</p>

                @if ($errors->any())
                    <div class="p-4 mb-6 bg-red-100 text-red-700 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="space-y-4" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Adresse Émail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@pfpt.edu.sn" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Mot de passe</label>
                        <div class="relative">
                            <input type="password" name="password" required class="w-full px-4 py-2.5 text-2xl border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-gray-700 h-11 tracking-[0.2em] font-serif leading-none">
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Checkbox Remember -->
                    <div class="flex items-center mt-3 mb-6">
                        <input type="checkbox" name="remember" id="remember" class="w-3.5 h-3.5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="remember" class="ml-2 text-[11px] text-gray-600 font-medium cursor-pointer">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-[#3b82f6] hover:bg-blue-600 text-white font-medium rounded-lg text-sm px-5 py-3 text-center transition shadow-md shadow-blue-200">
                        Se connecter
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-[11px] text-center text-gray-500 mt-5 font-medium">
                    <a href="#" class="text-[#3b82f6] font-bold hover:underline">Mot de passe oublié ?</a>
                </p>

            </div>
        </div>
    </div>
</body>
</html>