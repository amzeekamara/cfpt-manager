@extends('layouts.admin')

@section('header_title', 'Éditions & Budgets')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-[1400px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Cycles de Formation</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Gérez les éditions du programme. Les cycles terminés sont consultables dans 
                        <a href="{{ route('admin.archives') }}" class="text-brand-primary font-bold hover:underline inline-flex items-center gap-1">
                            l'archivage <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.editions.create') }}" class="px-6 py-3 bg-brand-primary hover:opacity-90 text-white font-medium rounded-full flex items-center gap-2 transition shadow-lg shadow-brand-primary/30">
                    <span class="material-symbols-outlined text-[20px] leading-none">add_circle</span>
                    Nouvelle Édition
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @forelse($editions as $edition)
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 p-5">
                    <span class="material-symbols-outlined text-5xl text-brand-light group-hover:text-brand-primary/20 transition-colors">calendar_month</span>
                </div>
                
                <div class="flex items-center gap-2 mb-4">
                    @if($edition->status === 'active')
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                            Actif
                        </span>
                    @elseif($edition->status === 'completed')
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-50 text-gray-700 border border-gray-200 shadow-sm">
                            Terminé
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                            Brouillon
                        </span>
                    @endif
                    <span class="text-xs text-gray-500 font-medium px-2 py-1 bg-gray-50 rounded-full border border-gray-100">{{ $edition->start_date->format('Y') }}</span>
                </div>

                <h2 class="text-xl font-headline font-bold text-gray-900 mb-2 group-hover:text-brand-primary transition-colors pr-10">{{ $edition->title }}</h2>
                <p class="text-sm text-gray-500 line-clamp-2 mb-6 h-10">{{ $edition->themes }}</p>

                <div class="space-y-4 pt-5 border-t border-gray-100 mt-auto">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Budget Alloué</span>
                        <span class="text-sm font-black text-gray-900">{{ number_format($edition->total_budget, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Séminaires</span>
                        <span class="text-xs font-bold text-brand-primary px-3 py-1 bg-brand-light/50 rounded-full border border-brand-light">{{ $edition->seminars_count }} sessions</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Période</span>
                        <span class="text-[11px] font-medium text-gray-700 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">{{ $edition->start_date->format('d M') }} - {{ $edition->end_date->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('admin.finances', ['edition_id' => $edition->id]) }}" class="flex-1 py-2 text-center text-xs font-bold text-brand-primary bg-brand-light/30 rounded-xl hover:bg-brand-light transition-colors border border-brand-light shadow-sm">Détails Budget</a>
                    
                    <a href="{{ route('admin.editions.edit', $edition) }}" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors border border-gray-200 shadow-sm" title="Modifier">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </a>

                    <form action="{{ route('admin.editions.toggle', $edition) }}" method="POST" class="inline m-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors border border-amber-200 shadow-sm" title="{{ $edition->status === 'active' ? 'Archiver' : 'Activer' }}">
                            <span class="material-symbols-outlined text-sm">{{ $edition->status === 'active' ? 'archive' : 'unarchive' }}</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.editions.destroy', $edition) }}" method="POST" class="inline m-0" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette édition ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors border border-rose-200 shadow-sm" title="Supprimer">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="lg:col-span-3 py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">event_busy</span>
                <p class="font-bold text-gray-500">Aucune édition n'a été créée pour le moment.</p>
                <a href="{{ route('admin.editions.create') }}" class="inline-block mt-4 text-brand-primary font-bold hover:underline">Initialiser le premier cycle</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
