@extends('layouts.admin')

@section('header_title', 'Messages & Annonces Pédagogiques')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Messages & Annonces</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Gérez les annonces pédagogiques envoyées aux étudiants, formateurs et départements.</p>
                </div>
            </div>
            <a href="{{ route('admin.messages.create') }}" class="bg-[#4B6A95] hover:bg-[#3c5678] text-white px-5 py-2.5 rounded-full text-xs font-bold transition inline-flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Nouvelle Annonce
            </a>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 overflow-visible">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Date</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Sujet</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Destinataires (Année Académique)</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider">Auteur</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($messages as $message)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-xs text-slate-500 whitespace-nowrap font-semibold">
                                {{ $message->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-6 text-sm font-bold text-slate-900">
                                {{ $message->subject }}
                            </td>
                            <td class="py-3.5 px-6 text-xs text-slate-600">
                                @if($message->cohort_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-[#4B6A95] border border-blue-200">
                                        {{ $message->cohort->title }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        Tous les étudiants & formateurs
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-xs font-semibold text-slate-600">
                                {{ $message->user ? $message->user->name : 'Administration CFPT' }}
                            </td>
                            <td class="py-3.5 px-6 text-sm text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick="openMessageModal({{ $message->id }})" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#4B6A95] text-slate-700 hover:text-white text-xs font-bold transition inline-flex items-center gap-1 cursor-pointer">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        Lire
                                    </button>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Supprimer ce message ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white transition cursor-pointer">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal for viewing message content -->
                        <div id="messageModal-{{ $message->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" onclick="closeMessageModal({{ $message->id }})"></div>
                                <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 z-10">
                                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">{{ $message->subject }}</h3>
                                    <div class="mb-4 text-xs text-slate-500 flex flex-wrap gap-4 border-b border-slate-100 pb-3">
                                        <span><strong>De :</strong> {{ $message->user ? $message->user->name : 'Administration CFPT' }}</span>
                                        <span><strong>Date :</strong> {{ $message->created_at->format('d/m/Y H:i') }}</span>
                                        <span><strong>Destinataires :</strong> {{ $message->cohort_id ? $message->cohort->title : 'Tous les étudiants & formateurs' }}</span>
                                    </div>
                                    <div class="text-sm text-slate-700 whitespace-pre-wrap bg-slate-50 p-4 rounded-2xl border border-slate-200/80 min-h-[100px]">{{ $message->body }}</div>
                                    <div class="mt-5 flex justify-end">
                                        <button type="button" onclick="closeMessageModal({{ $message->id }})" class="px-5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold cursor-pointer">
                                            Fermer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 bg-slate-50/50 text-xs">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">campaign</span>
                                Aucune annonce pédagogique n'a encore été envoyée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openMessageModal(id) {
        document.getElementById('messageModal-' + id).classList.remove('hidden');
    }
    function closeMessageModal(id) {
        document.getElementById('messageModal-' + id).classList.add('hidden');
    }
</script>
@endsection
