@extends('layouts.admin')

@section('header_title', 'Nouvelle Annonce Pédagogique')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.messages.index') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700">
                <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Nouvelle Annonce Pédagogique</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Diffusez une note d'information aux étudiants, formateurs ou départements du CFPT.</p>
            </div>
        </div>

        <div class="bg-white rounded-[26px] shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('admin.messages.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf

                <!-- Filtres Avancés -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-xs font-extrabold text-[#4B6A95] uppercase tracking-wider mb-1 border-b border-slate-200 pb-2">
                            Critères de Ciblage (Laissez vide pour envoyer à tous)
                        </h3>
                    </div>

                    <!-- Rôles -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Destinataires (Rôles)</label>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="target_roles[]" value="{{ $role->id }}"
                                        class="rounded border-slate-300 text-[#4B6A95] focus:ring-[#4B6A95]"
                                        {{ is_array(old('target_roles')) && in_array($role->id, old('target_roles')) ? 'checked' : '' }}>
                                    <span class="text-xs font-semibold text-slate-700">{{ $role->name === 'Candidat' ? 'Étudiants / Stagiaires' : $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('target_roles') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-4">
                        <!-- Filières -->
                        <div>
                            <x-multi-select-dropdown
                                name="target_seminars[]"
                                label="Filières spécifiques"
                                :options="$seminars"
                                :selected="old('target_seminars', [])"
                                placeholder="Toutes les filières ou sélectionner..." />
                        </div>

                        <!-- Départements -->
                        <div>
                            <x-multi-select-dropdown
                                name="target_departments[]"
                                label="Départements concernés"
                                :options="$departments"
                                :selected="old('target_departments', [])"
                                placeholder="Tous les départements ou sélectionner..." />
                        </div>
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sujet de l'annonce <span class="text-rose-500">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none transition"
                        placeholder="Ex: Modification d'emploi du temps ou planning des évaluations">
                    @error('subject')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="body" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contenu du message <span class="text-rose-500">*</span></label>
                    <textarea name="body" id="body" rows="6" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none transition"
                        placeholder="Votre message détaillé ici...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.messages.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-[#4B6A95] hover:bg-[#3c5678] rounded-xl shadow-sm transition inline-flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        Envoyer l'annonce
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
