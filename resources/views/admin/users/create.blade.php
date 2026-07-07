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

    <div class="bg-white rounded-xl shadow-xl border border-slate-100 p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-slate-700">Nom Complet</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border-slate-200 focus:ring-primary focus:border-primary"
                        placeholder="Ex: Jean Dupont">
                </div>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-bold text-slate-700">Adresse Email Professionnelle</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border-slate-200 focus:ring-primary focus:border-primary"
                        placeholder="jean.dupont@cfptsj.sn">
                </div>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="role_id" class="block text-sm font-bold text-slate-700">Rôle de l'utilisateur</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">shield_person</span>
                    <select name="role_id" id="role_id" required
                        class="w-full pl-12 pr-4 py-3 rounded-lg border-slate-200 focus:ring-primary focus:border-primary appearance-none">
                        <option value="">Sélectionnez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-lg text-white font-bold text-lg hover:opacity-90 transition-all shadow-lg"
                    style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                    Créer le compte et envoyer l'invitation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
