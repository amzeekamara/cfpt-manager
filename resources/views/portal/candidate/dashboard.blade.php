@extends('portal.layout')
@section('title', 'Espace Candidat')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-headline font-bold text-gray-900">Bienvenue, {{ $profile->first_names }}</h1>
    <p class="text-gray-500 mt-1">Gérez votre formation et consultez les messages de vos formateurs.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profil & Inscription (2 colonnes) -->
    <div class="lg:col-span-2 flex flex-col gap-8">
        
        <!-- Carte de la Formation Actuelle -->
        @if($enrollment)
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-light/20 rounded-bl-full pointer-events-none"></div>
            
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-brand-light/30 flex items-center justify-center text-brand-primary">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <h2 class="text-xl font-headline font-bold text-gray-900">Votre Formation Actuelle</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Cycle de Formation</div>
                    <div class="font-bold text-brand-primary">{{ $enrollment->edition->title ?? 'N/A' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Filière / Département</div>
                    <div class="font-bold text-brand-primary">{{ $enrollment->department->name ?? 'N/A' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Statut d'inscription</div>
                    <div>
                        @if($enrollment->status === 'approved')
                            <span class="px-3 py-1 bg-green-100 text-green-700 font-bold text-xs rounded-full uppercase">Validé</span>
                        @elseif($enrollment->status === 'pending')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 font-bold text-xs rounded-full uppercase">En Attente</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 font-bold text-xs rounded-full uppercase">{{ $enrollment->status }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <h3 class="text-sm font-bold text-gray-900 mt-6 mb-3">Vos Séminaires Associés</h3>
            <div class="flex flex-wrap gap-2">
                @forelse($enrollment->seminars as $seminar)
                    <span class="px-3 py-1.5 bg-brand-light/30 text-brand-primary text-xs font-bold rounded-xl border border-brand-light/50">
                        {{ $seminar->title }}
                    </span>
                @empty
                    <p class="text-sm text-gray-500">Aucun séminaire sélectionné.</p>
                @endforelse
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-3xl p-6 text-yellow-800 flex items-start gap-4">
            <span class="material-symbols-outlined">warning</span>
            <div>
                <h3 class="font-bold">Aucune formation en cours</h3>
                <p class="text-sm mt-1">Vous n'êtes actuellement inscrit à aucune formation validée.</p>
            </div>
        </div>
        @endif

        <!-- Informations Personnelles -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-headline font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-primary text-[20px]">badge</span> Informations Personnelles
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div><span class="text-gray-500">Nom Complet :</span> <span class="font-medium text-gray-900">{{ $profile->first_names }} {{ $profile->last_name }}</span></div>
                <div><span class="text-gray-500">Email :</span> <span class="font-medium text-gray-900">{{ $profile->user->email }}</span></div>
                <div><span class="text-gray-500">Téléphone :</span> <span class="font-medium text-gray-900">{{ $profile->phone }}</span></div>
                <div><span class="text-gray-500">Date de Naissance :</span> <span class="font-medium text-gray-900">{{ $profile->birth_date->format('d/m/Y') }}</span></div>
            </div>
        </div>

    </div>

    <!-- Boîte de Réception (1 colonne) -->
    <div class="flex flex-col gap-4">
        <h2 class="text-xl font-headline font-bold text-gray-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-primary">mail</span> 
            Boîte de Réception
        </h2>
        
        <div class="flex flex-col gap-4">
            @forelse($messages as $message)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                {{ substr($message->intervenant->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $message->intervenant->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wide">Formateur</div>
                            </div>
                        </div>
                        <div class="text-[11px] font-medium text-gray-400">{{ $message->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <h4 class="font-bold text-gray-800 text-sm mt-3 mb-1">{{ $message->subject }}</h4>
                    <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ $message->body }}</p>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm text-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                        <span class="material-symbols-outlined">inbox</span>
                    </div>
                    <p class="text-sm text-gray-500">Vous n'avez reçu aucun message pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
