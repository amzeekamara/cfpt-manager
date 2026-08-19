@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Messages & Alertes</h1>
        <p class="text-sm text-gray-600 mt-1">Gérez les annonces envoyées aux candidats.</p>
    </div>
    <a href="{{ route('admin.messages.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:brightness-90 transition-colors inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nouvelle Alerte
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible">
    <div class="overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Sujet</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Cible (Cohorte)</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Auteur</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($messages as $message)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-6 text-sm text-gray-500 whitespace-nowrap">
                        {{ $message->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="py-3 px-6 text-sm font-medium text-gray-900">
                        {{ $message->subject }}
                    </td>
                    <td class="py-3 px-6 text-sm text-gray-500">
                        @if($message->cohort_id)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $message->cohort->title }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Tous les candidats
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-sm text-gray-500">
                        {{ $message->user ? $message->user->name : 'Système' }}
                    </td>
                    <td class="py-3 px-6 text-sm text-right">
                        <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                            <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-40 bg-white border border-slate-100 shadow-lg rounded-md py-1 z-50">
                                <button type="button" onclick="openMessageModal({{ $message->id }})" class="w-full text-left px-4 py-2 text-sm text-primary hover:bg-blue-50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    Voir
                                </button>
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="m-0 border-t border-slate-100" onsubmit="return confirm('Supprimer ce message ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <!-- Modal for viewing message content -->
                <div id="messageModal-{{ $message->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMessageModal({{ $message->id }})"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-xl leading-6 font-medium text-gray-900 mb-2" id="modal-title">
                                            {{ $message->subject }}
                                        </h3>
                                        <div class="mb-4 text-sm text-gray-500 flex gap-4 border-b pb-3">
                                            <span><strong>De :</strong> {{ $message->user ? $message->user->name : 'Système' }}</span>
                                            <span><strong>Date :</strong> {{ $message->created_at->format('d/m/Y H:i') }}</span>
                                            <span><strong>Cible :</strong> {{ $message->cohort_id ? $message->cohort->title : 'Tous les candidats' }}</span>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-100 min-h-[100px]">
                                            {{ $message->body }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="closeMessageModal({{ $message->id }})" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500 bg-gray-50">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Aucun message n'a encore été envoyé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $messages->links() }}
        </div>
    @endif
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
