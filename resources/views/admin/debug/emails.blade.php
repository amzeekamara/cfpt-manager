@extends('layouts.admin')

@section('title', 'Débugger d\'Emails')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Débugger d'Emails</h1>
            <p class="text-gray-600">Visualisation des emails envoyés (via le driver LOG)</p>
        </div>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="material-symbols-outlined text-blue-400">info</span>
            </div>
            <div class="ml-3">
                <p class="text-sm text-primary">
                    Cette page permet de voir les emails générés par la plateforme sans avoir besoin d'un serveur SMTP réel. 
                    Idéal pour tester le contenu et le design des notifications.
                </p>
            </div>
        </div>
    </div>

    @if(empty($emails))
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">mail_outline</span>
            <p class="text-gray-500">Aucun email trouvé dans les logs récents.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($emails as $index => $email)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center cursor-pointer" onclick="document.getElementById('email-{{ $index }}').classList.toggle('hidden')">
                        <div>
                            <span class="font-bold text-gray-800">{{ $email['subject'] }}</span>
                            <span class="mx-2 text-gray-400">|</span>
                            <span class="text-sm text-gray-600">Destinataire : <span class="font-medium">{{ $email['to'] }}</span></span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">expand_more</span>
                    </div>
                    <div id="email-{{ $index }}" class="p-6 hidden">
                        <div class="prose max-w-none bg-gray-50 p-4 rounded-md border border-gray-200 overflow-auto font-mono text-xs whitespace-pre-wrap">
                            {{ $email['body'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
