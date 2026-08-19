@extends('layouts.admin')

@section('header_title', 'Gestion des Départements')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Départements</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Gérez les départements et filières de l'institution.</p>
            </div>
        </div>

    <!-- Add Form -->
    <div class="bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm mb-8">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
            @csrf
            <div class="flex-1 w-full space-y-1.5">
                <label for="name" class="text-xs font-bold text-slate-700">Nouveau département</label>
                <input type="text" name="name" required placeholder="Ex: Informatique, Électronique..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold transition shadow-sm">Ajouter</button>
        </form>
    </div>

    <!-- List -->
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50/80 text-slate-600 font-bold text-xs uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3.5">Nom du département</th>
                    <th class="px-6 py-3.5">Statut</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($departments as $department)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $department->name }}</td>
                    <td class="px-6 py-4 text-xs font-bold">
                        @if($department->is_active)
                            <span class="text-emerald-600">Actif</span>
                        @else
                            <span class="text-slate-400">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                            <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-40 bg-white border border-slate-100 shadow-lg rounded-md py-1 z-50">
                                <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Supprimer ce département ?');" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
