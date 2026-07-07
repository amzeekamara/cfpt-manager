@extends('layouts.admin')

@section('header_title', isset($form) ? 'Modifier le formulaire' : 'Nouveau Formulaire')

@section('content')
<div class="p-8 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.forms.index') }}"
           class="w-9 h-9 rounded-xl flex items-center justify-center bg-surface-container hover:bg-surface-container-high border border-outline-variant transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-headline font-bold text-on-surface">
                {{ isset($form) ? 'Modifier : ' . $form->title : 'Nouveau Formulaire' }}
            </h1>
            <p class="text-sm text-on-surface-variant">Glissez-déposez les champs pour personnaliser votre formulaire.</p>
        </div>
    </div>

    {{-- BUILDER LAYOUT --}}
    <form id="builder-form"
          action="{{ isset($form) ? route('admin.forms.update', $form->id) : route('admin.forms.store') }}"
          method="POST">
        @csrf
        @if(isset($form)) @method('PUT') @endif

        {{-- Hidden: serialized fields --}}
        <input type="hidden" name="fields_json" id="fields_json_input">

        <div class="grid grid-cols-1 xl:grid-cols-[300px_1fr_320px] gap-6 items-start">

            {{-- ===== COL 1: Field Palette ===== --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5 sticky top-24">
                <h2 class="font-headline font-semibold text-sm text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">widgets</span>
                    Champs disponibles
                </h2>
                <div id="field-palette" class="space-y-2">
                    @php
                    $palette = [
                        ['type' => 'text',     'icon' => 'text_fields',          'label' => 'Texte court'],
                        ['type' => 'textarea', 'icon' => 'notes',                'label' => 'Texte long'],
                        ['type' => 'email',    'icon' => 'alternate_email',      'label' => 'Email'],
                        ['type' => 'tel',      'icon' => 'call',                 'label' => 'Téléphone'],
                        ['type' => 'date',     'icon' => 'calendar_today',       'label' => 'Date'],
                        ['type' => 'number',   'icon' => 'pin',                  'label' => 'Nombre'],
                        ['type' => 'select',   'icon' => 'arrow_drop_down_circle','label' => 'Liste déroulante'],
                        ['type' => 'radio',    'icon' => 'radio_button_checked', 'label' => 'Choix unique'],
                        ['type' => 'checkbox', 'icon' => 'check_box',            'label' => 'Case à cocher'],
                        ['type' => 'file',     'icon' => 'attach_file',          'label' => 'Fichier'],
                    ];
                    @endphp
                    @foreach($palette as $p)
                    <div class="palette-item flex items-center gap-3 p-3 rounded-xl border border-outline-variant/50 bg-surface-container-low hover:bg-primary/5 hover:border-primary/30 cursor-grab active:cursor-grabbing transition-all select-none"
                         draggable="true"
                         data-type="{{ $p['type'] }}"
                         data-label="{{ $p['label'] }}">
                        <span class="material-symbols-outlined text-primary text-[20px]">{{ $p['icon'] }}</span>
                        <span class="text-sm font-medium text-on-surface">{{ $p['label'] }}</span>
                        <span class="material-symbols-outlined text-outline ml-auto text-[16px]">drag_indicator</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== COL 2: Form Canvas ===== --}}
            <div class="space-y-4">

                {{-- Form Meta --}}
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                    <h2 class="font-headline font-semibold text-sm text-on-surface mb-4">Informations du formulaire</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Titre *</label>
                            <input type="text" name="title" required
                                   value="{{ isset($form) ? $form->title : 'Formulaire PFPT — Inscription Séminaire Pays Tiers' }}"
                                   class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all bg-surface"
                                   placeholder="Titre du formulaire">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Description</label>
                            <textarea name="description" rows="2"
                                      class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all bg-surface resize-none"
                                      placeholder="Décrivez l'objet du formulaire...">{{ isset($form) ? $form->description : 'Formulaire de collecte des informations des participants au séminaire pays tiers' }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Séminaire associé</label>
                                <select name="seminar_id" class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none bg-surface">
                                    <option value="">— Aucun séminaire —</option>
                                    @foreach($seminars as $seminar)
                                    <option value="{{ $seminar->id }}"
                                        {{ isset($form) && $form->seminar_id == $seminar->id ? 'selected' : '' }}>
                                        {{ $seminar->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Statut</label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant cursor-pointer hover:bg-surface-container-low">
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ !isset($form) || $form->is_active ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary rounded">
                                    <span class="text-sm font-medium text-on-surface">Formulaire actif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Drop Zone --}}
                <div class="bg-surface-container-lowest rounded-2xl border-2 border-dashed border-outline-variant shadow-sm p-2 min-h-[300px] transition-all"
                     id="drop-zone">
                    <div id="fields-container" class="space-y-2 min-h-[200px]">
                        {{-- Fields rendered by JS --}}
                    </div>
                    <div id="drop-placeholder"
                         class="flex flex-col items-center justify-center py-12 text-center text-on-surface-variant hidden first:flex">
                        <span class="material-symbols-outlined text-5xl mb-3 text-outline">drag_pan</span>
                        <p class="text-sm font-medium">Glissez des champs ici pour construire votre formulaire</p>
                        <p class="text-xs mt-1">ou cliquez sur un champ du panneau gauche</p>
                    </div>
                </div>

                {{-- Save button --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 text-white font-semibold rounded-xl shadow hover:opacity-90 transition-all"
                            style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        {{ isset($form) ? 'Enregistrer les modifications' : 'Créer le formulaire' }}
                    </button>
                </div>
            </div>

            {{-- ===== COL 3: Field Settings Panel ===== --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5 sticky top-24">
                <h2 class="font-headline font-semibold text-sm text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary text-[20px]">tune</span>
                    Paramètres du champ
                </h2>
                <div id="field-settings-empty" class="text-center py-8 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl text-outline mb-3 block">touch_app</span>
                    <p class="text-sm">Sélectionnez un champ<br>pour le configurer.</p>
                </div>
                <div id="field-settings-panel" class="hidden space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Label *</label>
                        <input type="text" id="setting-label"
                               class="w-full border border-outline-variant rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Texte d'aide (placeholder)</label>
                        <input type="text" id="setting-placeholder"
                               class="w-full border border-outline-variant rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                    </div>

                    {{-- Source de données (pour select, radio, checkbox) --}}
                    <div id="setting-datasource-group" class="hidden">
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Source des options</label>
                        <select id="setting-datasource"
                                class="w-full border border-outline-variant rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none bg-white">
                            <option value="">— Options manuelles —</option>
                            <option value="countries">🌍 Pays (liste complète)</option>
                            <option value="seminars">📚 Séminaires (depuis la base)</option>
                            <option value="editions">📅 Éditions (depuis la base)</option>
                        </select>
                        <p class="text-[11px] text-on-surface-variant mt-1.5 italic">Si une source est sélectionnée, les options manuelles sont ignorées.</p>
                    </div>

                    <div id="setting-options-group" class="hidden">
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wider">Options manuelles (une par ligne)</label>
                        <textarea id="setting-options" rows="4"
                                  class="w-full border border-outline-variant rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none font-mono"
                                  placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl border border-outline-variant">
                        <span class="text-sm font-medium text-on-surface">Champ obligatoire</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="setting-required" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:bg-primary transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 bg-white rounded-full h-4 w-4 transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                        </label>
                    </div>
                    <button type="button" id="apply-settings"
                            class="w-full py-2 bg-primary/10 text-primary font-semibold text-sm rounded-xl hover:bg-primary/20 transition-colors">
                        Appliquer
                    </button>
                    <button type="button" id="delete-field"
                            class="w-full py-2 bg-rose-50 text-rose-700 font-semibold text-sm rounded-xl hover:bg-rose-100 transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Supprimer ce champ
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ===== JAVASCRIPT BUILDER ENGINE ===== --}}
<script>
// ============================================================
//  DATA SOURCES from PHP (seminars, editions list from DB)
// ============================================================
const dbSources = @json($dbSources ?? []);
// dbSources = { seminars: {1: 'Title1', ...}, editions: {...} }

// ============================================================
//  STATE
// ============================================================
let fields = [];
let selectedKey = null;

// ============================================================
//  INIT
// ============================================================
const existingFields = @json(isset($form) ? ($form->fields_json ?? []) : ($defaultFields ?? []));

document.addEventListener('DOMContentLoaded', () => {
    fields = existingFields.map(f => ({ data_source: '', ...f }));
    renderAll();
    setupDragFromPalette();
    setupDropZone();

    // datasource toggle: if datasource selected, hide manual options
    document.getElementById('setting-datasource').addEventListener('change', function() {
        const manualGroup = document.getElementById('setting-options-group');
        if (this.value) {
            manualGroup.classList.add('hidden');
        } else {
            const field = fields.find(f => f.key === selectedKey);
            if (field && ['select','radio','checkbox'].includes(field.type)) {
                manualGroup.classList.remove('hidden');
            }
        }
    });
});

// ============================================================
//  RENDER
// ============================================================
function renderAll() {
    const container = document.getElementById('fields-container');
    container.innerHTML = '';
    const placeholder = document.getElementById('drop-placeholder');

    if (fields.length === 0) {
        if (placeholder) placeholder.classList.remove('hidden');
    } else {
        if (placeholder) placeholder.classList.add('hidden');
        fields.forEach((field, idx) => container.appendChild(buildFieldCard(field, idx)));
    }
}

const dataSourceLabels = {
    'countries': '🌍 Pays',
    'seminars':  '📚 Séminaires',
    'editions':  '📅 Éditions',
};

function buildFieldCard(field, idx) {
    const div = document.createElement('div');
    div.className = 'field-card group flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all select-none ' +
        (selectedKey === field.key
            ? 'border-primary bg-primary/5 shadow-md'
            : 'border-outline-variant bg-surface hover:border-primary/40 hover:bg-surface-container-low');
    div.dataset.key = field.key;
    div.draggable = true;

    const typeIcon = {
        text: 'text_fields', textarea: 'notes', email: 'alternate_email',
        tel: 'call', date: 'calendar_today', number: 'pin',
        select: 'arrow_drop_down_circle', radio: 'radio_button_checked',
        checkbox: 'check_box', file: 'attach_file'
    }[field.type] || 'text_fields';

    const sourceBadge = field.data_source
        ? `<span class="text-[10px] font-semibold bg-primary/10 text-primary px-1.5 py-0.5 rounded-full">${dataSourceLabels[field.data_source] || field.data_source}</span>`
        : '';

    div.innerHTML = `
        <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-[20px] cursor-grab">drag_indicator</span>
        <span class="material-symbols-outlined text-primary text-[20px]">${typeIcon}</span>
        <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold text-on-surface truncate">
                ${field.label}
                ${field.required ? '<span class="text-rose-500 ml-0.5">*</span>' : ''}
            </div>
            <div class="text-[11px] text-on-surface-variant capitalize flex items-center gap-1">${field.type} ${sourceBadge}</div>
        </div>
        <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button type="button" onclick="moveField('${field.key}', -1); event.stopPropagation();">
                <span class="material-symbols-outlined text-[16px] text-outline hover:text-primary">expand_less</span>
            </button>
            <button type="button" onclick="moveField('${field.key}', 1); event.stopPropagation();">
                <span class="material-symbols-outlined text-[16px] text-outline hover:text-primary">expand_more</span>
            </button>
        </div>`;

    div.addEventListener('click', () => selectField(field.key));
    div.addEventListener('dragstart', e => { e.dataTransfer.setData('reorder', field.key); div.classList.add('opacity-50'); });
    div.addEventListener('dragend', () => div.classList.remove('opacity-50'));
    return div;
}

// ============================================================
//  SELECTION & SETTINGS PANEL
// ============================================================
function selectField(key) {
    selectedKey = key;
    const field = fields.find(f => f.key === key);
    if (!field) return;

    document.getElementById('field-settings-empty').classList.add('hidden');
    document.getElementById('field-settings-panel').classList.remove('hidden');

    document.getElementById('setting-label').value = field.label || '';
    document.getElementById('setting-placeholder').value = field.placeholder || '';
    document.getElementById('setting-required').checked = !!field.required;

    const optGroup    = document.getElementById('setting-options-group');
    const dsGroup     = document.getElementById('setting-datasource-group');
    const dsSelect    = document.getElementById('setting-datasource');

    if (['select', 'radio', 'checkbox'].includes(field.type)) {
        dsGroup.classList.remove('hidden');
        dsSelect.value = field.data_source || '';
        if (!field.data_source) {
            optGroup.classList.remove('hidden');
            document.getElementById('setting-options').value = (field.options || []).join('\n');
        } else {
            optGroup.classList.add('hidden');
        }
    } else {
        dsGroup.classList.add('hidden');
        optGroup.classList.add('hidden');
    }

    renderAll();
}

document.getElementById('apply-settings').addEventListener('click', () => {
    const field = fields.find(f => f.key === selectedKey);
    if (!field) return;

    field.label       = document.getElementById('setting-label').value.trim() || field.label;
    field.placeholder = document.getElementById('setting-placeholder').value.trim();
    field.required    = document.getElementById('setting-required').checked;

    if (['select', 'radio', 'checkbox'].includes(field.type)) {
        const ds = document.getElementById('setting-datasource').value;
        field.data_source = ds;
        if (!ds) {
            field.options = document.getElementById('setting-options').value
                .split('\n').map(o => o.trim()).filter(Boolean);
        } else {
            field.options = []; // cleared — resolved at render time from DB
        }
    }

    renderAll();
});

document.getElementById('delete-field').addEventListener('click', () => {
    fields = fields.filter(f => f.key !== selectedKey);
    selectedKey = null;
    document.getElementById('field-settings-empty').classList.remove('hidden');
    document.getElementById('field-settings-panel').classList.add('hidden');
    renderAll();
});

// ============================================================
//  MOVE UP/DOWN
// ============================================================
function moveField(key, direction) {
    const idx = fields.findIndex(f => f.key === key);
    const newIdx = idx + direction;
    if (newIdx < 0 || newIdx >= fields.length) return;
    [fields[idx], fields[newIdx]] = [fields[newIdx], fields[idx]];
    renderAll();
}

// ============================================================
//  DRAG FROM PALETTE (add new field)
// ============================================================
function setupDragFromPalette() {
    document.querySelectorAll('.palette-item').forEach(item => {
        item.addEventListener('dragstart', e => {
            e.dataTransfer.setData('newField', JSON.stringify({
                type: item.dataset.type,
                label: item.dataset.label,
            }));
        });
        // Click to add quickly
        item.addEventListener('click', () => {
            addField(item.dataset.type, item.dataset.label);
        });
    });
}

function addField(type, label) {
    const key = type + '_' + Date.now();
    fields.push({ key, type, label, required: false, placeholder: '', options: [] });
    selectField(key);
    renderAll();
}

// ============================================================
//  DROP ZONE (reorder + receive from palette)
// ============================================================
function setupDropZone() {
    const zone = document.getElementById('drop-zone');

    zone.addEventListener('dragover', e => {
        e.preventDefault();
        zone.classList.add('border-primary', 'bg-primary/5');
    });

    zone.addEventListener('dragleave', () => {
        zone.classList.remove('border-primary', 'bg-primary/5');
    });

    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('border-primary', 'bg-primary/5');

        const newFieldData = e.dataTransfer.getData('newField');
        const reorderKey   = e.dataTransfer.getData('reorder');

        if (newFieldData) {
            const { type, label } = JSON.parse(newFieldData);
            addField(type, label);
        } else if (reorderKey) {
            // Simple append to end (reorder by drag)
            const idx = fields.findIndex(f => f.key === reorderKey);
            if (idx > -1) {
                const [moved] = fields.splice(idx, 1);
                fields.push(moved);
                renderAll();
            }
        }
    });
}

// ============================================================
//  FORM SUBMIT: serialize fields to hidden input
// ============================================================
document.getElementById('builder-form').addEventListener('submit', e => {
    document.getElementById('fields_json_input').value = JSON.stringify(fields);
});
</script>
@endsection
