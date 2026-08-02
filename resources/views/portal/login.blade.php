@extends('portal.layout')
@section('title', $title ?? 'Connexion')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-brand-light/30 rounded-full flex items-center justify-center mx-auto mb-4 text-brand-primary">
                <span class="material-symbols-outlined text-3xl">login</span>
            </div>
            <h1 class="text-2xl font-headline font-bold text-gray-900">{{ $title ?? 'Connexion au Portail' }}</h1>
            <p class="text-gray-500 text-sm mt-2">Veuillez saisir vos informations pour continuer</p>
        </div>

        <form method="POST" action="{{ route('portal.login') }}" class="flex flex-col gap-5">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Identifiant (Email)</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                    <input type="email" name="email" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Date de naissance</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">calendar_today</span>
                    <input type="date" name="birth_date" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all text-gray-700">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Lieu de naissance</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                    <input type="text" name="birth_place" required placeholder="Ex: Paris" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all text-gray-700">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-brand-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg shadow-brand-primary/30 mt-2">
                Se connecter
            </button>
        </form>
    </div>
</div>
@endsection
