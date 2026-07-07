@extends('layouts.admin')

@section('header_title', 'Archivage et Historique')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Archivage</h1>
            <p class="text-on-surface-variant font-body">Consultez l'historique des séminaires passés et l'activité du système.</p>
        </div>
        <button class="bg-surface-container-lowest border border-outline-variant text-on-surface py-2.5 px-4 rounded-lg shadow-sm hover:bg-surface-variant transition-all font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">system_update_alt</span>
            Sauvegarde complète
        </button>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant mb-8 flex items-center gap-6">
        <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-700 shrinks-0">
            <span class="material-symbols-outlined text-[32px]">database</span>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-on-surface mb-1">Base de données conforme</h3>
            <p class="text-sm text-on-surface-variant">Dernière sauvegarde automatique effectuée aujourd'hui à {{ now()->format('H:i') }}.</p>
        </div>
        <div class="flex gap-8">
            <div class="text-right">
                <div class="text-2xl font-headline font-black text-on-surface">{{ $archivedEditions->count() }}</div>
                <div class="text-sm text-on-surface-variant">Cycles archivés</div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-headline font-black text-on-surface">{{ $pastSeminars->count() }}</div>
                <div class="text-sm text-on-surface-variant">Séminaires terminés</div>
            </div>
        </div>
    </div>

    <!-- Archived Editions Section -->
    @if($archivedEditions->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-amber-600">history_edu</span>
            <h2 class="text-xl font-headline font-bold text-on-surface tracking-tight">Cycles de Formation Historiques</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($archivedEditions as $edition)
            <div class="bg-surface-container-lowest border border-outline-variant p-5 rounded-2xl shadow-sm hover:shadow-md transition-all group relative">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">Archivé</span>
                    <span class="text-[11px] font-bold text-on-surface-variant">{{ $edition->start_date->format('Y') }}</span>
                </div>
                
                <h3 class="font-bold text-on-surface mb-1 group-hover:text-primary transition-colors">{{ $edition->title }}</h3>
                <p class="text-[11px] text-on-surface-variant mb-4">{{ $edition->seminars_count }} séminaires · {{ number_format($edition->total_budget, 0, ',', ' ') }} FCFA</p>
                
                <div class="flex gap-2 mt-4 pt-4 border-t border-outline-variant/50">
                    <a href="{{ route('admin.finances', ['edition_id' => $edition->id]) }}" class="flex-1 text-center py-2 text-[11px] font-bold text-primary bg-primary/5 rounded-lg hover:bg-primary/10 transition-colors">Détails</a>
                    
                    <form action="{{ route('admin.editions.toggle', $edition) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-2 text-[11px] font-bold text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                            Réactiver
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Archives Data Table -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm">
        <div class="p-4 border-b border-outline-variant flex justify-between bg-surface-container-low/30">
            <h2 class="font-bold text-lg text-on-surface">Séminaires Clôturés</h2>
            <div class="flex gap-2">
                <select class="bg-surface-container-lowest border border-outline-variant rounded-lg text-sm px-3 py-1.5 focus:ring-primary focus:border-primary">
                    <option>Toutes les années</option>
                    @foreach($pastSeminars->pluck('end_date')->map->format('Y')->unique() as $year)
                        <option>Année: {{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-lowest">
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Référence</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Titre du Séminaire</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant">Date Fin</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant text-right">Participants</th>
                    <th class="py-3 px-6 text-xs text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/60">
                @forelse($pastSeminars as $seminar)
                <tr class="hover:bg-surface-container-low/50">
                    <td class="py-4 px-6 text-sm font-mono text-slate-500">SEM-{{ $seminar->id }}-{{ $seminar->end_date->format('y') }}</td>
                    <td class="py-4 px-6 font-medium text-on-surface text-sm">{{ $seminar->title }}</td>
                    <td class="py-4 px-6 text-sm text-on-surface-variant">{{ $seminar->end_date->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-sm text-right font-medium">
                        {{ $seminar->enrollments()->where('status', 'approved')->count() }}/{{ $seminar->max_participants }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <button class="text-primary hover:bg-primary/10 p-1.5 rounded transition-colors text-sm font-medium">Consulter</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-20">history</span>
                        <p>Aucun séminaire archive trouvé.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
