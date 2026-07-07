@extends('layouts.admin')

@section('header_title', 'Modifier l\'Édition')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.editions.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Modifier le Cycle</h1>
            <p class="text-on-surface-variant font-body">Mettez à jour les informations de l'édition : <span class="font-bold text-primary">{{ $edition->title }}</span></p>
        </div>
    </div>

    <form action="{{ route('admin.editions.update', $edition) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant shadow-sm space-y-8">
            <!-- Basic Info Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">description</span> Objet de l'édition
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 lg:col-span-2">
                        <label for="title" class="text-sm font-bold text-on-surface-variant">Titre descriptif</label>
                        <input type="text" name="title" id="title" required value="{{ old('title', $edition->title) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>
            </div>

            <!-- Financials & Themes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-4">
                    <label for="total_budget" class="text-sm font-bold text-on-surface-variant">Budget Global Alloué (FCFA)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-on-surface-variant">CFA</span>
                        <input type="number" name="total_budget" id="total_budget" required step="1000" value="{{ old('total_budget', (int)$edition->total_budget) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl pl-14 pr-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-lg">
                    </div>
                </div>
                <div class="space-y-4">
                    <label for="themes" class="text-sm font-bold text-on-surface-variant">Thème / Domaine</label>
                    <input list="themes-list" name="themes" id="themes" value="{{ old('themes', $edition->themes) }}" placeholder="Ex: Informatique, Éducation..." 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <datalist id="themes-list">
                        @foreach($themes as $theme)
                            <option value="{{ $theme->name }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <!-- Period -->
            <div class="pt-4 space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">date_range</span> Période Couverte
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="text-sm font-bold text-on-surface-variant">Date de lancement</label>
                        <input type="date" name="start_date" id="start_date" required value="{{ old('start_date', $edition->start_date->format('Y-m-d')) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="text-sm font-bold text-on-surface-variant">Date d'échéance </label>
                        <input type="date" name="end_date" id="end_date" required value="{{ old('end_date', $edition->end_date->format('Y-m-d')) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button type="button" onclick="history.back()" class="px-8 py-3 rounded-xl border border-outline-variant font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
            <button type="submit" class="px-12 py-3 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-0.5 transition-all">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection
