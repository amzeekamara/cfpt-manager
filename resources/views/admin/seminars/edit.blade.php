@extends('layouts.admin')

@section('header_title', 'Modifier la Filière / Cours')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.seminars') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700">
                <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Modifier la Filière / Formation</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Mettez à jour les informations de la filière « {{ $seminar->title }} ».</p>
            </div>
        </div>

        <form action="{{ route('admin.seminars.update', $seminar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200 shadow-sm space-y-8">
                <!-- Basic Info Section -->
                <div class="space-y-4">
                    <h2 class="text-base font-extrabold text-[#4B6A95] flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="material-symbols-outlined text-[20px]">school</span> Informations Générales de la Filière
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="title" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Intitulé de la Filière <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $seminar->title) }}" required
                                class="w-full bg-slate-50 border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none transition">
                            @error('title')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <x-multi-select-dropdown
                                name="departments[]"
                                label="Département(s) de rattachement"
                                :options="$departments"
                                :selected="old('departments', $seminar->departments->pluck('id')->toArray())"
                                placeholder="Sélectionner le département..."
                                :required="true" />
                            @error('departments')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="cohort_id" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Année Académique</label>
                            <select name="cohort_id" id="cohort_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none transition cursor-pointer">
                                @foreach ($cohorts as $cohort)
                                    <option value="{{ $cohort->id }}" {{ old('cohort_id', $seminar->cohort_id) == $cohort->id ? 'selected' : '' }}>
                                        {{ $cohort->title }} ({{ $cohort->start_date->format('Y') }})
                                        — {{ $cohort->status === 'active' ? '✓ Active' : ($cohort->status === 'completed' ? '📁 Clôturée' : '✎ En préparation') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cohort_id')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description & Image -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div class="space-y-2">
                        <label for="description" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Description & Objectifs Pédagogiques</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none transition resize-none">{{ old('description', $seminar->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Image d'illustration</label>
                        <div class="relative group">
                            <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <label for="image" class="w-full aspect-video rounded-2xl border-2 border-dashed @error('image') border-rose-400 @else border-slate-200 @enderror bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:border-[#4B6A95] hover:bg-blue-50/30 transition-all overflow-hidden relative">
                                <img id="image-preview" src="{{ $seminar->image ? asset('storage/' . $seminar->image) : '' }}"
                                    class="absolute inset-0 w-full h-full object-cover {{ $seminar->image ? '' : 'hidden' }}">
                                <div id="upload-placeholder" class="text-center p-4 {{ $seminar->image ? 'hidden' : '' }}">
                                    <span class="material-symbols-outlined text-4xl text-slate-400 mb-1">add_photo_alternate</span>
                                    <p class="text-xs text-slate-500 font-medium">Changer l'image (JPG, PNG)</p>
                                </div>
                            </label>
                        </div>
                        @error('image')
                            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Topics Section -->
                <div class="pt-2 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-[#4B6A95] flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">menu_book</span> Matières / Modules de la Filière
                        </h2>
                        <button type="button" onclick="addTopic()" class="text-xs px-3.5 py-2 bg-blue-50 text-[#4B6A95] rounded-xl font-bold hover:bg-[#4B6A95] hover:text-white transition flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">add</span> Ajouter une matière
                        </button>
                    </div>
                    <div id="topics-container" class="space-y-2.5">
                        @php
                            $romanNumeralsPHP = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV'];
                        @endphp
                        @foreach ($seminar->topics as $index => $topic)
                            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <div class="flex-none text-xs font-extrabold text-[#4B6A95] w-24">Matière {{ $romanNumeralsPHP[$index] ?? $index + 1 }}</div>
                                <input type="text" name="topics[]" value="{{ $topic->name }}" placeholder="Intitulé de la matière"
                                    class="flex-1 bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-xs font-semibold text-slate-800 focus:border-[#4B6A95] outline-none">
                                <button type="button" onclick="this.parentElement.remove(); updateTopicNumbers();" class="w-8 h-8 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Supprimer">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    @error('topics')
                        <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logistics -->
                <div class="pt-2 space-y-4">
                    <h2 class="text-base font-extrabold text-[#4B6A95] flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="material-symbols-outlined text-[20px]">schedule</span> Période & Effectif Maximum
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-1.5">
                            <label for="start_date" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Date de début <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                value="{{ old('start_date', $seminar->start_date ? $seminar->start_date->format('Y-m-d\TH:i') : '') }}" required
                                class="w-full bg-slate-50 border @error('start_date') border-rose-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none">
                            @error('start_date')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="end_date" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Date de fin <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" name="end_date" id="end_date"
                                value="{{ old('end_date', $seminar->end_date ? $seminar->end_date->format('Y-m-d\TH:i') : '') }}" required
                                class="w-full bg-slate-50 border @error('end_date') border-rose-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none">
                            @error('end_date')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="max_participants" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Effectif max d'étudiants <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="max_participants" id="max_participants" required
                                value="{{ old('max_participants', $seminar->max_participants) }}" min="1"
                                class="w-full bg-slate-50 border @error('max_participants') border-rose-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#4B6A95] outline-none">
                            @error('max_participants')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="p-4 flex items-center gap-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider">Statut actuel :</div>
                    <div class="flex gap-5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="draft" {{ old('status', $seminar->status) === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-[#4B6A95]">
                            <span class="text-xs font-bold text-slate-700">En préparation</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', $seminar->status) === 'active' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600">
                            <span class="text-xs font-bold text-emerald-700">Active (Ouverte)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="completed" {{ old('status', $seminar->status) === 'completed' ? 'checked' : '' }} class="w-4 h-4 text-slate-500">
                            <span class="text-xs font-bold text-slate-600">Clôturée</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.seminars') }}" class="px-6 py-3 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">Annuler</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold shadow-sm transition cursor-pointer">Mettre à jour la filière</button>
            </div>
        </form>
    </div>
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
        topicDiv.className = 'flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200';
        topicDiv.innerHTML = `
            <div class="flex-none text-xs font-extrabold text-[#4B6A95] w-24">Matière ${roman}</div>
            <input type="text" name="topics[]" value="${value}" placeholder="Intitulé de la matière (ex: Automatisme Industriel)"
                class="flex-1 bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-xs font-semibold text-slate-800 focus:border-[#4B6A95] outline-none">
            <button type="button" onclick="this.parentElement.remove(); updateTopicNumbers();" class="w-8 h-8 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Supprimer">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        `;
        container.appendChild(topicDiv);
    }

    function updateTopicNumbers() {
        const topics = document.getElementById('topics-container').children;
        for (let i = 0; i < topics.length; i++) {
            const roman = romanNumerals[i] || (i + 1);
            topics[i].querySelector('.w-24').textContent = `Matière ${roman}`;
        }
    }
</script>
@endsection
