@extends('layouts.admin')

@section('header_title', 'Modifier le Formulaire')

@section('content')
<div class="p-8 max-w-4xl mx-auto pb-20">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.forms.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Modifier le Formulaire</h1>
            <p class="text-on-surface-variant font-body">Mettez à jour les informations du formulaire.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg flex gap-4">
            <span class="material-symbols-outlined text-3xl">report</span>
            <div>
                <h3 class="font-bold mb-1">Des erreurs ont été détectées</h3>
                <ul class="text-sm space-y-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.forms.update', $form) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-lowest rounded-[40px] p-10 border border-outline-variant shadow-sm space-y-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 p-10 opacity-[0.03] pointer-events-none">
                <span class="material-symbols-outlined text-[120px]">edit</span>
            </div>

            <div class="space-y-4 relative z-10">
                <h2 class="text-xl font-headline font-black text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">description</span> Informations de Base
                </h2>
                
                <div class="space-y-2">
                    <label for="title" class="text-sm font-black text-on-surface-variant uppercase tracking-widest px-1">Titre du formulaire <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $form->title) }}" required placeholder="Ex: Inscription au Séminaire de Gestion" 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-md px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-black text-on-surface-variant uppercase tracking-widest px-1">Description / Consignes</label>
                    <textarea name="description" id="description" rows="3" placeholder="Texte d'introduction affiché en haut du formulaire..." 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-md px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('description', $form->description) }}</textarea>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <div class="space-y-4 relative z-10">
                <h2 class="text-xl font-headline font-black text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">category</span> Rattachement
                </h2>

                <div class="space-y-2">
                    <label for="seminar_id" class="text-sm font-black text-on-surface-variant uppercase tracking-widest px-1">Séminaire & Édition <span class="text-rose-500">*</span></label>
                    <select name="seminar_id" id="seminar_id" required class="w-full bg-surface-container-low border border-outline-variant rounded-md px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all appearance-none font-bold">
                        <option value="">-- Sélectionnez un Séminaire --</option>
                        @foreach($cohorts as $cohort)
                            <optgroup label="Édition : {{ $cohort->title }}">
                                @foreach($cohort->seminars as $seminar)
                                    <option value="{{ $seminar->id }}" {{ old('seminar_id', $form->seminar_id) == $seminar->id ? 'selected' : '' }}>
                                        {{ $seminar->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <p class="text-xs text-on-surface-variant/70 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Les candidatures soumises seront automatiquement assignées à ce séminaire.
                    </p>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <div class="space-y-4 relative z-10">
                <h2 class="text-xl font-headline font-black text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500">toggle_on</span> Statut
                </h2>
                
                <label class="flex items-center gap-4 cursor-pointer p-4 rounded-md border border-outline-variant hover:bg-surface-container transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $form->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary">
                    <div>
                        <div class="font-bold text-on-surface">Formulaire Actif</div>
                        <div class="text-xs text-on-surface-variant">Si désactivé, le lien public affichera que le formulaire est fermé.</div>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-6 pt-6">
            <a href="{{ route('admin.forms.index') }}" class="px-10 py-4 rounded-md border border-outline-variant font-bold text-on-surface-variant hover:bg-surface-container transition-all">Annuler</a>
            <button type="submit" class="px-12 py-4 rounded-md bg-primary text-white font-black shadow-lg shadow-primary/10 hover:shadow-2xl hover:-translate-y-1 transition-all">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
