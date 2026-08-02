@extends('portal.layout')
@section('title', 'Espace Formateur')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-headline font-bold text-gray-900">Bienvenue, {{ $trainer->name }}</h1>
        <p class="text-gray-500 mt-1">Gérez vos communications et séminaires.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Colonne Gauche: Profil & Envoi de Message -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        
        <!-- Info Séminaire Principal -->
        <div class="bg-brand-primary rounded-3xl p-6 text-white shadow-lg shadow-brand-primary/20 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="text-brand-light font-bold text-xs uppercase tracking-wider mb-2">Séminaire Assigné</div>
            <h2 class="text-xl font-headline font-bold mb-4">{{ $trainer->seminar->title ?? 'Aucun séminaire' }}</h2>
            
            <div class="flex items-center gap-2 text-sm text-brand-light">
                <span class="material-symbols-outlined text-[16px]">mail</span>
                {{ $trainer->email ?? 'Non renseigné' }}
            </div>
            <div class="flex items-center gap-2 text-sm text-brand-light mt-1">
                <span class="material-symbols-outlined text-[16px]">call</span>
                {{ $trainer->phone ?? 'Non renseigné' }}
            </div>
        </div>

        <!-- Formulaire d'envoi de message -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-headline font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-primary">send</span>
                Nouveau Message
            </h3>
            
            <form action="{{ route('portal.trainer.messages.send') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-2">
                    <p class="text-xs text-gray-500 mb-3 uppercase font-bold tracking-wider">Filtres de Ciblage (Optionnel)</p>
                    
                    <div class="flex flex-col gap-3">
                        <div>
                            <select name="edition_id" class="w-full text-sm py-2 px-3 bg-white border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary outline-none">
                                <option value="">Toutes les Éditions</option>
                                @foreach($editions as $edition)
                                    <option value="{{ $edition->id }}">{{ $edition->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="department_id" class="w-full text-sm py-2 px-3 bg-white border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary outline-none">
                                <option value="">Toutes les Filières</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Sujet</label>
                    <input type="text" name="subject" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary outline-none text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                    <textarea name="body" required rows="5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary outline-none text-sm resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-brand-primary text-white rounded-xl font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span> Envoyer
                </button>
            </form>
        </div>
    </div>

    <!-- Colonne Droite: Historique des Messages -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm h-full">
            <h2 class="text-xl font-headline font-bold text-gray-900 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-primary">history</span>
                Historique des envois
            </h2>
            
            <div class="flex flex-col gap-4">
                @forelse($messages as $msg)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-gray-900 text-sm">{{ $msg->subject }}</h4>
                            <span class="text-[11px] font-medium text-gray-400 bg-white px-2 py-1 rounded-md border border-gray-100">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if($msg->edition)
                                <span class="text-[10px] font-bold text-brand-primary bg-brand-light/30 px-2 py-1 rounded-md">Édition: {{ $msg->edition->title }}</span>
                            @endif
                            @if($msg->department)
                                <span class="text-[10px] font-bold text-brand-primary bg-brand-light/30 px-2 py-1 rounded-md">Filière: {{ $msg->department->name }}</span>
                            @endif
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-200 px-2 py-1 rounded-md">Séminaire: {{ $msg->seminar->title ?? 'N/A' }}</span>
                        </div>

                        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ $msg->body }}</p>
                    </div>
                @empty
                    <div class="py-10 text-center text-gray-500">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-gray-400">speaker_notes_off</span>
                        </div>
                        <p class="text-sm">Vous n'avez envoyé aucun message pour l'instant.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
