@extends('layouts.admin')

@section('header_title', 'Emploi du Temps Pédagogique')

@section('content')
<div class="w-full min-h-screen bg-[#f3f4f8] p-3 sm:p-5 lg:p-7 text-slate-800" x-data="{
    viewMode: 'week', // 'day', 'week', 'month', 'table'
    addModalOpen: false,
    selectedDeptFilter: '{{ $effectiveDeptId ?: request('department_id', '') }}',
    
    // Calendar Navigation State
    currentDate: new Date(),
    selectedDayIndex: 3, // 0: Lun, 1: Mar, 2: Mer, 3: Jeu (active in mock), 4: Ven, 5: Sam
    weekOffset: 0,
    monthOffset: 0,

    // Raw and Enriched Slots Data
    slots: @js($calendarSlots ?? []),
    allSlots: @js($allSlots ?? []),
    daysList: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayShorts: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
    hoursList: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'],

    // Form modal state
    selectedDepartmentId: '{{ $effectiveDeptId ?: ($departments->first()?->id ?? '') }}',
    selectedSeminarId: '{{ $seminars->first()?->id ?? '' }}',
    selectedDay: 'Lundi',
    startTime: '08:00',
    endTime: '12:00',

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
    },

    // Navigation helpers
    navigate(delta) {
        if (this.viewMode === 'week') {
            this.weekOffset += delta;
        } else if (this.viewMode === 'day') {
            this.selectedDayIndex = (this.selectedDayIndex + delta + 6) % 6;
        } else if (this.viewMode === 'month') {
            this.monthOffset += delta;
        }
    },
    resetDate() {
        this.weekOffset = 0;
        this.monthOffset = 0;
        this.selectedDayIndex = 3; // Jeudi
    },
    get currentDayName() {
        return this.daysList[this.selectedDayIndex];
    },
    get dateRangeLabel() {
        if (this.viewMode === 'day') {
            const dayNum = 5 + this.selectedDayIndex + (this.weekOffset * 7);
            return this.daysList[this.selectedDayIndex] + ' ' + dayNum + ' mai 2026';
        } else if (this.viewMode === 'month') {
            const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            const mIdx = (4 + this.monthOffset + 120) % 12;
            return months[mIdx] + ' 2026';
        } else {
            const startDay = 5 + (this.weekOffset * 7);
            const endDay = startDay + 6;
            return startDay + ' — ' + endDay + ' mai 2026';
        }
    },
    getDaySlots(day) {
        return this.slots.filter(s => s.day_of_week === day);
    },
    getTopOffset(startHourFloat) {
        // Base: 08:00 = 0px. Each hour = 76px
        return Math.max(0, (startHourFloat - 8) * 76);
    },
    getSlotHeight(durationHours) {
        return Math.max(48, durationHours * 76 - 6);
    },
    getCardClasses(slot) {
        if (slot.has_conflict) {
            return 'bg-[#ffebee] border-2 border-[#ef5350] text-[#c62828] shadow-sm';
        }
        switch (slot.color) {
            case 'sky':
                return 'bg-[#e0f2fe] border border-[#bae6fd] text-[#0369a1] hover:bg-[#d0ecfe]';
            case 'emerald':
                return 'bg-[#dcfce7] border border-[#bbf7d0] text-[#15803d] hover:bg-[#cbf7dc]';
            case 'purple':
                return 'bg-[#f3e8ff] border border-[#e9d5ff] text-[#7e22ce] hover:bg-[#ecdcff]';
            case 'amber':
                return 'bg-[#ffedd5] border border-[#fed7aa] text-[#c2410c] hover:bg-[#fee4c4]';
            case 'indigo':
            default:
                return 'bg-[#e0e7ff] border border-[#c7d2fe] text-[#4338ca] hover:bg-[#d4ddff]';
        }
    },
    getBulletColor(slot) {
        if (slot.has_conflict) return 'bg-rose-500';
        switch (slot.color) {
            case 'sky': return 'bg-sky-500';
            case 'emerald': return 'bg-emerald-500';
            case 'purple': return 'bg-purple-500';
            case 'amber': return 'bg-amber-500';
            default: return 'bg-indigo-500';
        }
    }
}">
    <div class="w-full max-w-[1680px] mx-auto space-y-6">

        <!-- ============================================================== -->
        <!-- HEADER PRINCIPAL AVEC STYLE CALENDAR UI MODERNE -->
        <!-- ============================================================== -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/70 text-blue-900 text-xs font-black uppercase tracking-wider mb-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span>Calendar UI • Planification Pédagogique CFPT</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Emploi du Temps des Cours & Salles
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Jour, semaine et mois — Planification anti-conflit des salles, formateurs et cours.
                </p>
            </div>

            <!-- Boutons Actions Rapides -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.departments.index') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-outlined text-[16px] text-slate-500">meeting_room</span>
                    <span>Salles ({{ $classrooms->count() }})</span>
                </a>
                <a href="{{ route('admin.attendances.index') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-outlined text-[16px] text-[#4B6A95]">fact_check</span>
                    <span>Émargement / Présences</span>
                </a>
                <button type="button" @click="addModalOpen = true" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-black transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <span class="material-symbols-outlined text-[17px]">add</span>
                    <span>+ Quick add</span>
                </button>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- BARRE DE FILTRES RAPIDES PAR DÉPARTEMENT / FILIÈRE / FORMATEUR -->
        <!-- ============================================================== -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs">
            <form action="{{ route('admin.timetables.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end text-xs">
                @if(!auth()->user()->isDepartmentHead())
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Département</label>
                    <select name="department_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-bold text-slate-800 outline-none focus:bg-white focus:border-blue-500 transition">
                        <option value="">Tous les départements</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Filière / Classe</label>
                    <select name="seminar_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-bold text-slate-800 outline-none focus:bg-white focus:border-blue-500 transition">
                        <option value="">Toutes les classes</option>
                        @foreach($seminars as $sem)
                            <option value="{{ $sem->id }}" {{ request('seminar_id') == $sem->id ? 'selected' : '' }}>
                                {{ $sem->title }} ({{ $sem->enrolled_count }} étu.)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Formateur</label>
                    <select name="trainer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-bold text-slate-800 outline-none focus:bg-white focus:border-blue-500 transition">
                        <option value="">Tous les formateurs</option>
                        @foreach($trainers as $tr)
                            <option value="{{ $tr->id }}" {{ request('trainer_id') == $tr->id ? 'selected' : '' }}>{{ $tr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Salle / Labo</label>
                    <select name="classroom_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-bold text-slate-800 outline-none focus:bg-white focus:border-blue-500 transition">
                        <option value="">Toutes les salles</option>
                        @foreach($classrooms as $room)
                            <option value="{{ $room->id }}" {{ request('classroom_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} ({{ $room->capacity }} pl.)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5 shadow-2xs">
                        <span class="material-symbols-outlined text-[15px]">filter_alt</span>
                        <span>Filtrer</span>
                    </button>
                    @if(request()->anyFilled(['department_id', 'seminar_id', 'trainer_id', 'classroom_id']))
                        <a href="{{ route('admin.timetables.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition" title="Réinitialiser">
                            <span class="material-symbols-outlined text-[15px] leading-none">restart_alt</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ============================================================== -->
        <!-- CALENDAR WRAPPER (CALENDAR UI FIDÈLE À L'IMAGE) -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-[26px] border border-slate-200/90 shadow-sm overflow-hidden">

            <!-- BARRE DE COMMANDE SUPÉRIEURE : TITRE VUE + NAVIGATION + TOGGLE MODES + BOUTON QUICK ADD -->
            <div class="px-5 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Gauche : Titre du mode actif + Flèches Précédent/Suivant + Période -->
                <div class="flex items-center gap-4">
                    <span x-text="viewMode === 'day' ? 'Day' : (viewMode === 'week' ? 'Week' : (viewMode === 'month' ? 'Month' : 'Table'))" 
                          class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight"></span>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="navigate(-1)" 
                                class="w-8 h-8 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition cursor-pointer" title="Précédent">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                        
                        <span x-text="dateRangeLabel" class="text-sm font-bold text-slate-800 min-w-[140px] text-center px-1"></span>

                        <button type="button" @click="navigate(1)" 
                                class="w-8 h-8 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition cursor-pointer" title="Suivant">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>

                        <button type="button" @click="resetDate()" 
                                class="text-[11px] font-bold text-blue-600 hover:underline px-2 cursor-pointer">
                            Aujourd'hui
                        </button>
                    </div>
                </div>

                <!-- Droite : Switcher [ Day | Week | Month | Table ] + [ + Quick add ] -->
                <div class="flex items-center gap-3">
                    <div class="inline-flex bg-slate-100/90 p-1 rounded-xl border border-slate-200/80 text-xs font-bold text-slate-600">
                        <button type="button" @click="viewMode = 'day'" 
                                :class="viewMode === 'day' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            Day
                        </button>
                        <button type="button" @click="viewMode = 'week'" 
                                :class="viewMode === 'week' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            Week
                        </button>
                        <button type="button" @click="viewMode = 'month'" 
                                :class="viewMode === 'month' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            Month
                        </button>
                        <button type="button" @click="viewMode = 'table'" 
                                :class="viewMode === 'table' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            Table
                        </button>
                    </div>

                    <button type="button" @click="addModalOpen = true" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-black transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>+ Quick add</span>
                    </button>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 1. VUE SEMAINE (WEEK VIEW) — GRILLE FIDÈLE À L'IMAGE -->
            <!-- ============================================================== -->
            <div x-show="viewMode === 'week'" class="overflow-x-auto">
                <div class="min-w-[960px]">
                    
                    <!-- Ligne d'En-tête des Colonnes (Heure + Jours) -->
                    <div class="grid grid-cols-[70px_repeat(6,1fr)] border-b border-slate-200 text-center text-xs font-bold text-slate-700 bg-slate-50/50">
                        <div class="py-3 px-2 text-[10px] font-black uppercase text-slate-400 border-r border-slate-100 flex items-center justify-center">
                            GMT+0
                        </div>
                        <template x-for="(day, dIdx) in daysList" :key="day">
                            <div class="py-3 px-2 border-r border-slate-100 last:border-r-0 flex flex-col items-center justify-center gap-1">
                                <span class="text-[11px] font-semibold text-slate-500 uppercase" x-text="dayShorts[dIdx]"></span>
                                <div class="flex items-center justify-center">
                                    <span :class="dIdx === selectedDayIndex 
                                            ? 'w-7 h-7 rounded-full bg-blue-600 text-white font-black flex items-center justify-center text-xs shadow-xs' 
                                            : 'text-xs font-bold text-slate-800'"
                                          x-text="(5 + dIdx + (weekOffset * 7)) + ' mai'">
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Corps de la Grille Hebdomadaire (Axe des heures + 6 Colonnes de Jours) -->
                    <div class="relative grid grid-cols-[70px_repeat(6,1fr)] bg-white">
                        
                        <!-- Colonne de gauche : Axe des Heures (08:00 à 19:00) -->
                        <div class="border-r border-slate-100 text-[11px] font-medium text-slate-400 select-none">
                            <template x-for="hour in hoursList" :key="hour">
                                <div class="h-[76px] pr-2.5 pt-1 text-right border-b border-slate-100" x-text="hour"></div>
                            </template>
                        </div>

                        <!-- 6 Colonnes de Jours avec Événements Positionnés Précisément -->
                        <template x-for="(day, dIdx) in daysList" :key="day">
                            <div class="relative border-r border-slate-100 last:border-r-0 min-h-[912px]">
                                
                                <!-- Lignes d'heures horizontales en arrière-plan -->
                                <template x-for="hour in hoursList" :key="hour">
                                    <div class="h-[76px] border-b border-slate-100"></div>
                                </template>

                                <!-- Bannière Pause Déjeuner (13:00 - 14:00) -->
                                <div class="absolute left-1 right-1 top-[380px] h-[70px] rounded-xl bg-blue-50/60 border border-dashed border-blue-200/80 flex items-center justify-center gap-1.5 text-[11px] font-bold text-blue-700 pointer-events-none z-0">
                                    <span>🍴</span>
                                    <span>13:00 - 14:00 Pause déjeuner</span>
                                </div>

                                <!-- Cartes de Cours Positionnées dans le Jour -->
                                <template x-for="slot in getDaySlots(day)" :key="slot.id">
                                    <div class="absolute left-1.5 right-1.5 rounded-xl p-2.5 transition z-10 cursor-pointer overflow-hidden group shadow-2xs"
                                         :style="'top: ' + getTopOffset(slot.start_hour_float) + 'px; height: ' + getSlotHeight(slot.duration_hours) + 'px;'"
                                         :class="getCardClasses(slot)">
                                        
                                        <!-- En-tête de Carte : Puce / Heure / Badge Conflit -->
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <div class="flex items-center gap-1.5 text-[11px] font-extrabold truncate">
                                                <span class="w-2 h-2 rounded-full shrink-0" :class="getBulletColor(slot)"></span>
                                                <span x-text="slot.start_time + ' - ' + slot.end_time"></span>
                                            </div>

                                            <template x-if="slot.has_conflict">
                                                <span class="px-1.5 py-0.5 rounded-md bg-rose-200 text-rose-800 text-[10px] font-black uppercase tracking-wider shrink-0">
                                                    Conflict
                                                </span>
                                            </template>
                                        </div>

                                        <!-- Titre du Cours / Matière -->
                                        <div class="font-black text-xs leading-snug line-clamp-2" x-text="slot.topic_name"></div>

                                        <!-- Sous-titre : Filière & Classe -->
                                        <div class="text-[10px] font-bold opacity-80 truncate mt-0.5" x-text="slot.seminar_title"></div>

                                        <!-- Détails : Salle & Formateur -->
                                        <div class="mt-1.5 pt-1.5 border-t border-black/5 flex flex-col gap-0.5 text-[10px]">
                                            <div class="flex items-center justify-between font-semibold">
                                                <span class="truncate" x-text="'📍 ' + slot.classroom_name"></span>
                                                <span class="opacity-75 shrink-0" x-text="slot.planned_hours + 'h'"></span>
                                            </div>
                                            <div class="truncate opacity-80" x-text="'👤 ' + slot.trainer_name"></div>
                                        </div>

                                        <!-- Alerte Détaillée Conflit -->
                                        <template x-if="slot.has_conflict">
                                            <div class="mt-1 p-1 rounded-md bg-rose-100 text-rose-900 text-[10px] font-bold" x-text="slot.conflict_reason"></div>
                                        </template>

                                        <!-- Hover Action Overlay : Émargement & Suppression -->
                                        <div class="absolute top-1.5 right-1.5 hidden group-hover:flex items-center gap-1 z-20">
                                            <a :href="slot.attendance_url" class="p-1 rounded-md bg-white/90 text-blue-700 hover:bg-blue-600 hover:text-white transition shadow-2xs" title="Faire l'appel">
                                                <span class="material-symbols-outlined text-[13px] leading-none">fact_check</span>
                                            </a>
                                            <form :action="slot.delete_url" method="POST" onsubmit="return confirm('Retirer ce cours de l\'emploi du temps ?');" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-md bg-white/90 text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-2xs cursor-pointer" title="Supprimer">
                                                    <span class="material-symbols-outlined text-[13px] leading-none">close</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 2. VUE JOUR (DAY VIEW) — FIDÈLE AU BAS GAUCHE DE L'IMAGE -->
            <!-- ============================================================== -->
            <div x-show="viewMode === 'day'" class="p-5" style="display: none;">
                
                <!-- Sélecteur rapide des jours de la semaine -->
                <div class="flex items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jour sélectionné :</span>
                        <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold">
                            <template x-for="(day, dIdx) in daysList" :key="day">
                                <button type="button" @click="selectedDayIndex = dIdx" 
                                        :class="selectedDayIndex === dIdx ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg transition cursor-pointer"
                                        x-text="day">
                                </button>
                            </template>
                        </div>
                    </div>
                    <span class="text-xs font-black text-slate-700" x-text="getDaySlots(currentDayName).length + ' cours programmés'"></span>
                </div>

                <!-- Grille horaire du jour sélectionné -->
                <div class="relative grid grid-cols-[70px_1fr] bg-white max-w-4xl">
                    
                    <!-- Colonne des heures -->
                    <div class="border-r border-slate-100 text-[11px] font-medium text-slate-400 select-none">
                        <template x-for="hour in hoursList" :key="hour">
                            <div class="h-[80px] pr-3 pt-1 text-right border-b border-slate-100" x-text="hour"></div>
                        </template>
                    </div>

                    <!-- Colonne des créneaux du jour -->
                    <div class="relative min-h-[960px] pl-4">
                        
                        <!-- Lignes horizontales -->
                        <template x-for="hour in hoursList" :key="hour">
                            <div class="h-[80px] border-b border-slate-100"></div>
                        </template>

                        <!-- Indicateur de temps présent (Ligne pointillée avec puce bleue) -->
                        <div class="absolute left-0 right-0 top-[280px] flex items-center pointer-events-none z-20">
                            <span class="w-3 h-3 rounded-full bg-blue-600 -ml-1.5 shadow-xs"></span>
                            <div class="flex-1 border-t-2 border-dotted border-blue-600"></div>
                        </div>

                        <!-- Cartes larges pour le Jour -->
                        <template x-for="slot in getDaySlots(currentDayName)" :key="slot.id">
                            <div class="absolute left-4 right-4 rounded-2xl p-4 transition z-10 cursor-pointer shadow-xs border"
                                 :style="'top: ' + ((slot.start_hour_float - 8) * 80) + 'px; height: ' + Math.max(68, slot.duration_hours * 80 - 8) + 'px;'"
                                 :class="getCardClasses(slot)">
                                
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" :class="getBulletColor(slot)"></span>
                                        <span class="text-xs font-black" x-text="slot.start_time + ' - ' + slot.end_time"></span>
                                        <span class="text-sm font-black text-slate-900" x-text="slot.topic_name"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full bg-white/70 text-slate-800 text-[11px] font-black border border-slate-200">
                                            <span x-text="slot.planned_hours + ' heures'"></span>
                                        </span>
                                        <a :href="slot.attendance_url" class="px-3 py-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold flex items-center gap-1 transition">
                                            <span class="material-symbols-outlined text-[14px]">fact_check</span>
                                            <span>Faire l'appel</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs pt-2 border-t border-black/5">
                                    <div>
                                        <span class="text-slate-500 font-semibold text-[10px] block uppercase">Filière / Classe</span>
                                        <span class="font-bold text-slate-800" x-text="slot.seminar_title"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 font-semibold text-[10px] block uppercase">Salle & Capacité</span>
                                        <span class="font-extrabold text-emerald-800" x-text="slot.classroom_name + ' (' + slot.classroom_capacity + ' places)'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 font-semibold text-[10px] block uppercase">Formateur Assigné</span>
                                        <span class="font-bold text-slate-800" x-text="slot.trainer_name"></span>
                                    </div>
                                </div>

                                <template x-if="slot.has_conflict">
                                    <div class="mt-2 p-2 rounded-xl bg-rose-100 text-rose-900 text-xs font-bold flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">warning</span>
                                        <span x-text="slot.conflict_reason"></span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="getDaySlots(currentDayName).length === 0">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 text-xs">
                                <span class="material-symbols-outlined text-4xl opacity-30 mb-1">event_available</span>
                                <span class="font-bold">Aucun cours programmé pour ce jour</span>
                            </div>
                        </template>

                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 3. VUE MOIS (MONTH VIEW) — FIDÈLE AU BAS DROIT DE L'IMAGE -->
            <!-- ============================================================== -->
            <div x-show="viewMode === 'month'" class="p-5" style="display: none;">
                <div class="max-w-4xl mx-auto">
                    
                    <!-- En-tête des jours de la semaine du mois -->
                    <div class="grid grid-cols-7 text-center text-xs font-bold text-slate-400 uppercase tracking-wider py-3 border-b border-slate-100">
                        <div>Lun</div>
                        <div>Mar</div>
                        <div>Mer</div>
                        <div>Jeu</div>
                        <div>Ven</div>
                        <div>Sam</div>
                        <div>Dim</div>
                    </div>

                    <!-- Grille matricielle 7x5 des jours du mois -->
                    <div class="grid grid-cols-7 border-b border-l border-slate-100 text-xs">
                        
                        <!-- Jours du mois précédent (inactifs) -->
                        <div class="h-28 p-2 border-r border-t border-slate-100 bg-slate-50/40 text-slate-300">28</div>
                        <div class="h-28 p-2 border-r border-t border-slate-100 bg-slate-50/40 text-slate-300">29</div>
                        <div class="h-28 p-2 border-r border-t border-slate-100 bg-slate-50/40 text-slate-300">30</div>

                        <!-- 1er au 31 mai -->
                        <template x-for="dayNum in 31" :key="dayNum">
                            <div @click="selectedDayIndex = ((dayNum - 1) % 6); viewMode = 'day'" 
                                 class="h-28 p-2 border-r border-t border-slate-100 hover:bg-slate-50 transition cursor-pointer flex flex-col justify-between">
                                
                                <div class="flex items-center justify-between">
                                    <span :class="dayNum === 8 ? 'w-6 h-6 rounded-full bg-blue-600 text-white font-black flex items-center justify-center text-xs shadow-xs' : 'font-bold text-slate-700'"
                                          x-text="dayNum">
                                    </span>
                                    <template x-if="dayNum === 8">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    </template>
                                </div>

                                <!-- Puces colorées pour les cours du jour (comme dans l'image) -->
                                <div class="flex items-center gap-1 flex-wrap">
                                    <template x-for="s in getDaySlots(daysList[(dayNum - 1) % 6]).slice(0, 4)" :key="s.id">
                                        <span class="w-2 h-2 rounded-full" :class="getBulletColor(s)" :title="s.topic_name"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div class="h-28 p-2 border-r border-t border-slate-100 bg-slate-50/40 text-slate-300">1</div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 4. VUE TABLEAU (LISTE CHRONOLOGIQUE DÉTAILLÉE) -->
            <!-- ============================================================== -->
            <div x-show="viewMode === 'table'" class="overflow-x-auto" style="display: none;">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-white uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="py-4 px-5">Jour</th>
                            <th class="py-4 px-5">Horaire</th>
                            <th class="py-4 px-5">Filière & Matière</th>
                            <th class="py-4 px-5">Département</th>
                            <th class="py-4 px-5">Formateur</th>
                            <th class="py-4 px-5">Salle & Capacité</th>
                            <th class="py-4 px-5 text-center">Statut</th>
                            <th class="py-4 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <template x-for="slot in slots" :key="slot.id">
                            <tr class="hover:bg-slate-50 transition" :class="slot.has_conflict ? 'bg-rose-50/40' : ''">
                                <td class="py-3.5 px-5 font-black text-slate-900" x-text="slot.day_of_week"></td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-black" x-text="slot.start_time + ' - ' + slot.end_time"></span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-black text-slate-900" x-text="slot.topic_name"></div>
                                    <div class="text-[11px] text-[#4B6A95] font-bold" x-text="slot.seminar_title"></div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 font-semibold" x-text="slot.department_name"></td>
                                <td class="py-3.5 px-5 font-bold text-slate-900" x-text="slot.trainer_name"></td>
                                <td class="py-3.5 px-5">
                                    <span class="font-extrabold text-emerald-700 block" x-text="slot.classroom_name"></span>
                                    <span class="text-[10px] text-slate-400" x-text="slot.enrolled_count + ' inscrits / ' + slot.classroom_capacity + ' places'"></span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <template x-if="slot.has_conflict">
                                        <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-black text-[10px] uppercase">
                                            ⚠ Conflit
                                        </span>
                                    </template>
                                    <template x-if="!slot.has_conflict">
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-black text-[10px] uppercase">
                                            ✓ Valide ({{ slot.planned_hours }}h)
                                        </span>
                                    </template>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a :href="slot.attendance_url" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Faire l'appel">
                                            <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                        </a>
                                        <form :action="slot.delete_url" method="POST" onsubmit="return confirm('Supprimer ce créneau ?');" class="m-0 inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer" title="Supprimer">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- 4 CARTES EXPLICATIVES EN BAS (EXACTEMENT COMME DANS L'IMAGE) -->
        <!-- ============================================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Carte 1 : Event -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-black text-xs flex items-center justify-center">1</span>
                    <h4 class="font-black text-sm text-slate-900">Event</h4>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Cartes pédagogiques claires avec horaires, formateurs et salles.
                </p>
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-950 text-xs">
                    <div class="flex items-center gap-1.5 font-bold mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>10:00 - 12:00</span>
                    </div>
                    <div class="font-black">Travaux Pratiques Réseaux</div>
                    <div class="text-[10px] text-emerald-700 opacity-80 mt-1">Labo Info-1 • 20 participants</div>
                </div>
            </div>

            <!-- Carte 2 : Conflict -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 font-black text-xs flex items-center justify-center">2</span>
                    <h4 class="font-black text-sm text-slate-900">Conflict</h4>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Le système alerte et bloque immédiatement toute collision horaire.
                </p>
                <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-950 text-xs">
                    <div class="flex items-center gap-1.5 font-bold text-rose-700 mb-1">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        <span>11:00 - 12:00 Conflict</span>
                    </div>
                    <div class="font-bold text-[11px]">Salle occupée ou Formateur en double réservation</div>
                </div>
            </div>

            <!-- Carte 3 : Quick add -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-black text-xs flex items-center justify-center">3</span>
                    <h4 class="font-black text-sm text-slate-900">Quick add</h4>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Programmation rapide d'un cours avec filtrage instantané anti-collision.
                </p>
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs flex items-center justify-between">
                    <span class="font-bold">+ Nouveau cours</span>
                    <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white font-black text-[10px]">1 clic</span>
                </div>
            </div>

            <!-- Carte 4 : Drag & Multi-vues -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 font-black text-xs flex items-center justify-center">4</span>
                    <h4 class="font-black text-sm text-slate-900">Multi-vues</h4>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Passez du niveau micro (Jour) au macro (Mois) en un seul geste.
                </p>
                <div class="p-2 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center gap-2 text-xs font-black text-purple-700">
                    <span>Jour</span>
                    <span>•</span>
                    <span>Semaine</span>
                    <span>•</span>
                    <span>Mois</span>
                </div>
            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- MODAL QUICK ADD : PROGRAMMER UN COURS AVEC FILTRAGE ANTI-CONFLIT -->
    <!-- ============================================================== -->
    <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" x-transition>
        <div @click.outside="addModalOpen = false" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">+ Quick add — Programmer un Cours</h3>
                    <p class="text-xs text-slate-500">Contrôle instantané anti-collision des salles et des formateurs.</p>
                </div>
                <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.timetables.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <!-- ÉTAPE 1 : JOUR & CRÉNEAU HORAIRE -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">1. Jour & Créneau Horaire</span>
                        <span class="text-[11px] font-bold text-emerald-700">Filtrage anti-conflit actif</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jour de la semaine <span class="text-rose-500">*</span></label>
                            <select name="day_of_week" x-model="selectedDay" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold outline-none focus:border-blue-500">
                                @foreach($days as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Heure début <span class="text-rose-500">*</span></label>
                            <input type="time" name="start_time" x-model="startTime" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Heure fin <span class="text-rose-500">*</span></label>
                            <input type="time" name="end_time" x-model="endTime" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 2 : DÉPARTEMENT, SALLE & FORMATEUR -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">2. Département <span class="text-rose-500">*</span></label>
                        <select name="department_id" x-model="selectedDepartmentId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold outline-none focus:border-blue-500">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->classrooms->count() }} salles)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">3. Salle / Labo <span class="text-rose-500">*</span></label>
                        <select name="classroom_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold outline-none focus:border-blue-500">
                            <template x-if="availableClassrooms.length === 0">
                                <option value="">-- Aucune salle dans ce département --</option>
                            </template>
                            <template x-for="room in availableClassrooms" :key="room.id">
                                <option :value="room.id"
                                        :disabled="getRoomConflict(room.id) !== null"
                                        x-text="getRoomConflict(room.id)
                                            ? '🔒 ' + room.name + ' — OCCUPÉE (' + getRoomConflict(room.id).start_time + '-' + getRoomConflict(room.id).end_time + ' par ' + getRoomConflict(room.id).trainer_name + ')'
                                            : '✓ ' + room.name + ' (' + room.capacity + ' places) — DISPONIBLE'">
                                </option>
                            </template>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">
                            4. Formateur assigné (Anti-duplication) <span class="text-rose-500">*</span>
                        </label>
                        <select name="trainer_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold outline-none focus:border-blue-500">
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
                            5. Filière / Classe d'étudiants <span class="text-rose-500">*</span>
                        </label>
                        <select name="seminar_id" x-model="selectedSeminarId" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold outline-none focus:border-blue-500">
                            @foreach($seminars as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->title }} ({{ $sem->enrolled_count }} inscrits)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">6. Matière / Module enseigné</label>
                        <select name="topic_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold outline-none focus:border-blue-500">
                            <option value="">-- Tronc commun / Cours général --</option>
                            <template x-for="top in currentTopics" :key="top.id">
                                <option :value="top.id" x-text="top.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase text-blue-700 block">Volume horaire calculé</span>
                        <span class="text-xs text-slate-600">Calcul automatique entre l'heure de début et de fin.</span>
                    </div>
                    <span class="px-3 py-1 rounded-xl bg-blue-600 text-white font-black text-sm" x-text="calculatedHours + ' h'"></span>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold cursor-pointer">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm cursor-pointer">
                        Enregistrer dans le Calendar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
