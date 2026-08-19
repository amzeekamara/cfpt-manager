@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.messages.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour aux messages
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Alerte Globale</h1>
            <p class="text-sm text-gray-600 mt-1">Envoyez une annonce ou alerte aux candidats.</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
    <form action="{{ route('admin.messages.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        
        <!-- Filtres Avancés -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 border-b pb-2">Critères de Ciblage (Laissez vide pour envoyer à tous)</h3>
            </div>
            
            <!-- Rôles -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Types d'utilisateurs (Rôles)</label>
                <div class="space-y-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="target_roles[]" value="{{ $role->id }}" 
                                class="rounded border-gray-300 text-primary focus:ring-blue-500"
                                {{ is_array(old('target_roles')) && in_array($role->id, old('target_roles')) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('target_roles') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4">
                <!-- Séminaires -->
                <div>
                    <x-multi-select-dropdown 
                        name="target_seminars[]" 
                        label="Séminaires spécifiques" 
                        :options="$seminars" 
                        :selected="old('target_seminars', [])" 
                        placeholder="Tous les séminaires ou sélectionner..." />
                </div>

                <!-- Thèmes (Départements) -->
                <div>
                    <x-multi-select-dropdown 
                        name="target_departments[]" 
                        label="Thèmes (Départements)" 
                        :options="$departments" 
                        :selected="old('target_departments', [])" 
                        placeholder="Tous les départements ou sélectionner..." />
                </div>
            </div>
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet de l'alerte <span class="text-red-500">*</span></label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors" placeholder="Ex: Changement de salle pour le prochain séminaire">
            @error('subject')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Contenu du message <span class="text-red-500">*</span></label>
            <textarea name="body" id="body" rows="6" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors" placeholder="Votre message détaillé ici...">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.messages.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg shadow-sm hover:brightness-90 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Envoyer l'alerte
            </button>
        </div>
    </form>
</div>
@endsection
