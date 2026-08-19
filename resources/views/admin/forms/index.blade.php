@extends('layouts.admin')

@section('header_title', 'Formulaires Partageables')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Formulaires Partageables</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Créez et partagez des formulaires de collecte d'informations.</p>
                </div>
            </div>
            <a href="{{ route('admin.forms.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-full transition shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Nouveau Formulaire
            </a>
        </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-start gap-3">
        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        <div>
            <p class="font-semibold text-sm">Succès</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Forms Grid --}}
    @if($forms->isEmpty())
    <div class="bg-surface-container-lowest rounded-md border border-outline-variant p-16 text-center">
        <span class="material-symbols-outlined text-6xl text-outline mb-4 block" style="font-variation-settings: 'FILL' 0;">dynamic_form</span>
        <h3 class="text-lg font-headline font-semibold text-on-surface mb-2">Aucun formulaire créé</h3>
        <p class="text-on-surface-variant text-sm mb-6">Commencez par créer un formulaire partageable pour collecter les informations des participants.</p>
        <a href="{{ route('admin.forms.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:opacity-90 transition-all text-sm"
           style="background: linear-gradient(135deg, #0f4c81 0%, #4B6A95 100%);">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Créer mon premier formulaire
        </a>
    </div>
    @else
    <style>
        .form-card {
            background-color: #ffffff;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.02);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .fc-subtitle {
            font-size: 11px;
            font-weight: 600;
            color: #8c9096;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .fc-title-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }
        .fc-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            flex: 1;
        }
        .fc-badge {
            background-color: #f0f2f5;
            color: #5c5f66;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 12px;
            white-space: nowrap;
        }
        .fc-badge.active {
            background-color: #ecfdf5;
            color: #059669;
        }
        .fc-badge.inactive {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .fc-chart-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 20px auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .fc-chart-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
            filter: drop-shadow(0px 8px 10px rgba(255, 115, 0, 0.2));
        }
        .fc-chart-track {
            fill: none;
            stroke: #f0f2f5;
            stroke-width: 10;
        }
        .fc-chart-progress {
            fill: none;
            stroke: url(#orangeGradient);
            stroke-width: 10;
            stroke-dasharray: 251.2;
            stroke-dashoffset: 0; /* Full circle representing the data */
            stroke-linecap: round;
        }
        .fc-chart-content {
            text-align: center;
            z-index: 2;
        }
        .fc-chart-percentage {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1;
            margin-bottom: 4px;
        }
        .fc-chart-label {
            font-size: 8px;
            font-weight: 600;
            color: #8c9096;
            letter-spacing: 0.5px;
        }

        .fc-warning-badge-container {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }
        .fc-warning-badge {
            color: #4B6A95;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .fc-details-table {
            background-color: #f7f8f9;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            padding: 0 16px;
            margin-bottom: 25px;
            flex: 1;
        }
        .fc-details-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #eeeeee;
            font-size: 13px;
        }
        .fc-details-row:last-child {
            border-bottom: none;
        }
        .fc-details-label {
            color: #6c7077;
        }
        .fc-details-value {
            color: #1a1a1a;
            font-weight: 500;
        }

        .fc-footer-text {
            font-size: 12px;
            color: #6c7077;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
            cursor: pointer;
            transition: background 0.2s;
        }
        .fc-footer-text:hover {
            background: #f1f5f9;
        }
        .fc-footer-text input {
            background: transparent;
            border: none;
            outline: none;
            flex: 1;
            font-family: monospace;
            color: #0f4c81;
            font-size: 11px;
            cursor: pointer;
        }

        .fc-action-buttons {
            display: flex;
            gap: 12px;
        }
        .fc-btn {
            flex: 1;
            padding: 14px 0;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .fc-btn-outline {
            background-color: #ffffff;
            border: 1px solid #d2e4ff;
            color: #0f4c81;
        }
        .fc-btn-outline:hover {
            background-color: #f4f8fc;
        }
        .fc-btn-solid {
            background: linear-gradient(135deg, #0f4c81 0%, #4B6A95 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 8px 16px rgba(15, 76, 129, 0.2);
        }
        .fc-btn-solid:hover {
            box-shadow: 0 6px 12px rgba(15, 76, 129, 0.3);
            transform: translateY(1px);
        }
        
        /* 3 dots menu */
        .fc-menu-btn {
            position: absolute;
            top: 24px;
            right: 24px;
            color: #8c9096;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .fc-menu-btn:hover {
            background: #f0f2f5;
        }
    </style>
    
    <svg style="width:0;height:0;position:absolute;" aria-hidden="true" focusable="false">
      <defs>
        <linearGradient id="orangeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#ff9900" />
            <stop offset="100%" stop-color="#ff4800" />
        </linearGradient>
      </defs>
    </svg>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($forms as $form)
        <div class="form-card group">
            
            <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block" style="position: absolute; right: 24px; top: 24px;">
                <button @click="open = !open" @click.outside="open = false" class="fc-menu-btn">
                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                </button>
                <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-44 bg-white border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-xl py-1 z-50 flex flex-col overflow-visible">
                    <form action="{{ route('admin.forms.toggle', $form->id) }}" method="POST" class="m-0 w-full">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-medium flex items-center gap-2 {{ $form->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                            <span class="material-symbols-outlined text-[16px]">{{ $form->is_active ? 'block' : 'check_circle' }}</span>
                            {{ $form->is_active ? 'Fermer le formulaire' : 'Ouvrir le formulaire' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Supprimer ce formulaire et toutes ses réponses ?')" class="m-0 w-full border-t border-gray-50">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">delete</span> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="fc-subtitle">Formulaire</div>
            <div class="fc-title-container pr-8">
                <div class="fc-title" title="{{ $form->title }}">{{ $form->title }}</div>
                <div class="fc-badge {{ $form->is_active ? 'active' : 'inactive' }}">
                    {{ $form->is_active ? 'Actif' : 'Fermé' }}
                </div>
            </div>

            <div class="fc-chart-wrapper">
                <svg class="fc-chart-svg" viewBox="0 0 100 100">
                    <circle class="fc-chart-track" cx="50" cy="50" r="40"></circle>
                    <circle class="fc-chart-progress" cx="50" cy="50" r="40"></circle>
                </svg>
                <div class="fc-chart-content">
                    <div class="fc-chart-percentage">{{ $form->submissions_count }}</div>
                    <div class="fc-chart-label">RÉPONSES</div>
                </div>
            </div>

            @if($form->seminar)
            <div class="fc-warning-badge-container">
                <div class="fc-warning-badge">
                    Séminaire: {{ Str::limit($form->seminar->title, 25) }}
                </div>
            </div>
            @else
            <div class="fc-warning-badge-container">
                <div class="fc-warning-badge" style="visibility: hidden;">
                    <span class="material-symbols-outlined text-[14px]">bookmark</span>
                    Placeholder
                </div>
            </div>
            @endif

            <div class="fc-details-table">
                <div class="fc-details-row">
                    <span class="fc-details-label">Champs</span>
                    <span class="fc-details-value">{{ count($form->fields_json ?? []) }} champs configurés</span>
                </div>
                <div class="fc-details-row">
                    <span class="fc-details-label">Créé le</span>
                    <span class="fc-details-value">{{ $form->created_at->format('d M Y') }}</span>
                </div>
                <div class="fc-details-row">
                    <span class="fc-details-label">Statut URL</span>
                    <span class="fc-details-value" style="{{ $form->is_active ? 'color:#059669;' : 'color:#dc2626;' }}">{{ $form->is_active ? 'Public' : 'Privé' }}</span>
                </div>
            </div>

            <div class="fc-footer-text" onclick="copyLink('share-{{ $form->id }}', this)">
                <span class="material-symbols-outlined text-[16px] text-gray-400">link</span>
                <input type="text" value="{{ $form->share_url }}" id="share-{{ $form->id }}" readonly>
                <span class="material-symbols-outlined text-[16px] text-gray-400">content_copy</span>
            </div>

            <div class="fc-action-buttons">
                <a href="{{ route('admin.forms.edit', $form->id) }}" class="fc-btn fc-btn-outline">
                    <span class="material-symbols-outlined text-[16px]">edit_note</span> Modifier
                </a>
                <a href="{{ route('admin.forms.responses', $form->id) }}" class="fc-btn fc-btn-solid">
                    <span class="material-symbols-outlined text-[16px]">visibility</span> Réponses
                </a>
            </div>

        </div>
        @endforeach
    </div>
    @endif
    </div>
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
