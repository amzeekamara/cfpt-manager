@extends('layouts.admin')

@section('header_title', 'Formulaires Partageables')

@section('content')
<div class="p-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface mb-1">Formulaires</h1>
            <p class="text-on-surface-variant text-sm">Créez et partagez des formulaires de collecte d'informations.</p>
        </div>
        <a href="{{ route('admin.forms.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white font-semibold rounded-xl shadow hover:opacity-90 transition-all text-sm"
           style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Nouveau Formulaire
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        <div>
            <p class="font-semibold text-sm">Succès</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Forms Grid --}}
    @if($forms->isEmpty())
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-16 text-center">
        <span class="material-symbols-outlined text-6xl text-outline mb-4 block" style="font-variation-settings: 'FILL' 0;">dynamic_form</span>
        <h3 class="text-lg font-headline font-semibold text-on-surface mb-2">Aucun formulaire créé</h3>
        <p class="text-on-surface-variant text-sm mb-6">Commencez par créer un formulaire partageable pour collecter les informations des participants.</p>
        <a href="{{ route('admin.forms.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-xl hover:opacity-90 transition-all text-sm"
           style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Créer mon premier formulaire
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($forms as $form)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-1 flex flex-col overflow-hidden">
            {{-- Card Header --}}
            <div class="p-5 border-b border-outline-variant/50 flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $form->is_active ? 'bg-primary/10 text-primary' : 'bg-outline-variant/40 text-outline' }}">
                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">dynamic_form</span>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-on-surface text-sm leading-tight line-clamp-2">{{ $form->title }}</h3>
                        @if($form->seminar)
                            <span class="text-[11px] text-on-surface-variant">📌 {{ $form->seminar->title }}</span>
                        @endif
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border flex-shrink-0
                    {{ $form->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $form->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    {{ $form->is_active ? 'Actif' : 'Fermé' }}
                </span>
            </div>

            {{-- Stats --}}
            <div class="px-5 py-4 flex items-center gap-6 bg-surface-container-low/30">
                <div class="text-center">
                    <div class="text-2xl font-headline font-bold text-on-surface">{{ $form->submissions_count }}</div>
                    <div class="text-[11px] text-on-surface-variant font-medium">Réponses</div>
                </div>
                <div class="h-8 w-px bg-outline-variant"></div>
                <div class="text-[11px] text-on-surface-variant flex-1">
                    <div class="flex items-center gap-1 mb-0.5">
                        <span class="material-symbols-outlined text-[13px]">sell</span>
                        {{ count($form->fields_json ?? []) }} champs
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                        Créé le {{ $form->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- Share link --}}
            <div class="px-5 py-3 bg-primary/5 border-t border-outline-variant/30">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[16px]">link</span>
                    <input type="text"
                           value="{{ $form->share_url }}"
                           id="share-{{ $form->id }}"
                           readonly
                           class="flex-1 text-[11px] bg-transparent text-primary font-mono truncate outline-none cursor-pointer"
                           onclick="copyLink('share-{{ $form->id }}', this)">
                    <button onclick="copyLink('share-{{ $form->id }}', document.getElementById('share-{{ $form->id }}'))"
                            class="text-primary hover:text-primary-container transition-colors flex-shrink-0"
                            title="Copier le lien">
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-4 border-t border-outline-variant/50 flex items-center gap-2 mt-auto flex-wrap">
                <a href="{{ route('admin.forms.edit', $form->id) }}"
                   class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs rounded-lg border border-outline-variant transition-colors">
                    <span class="material-symbols-outlined text-[16px]">edit</span>Modifier
                </a>
                <a href="{{ route('admin.forms.responses', $form->id) }}"
                   class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-medium text-xs rounded-lg border border-outline-variant transition-colors">
                    <span class="material-symbols-outlined text-[16px]">table_view</span>Réponses
                </a>
                <form action="{{ route('admin.forms.toggle', $form->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 font-medium text-xs rounded-lg border transition-colors
                            {{ $form->is_active ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $form->is_active ? 'pause_circle' : 'play_circle' }}</span>
                        {{ $form->is_active ? 'Fermer' : 'Ouvrir' }}
                    </button>
                </form>
                <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST"
                      onsubmit="return confirm('Supprimer ce formulaire et toutes ses réponses ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-700 border-rose-200 border font-medium text-xs rounded-lg hover:bg-rose-100 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
function copyLink(inputId, el) {
    const input = document.getElementById(inputId);
    navigator.clipboard.writeText(input.value).then(() => {
        const icon = el.closest('.flex').querySelector('.material-symbols-outlined:last-of-type') ||
                     el.querySelector('.material-symbols-outlined');
        const old = icon ? icon.textContent : '';
        if (icon) { icon.textContent = 'check'; }
        setTimeout(() => { if (icon) icon.textContent = old || 'content_copy'; }, 2000);
    });
}
</script>
@endsection
