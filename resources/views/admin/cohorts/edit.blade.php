@extends('layouts.admin')

@section('header_title', 'Modifier l\'Année Académique')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.cohorts.index') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700">
                <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Modifier l'Année Académique</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Mettez à jour l'année académique : <strong class="text-[#4B6A95]">{{ $cohort->title }}</strong></p>
            </div>
        </div>

        <form action="{{ route('admin.cohorts.update', $cohort) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                <div class="space-y-1.5">
                    <label for="title" class="block text-xs font-bold text-slate-700">Intitulé de l'Année Académique <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $cohort->title) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="start_date" class="block text-xs font-bold text-slate-700">Date de rentrée <span class="text-rose-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" required value="{{ old('start_date', $cohort->start_date->format('Y-m-d')) }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                    </div>
                    <div class="space-y-1.5">
                        <label for="end_date" class="block text-xs font-bold text-slate-700">Date de clôture <span class="text-rose-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" required value="{{ old('end_date', $cohort->end_date->format('Y-m-d')) }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-[#4B6A95] transition">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.cohorts.index') }}" class="px-6 py-2.5 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-8 py-2.5 rounded-full bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
