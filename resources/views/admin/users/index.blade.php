@extends('layouts.admin')

@section('header_title', 'Gestion des Profils')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Gestion des Comptes Utilisateurs</h1>
            <p class="text-on-secondary-container font-body body-md">Administrez les accès et rôles de l'institution.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-blue-900/10" style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                <span class="material-symbols-outlined">person_add</span>
                + Ajouter un Utilisateur
            </a>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_20px_40px_rgba(30,41,59,0.06)] overflow-hidden">
        <div class="p-6 bg-surface-container-low/30 border-b border-outline-variant/10 flex items-center justify-between">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 transition-all border-b-2 border-transparent focus:border-primary" placeholder="Rechercher par nom ou email..." type="text"/>
            </div>
            <div class="flex items-center gap-2 text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-widest">{{ $users->total() }} Utilisateurs trouvés</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="px-8 py-4 text-[10px] uppercase tracking-[0.1em] font-bold text-on-secondary-container">Nom complet</th>
                        <th class="px-8 py-4 text-[10px] uppercase tracking-[0.1em] font-bold text-on-secondary-container">Email</th>
                        <th class="px-8 py-4 text-[10px] uppercase tracking-[0.1em] font-bold text-on-secondary-container">Rôle</th>
                        <th class="px-8 py-4 text-[10px] uppercase tracking-[0.1em] font-bold text-on-secondary-container">Date de Création</th>
                        <th class="px-8 py-4 text-[10px] uppercase tracking-[0.1em] font-bold text-on-secondary-container text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    @foreach($users as $user)
                    <tr class="group hover:bg-surface-container-high transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span class="font-semibold text-on-surface">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-on-secondary-container body-md">{{ $user->email }}</td>
                        <td class="px-8 py-6">
                            @if($user->role && $user->role->name == 'Admin')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-[10px] font-extrabold uppercase rounded-full tracking-wider border border-blue-200">Admin</span>
                            @elseif($user->role && $user->role->name == 'Formateur')
                                <span class="px-3 py-1 bg-teal-100 text-teal-800 text-[10px] font-extrabold uppercase rounded-full tracking-wider border border-teal-200">Formateur</span>
                            @else
                                <span class="px-3 py-1 bg-slate-200 text-slate-700 text-[10px] font-extrabold uppercase rounded-full tracking-wider border border-slate-300">{{ $user->role ? $user->role->name : 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-on-secondary-container body-md">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                <button class="p-2 hover:bg-white rounded-lg text-primary transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button class="p-2 hover:bg-red-50 rounded-lg text-error transition-all">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Custom Tailwind Pagination links -->
        <div class="px-8 py-6 bg-surface-container-low/20 border-t border-outline-variant/10">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
