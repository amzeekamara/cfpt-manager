@extends('layouts.admin')

@section('header_title', 'Gestion des Départements & Salles')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    classroomModalOpen: false,
    editDeptModalOpen: false,
    selectedDeptId: '{{ $departments->first()?->id ?? '' }}',
    selectedDeptName: '',
    editDept: { id: '', name: '', head_user_id: '', description: '' }
}">
    <div class="w-full max-w-[1600px] mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Départements & Salles de Cours</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                        Gérez les départements du CFPT Sénégal-Japon, désignez chaque Chef de Département et configurez les salles disponibles.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" @click="classroomModalOpen = true" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-[#4B6A95] border border-slate-200 rounded-full text-xs font-bold flex items-center gap-2 shadow-2xs transition cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                    + Ajouter une Salle / Atelier
                </button>
            </div>
        </div>

        @if(!auth()->user()->isDepartmentHead())
        <!-- Add Department Form (Directeur des Études / Admin) -->
        <div class="bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm mb-8">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#4B6A95] text-[18px]">domain_add</span>
                Nouveau Département Pédagogique
            </h2>
            <form action="{{ route('admin.departments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Nom du département <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Ex: Génie Informatique, Électrotechnique, Automobile..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Chef de Département (Administrateur)</label>
                    <select name="head_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        <option value="">-- Sélectionner un Chef de Département --</option>
                        @foreach($eligibleHeads as $head)
                            <option value="{{ $head->id }}">{{ $head->name }} ({{ $head->role?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Description / Spécialités</label>
                    <input type="text" name="description" placeholder="Filières BTS / BT rattachées..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                </div>
                <div>
                    <button type="submit" class="w-full px-6 py-2.5 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold transition shadow-sm cursor-pointer">
                        Créer le département
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Departments Cards & Classrooms List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($departments as $department)
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <!-- Top Dept Info -->
                    <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#4B6A95]">
                                    Département
                                </span>
                                @if($department->is_active)
                                    <span class="text-[11px] font-bold text-emerald-600">● Actif</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mt-1">{{ $department->name }}</h3>
                            @if($department->description)
                                <p class="text-xs text-slate-500 mt-0.5">{{ $department->description }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5">
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
                                    class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition cursor-pointer"
                                    title="Modifier le département / Chef de département">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
                            @if(!auth()->user()->isDepartmentHead())
                            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Supprimer ce département et ses salles ?');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer" title="Supprimer">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Chef de Département Info -->
                    <div class="my-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#4B6A95] text-white font-bold text-xs flex items-center justify-center">
                                {{ $department->head ? strtoupper(substr($department->head->name, 0, 2)) : 'CD' }}
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Chef de Département</span>
                                @if($department->head)
                                    <span class="text-xs font-extrabold text-slate-900">{{ $department->head->name }}</span>
                                    <span class="text-[11px] text-slate-500 block">{{ $department->head->email }}</span>
                                @else
                                    <span class="text-xs font-semibold text-amber-600 italic">Non assigné</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Filières / Cours</span>
                            <span class="text-sm font-black text-slate-800">{{ $department->seminars_count }}</span>
                        </div>
                    </div>

                    <!-- Classrooms / Salles disponibles dans ce département -->
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-[#4B6A95]">meeting_room</span>
                                Salles & Ateliers disponibles ({{ $department->classrooms->count() }})
                            </h4>
                            <button type="button"
                                    @click="selectedDeptId = '{{ $department->id }}'; selectedDeptName = '{{ addslashes($department->name) }}'; classroomModalOpen = true;"
                                    class="text-xs font-bold text-[#4B6A95] hover:underline flex items-center gap-1 cursor-pointer">
                                <span>+ Ajouter une salle</span>
                            </button>
                        </div>

                        @if($department->classrooms->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto pr-1">
                                @foreach($department->classrooms as $room)
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white flex items-center justify-between group hover:border-[#4B6A95] transition">
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-xs text-slate-900">{{ $room->name }}</span>
                                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md {{ $room->type === 'atelier' ? 'bg-amber-50 text-amber-700' : ($room->type === 'labo' ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700') }}">
                                                    {{ ucfirst($room->type) }}
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-slate-500 font-medium">Capacité : <strong class="text-slate-800">{{ $room->capacity }} étudiants</strong></span>
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
                            <div class="p-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400">
                                Aucune salle configurée pour ce département. Ajoutez des salles pour planifier les emplois du temps.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 bg-white rounded-[24px] p-12 text-center border border-slate-200">
                <p class="text-slate-500 text-sm font-semibold">Aucun département enregistré.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Ajouter une Salle / Atelier -->
    <div x-show="classroomModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="classroomModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Ajouter une Salle / Atelier</h3>
                <button type="button" @click="classroomModalOpen = false" class="text-slate-400 hover:text-slate-700">
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
                    <input type="text" name="name" required placeholder="Ex: Salle B204, Atelier Mécatronique 1..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
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
                    <button type="button" @click="classroomModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold">Enregistrer la salle</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Modifier un Département -->
    <div x-show="editDeptModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="editDeptModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-extrabold text-slate-900">Modifier le Département</h3>
                <button type="button" @click="editDeptModalOpen = false" class="text-slate-400 hover:text-slate-700">
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
                    <label class="block font-bold text-slate-700 mb-1">Chef de Département</label>
                    <select name="head_user_id" x-model="editDept.head_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                        <option value="">-- Aucun --</option>
                        @foreach($eligibleHeads as $head)
                            <option value="{{ $head->id }}">{{ $head->name }} ({{ $head->role?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Description</label>
                    <input type="text" name="description" x-model="editDept.description" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-[#4B6A95]">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editDeptModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4B6A95] text-white font-bold">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
