@extends('layouts.admin')

@section('header_title', 'Éditions & Budgets')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Cycles de Formation</h1>
            <p class="text-on-surface-variant font-body">
                Gérez les éditions du programme. Les cycles terminés sont consultables dans 
                <a href="{{ route('admin.archives') }}" class="text-primary font-bold hover:underline inline-flex items-center gap-1">
                    l'archivage <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </p>
        </div>
        <a href="{{ route('admin.editions.create') }}" class="bg-primary hover:opacity-90 text-on-primary py-2.5 px-5 rounded-lg shadow-sm hover:shadow transition-all group flex items-center gap-2 font-semibold">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Nouvelle Édition
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @forelse($editions as $edition)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                <span class="material-symbols-outlined text-4xl text-primary/10 group-hover:text-primary/20 transition-colors">calendar_month</span>
            </div>
            
            <div class="flex items-center gap-2 mb-4">
                @if($edition->status === 'active')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                        Actif
                    </span>
                @elseif($edition->status === 'completed')
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                        Terminé
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                        Brouillon
                    </span>
                @endif
                <span class="text-xs text-on-surface-variant font-medium">{{ $edition->start_date->format('Y') }}</span>
            </div>

            <h2 class="text-xl font-headline font-bold text-on-surface mb-2 group-hover:text-primary transition-colors">{{ $edition->title }}</h2>
            <p class="text-sm text-on-surface-variant line-clamp-2 mb-6 h-10">{{ $edition->themes }}</p>

            <div class="space-y-4 pt-4 border-t border-outline-variant/50">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-on-surface-variant uppercase">Budget Alloué</span>
                    <span class="text-sm font-black text-blue-900">{{ number_format($edition->total_budget, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-on-surface-variant uppercase">Séminaires</span>
                    <span class="text-xs font-bold px-2 py-1 bg-surface-container rounded-lg">{{ $edition->seminars_count }} sessions</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-on-surface-variant uppercase">Période</span>
                    <span class="text-[11px] font-medium">{{ $edition->start_date->format('d M') }} - {{ $edition->end_date->format('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.finances', ['edition_id' => $edition->id]) }}" class="flex-1 py-2 text-center text-xs font-bold text-primary bg-primary/5 rounded-lg hover:bg-primary/10 transition-colors border border-primary/20">Détails Budget</a>
                
                <a href="{{ route('admin.editions.edit', $edition) }}" class="p-2 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant/30" title="Modifier">
                    <span class="material-symbols-outlined text-sm">edit</span>
                </a>

                <form action="{{ route('admin.editions.toggle', $edition) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors border border-amber-200/30" title="{{ $edition->status === 'active' ? 'Archiver' : 'Activer' }}">
                        <span class="material-symbols-outlined text-sm">{{ $edition->status === 'active' ? 'archive' : 'unarchive' }}</span>
                    </button>
                </form>

                <form action="{{ route('admin.editions.destroy', $edition) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette édition ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200/30" title="Supprimer">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="lg:col-span-3 py-20 text-center bg-surface-container-low rounded-3xl border-2 border-dashed border-outline-variant">
            <span class="material-symbols-outlined text-5xl text-outline mb-4">event_busy</span>
            <p class="font-bold text-on-surface-variant">Aucune édition n'a été créée pour le moment.</p>
            <a href="{{ route('admin.editions.create') }}" class="inline-block mt-4 text-primary font-bold hover:underline">Initialiser le premier cycle</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
