@extends('layouts.admin')

@section('header_title', 'Catalogue des Séminaires')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-[1400px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Séminaires</h1>
                    <p class="text-gray-500 text-sm mt-1">Créez et planifiez les sessions de formation continues.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.seminars.create') }}" class="px-6 py-3 bg-brand-primary hover:opacity-90 text-white font-medium rounded-full flex items-center gap-2 transition shadow-lg shadow-brand-primary/30">
                    <span class="material-symbols-outlined text-[20px] leading-none">add_task</span>
                    Créer un Séminaire
                </a>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 mb-8 bg-gray-50 p-1 rounded-full w-fit border border-gray-100">
            <button class="px-6 py-2 bg-white text-gray-900 font-semibold text-sm rounded-full shadow-sm border border-gray-200 transition-all">Actifs ({{ $activeSeminars->count() }})</button>
            <button class="px-6 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-full hover:bg-gray-100 transition-all">Brouillons ({{ $draftSeminars->count() }})</button>
            <button class="px-6 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-full hover:bg-gray-100 transition-all">Passés ({{ $pastSeminars->count() }})</button>
        </div>

        <!-- Seminar Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Create New Card Placeholder -->
            <a href="{{ route('admin.seminars.create') }}" class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 hover:border-brand-primary/50 hover:bg-brand-light/30 transition-all cursor-pointer flex flex-col items-center justify-center p-8 text-center min-h-[350px] group shadow-sm">
                <div class="w-16 h-16 rounded-full bg-white border border-gray-200 group-hover:bg-brand-primary group-hover:text-white group-hover:border-brand-primary flex items-center justify-center mb-4 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[32px] text-gray-400 group-hover:text-white">add</span>
                </div>
                <h3 class="font-headline font-bold text-lg text-gray-900 mb-1">Nouveau Séminaire</h3>
                <p class="text-sm text-gray-500 max-w-[200px]">Commencez à planifier la prochaine session de formation.</p>
            </a>

            @foreach($activeSeminars as $seminar)
                @php
                    $enrolledCount = $seminar->enrollments()->where('status', 'approved')->count();
                    $percent = $seminar->max_participants > 0 ? ($enrolledCount / $seminar->max_participants) * 100 : 0;
                    $statusColor = $percent >= 90 ? 'amber' : 'emerald';
                    $statusText = $percent >= 90 ? 'Bientôt Plein' : 'Inscriptions Ouvertes';
                    
                    $imageUrl = $seminar->image ? asset('storage/' . $seminar->image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop';
                @endphp
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all overflow-hidden flex flex-col">
                    <div class="h-40 relative bg-cover bg-center" style="background-image: url('{{ $imageUrl }}');">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent flex items-end p-5">
                            <span class="bg-{{ $statusColor }}-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">{{ $statusText }}</span>
                        </div>
                        @if($seminar->edition)
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/95 backdrop-blur-sm text-brand-primary text-xs font-bold px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                    {{ $seminar->edition->title }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs font-bold text-brand-primary mb-2 uppercase tracking-wider">{{ $seminar->department_name }}</div>
                        <h3 class="text-xl font-headline font-bold text-gray-900 mb-3 leading-tight">{{ $seminar->title }}</h3>
                        <p class="text-sm text-gray-500 mb-5 line-clamp-2">{{ $seminar->description }}</p>
                        
                        <div class="space-y-3 mt-auto mb-6">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                                </div>
                                {{ $seminar->start_date->format('d M') }} - {{ $seminar->end_date->format('d M Y') }}
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">meeting_room</span>
                                </div>
                                {{ $seminar->intervenants->count() }} Intervenant(s)
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">group</span>
                                </div>
                                {{ $enrolledCount }} / {{ $seminar->max_participants }} Inscrits
                            </div>
                        </div>

                        <div class="relative w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-6">
                            <div class="absolute top-0 left-0 h-full bg-{{ $statusColor }}-500 rounded-full transition-all" style="width: {{ $percent }}%;"></div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('admin.seminars.edit', $seminar->id) }}" class="flex-1 text-center border border-gray-200 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm">Modifier</a>
                            <form action="{{ route('admin.seminars.destroy', $seminar->id) }}" method="POST" class="flex-1 m-0" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-rose-50 text-rose-600 border border-rose-100 py-2.5 rounded-xl text-sm font-semibold hover:bg-rose-100 hover:border-rose-200 transition-colors shadow-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
