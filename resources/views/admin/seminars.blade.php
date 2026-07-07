@extends('layouts.admin')

@section('header_title', 'Catalogue des Séminaires')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Séminaires</h1>
            <p class="text-on-surface-variant font-body">Créez et planifiez les sessions de formation continues.</p>
        </div>
        <a href="{{ route('admin.seminars.create') }}" class="bg-primary hover:opacity-90 text-on-primary py-2.5 px-5 rounded-lg shadow-sm hover:shadow transition-all group flex items-center gap-2 font-semibold">
            <span class="material-symbols-outlined text-[20px]">add_task</span>
            Créer un Séminaire
        </a>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-outline-variant mb-6">
        <button class="px-6 py-3 border-b-2 border-primary text-primary font-semibold text-sm">Séminaires Actifs ({{ $activeSeminars->count() }})</button>
        <button class="px-6 py-3 border-b-2 border-transparent hover:border-outline-variant text-on-surface-variant font-semibold text-sm transition-colors">Brouillons ({{ $draftSeminars->count() }})</button>
        <button class="px-6 py-3 border-b-2 border-transparent hover:border-outline-variant text-on-surface-variant font-semibold text-sm transition-colors">Passés ({{ $pastSeminars->count() }})</button>
    </div>

    <!-- Seminar Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Create New Card Placeholder -->
        <a href="{{ route('admin.seminars.create') }}" class="bg-surface-container-lowest rounded-2xl border-2 border-dashed border-outline-variant shadow-sm hover:border-primary hover:bg-primary/5 transition-all cursor-pointer flex flex-col items-center justify-center p-8 text-center min-h-[350px] group">
            <div class="w-16 h-16 rounded-full bg-surface-variant group-hover:bg-primary group-hover:text-white flex items-center justify-center mb-4 transition-colors">
                <span class="material-symbols-outlined text-[32px]">add</span>
            </div>
            <h3 class="font-headline font-bold text-lg text-on-surface mb-1">Nouveau Séminaire</h3>
            <p class="text-sm text-on-surface-variant max-w-[200px]">Commencez à planifier la prochaine session de formation.</p>
        </a>

        @foreach($activeSeminars as $seminar)
            @php
                $enrolledCount = $seminar->enrollments()->where('status', 'approved')->count();
                $percent = $seminar->max_participants > 0 ? ($enrolledCount / $seminar->max_participants) * 100 : 0;
                $statusColor = $percent >= 90 ? 'amber' : 'emerald';
                $statusText = $percent >= 90 ? 'Bientôt Plein' : 'Inscriptions Ouvertes';
                
                $imageUrl = $seminar->image ? asset('storage/' . $seminar->image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop';
            @endphp
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                <div class="h-32 relative bg-cover bg-center" style="background-image: url('{{ $imageUrl }}');">
                    <div class="absolute inset-0 bg-blue-900/40 flex items-end p-4">
                        <span class="bg-{{ $statusColor }}-500 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ $statusText }}</span>
                    </div>
                    @if($seminar->edition)
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-sm text-primary text-[10px] font-bold px-2 py-0.5 rounded-full border border-primary/20 shadow-sm">
                                {{ $seminar->edition->title }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="text-xs font-bold text-primary mb-1 uppercase tracking-wider">{{ $seminar->department_name }}</div>
                    <h3 class="text-xl font-headline font-bold text-on-surface mb-2 leading-tight">{{ $seminar->title }}</h3>
                    <p class="text-xs text-on-surface-variant mb-4 line-clamp-2">{{ $seminar->description }}</p>
                    
                    <div class="space-y-2 mt-auto mb-5">
                        <div class="flex items-center gap-2 text-xs text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-blue-900">calendar_month</span> 
                            {{ $seminar->start_date->format('d M') }} - {{ $seminar->end_date->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-xs text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-blue-900">meeting_room</span> 
                            Intervenants: <span class="font-bold">{{ $seminar->intervenants->count() }} enregistrés</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-blue-900">group</span> {{ $enrolledCount }}/{{ $seminar->max_participants }} Porteurs de projet
                        </div>
                    </div>

                    <div class="relative w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden mb-4">
                        <div class="absolute top-0 left-0 h-full bg-{{ $statusColor }}-500 rounded-full transition-all" style="width: {{ $percent }}%;"></div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('admin.seminars.edit', $seminar->id) }}" class="flex-1 text-center border border-outline-variant py-2 rounded-lg text-xs font-bold hover:bg-surface-variant transition-colors">Modifier</a>
                        <form action="{{ route('admin.seminars.destroy', $seminar->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-100 py-2 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection
