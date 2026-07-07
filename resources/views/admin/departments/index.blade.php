@extends('layouts.admin')

@section('header_title', 'Gestion des Départements')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Départements</h1>
    </div>

    <!-- Add Form -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm mb-8">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1 space-y-2">
                <label for="name" class="text-sm font-bold text-on-surface-variant">Nouveau département</label>
                <input type="text" name="name" required placeholder="Ex: Informatique, Électronique..." class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3">
            </div>
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-on-primary font-bold hover:shadow-lg transition-all">Ajouter</button>
        </form>
    </div>

    <!-- List -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant font-bold border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4">Nom du département</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                @foreach($departments as $department)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-on-surface">{{ $department->name }}</td>
                    <td class="px-6 py-4">
                        @if($department->is_active)
                            <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-bold">Actif</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Supprimer ce département ?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-bold px-2 py-1">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
