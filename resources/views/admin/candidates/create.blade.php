@extends('layouts.admin')

@section('header_title', 'Nouvelle Inscription')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.candidates') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Inscrire un Candidat</h1>
            <p class="text-on-surface-variant font-body">Enregistrez un nouveau candidat et assignez-le à un séminaire.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex gap-3">
            <span class="material-symbols-outlined">error</span>
            <ul class="text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.candidates') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant shadow-sm space-y-8">
            <!-- Identity Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span> Identité du Candidat
                </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-outline-variant/30">
                <div class="space-y-2">
                    <label for="name" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nom complet</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Ex: Jean Martin" 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="space-y-2">
                    <label for="email" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Adresse Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="Ex: jean.martin@email.com" 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="space-y-2">
                    <label for="phone" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Numéro de Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+221 ..." 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="space-y-2">
                    <label for="religion" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Religion</label>
                    <input type="text" name="religion" id="religion" value="{{ old('religion') }}" placeholder="Ex: Islam, Christianisme..." 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="space-y-2">
                    <label for="nationality" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nationalité</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" placeholder="Sénégalaise, ..." 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="space-y-2">
                    <label for="current_profession" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Profession actuelle</label>
                    <input type="text" name="current_profession" id="current_profession" value="{{ old('current_profession') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>

            <!-- Seminar Selection -->
            <div class="pt-4 space-y-4">
                <h2 class="text-lg font-bold text-secondary flex items-center gap-2 uppercase tracking-tight">
                    <span class="material-symbols-outlined">school</span> Programme
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 lg:col-span-2">
                        <label for="seminar_id" class="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Sélectionner un séminaire actif</label>
                        <select name="seminar_id" id="seminar_id" required 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="">-- Choisir un programme --</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}" {{ old('seminar_id') == $seminar->id ? 'selected' : '' }}>
                                    {{ $seminar->title }} ({{ $seminar->start_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Enrollment Status -->
            <div class="pt-4 space-y-4">
                <h2 class="text-lg font-bold text-tertiary flex items-center gap-2">
                    <span class="material-symbols-outlined">assignment_turned_in</span> Statut de l'inscription
                </h2>
                <div class="flex flex-wrap gap-4">
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="pending" checked class="hidden peer">
                        <div class="p-4 rounded-xl border-2 border-outline-variant bg-surface-container-low peer-checked:border-amber-500 peer-checked:bg-amber-50 group-hover:bg-surface-variant transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-on-surface">En attente</span>
                                <span class="material-symbols-outlined text-amber-600">hourglass_empty</span>
                            </div>
                            <p class="text-xs text-on-surface-variant">Nécessite une validation ultérieure.</p>
                        </div>
                    </label>
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="approved" class="hidden peer">
                        <div class="p-4 rounded-xl border-2 border-outline-variant bg-surface-container-low peer-checked:border-emerald-500 peer-checked:bg-emerald-50 group-hover:bg-surface-variant transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-on-surface">Confirmé</span>
                                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                            </div>
                            <p class="text-xs text-on-surface-variant">Candidat admis immédiatement.</p>
                        </div>
                    </label>
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="waiting_list" class="hidden peer">
                        <div class="p-4 rounded-xl border-2 border-outline-variant bg-surface-container-low peer-checked:border-slate-500 peer-checked:bg-slate-50 group-hover:bg-surface-variant transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-on-surface">Liste d'attente</span>
                                <span class="material-symbols-outlined text-slate-600">format_list_bulleted</span>
                            </div>
                            <p class="text-xs text-on-surface-variant">Placer en attente de place libre.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button type="button" onclick="history.back()" class="px-8 py-3 rounded-xl border border-outline-variant font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
            <button type="submit" class="px-12 py-3 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-0.5 transition-all">Inscrire le candidat</button>
        </div>
    </form>
</div>
@endsection
