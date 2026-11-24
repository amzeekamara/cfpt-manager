@extends('layouts.admin')

@section('header_title', 'Les 3 Départements du CFPT & Salles')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    classroomModalOpen: false,
    editDeptModalOpen: false,
    selectedDeptId: '{{ $departments->first()?->id ?? '' }}',
    selectedDeptName: '',
    editDept: { id: '', name: '', head_user_id: '', description: '' }
}">
    <div class="w-full max-w-[1600px] mx-auto space-y-6">

        <!-- Header & Filtre Année Académique (Filières variables selon l'année) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-[26px] border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-[#4B6A95] text-[10px] font-black uppercase tracking-wider mb-1">
                        Structure Officielle • CFPT Sénégal-Japon
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Les 3 Départements, Filières & Salles</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                        Le CFPT Sénégal-Japon s'articule autour de <strong>3 départements</strong> (Informatique, Mécanique, Électrique) dont les filières ouvertes évoluent chaque année académique.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Sélecteur d'Année Académique pour voir les filières de l'année -->
                <form action="{{ route('admin.departments.index') }}" method="GET" id="deptCohortForm" class="m-0 flex items-center gap-2 bg-slate-50 px-3.5 py-2 rounded-full border border-slate-200">
                    <span class="material-symbols-outlined text-[18px] text-[#4B6A95]">calendar_month</span>
                    <span class="text-xs font-bold text-slate-500">Année :</span>
                    <select name="cohort_id" onchange="document.getElementById('deptCohortForm').submit()" class="bg-transparent text-xs font-black text-slate-900 outline-none cursor-pointer">
                        <option value="">Toutes les années</option>
                        @foreach($cohorts as $cohort)
                            <option value="{{ $cohort->id }}" {{ $selectedCohortId == $cohort->id ? 'selected' : '' }}>
                                {{ $cohort->title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button type="button" @click="classroomModalOpen = true" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-[#4B6A95] border border-slate-200 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs transition cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                    <span>+ Ajouter une Salle</span>
                </button>

                <a href="{{ route('admin.seminars.create', ['cohort_id' => $selectedCohortId]) }}" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>+ Ouvrir une Filière</span>
                </a>
            </div>
        </div>

        <!-- Grille des 3 Départements Officiels -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($departments as $index => $department)
            @php
                $deptTheme = match($index) {
                    0 => ['gradient' => 'from-[#162536] to-[#305076]', 'icon' => 'computer', 'badge' => 'bg-blue-50 text-[#4B6A95]'],
                    1 => ['gradient' => 'from-[#1f2937] to-[#374151]', 'icon' => 'precision_manufacturing', 'badge' => 'bg-amber-50 text-amber-700'],
                    default => ['gradient' => 'from-[#1e3a5f] to-[#2d6197]', 'icon' => 'bolt', 'badge' => 'bg-emerald-50 text-emerald-700'],
                };
            @endphp
            <div class="bg-white rounded-[28px] border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- En-tête du Département -->
                    <div class="p-6 bg-gradient-to-br {{ $deptTheme['gradient'] }} text-white relative">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-xs flex items-center justify-center border border-white/20 shrink-0">
                                    <span class="material-symbols-outlined text-2xl text-white">{{ $deptTheme['icon'] }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-200 block">Département Officiel CFPT</span>
                                    <h3 class="text-xl font-black leading-tight mt-0.5">{{ $department->name }}</h3>
                                </div>
                            </div>

                            <button type="button"
                                    @click="
                                        editDept = {
                                            id: '{{ $department->id }}',
                                            name: '{{ addslashes($department->name) }}',
                                            head_user_id: '{{ $department->head_user_id ?? '' }}',
                                            description: '{{ addslashes($department->description ?? '') }}'
                                        };
                                        editDeptModalOpen = true;
                                    "
                                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition cursor-pointer"
                                    title="Assigner le Chef de Département / Modifier">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
                        </div>

                        @if($department->description)
                            <p class="text-xs text-blue-100/85 mt-3 leading-relaxed">{{ $department->description }}</p>
                        @endif
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Chef de Département Card -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#4B6A95] text-white font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $department->head ? strtoupper(substr($department->head->name, 0, 2)) : 'CD' }}
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Chef de Département</span>
                                    @if($department->head)
                                        <span class="text-xs font-extrabold text-slate-900">{{ $department->head->name }}</span>
                                        <span class="text-[11px] text-slate-500 block">{{ $department->head->email }}</span>
                                    @else
                                        <span class="text-xs font-semibold text-amber-600 italic">Non assigné</span>
                                    @endif
                                </div>
                            </div>
                            <button type="button"
                                    @click="
                                        editDept = {
                                            id: '{{ $department->id }}',
                                            name: '{{ addslashes($department->name) }}',
                                            head_user_id: '{{ $department->head_user_id ?? '' }}',
                                            description: '{{ addslashes($department->description ?? '') }}'
                                        };
                                        editDeptModalOpen = true;
                                    "
                                    class="text-[11px] font-bold text-[#4B6A95] hover:underline cursor-pointer">
                                Changer
                            </button>
                        </div>

                        <!-- Section 1 : Filières ouvertes dans ce département (Variables par année) -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px] text-[#4B6A95]">school</span>
                                        <span>Filières ouvertes ({{ $department->seminars->count() }})</span>
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium">Peuvent varier selon l'année académique</span>
                                </div>
                                <a href="{{ route('admin.seminars.create', ['department_id' => $department->id, 'cohort_id' => $selectedCohortId]) }}"
                                   class="px-3 py-1 rounded-full bg-blue-50 hover:bg-[#4B6A95] text-[#4B6A95] hover:text-white text-[11px] font-bold transition">
                                    + Nouvelle filière
                                </a>
                            </div>

                            @if($department->seminars->count() > 0)
                                <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                                    @foreach($department->seminars as $filiere)
                                        <div class="p-3 rounded-xl border border-slate-200/80 bg-white hover:border-[#4B6A95] transition flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.seminars.edit', $filiere->id) }}" class="text-xs font-extrabold text-slate-900 hover:text-[#4B6A95] block truncate">
                                                    {{ $filiere->title }}
                                                </a>
                                                <div class="flex items-center gap-2 mt-0.5 text-[10px] text-slate-500">
                                                    <span class="font-bold text-[#4B6A95]">{{ $filiere->cohort?->title ?? 'Année en cours' }}</span>
                                                    <span>•</span>
                                                    <span><strong>{{ $filiere->enrolled_count ?? 0 }}</strong> / {{ $filiere->max_participants }} étudiants</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.timetables.index', ['department_id' => $department->id, 'seminar_id' => $filiere->id]) }}"
                                               class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-[#4B6A95] text-slate-700 hover:text-white text-[10px] font-bold transition shrink-0"
                                               title="Voir l'emploi du temps de cette filière">
                                                Planning
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400">
                                    Aucune filière ouverte dans ce département pour l'année sélectionnée.
                                </div>
                            @endif
                        </div>

                        <!-- Section 2 : Salles, Ateliers & Laboratoires disponibles dans ce département -->
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">meeting_room</span>
                                    <span>Salles & Ateliers ({{ $department->classrooms->count() }})</span>
                                </h4>
                                <button type="button"
                                        @click="selectedDeptId = '{{ $department->id }}'; selectedDeptName = '{{ addslashes($department->name) }}'; classroomModalOpen = true;"
                                        class="text-[11px] font-bold text-[#4B6A95] hover:underline cursor-pointer">
                                    + Ajouter une salle
                                </button>
                            </div>

                            @if($department->classrooms->count() > 0)
                                <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto pr-1">
                                    @foreach($department->classrooms as $room)
                                        <div class="px-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50/70 flex items-center justify-between group hover:bg-white hover:border-[#4B6A95] transition">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-xs text-slate-900">{{ $room->name }}</span>
                                                    <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md {{ $room->type === 'atelier' ? 'bg-amber-100 text-amber-800' : ($room->type === 'labo' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800') }}">
                                                        {{ ucfirst($room->type) }}
                                                    </span>
                                                </div>
                                                <span class="text-[10px] text-slate-500 font-medium">Capacité d'accueil : <strong class="text-slate-800">{{ $room->capacity }} étudiants</strong></span>
                                            </div>
                                            <form action="{{ route('admin.classrooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Supprimer cette salle ?');" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-rose-600 p-1 transition cursor-pointer" title="Supprimer la salle">
                                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400">
                                    Aucune salle configurée.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal: Ajouter une Salle / Atelier -->
    <div x-show="classroomModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="classroomModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Ajouter une Salle / Atelier au Département</h3>
                <button type="button" @click="classroomModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('admin.classrooms.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Département rattaché <span class="text-rose-500">*</span></label>
                    <select name="department_id" x-model="selectedDeptId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom / Numéro de la Salle <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Ex: Salle Info B103, Atelier Moteur C102..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Capacité (places) <span class="text-rose-500">*</span></label>
                        <input type="number" name="capacity" required min="1" max="500" value="30" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Type de salle <span class="text-rose-500">*</span></label>
                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                            <option value="cours">Salle de Cours</option>
                            <option value="atelier">Atelier Pratique</option>
                            <option value="labo">Laboratoire</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="classroomModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold cursor-pointer">Enregistrer la salle</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Modifier un Département / Chef de Département -->
    <div x-show="editDeptModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="editDeptModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Paramètres du Département</h3>
                <button type="button" @click="editDeptModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="'{{ url('/admin/departments') }}/' + editDept.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom du département <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editDept.name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Chef de Département (Administrateur du département)</label>
                    <select name="head_user_id" x-model="editDept.head_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                        <option value="">-- Aucun --</option>
                        @foreach($eligibleHeads as $head)
                            <option value="{{ $head->id }}">{{ $head->name }} ({{ $head->role?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Description / Périmètre</label>
                    <input type="text" name="description" x-model="editDept.description" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editDeptModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold cursor-pointer">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
