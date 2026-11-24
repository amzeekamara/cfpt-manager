@extends('layouts.admin')

@section('header_title', 'Nouvelle Année Académique')

@section('content')
    <div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800" x-data="{
        selectedSourceCohort: '{{ $previousCohorts->first()?->id ?? '' }}',
        keepPreviousFilieres: true
    }">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('admin.cohorts.index') }}"
                    class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Ouvrir une Nouvelle Année
                        Académique</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                        Définissez la période académique et reconduisez tout ou partie des filières des 3 départements des
                        années passées.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.cohorts.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <!-- Identification de l'année académique -->
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-wider text-[#4B6A95] flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            1. Informations de l'Année Académique
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1 space-y-1.5">
                                <label for="title" class="block text-xs font-bold text-slate-700">Intitulé de l'Année
                                    Académique <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" id="title" required
                                    placeholder="Ex: Année Académique 2027 - 2028"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                            </div>
                            <div class="space-y-1.5">
                                <label for="start_date" class="block text-xs font-bold text-slate-700">Date de rentrée <span
                                        class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" id="start_date" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                            </div>
                            <div class="space-y-1.5">
                                <label for="end_date" class="block text-xs font-bold text-slate-700">Date de clôture <span
                                        class="text-rose-500">*</span></label>
                                <input type="date" name="end_date" id="end_date" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                            </div>
                        </div>
                    </div>

                    <!-- Reconduction des Filières et Départements des années passées -->
                    @if ($previousCohorts->count() > 0)
                        <div class="pt-6 border-t border-slate-100">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                                <div>
                                    <h2
                                        class="text-sm font-black uppercase tracking-wider text-[#4B6A95] flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                        2. Conserver les Filières & Départements d'une Année Passée
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Cochez les filières que vous souhaitez conserver pour cette nouvelle année. Vous
                                        pourrez ensuite les modifier ou en ajouter d'autres au besoin.
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-600">Année source :</span>
                                    <select name="clone_from_cohort_id" x-model="selectedSourceCohort"
                                        class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#4B6A95]">
                                        @foreach ($previousCohorts as $prev)
                                            <option value="{{ $prev->id }}">{{ $prev->title }}
                                                ({{ $prev->seminars->count() }} filières)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @foreach ($previousCohorts as $prev)
                                <div x-show="selectedSourceCohort == '{{ $prev->id }}'" class="space-y-2.5">
                                    @forelse($prev->seminars as $filiere)
                                        <label
                                            class="flex items-start justify-between gap-3 p-3.5 rounded-2xl border border-slate-200/90 bg-slate-50/70 hover:bg-blue-50/40 hover:border-[#4B6A95] transition cursor-pointer">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" name="selected_seminar_ids[]"
                                                    value="{{ $filiere->id }}" checked
                                                    class="mt-0.5 w-4 h-4 rounded text-[#4B6A95] focus:ring-[#4B6A95]">
                                                <div>
                                                    <span
                                                        class="text-xs font-black text-slate-900 block">{{ $filiere->title }}</span>
                                                    <span class="text-[11px] text-slate-500">
                                                        Matières conservées :
                                                        <strong>{{ $filiere->topics->pluck('name')->join(', ') ?: 'Tronc commun' }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#4B6A95] text-[10px] font-black uppercase shrink-0">
                                                {{ $filiere->departments->pluck('name')->join(', ') ?: 'CFPT' }}
                                            </span>
                                        </label>
                                    @empty
                                        <div class="p-4 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                                            Aucune filière enregistrée sur cette année académique.
                                        </div>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.cohorts.index') }}"
                        class="px-6 py-2.5 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold shadow-sm transition cursor-pointer flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>Créer l'Année Académique & Reconduire les Filières</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
