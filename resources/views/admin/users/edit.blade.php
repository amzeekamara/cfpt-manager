@extends('layouts.admin')

@section('header_title', 'Modifier un Utilisateur')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1200px] mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour aux utilisateurs">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Modifier le Compte Utilisateur</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Gérez les accès, rôles et mots de passe pour {{ $user->name }}.</p>
                </div>
            </div>
            <div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider 
                    @if($user->role?->name === 'Administrateur') bg-blue-100 text-[#4B6A95] border border-blue-200
                    @elseif($user->role?->name === 'Formateur') bg-emerald-100 text-emerald-700 border border-emerald-200
                    @elseif($user->role?->name === 'Candidat') bg-orange-100 text-orange-700 border border-orange-200
                    @elseif($user->role?->name === 'Agent JICA') bg-purple-100 text-purple-700 border border-purple-200
                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                    {{ $user->role ? $user->role->name : 'Sans Rôle' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl">
                <div class="flex items-center gap-2 font-bold text-sm mb-1">
                    <span class="material-symbols-outlined text-red-600">error</span>
                    Veuillez corriger les erreurs suivantes :
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ 
            role: '{{ old('role_id', $user->role_id) }}', 
            trainerRoleId: '{{ $roles->where('name', 'Formateur')->first()->id ?? '' }}',
            adminRoleId: '{{ $roles->where('name', 'Administrateur')->first()->id ?? '' }}',
            coordinatorRoleId: '{{ $roles->where('name', 'Coordinateur')->first()->id ?? '' }}',
            jicaRoleId: '{{ $roles->where('name', 'Agent JICA')->first()->id ?? '' }}',
            candidateRoleId: '{{ $roles->where('name', 'Candidat')->first()->id ?? '' }}'
        }">
            
            <!-- Left 2 Cols: Main Edit Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#4B6A95]">manage_accounts</span>
                        Informations générales & Rôles
                    </h2>

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Rôle & Accès -->
                        <div class="space-y-2">
                            <label for="role_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Rôle & Niveau d'accès <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">shield_person</span>
                                <select name="role_id" id="role_id" required x-model="role"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                                    <option value="">Sélectionnez un rôle</option>
                                    @foreach($roles as $roleOption)
                                        <option value="{{ $roleOption->id }}" {{ old('role_id', $user->role_id) == $roleOption->id ? 'selected' : '' }}>
                                            {{ $roleOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-[11px] text-slate-500">Modifier le rôle donne accès aux modules correspondants (Admin, Formateur, Candidat, JICA).</p>
                        </div>

                        <!-- Nom & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Nom Complet <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">person</span>
                                    <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                        placeholder="Ex: Jean Dupont">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Adresse Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                        placeholder="utilisateur@domaine.com">
                                </div>
                            </div>
                        </div>

                        <!-- Specific fields according to role -->
                        <div x-show="role == trainerRoleId" x-transition class="space-y-4 pt-2 border-t border-slate-100">
                            <div class="space-y-2">
                                <label for="specialty" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Matière / Spécialité du formateur</label>
                                <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $user->specialty) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                    placeholder="Ex: Froid industriel, Électrotechnique...">
                            </div>

                            <div class="space-y-2 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                                <x-multi-select-dropdown 
                                    name="seminars[]" 
                                    label="Séminaires assignés à ce formateur" 
                                    :options="$seminars" 
                                    :selected="old('seminars', $assignedSeminars)" 
                                    placeholder="Sélectionner les séminaires..." 
                                    helpText="Cochez les séminaires que ce formateur est habilité à animer et émarger." />
                            </div>
                        </div>

                        <div x-show="role == adminRoleId" x-transition class="space-y-2 pt-2 border-t border-slate-100">
                            <label for="position_admin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Poste occupé au CFPT Sénégal-Japon</label>
                            <input type="text" name="position" id="position_admin" value="{{ old('position', $user->position) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                placeholder="Ex: Directeur des Études, Responsable Pédagogique...">
                        </div>

                        <div x-show="role == coordinatorRoleId" x-transition class="space-y-2 pt-2 border-t border-slate-100">
                            <label for="supervisor" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Supérieur hiérarchique</label>
                            <input type="text" name="supervisor" id="supervisor" value="{{ old('supervisor', $user->supervisor) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                placeholder="Ex: Directeur Général, Coordonnateur JICA...">
                        </div>

                        <div x-show="role == jicaRoleId" x-transition class="space-y-2 pt-2 border-t border-slate-100">
                            <label for="position_jica" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Fonction au sein de la JICA</label>
                            <input type="text" name="position" id="position_jica" value="{{ old('position', $user->position) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                placeholder="Ex: Représentant Résident, Chargé de Programme...">
                        </div>

                        <!-- Candidate Info Clean Text -->
                        <div x-show="role == candidateRoleId" x-transition class="pt-2 text-xs text-slate-500">
                            <p><span class="font-bold text-slate-700">Note sur le compte Candidat :</span> Vous pouvez modifier son nom, son email, son mot de passe ci-dessous, ou lui attribuer un autre rôle.</p>
                        </div>

                        <!-- Direct Password Update (Optional) -->
                        <div class="pt-4 border-t border-slate-200 space-y-4">
                            <h3 class="text-sm font-bold text-slate-800">
                                Définir un nouveau mot de passe (Optionnel)
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="password" class="block text-xs font-bold text-slate-700">Nouveau mot de passe</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">key</span>
                                        <input type="password" name="password" id="password" minlength="6"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition"
                                            placeholder="Laisser vide pour ne pas changer">
                                    </div>
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                        <input type="checkbox" name="must_change_password" value="1" {{ $user->must_change_password ? 'checked' : '' }} class="w-4 h-4 text-[#4B6A95] rounded border-slate-300 focus:ring-[#4B6A95]">
                                        <span class="text-xs font-semibold text-slate-700">Forcer le changement de mot de passe à la prochaine connexion</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Photo -->
                        <div class="space-y-2 pt-4 border-t border-slate-100">
                            <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Photo de profil / Avatar</label>
                            <div class="flex items-center gap-4">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-xs">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[#4B6A95] font-bold text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                                <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#4B6A95] hover:file:bg-blue-100 transition">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs sm:text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Enregistrer toutes les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Col: Quick Password Reset Card -->
            <div class="space-y-6">
                <!-- User Summary Card -->
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 text-center">
                    <div class="relative inline-block mb-3">
                        @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-[#4B6A95]/20 mx-auto shadow-sm">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-50 border-2 border-blue-100 flex items-center justify-center text-[#4B6A95] font-extrabold text-2xl mx-auto shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">{{ $user->name }}</h3>
                    <p class="text-slate-500 text-xs mt-0.5">{{ $user->email }}</p>

                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-left text-xs">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Statut mot de passe :</span>
                            @if($user->must_change_password)
                                <span class="text-amber-600 font-bold">À renouveler</span>
                            @else
                                <span class="text-emerald-600 font-bold">Actif / À jour</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Créé le :</span>
                            <span class="font-medium text-slate-700">{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Password Reset -->
                <div class="bg-amber-50/70 rounded-[24px] border border-amber-200/80 shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-amber-600">lock_reset</span>
                        <h3 class="font-bold text-slate-900 text-sm">Réinitialisation rapide</h3>
                    </div>
                    <p class="text-xs text-slate-600 mb-4">
                        Définissez un mot de passe temporaire pour <strong>{{ $user->name }}</strong>.
                    </p>

                    <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="quick_password" class="block text-xs font-bold text-slate-700">Nouveau mot de passe temporaire</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">key</span>
                                <input type="text" name="password" id="quick_password" required minlength="6"
                                    value="Pfpt2026@"
                                    class="w-full bg-white border border-amber-300 rounded-xl pl-10 pr-4 py-2 text-xs font-mono text-slate-800 outline-none focus:border-amber-500 transition">
                            </div>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="must_change_password" value="1" checked class="w-4 h-4 text-amber-600 rounded border-amber-300 focus:ring-amber-500">
                            <span class="text-[11px] font-semibold text-slate-700">Forcer le changement à la connexion</span>
                        </label>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-xs flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">send</span>
                            Appliquer et réinitialiser
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
