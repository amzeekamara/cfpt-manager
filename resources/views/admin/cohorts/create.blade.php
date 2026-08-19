@extends('layouts.admin')

@section('header_title', 'Nouvelle Édition')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.cohorts.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Lancer un nouveau Cycle</h1>
            <p class="text-on-surface-variant font-body">Définissez le budget global et la période de formation.</p>
        </div>
    </div>

    <form action="{{ route('admin.cohorts.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-surface-container-lowest rounded-md p-8 border border-outline-variant shadow-sm space-y-8">
            <!-- Basic Info Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">description</span> Objet de l'édition
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 lg:col-span-2">
                        <label for="title" class="text-sm font-bold text-on-surface-variant">Titre descriptif</label>
                        <input type="text" name="title" id="title" required placeholder="Ex: PFPT 2024 - Phase de renforcement technique" 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>
            </div>

            <!-- Financials -->
            <div class="pt-4">
                <div class="space-y-4 max-w-xl">
                    <label for="total_budget" class="text-sm font-bold text-on-surface-variant">Budget Global Alloué (FCFA)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-on-surface-variant">CFA</span>
                        <input type="number" name="total_budget" id="total_budget" required step="1000" placeholder="5 000 000" 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-md pl-14 pr-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-lg">
                    </div>
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
                        <input type="date" name="start_date" id="start_date" required 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="text-sm font-bold text-on-surface-variant">Date d'échéance </label>
                        <input type="date" name="end_date" id="end_date" required 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button type="button" onclick="history.back()" class="px-8 py-3 rounded-lg border border-outline-variant font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
            <button type="submit" class="px-12 py-3 rounded-lg bg-primary text-on-primary font-bold shadow-lg shadow-primary/10 hover:shadow-xl hover:-translate-y-0.5 transition-all">Valider l'Édition</button>
        </div>
    </form>
</div>
@endsection
