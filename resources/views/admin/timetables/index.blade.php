@extends('layouts.admin')

@section('header_title', 'Emplois du Temps & Salles')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
    addModalOpen: false,
    viewMode: 'grid',
    selectedDepartmentId: '{{ $effectiveDeptId ?: ($departments->first()?->id ?? '') }}',
    selectedSeminarId: '{{ $seminars->first()?->id ?? '' }}',
    selectedDay: 'Lundi',
    startTime: '08:00',
    endTime: '12:00',
    allSlots: @js($allSlots ?? []),
    trainersList: [
        @foreach($trainers as $tr)
            { id: '{{ $tr->id }}', name: '{{ addslashes($tr->name) }}', email: '{{ addslashes($tr->email) }}' },
        @endforeach
    ],
    seminarsMap: {
        @foreach($seminars as $sem)
            '{{ $sem->id }}': {
                enrolled: {{ $sem->enrolled_count ?? 0 }},
                topics: [
                    @foreach($sem->topics as $top)
                        { id: '{{ $top->id }}', name: '{{ addslashes($top->name) }}' },
                    @endforeach
                ]
            },
        @endforeach
    },
    classroomsByDept: {
        @foreach($departments as $dept)
            '{{ $dept->id }}': [
                @foreach($dept->classrooms as $room)
                    { id: '{{ $room->id }}', name: '{{ addslashes($room->name) }}', capacity: {{ $room->capacity }}, type: '{{ $room->type }}' },
                @endforeach
            ],
        @endforeach
    },
    get currentTopics() {
        return this.seminarsMap[this.selectedSeminarId]?.topics || [];
    },
    get currentEnrolled() {
        return this.seminarsMap[this.selectedSeminarId]?.enrolled || 0;
    },
    get availableClassrooms() {
        return this.classroomsByDept[this.selectedDepartmentId] || [];
    },
    get calculatedHours() {
        if (!this.startTime || !this.endTime) return 0;
        const [sh, sm] = this.startTime.split(':').map(Number);
        const [eh, em] = this.endTime.split(':').map(Number);
        const diff = ((eh * 60 + em) - (sh * 60 + sm)) / 60;
        return diff > 0 ? diff.toFixed(1) : 0;
    },
    overlaps(slot) {
        if (slot.day_of_week !== this.selectedDay) return false;
        if (!this.startTime || !this.endTime) return false;
        return slot.start_time < this.endTime && slot.end_time > this.startTime;
    },
    getRoomConflict(roomId) {
        return this.allSlots.find(s => String(s.classroom_id) === String(roomId) && this.overlaps(s)) || null;
    },
    getTrainerConflict(trainerId) {
        return this.allSlots.find(s => String(s.trainer_id) === String(trainerId) && this.overlaps(s)) || null;
    },
    getSeminarConflict(seminarId) {
        return this.allSlots.find(s => String(s.seminar_id) === String(seminarId) && this.overlaps(s)) || null;
    }
}">
    <div class="w-full max-w-[1600px] mx-auto space-y-6">

        <!-- HERO HEADER BANNER -->
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#162536] via-[#2b425e] to-[#4B6A95] p-6 sm:p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-full transition text-white shrink-0 mt-1" title="Retour au tableau de bord">
                        <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                    </a>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-wider text-blue-100 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Planification Pédagogique • CFPT Sénégal-Japon</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Emplois du Temps & Attribution des Salles</h1>
                        <p class="text-blue-100/85 text-xs sm:text-sm mt-1 max-w-2xl">
                            Gérez l'emploi du temps des étudiants et des formateurs selon la capacité des salles disponibles dans chaque département.
                        </p>
                    </div>
                </div>

                <!-- Right Actions & View Switcher -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <!-- Switch Grille / Tableau -->
                    <div class="bg-slate-900/40 backdrop-blur-xs p-1 rounded-full border border-white/15 flex items-center text-xs font-bold">
                        <button type="button" @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-3.5 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">calendar_view_week</span>
                            <span>Vue Semaine</span>
                        </button>
                        <button type="button" @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-xs' : 'text-white/80 hover:text-white'" class="px-3.5 py-1.5 rounded-full transition flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">table_rows</span>
                            <span>Vue Liste</span>
                        </button>
                    </div>

                    <a href="{{ route('admin.departments.index') }}" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/20 rounded-full text-xs font-bold flex items-center gap-1.5 transition backdrop-blur-xs">
                        <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                        <span>Salles ({{ $classrooms->count() }})</span>
                    </a>

                    <button type="button" @click="addModalOpen = true" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full text-xs font-extrabold flex items-center gap-2 shadow-md transition cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>Programmer un Cours</span>
                    </button>
                </div>
            </div>

            <!-- KPIs Strip inside Banner -->
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-white/15">
                <div class="bg-white/10 backdrop-blur-xs rounded-2xl p-3.5 border border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">event_note</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Créneaux Programmés</span>
                        <span class="text-xl font-black">{{ $timetables->count() }} cours</span>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-2xl p-3.5 border border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-300">schedule</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Volume Hebdomadaire</span>
                        <span class="text-xl font-black">{{ $timetables->sum('planned_hours') }} heures</span>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-2xl p-3.5 border border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-300">meeting_room</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Salles & Ateliers</span>
                        <span class="text-xl font-black">{{ $classrooms->count() }} disponibles</span>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-2xl p-3.5 border border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sky-300">school</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Filières Actives</span>
                        <span class="text-xl font-black">{{ $seminars->count() }} filières</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARRE DE FILTRES ÉPURÉE -->
        <div class="bg-white p-5 rounded-[24px] border border-slate-200/90 shadow-sm">
            <form action="{{ route('admin.timetables.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end text-xs">
                @if(!auth()->user()->isDepartmentHead())
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Département</label>
                    <select name="department_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        <option value="">Tous les départements</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Filière / Classe (Étudiants)</label>
                    <select name="seminar_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        <option value="">Toutes les filières</option>
                        @foreach($seminars as $sem)
                            <option value="{{ $sem->id }}" {{ request('seminar_id') == $sem->id ? 'selected' : '' }}>
                                {{ $sem->title }} ({{ $sem->enrolled_count }} étudiants)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Formateur</label>
                    <select name="trainer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        <option value="">Tous les formateurs</option>
                        @foreach($trainers as $tr)
                            <option value="{{ $tr->id }}" {{ request('trainer_id') == $tr->id ? 'selected' : '' }}>{{ $tr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Salle / Atelier / Labo</label>
                    <select name="classroom_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                        <option value="">Toutes les salles</option>
                        @foreach($classrooms as $room)
                            <option value="{{ $room->id }}" {{ request('classroom_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} ({{ $room->capacity }} places)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5 shadow-2xs">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                        <span>Appliquer</span>
                    </button>
                    @if(request()->anyFilled(['department_id', 'seminar_id', 'trainer_id', 'classroom_id']))
                        <a href="{{ route('admin.timetables.index') }}" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition" title="Réinitialiser">
                            <span class="material-symbols-outlined text-[16px] leading-none">restart_alt</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ============================================================== -->
        <!-- VUE 1 : GRILLE HEBDOMADAIRE MODERNE (LUNDI -> SAMEDI) -->
        <!-- ============================================================== -->
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($days as $index => $day)
                @php
                    $daySlots = $timetables->where('day_of_week', $day)->sortBy('start_time');
                    $dayHours = $daySlots->sum('planned_hours');
                    $headerAccent = match($index % 3) {
                        0 => 'from-[#162536] to-[#2c435f]',
                        1 => 'from-[#284466] to-[#4B6A95]',
                        default => 'from-[#1e3a5f] to-[#385980]',
                    };
                @endphp
                <div class="bg-white rounded-[26px] border border-slate-200/90 shadow-sm flex flex-col overflow-hidden hover:shadow-md transition">
                    <!-- En-tête du Jour -->
                    <div class="px-5 py-4 bg-gradient-to-r {{ $headerAccent }} text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center font-black text-xs">
                                {{ substr($day, 0, 3) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-wide uppercase">{{ $day }}</h3>
                                <span class="text-[10px] text-blue-200 font-semibold">{{ $daySlots->count() }} séance(s) programmée(s)</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-black">
                            {{ $dayHours }} h
                        </span>
                    </div>

                    <!-- Liste des cours du jour -->
                    <div class="p-4 space-y-3.5 flex-1 bg-slate-50/60 min-h-[250px]">
                        @forelse($daySlots as $slot)
                            @php
                                $enrolled = $seminars->firstWhere('id', $slot->seminar_id)?->enrolled_count ?? 0;
                                $roomCap = $slot->classroom?->capacity ?? 0;
                                $isOverCapacity = $roomCap > 0 && $enrolled > $roomCap;
                                $roomType = $slot->classroom?->type ?? 'cours';
                                $accentBorder = $roomType === 'atelier' ? 'border-l-amber-500' : ($roomType === 'labo' ? 'border-l-purple-500' : 'border-l-[#4B6A95]');
                                $badgeBg = $roomType === 'atelier' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($roomType === 'labo' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-blue-50 text-[#4B6A95] border-blue-200');
                            @endphp
                            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 border-l-4 {{ $accentBorder }} shadow-2xs hover:shadow-md transition relative group">
                                <!-- Top Row: Time Range + Duration Pill -->
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-900 text-white font-black text-xs">
                                        <span class="material-symbols-outlined text-[14px] text-amber-300">schedule</span>
                                        <span>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black border {{ $badgeBg }}">
                                            {{ $slot->planned_hours }}h
                                        </span>
                                        <form action="{{ route('admin.timetables.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Retirer ce cours de l\'emploi du temps ?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 hover:bg-rose-600 hover:text-white flex items-center justify-center transition cursor-pointer" title="Supprimer ce créneau">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Matière / Module Principal -->
                                <h4 class="text-sm font-black text-slate-900 leading-snug mb-1">
                                    {{ $slot->topic?->name ?? $slot->seminar?->title ?? 'Cours' }}
                                </h4>
                                <p class="text-[11px] font-bold text-[#4B6A95] line-clamp-1 mb-3">
                                    {{ $slot->seminar?->title }}
                                </p>

                                <!-- Details Grid: Formateur + Salle + Capacité -->
                                <div class="pt-2.5 border-t border-slate-100 flex flex-col gap-1.5 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-400 font-semibold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px] text-slate-400">person</span>
                                            Formateur :
                                        </span>
                                        <span class="font-bold text-slate-800">{{ $slot->trainer?->name ?? 'Non assigné' }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-400 font-semibold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px] text-emerald-600">meeting_room</span>
                                            Salle :
                                        </span>
                                        <span class="font-extrabold text-emerald-700">
                                            {{ $slot->classroom?->name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] pt-1">
                                        <span class="text-slate-400 font-medium">{{ $slot->department?->name }}</span>
                                        <span class="px-2 py-0.5 rounded-md font-bold {{ $isOverCapacity ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $enrolled }} inscrits / {{ $roomCap }} places
                                        </span>
                                    </div>

                                    <div class="mt-1 pt-2 border-t border-dashed border-slate-100 flex items-center justify-between text-[10px]">
                                        <span class="text-slate-500 font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px] text-[#4B6A95]">fact_check</span>
                                            Appel / Présences :
                                        </span>
                                        <span class="font-extrabold text-[#4B6A95]">Par {{ $slot->trainer?->name ?? 'le formateur' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-44 rounded-2xl border-2 border-dashed border-slate-200/80 flex flex-col items-center justify-center text-center text-slate-400 text-xs p-4">
                                <span class="material-symbols-outlined text-3xl opacity-30 mb-1">event_available</span>
                                <span class="font-semibold">Aucun cours programmé</span>
                                <button type="button" @click="addModalOpen = true" class="mt-2 text-[11px] font-bold text-[#4B6A95] hover:underline cursor-pointer">
                                    + Ajouter un créneau
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ============================================================== -->
        <!-- VUE 2 : VUE TABLEAU CHRONOLOGIQUE STRUCTURÉE -->
        <!-- ============================================================== -->
        <div x-show="viewMode === 'table'" style="display: none;" class="bg-white rounded-[26px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-white uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="py-4 px-5">Jour</th>
                            <th class="py-4 px-5">Horaire</th>
                            <th class="py-4 px-5">Filière & Matière</th>
                            <th class="py-4 px-5">Département</th>
                            <th class="py-4 px-5">Formateur</th>
                            <th class="py-4 px-5">Salle & Capacité</th>
                            <th class="py-4 px-5 text-center">Heures</th>
                            <th class="py-4 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($timetables as $slot)
                            @php
                                $enrolled = $seminars->firstWhere('id', $slot->seminar_id)?->enrolled_count ?? 0;
                                $roomCap = $slot->classroom?->capacity ?? 0;
                            @endphp
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="py-3.5 px-5 font-black text-slate-900">{{ $slot->day_of_week }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#4B6A95] font-black">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-black text-slate-900">{{ $slot->topic?->name ?? 'Tronc commun' }}</div>
                                    <div class="text-[11px] text-[#4B6A95] font-bold">{{ $slot->seminar?->title }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 font-semibold">{{ $slot->department?->name ?? '-' }}</td>
                                <td class="py-3.5 px-5 font-bold text-slate-900">{{ $slot->trainer?->name ?? '-' }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="font-extrabold text-emerald-700 block">{{ $slot->classroom?->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $enrolled }} étudiants / {{ $roomCap }} places</span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-white font-black">{{ $slot->planned_hours }} h</span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <form action="{{ route('admin.timetables.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Supprimer ce créneau ?');" class="m-0 inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">Aucun créneau d'emploi du temps configuré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Programmer un créneau d'emploi du temps -->
    <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="addModalOpen = false" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Programmer un Cours dans l'Emploi du Temps</h3>
                    <p class="text-xs text-slate-500">Contrôle automatique des disponibilités de salles, formateurs et effectifs inscrits.</p>
                </div>
                <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.timetables.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <!-- ÉTAPE 1 : CHOIX DU CRÉNEAU HORAIRE (POUR CALCULER EN TEMPS RÉEL LES SALLES & FORMATEURS DISPONIBLES) -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#4B6A95]">1. Jour & Créneau Horaire du Cours</span>
                        <span class="text-[11px] font-bold text-emerald-700">Filtrage anti-conflit actif</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jour de la semaine <span class="text-rose-500">*</span></label>
                            <select name="day_of_week" x-model="selectedDay" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                                @foreach($days as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Heure début <span class="text-rose-500">*</span></label>
                            <input type="time" name="start_time" x-model="startTime" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Heure fin <span class="text-rose-500">*</span></label>
                            <input type="time" name="end_time" x-model="endTime" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 font-bold outline-none focus:border-[#4B6A95]">
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 2 : DÉPARTEMENT, SALLE DISPONIBLE & FORMATEUR DISPONIBLE -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">2. Département <span class="text-rose-500">*</span></label>
                        <select name="department_id" x-model="selectedDepartmentId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->classrooms->count() }} salles)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">3. Salle / Atelier (Salles occupées verrouillées) <span class="text-rose-500">*</span></label>
                        <select name="classroom_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            <template x-if="availableClassrooms.length === 0">
                                <option value="">-- Aucune salle dans ce département --</option>
                            </template>
                            <template x-for="room in availableClassrooms" :key="room.id">
                                <option :value="room.id"
                                        :disabled="getRoomConflict(room.id) !== null"
                                        x-text="getRoomConflict(room.id)
                                            ? '🔒 ' + room.name + ' — OCCUPÉE (' + getRoomConflict(room.id).start_time + '-' + getRoomConflict(room.id).end_time + ' par ' + getRoomConflict(room.id).trainer_name + ')'
                                            : '✓ ' + room.name + ' (' + room.capacity + ' places - ' + room.type + ') — DISPONIBLE'">
                                </option>
                            </template>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">
                            4. Formateur assigné (Un formateur ne peut être que dans une seule salle à la fois) <span class="text-rose-500">*</span>
                        </label>
                        <select name="trainer_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            <template x-for="tr in trainersList" :key="tr.id">
                                <option :value="tr.id"
                                        :disabled="getTrainerConflict(tr.id) !== null"
                                        x-text="getTrainerConflict(tr.id)
                                            ? '🔒 ' + tr.name + ' — INDISPONIBLE (Déjà en cours en ' + getTrainerConflict(tr.id).classroom_name + ' de ' + getTrainerConflict(tr.id).start_time + ' à ' + getTrainerConflict(tr.id).end_time + ')'
                                            : '✓ ' + tr.name + ' (' + tr.email + ') — DISPONIBLE'">
                                </option>
                            </template>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">
                            5. Filière / Classe (avec effectif des étudiants inscrits) <span class="text-rose-500">*</span>
                        </label>
                        <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            @foreach($seminars as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->title }} — {{ $sem->enrolled_count }} étudiant(s) inscrit(s)</option>
                            @endforeach
                        </select>
                        <template x-if="getSeminarConflict(selectedSeminarId)">
                            <p class="mt-1 text-[11px] font-bold text-amber-700">
                                ⚠ Attention : Cette filière a déjà un cours sur ce créneau (<span x-text="getSeminarConflict(selectedSeminarId).topic_name + ' en ' + getSeminarConflict(selectedSeminarId).classroom_name"></span>).
                            </p>
                        </template>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">6. Matière / Module enseigné</label>
                        <select name="topic_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 font-semibold outline-none focus:border-[#4B6A95]">
                            <option value="">-- Toute la classe / Tronc commun --</option>
                            <template x-for="top in currentTopics" :key="top.id">
                                <option :value="top.id" x-text="top.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Summary Box of Planned Hours & Enrolled students -->
                <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase text-[#4B6A95] block">Volume horaire conforme requis</span>
                        <span class="text-xs text-slate-600">Le formateur devra déclarer exactement ce volume horaire lors de l'émargement.</span>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-xl bg-[#4B6A95] text-white font-black text-sm" x-text="calculatedHours + ' h'"></span>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4B6A95] hover:bg-[#3c5678] text-white font-bold shadow-sm cursor-pointer">
                        Enregistrer dans l'emploi du temps
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
