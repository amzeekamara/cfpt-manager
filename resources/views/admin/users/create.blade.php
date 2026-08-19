@extends('layouts.admin')

@section('header_title', 'Ajouter un Utilisateur')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <div class="mb-10">
        <a href="{{ route('admin.users.index') }}" class="text-primary flex items-center gap-2 font-semibold mb-4 hover:underline">
            <span class="material-symbols-outlined">arrow_back</span>
            Retour à la liste
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Nouveau Compte</h1>
        <p class="text-on-secondary-container font-body body-md">L'utilisateur recevra un email pour configurer son mot de passe.</p>
    </div>

    <div class="bg-white rounded-lg shadow-xl border border-slate-100 p-8" x-data="{ 
        role: '{{ old('role_id') }}', 
        trainerRoleId: '{{ $roles->where('name', 'Formateur')->first()->id ?? '' }}',
        adminRoleId: '{{ $roles->where('name', 'Administrateur')->first()->id ?? '' }}',
        coordinatorRoleId: '{{ $roles->where('name', 'Coordinateur')->first()->id ?? '' }}',
        jicaRoleId: '{{ $roles->where('name', 'Agent JICA')->first()->id ?? '' }}',
        candidateRoleId: '{{ $roles->where('name', 'Candidat')->first()->id ?? '' }}'
    }">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="role_id" class="block text-sm font-bold text-slate-700">Rôle de l'utilisateur <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">shield_person</span>
                    <select name="role_id" id="role_id" required x-model="role"
                        class="w-full pl-12 pr-4 py-3 rounded-md border-slate-200 focus:ring-primary focus:border-primary appearance-none">
                        <option value="">Sélectionnez d'abord un rôle</option>
                        @foreach($roles as $roleOption)
                            <option value="{{ $roleOption->id }}" data-name="{{ $roleOption->name }}" {{ old('role_id') == $roleOption->id ? 'selected' : '' }}>
                                {{ $roleOption->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('role_id') <p class="text-blue-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Alert for Candidate -->
            <div x-show="role == candidateRoleId" style="display: none;" x-transition class="bg-blue-50 text-primary p-4 rounded-lg border border-blue-200 flex gap-3">
                <span class="material-symbols-outlined text-blue-500">info</span>
                <p class="text-sm font-medium mt-0.5">Les candidats doivent s'inscrire eux-mêmes via le portail de candidature ou être ajoutés depuis le menu Gestion des Candidatures. Un profil utilisateur est automatiquement créé lors de l'inscription.</p>
            </div>

            <!-- Main Form Content -->
            <div x-show="role != candidateRoleId && role !== ''" x-transition class="space-y-6" style="display: none;">
                
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-slate-700">Nom Complet</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                        <input type="text" name="name" id="name" :required="role != candidateRoleId && role !== ''" value="{{ old('name') }}"
                            class="w-full pl-12 pr-4 py-3 rounded-md border-slate-200 focus:ring-primary focus:border-primary"
                            placeholder="Ex: Jean Dupont">
                    </div>
                    @error('name') <p class="text-blue-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-slate-700">Adresse Email Professionnelle</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                        <input type="email" name="email" id="email" :required="role != candidateRoleId && role !== ''" value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3 rounded-md border-slate-200 focus:ring-primary focus:border-primary"
                            placeholder="jean.dupont@cfptsj.sn">
                    </div>
                    @error('email') <p class="text-blue-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Dynamic Fields -->
                <div class="space-y-4" x-show="role == trainerRoleId" style="display: none;">
                    <div class="space-y-2">
                        <label for="specialty" class="block text-sm font-bold text-slate-700">Matière / Spécialité</label>
                        <input type="text" name="specialty" id="specialty" :disabled="role != trainerRoleId" value="{{ old('specialty') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-primary focus:border-primary block p-3 transition-colors" placeholder="Ex: Informatique, Gestion...">
                        @error('specialty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2 p-4 bg-blue-50/60 rounded-2xl border border-blue-100">
                        <x-multi-select-dropdown 
                            name="seminars[]" 
                            label="Séminaires assignés au formateur" 
                            :options="$seminars" 
                            :selected="old('seminars', [])" 
                            placeholder="Sélectionner les séminaires..." 
                            helpText="Vous pouvez assigner un ou plusieurs séminaires initiaux à ce formateur." />
                        @error('seminars') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2" x-show="role == adminRoleId" style="display: none;">
                    <label for="position_admin" class="block text-sm font-bold text-slate-700">Poste occupé au CFPT Sénégal Japon</label>
                    <input type="text" name="position" id="position_admin" :disabled="role != adminRoleId" value="{{ old('position') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-primary focus:border-primary block p-3 transition-colors" placeholder="Ex: Directeur des Études...">
                </div>

                <!-- Champs pour le Coordinateur (Ex-Secrétaire) -->
                <div class="space-y-2" x-show="role == coordinatorRoleId" style="display: none;">
                    <label for="supervisor" class="block text-sm font-bold text-slate-700">Supérieur hiérarchique</label>
                    <input type="text" name="supervisor" id="supervisor" :disabled="role != coordinatorRoleId" value="{{ old('supervisor') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-primary focus:border-primary block p-3 transition-colors" placeholder="Ex: Directeur Général, Responsable RH...">
                </div>

                <div class="space-y-2" x-show="role == jicaRoleId" style="display: none;">
                    <label for="position_jica" class="block text-sm font-bold text-slate-700">Fonction au niveau de la JICA</label>
                    <input type="text" name="position" id="position_jica" :disabled="role != jicaRoleId" value="{{ old('position') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-primary focus:border-primary block p-3 transition-colors" placeholder="Ex: Représentant Résident...">
                </div>

                <div class="space-y-2" id="password_container">
                    <label for="password" class="block text-sm font-bold text-slate-700">Mot de passe par défaut</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">key</span>
                        <input type="text" name="password" id="password" value="{{ old('password') }}" :required="role != candidateRoleId && role !== ''"
                            class="w-full pl-12 pr-4 py-3 rounded-md border-slate-200 focus:ring-primary focus:border-primary"
                            placeholder="Mot de passe initial">
                    </div>
                    @error('password') <p class="text-blue-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-500 mt-1" id="password_hint">L'utilisateur devra le changer à sa première connexion.</p>
                </div>

                <div class="space-y-2">
                    <label for="avatar" class="block text-sm font-bold text-slate-700">Photo de profil (Optionnel)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">image</span>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg"
                            class="w-full pl-12 pr-4 py-3 rounded-md border-slate-200 focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100">
                    </div>
                    @error('avatar') <p class="text-blue-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full py-4 px-6 rounded-md text-white font-bold text-lg hover:opacity-90 transition-all shadow-lg"
                        style="background: linear-gradient(135deg, #0f4c81 0%, #4B6A95 100%);">
                        Créer le compte et envoyer l'invitation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
