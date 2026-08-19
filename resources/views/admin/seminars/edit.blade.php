@extends('layouts.admin')

@section('header_title', 'Modifier le Séminaire')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.seminars') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-dim flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Modifier le Séminaire</h1>
            <p class="text-on-surface-variant font-body">Mettez à jour les informations du séminaire "{{ $seminar->title }}".</p>
        </div>
    </div>

    <form action="{{ route('admin.seminars.update', $seminar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-lowest rounded-md p-8 border border-outline-variant shadow-sm space-y-8">
            <!-- Basic Info Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span> Informations Générales
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="lg:col-span-2 space-y-2">
                        <label for="title" class="text-sm font-bold text-on-surface-variant">
                            Titre du séminaire <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $seminar->title) }}" required 
                            class="w-full bg-surface-container-low border @error('title') border-red-500 @else border-outline-variant @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        @error('title')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <x-multi-select-dropdown 
                            name="departments[]" 
                            label="Départements concernés" 
                            :options="$departments" 
                            :selected="old('departments', $seminar->departments->pluck('id')->toArray())" 
                            placeholder="Sélectionner les départements..." 
                            :required="true" />
                        @error('departments')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="cohort_id" class="text-sm font-bold text-on-surface-variant">Édition / Cycle</label>
                        <select name="cohort_id" id="cohort_id" 
                            class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="">-- Session Indépendante --</option>
                            @foreach($cohorts as $cohort)
                                <option value="{{ $cohort->id }}" {{ old('cohort_id', $seminar->cohort_id) == $cohort->id ? 'selected' : '' }}>
                                    {{ $cohort->title }} ({{ $cohort->start_date->format('Y') }}) 
                                    — {{ $cohort->status === 'active' ? '✓ Actif' : ($cohort->status === 'completed' ? '📁 Archivé' : '✎ Brouillon') }}
                                </option>
                            @endforeach
                        </select>
                        @error('cohort_id')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description & Image -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-4">
                    <label for="description" class="text-sm font-bold text-on-surface-variant">Description</label>
                    <textarea name="description" id="description" rows="5" 
                        class="w-full bg-surface-container-low border border-outline-variant rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none">{{ old('description', $seminar->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-4">
                    <label class="text-sm font-bold text-on-surface-variant">Image de couverture</label>
                    <div class="relative group">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        <label for="image" class="w-full aspect-video rounded-md border-2 border-dashed @error('image') border-red-400 @else border-outline-variant @enderror bg-surface-container-low flex flex-col items-center justify-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all overflow-hidden relative">
                            <img id="image-preview" src="{{ $seminar->image ? asset('storage/' . $seminar->image) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $seminar->image ? '' : 'hidden' }}">
                            <div id="upload-placeholder" class="text-center p-4 {{ $seminar->image ? 'hidden' : '' }}">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">add_photo_alternate</span>
                                <p class="text-xs text-on-surface-variant font-medium">Changer l'image (JPG, PNG)</p>
                            </div>
                        </label>
                    </div>
                    @error('image')
                        <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Topics Section -->
            <div class="pt-4 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">category</span> Thèmes du Séminaire (Optionnel)
                    </h2>
                    <button type="button" onclick="addTopic()" class="text-sm px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold hover:bg-primary/20 transition-all flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-sm">add</span> Ajouter un thème
                    </button>
                </div>
                <div id="topics-container" class="space-y-3">
                    @php
                        $romanNumeralsPHP = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII", "XIII", "XIV", "XV"];
                    @endphp
                    @foreach($seminar->topics as $index => $topic)
                        <div class="flex items-center gap-4 bg-surface-container-low p-3 rounded-lg border border-outline-variant">
                            <div class="flex-none font-bold text-on-surface-variant w-24">Thème {{ $romanNumeralsPHP[$index] ?? ($index + 1) }}</div>
                            <input type="text" name="topics[]" value="{{ $topic->name }}" placeholder="Intitulé du thème" 
                                class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-md px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <button type="button" onclick="this.parentElement.remove(); updateTopicNumbers();" class="w-10 h-10 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-md transition-colors cursor-pointer" title="Supprimer">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    @endforeach
                </div>
                @error('topics')
                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logistics -->
            <div class="pt-4 space-y-4">
                <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined">schedule</span> Logistique & Planning
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="start_date" class="text-sm font-bold text-on-surface-variant">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $seminar->start_date ? $seminar->start_date->format('Y-m-d\TH:i') : '') }}" required 
                            class="w-full bg-surface-container-low border @error('start_date') border-red-500 @else border-outline-variant @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        @error('start_date')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="text-sm font-bold text-on-surface-variant">
                            Date de fin <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $seminar->end_date ? $seminar->end_date->format('Y-m-d\TH:i') : '') }}" required 
                            class="w-full bg-surface-container-low border @error('end_date') border-red-500 @else border-outline-variant @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        @error('end_date')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="max_participants" class="text-sm font-bold text-on-surface-variant">
                            Capacité max <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="max_participants" id="max_participants" required value="{{ old('max_participants', $seminar->max_participants) }}" min="1"
                            class="w-full bg-surface-container-low border @error('max_participants') border-red-500 @else border-outline-variant @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        @error('max_participants')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="pt-4 flex items-center gap-6 bg-surface-container-low/50 p-6 rounded-md border border-outline-variant/30">
                <div class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">État actuel :</div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="status" value="draft" {{ old('status', $seminar->status) === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary border-outline-variant">
                        <span class="text-sm font-medium text-on-surface group-hover:text-primary transition-colors">Brouillon</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="status" value="active" {{ old('status', $seminar->status) === 'active' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-outline-variant">
                        <span class="text-sm font-medium text-on-surface group-hover:text-emerald-600 transition-colors">Actif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="status" value="completed" {{ old('status', $seminar->status) === 'completed' ? 'checked' : '' }} class="w-4 h-4 text-gray-500 focus:ring-gray-500 border-outline-variant">
                        <span class="text-sm font-medium text-on-surface group-hover:text-gray-500 transition-colors">Terminé</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button type="button" onclick="history.back()" class="px-8 py-3 rounded-lg border border-outline-variant font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
            <button type="submit" class="px-12 py-3 rounded-lg bg-primary text-on-primary font-bold shadow-lg shadow-primary/10 hover:shadow-xl hover:-translate-y-0.5 transition-all cursor-pointer">Mettre à jour</button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    const romanNumerals = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII", "XIII", "XIV", "XV"];

    function addTopic(value = '') {
        const container = document.getElementById('topics-container');
        const index = container.children.length;
        const roman = romanNumerals[index] || (index + 1);
        
        const topicDiv = document.createElement('div');
        topicDiv.className = 'flex items-center gap-4 bg-surface-container-low p-3 rounded-lg border border-outline-variant';
        topicDiv.innerHTML = `
            <div class="flex-none font-bold text-on-surface-variant w-24">Thème ${roman}</div>
            <input type="text" name="topics[]" value="${value}" placeholder="Intitulé du thème (ex: Conception Pédagogique)" 
                class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-md px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <button type="button" onclick="this.parentElement.remove(); updateTopicNumbers();" class="w-10 h-10 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-md transition-colors cursor-pointer" title="Supprimer">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        `;
        container.appendChild(topicDiv);
    }

    function updateTopicNumbers() {
        const topics = document.getElementById('topics-container').children;
        for (let i = 0; i < topics.length; i++) {
            const roman = romanNumerals[i] || (i + 1);
            topics[i].querySelector('.w-24').textContent = `Thème ${roman}`;
        }
    }
</script>
@endsection
