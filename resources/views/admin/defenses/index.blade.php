@extends('layouts.admin')

@section('header_title', 'Suivi des Soutenances')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Soutenances</h1>
        <p class="text-on-surface-variant font-body">Planifiez et évaluez les défenses des porteurs de projet.</p>
    </div>

    <!-- Planifier Form -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm mb-8">
        <h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">event</span> Planifier une nouvelle soutenance
        </h2>
        <form action="{{ route('admin.defenses.store') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1 space-y-2">
                <label for="enrollment_id" class="text-sm font-bold text-on-surface-variant">Candidat (Inscriptions validées)</label>
                <select name="enrollment_id" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3">
                    <option value="">Sélectionner un candidat...</option>
                    @foreach($enrollments as $enrollment)
                        <option value="{{ $enrollment->id }}">{{ $enrollment->user->name }} - {{ $enrollment->seminar->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label for="defense_date" class="text-sm font-bold text-on-surface-variant">Date et Heure</label>
                <input type="datetime-local" name="defense_date" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3">
            </div>
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-on-primary font-bold hover:shadow-lg transition-all">Planifier</button>
        </form>
    </div>

    <!-- Defenses List -->
    <div class="grid gap-6">
        @foreach($defenses as $defense)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
            
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-surface-variant text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                        {{ $defense->enrollment->seminar->department_name }}
                    </span>
                    @if($defense->status == 'scheduled')
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Planifiée</span>
                    @elseif($defense->status == 'passed')
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Validée</span>
                    @else
                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Échouée</span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-1">{{ $defense->enrollment->user->name }}</h3>
                <p class="text-sm text-on-surface-variant">Projet: <span class="font-semibold">{{ $defense->enrollment->project_title ?? 'Non défini' }}</span></p>
                <div class="mt-4 flex items-center gap-4 text-sm font-medium text-on-surface-variant">
                    <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">calendar_month</span> {{ $defense->defense_date->format('d/m/Y H:i') }}</div>
                    @if($defense->grade)
                        <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">grade</span> Note: {{ $defense->grade }}/20</div>
                    @endif
                </div>
            </div>

            <!-- Edit Evaluation Form -->
            <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant w-full md:w-auto">
                <form action="{{ route('admin.defenses.update', $defense->id) }}" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <input type="hidden" name="defense_date" value="{{ $defense->defense_date }}">
                    
                    <div class="flex gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-on-surface-variant">Note (/20)</label>
                            <input type="number" step="0.5" name="grade" value="{{ $defense->grade }}" class="w-20 bg-white border border-outline-variant rounded p-1.5 text-sm">
                        </div>
                        <div class="space-y-1 flex-1">
                            <label class="text-xs font-bold text-on-surface-variant">Statut</label>
                            <select name="status" class="w-full bg-white border border-outline-variant rounded p-1.5 text-sm">
                                <option value="scheduled" {{ $defense->status == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                                <option value="passed" {{ $defense->status == 'passed' ? 'selected' : '' }}>Validée</option>
                                <option value="failed" {{ $defense->status == 'failed' ? 'selected' : '' }}>Échouée</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-on-surface-variant">Commentaires du Jury</label>
                        <textarea name="jury_comments" rows="2" class="w-full bg-white border border-outline-variant rounded p-1.5 text-sm">{{ $defense->jury_comments }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded text-xs font-bold hover:bg-blue-700">Enregistrer</button>
                    </div>
                </form>
            </div>

            <form action="{{ route('admin.defenses.destroy', $defense->id) }}" method="POST" onsubmit="return confirm('Supprimer cette soutenance ?');">
                @csrf @method('DELETE')
                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Supprimer">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </form>

        </div>
        @endforeach

        @if($defenses->isEmpty())
            <div class="text-center p-8 bg-surface-container-lowest rounded-2xl border border-outline-variant text-on-surface-variant">
                Aucune soutenance n'a été planifiée.
            </div>
        @endif
    </div>
</div>
@endsection
