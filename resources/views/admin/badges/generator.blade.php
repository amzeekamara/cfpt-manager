@extends('layouts.admin')

@section('title', 'Studio Cartes, Badges & Attestations — PFPT')

@push('styles')
<style>
    .canvas-stage {
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
        background-size: 16px 16px;
        background-color: #f1f5f9;
    }
    .field-box {
        position: absolute;
        cursor: move;
        user-select: none;
        box-sizing: border-box;
        border: 1.5px dashed rgba(75, 106, 149, 0.55);
        transition: border-color 0.12s ease, box-shadow 0.12s ease;
    }
    .field-box:hover {
        border-color: #4B6A95;
        background-color: rgba(75, 106, 149, 0.05);
    }
    .field-box.selected {
        border: 2px solid #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        z-index: 30 !important;
    }
    .resize-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #2563eb;
        border: 2px solid #ffffff;
        border-radius: 9999px;
        z-index: 40;
    }
    .resize-handle.e  { right: -6px; top: 50%; transform: translateY(-50%); cursor: ew-resize; }
    .resize-handle.w  { left: -6px; top: 50%; transform: translateY(-50%); cursor: ew-resize; }
    .resize-handle.s  { bottom: -6px; left: 50%; transform: translateX(-50%); cursor: ns-resize; }
    .resize-handle.se { right: -6px; bottom: -6px; cursor: nwse-resize; }
    .field-tag {
        position: absolute;
        top: -20px;
        left: -2px;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 4px;
        background: #2563eb;
        color: #ffffff;
        white-space: nowrap;
        pointer-events: none;
        letter-spacing: 0.02em;
    }
    .guide-center-v {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 1px;
        border-left: 1px dashed rgba(239, 68, 68, 0.55);
        pointer-events: none;
        z-index: 25;
    }
    .guide-center-h {
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
        border-top: 1px dashed rgba(239, 68, 68, 0.35);
        pointer-events: none;
        z-index: 25;
    }
</style>
@endpush

@section('content')
<div x-data="cardStudio()" x-init="init()" @keydown.window="handleKeydown($event)" class="space-y-5 pb-10">

    <!-- EN-TÊTE DU STUDIO -->
    <div class="bg-white rounded-3xl p-5 md:p-6 border border-slate-200/80 shadow-xs flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[#4B6A95] text-[11px] font-bold uppercase tracking-wider mb-2">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>Studio d'Automatisation PDF & Excel</span>
            </div>
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-900 font-headline tracking-tight">
                Générateur de Cartes, Badges & Attestations
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Importez votre modèle PDF, liez votre fichier Excel (ou un séminaire PFPT), positionnez les zones et générez en masse.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Bouton Démo rapide -->
            <button @click="loadPfptSample()"
                    type="button"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold transition cursor-pointer">
                <i data-lucide="wand-2" class="w-4 h-4"></i>
                <span>Charger Exemple PFPT 2026</span>
            </button>

            <!-- Sauvegarder Gabarit -->
            <button @click="openSavePresetModal()"
                    :disabled="!hasPdf || fields.length === 0"
                    type="button"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 disabled:opacity-40 text-slate-700 text-xs font-bold transition cursor-pointer">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Enregistrer gabarit</span>
            </button>

            <!-- Menu d'Export / Génération -->
            <div class="relative" x-data="{ exportMenu: false }" @click.outside="exportMenu = false">
                <button @click="exportMenu = !exportMenu"
                        :disabled="!hasPdf || selectedFilteredRows.length === 0 || generating"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#4B6A95] hover:bg-[#354e70] disabled:opacity-40 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    <i data-lucide="download" class="w-4 h-4" x-show="!generating"></i>
                    <svg x-show="generating" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="generating ? ('Génération ' + progressCurrent + '/' + progressTotal + '...') : ('Générer (' + selectedFilteredRows.length + ' / ' + filteredRows.length + ' docs)')"></span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </button>

                <div x-show="exportMenu" x-transition style="display: none;"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50">
                    <button @click="exportMenu = false; generateBatch('multipage')" type="button"
                            class="w-full text-left flex items-start gap-3 p-2.5 rounded-xl hover:bg-blue-50/70 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#4B6A95] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="file-stack" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">PDF Multipages (<span x-text="selectedFilteredRows.length"></span> sélectionnés)</p>
                            <p class="text-[11px] text-slate-500">1 page par participant coché aux dimensions exactes du PDF modèle.</p>
                        </div>
                    </button>

                    <button @click="exportMenu = false; generateBatch('a4sheet')" type="button"
                            class="w-full text-left flex items-start gap-3 p-2.5 rounded-xl hover:bg-blue-50/70 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Planche d'impression A4 (<span x-text="selectedFilteredRows.length"></span> docs)</p>
                            <p class="text-[11px] text-slate-500">Regroupe les cartes cochées sur feuilles A4 avec repères de découpe.</p>
                        </div>
                    </button>

                    <button @click="exportMenu = false; generateBatch('zip')" type="button"
                            class="w-full text-left flex items-start gap-3 p-2.5 rounded-xl hover:bg-blue-50/70 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="folder-archive" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Archive ZIP (<span x-text="selectedFilteredRows.length"></span> PDF individuels)</p>
                            <p class="text-[11px] text-slate-500">1 fichier PDF séparé et nommé pour chaque personne cochée.</p>
                        </div>
                    </button>

                    <div class="my-1 border-t border-slate-100"></div>

                    <button @click="exportMenu = false; generateBatch('single')" type="button"
                            class="w-full text-left flex items-start gap-3 p-2.5 rounded-xl hover:bg-amber-50/70 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="file-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Uniquement l'aperçu actuel (1 doc)</p>
                            <p class="text-[11px] text-slate-500">Télécharge uniquement la carte/attestation affichée à l'écran.</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- BARRE DE PROGRESSION PENDANT GÉNÉRATION -->
    <div x-show="generating" x-transition style="display: none;"
         class="bg-white rounded-2xl p-4 border border-blue-200 shadow-xs">
        <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
            <span x-text="progressLabel">Génération des documents en cours...</span>
            <span x-text="Math.round((progressCurrent / Math.max(progressTotal, 1)) * 100) + '%'"></span>
        </div>
        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#4B6A95] rounded-full transition-all duration-150"
                 :style="'width:' + Math.round((progressCurrent / Math.max(progressTotal, 1)) * 100) + '%'"></div>
        </div>
    </div>

    <!-- ESPACE DE TRAVAIL 3 COLONNES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- =========================================================
             COLONNE GAUCHE (3/12) : SOURCES PDF, EXCEL & CHAMPS
             ========================================================= -->
        <div class="lg:col-span-3 space-y-4">

            <!-- 1. MODÈLE PDF -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#4B6A95] text-white text-[10px] flex items-center justify-center">1</span>
                        Modèle PDF (Design)
                    </h2>
                    <span x-show="pdfMeta.widthMm" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600"
                          x-text="pdfMeta.widthMm + ' × ' + pdfMeta.heightMm + ' mm'"></span>
                </div>

                <label class="block border-2 border-dashed border-slate-200 hover:border-[#4B6A95] rounded-xl p-3.5 text-center cursor-pointer transition bg-slate-50/60 hover:bg-blue-50/30">
                    <input type="file" accept="application/pdf" @change="handlePdfUpload($event)" class="hidden">
                    <i data-lucide="file-up" class="w-6 h-6 text-[#4B6A95] mx-auto mb-1.5"></i>
                    <p class="text-xs font-bold text-slate-700" x-text="pdfFileName || 'Importer un modèle PDF'"></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Carte, badge ou attestation (.pdf)</p>
                </label>

                <!-- Réglages Format & Orientation de sortie -->
                <div x-show="hasPdf" class="pt-2.5 border-t border-slate-100 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold text-slate-700">Format & Orientation de sortie</span>
                        <span class="text-[10px] font-bold text-[#4B6A95]" x-text="effectiveOutputSize.wMm + ' × ' + effectiveOutputSize.hMm + ' mm'"></span>
                    </div>

                    <select x-model="outputPreset" @change="applyOutputPreset()"
                            class="w-full rounded-xl border border-slate-200 px-2.5 py-1.5 bg-white text-slate-700 font-semibold text-xs focus:border-[#4B6A95] focus:outline-none">
                        <option value="original">Taille d'origine du PDF (1:1)</option>
                        <option value="cr80">Carte CR80 (85,6 × 54 mm)</option>
                        <option value="badge70">Badge standard (70 × 100 mm)</option>
                        <option value="badge90">Grand Badge (90 × 125 mm)</option>
                        <option value="a5">Format A5 (148 × 210 mm)</option>
                        <option value="a4">Attestation A4 (210 × 297 mm)</option>
                        <option value="custom">Personnalisé (Largeur × Hauteur mm)</option>
                    </select>

                    <!-- Sélecteur Portrait / Paysage -->
                    <div class="grid grid-cols-3 gap-1">
                        <button type="button" @click="setOutputOrientation('auto')"
                                class="py-1.5 px-2 rounded-lg border text-[11px] font-bold transition cursor-pointer"
                                :class="outputOrientation === 'auto' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            Auto
                        </button>
                        <button type="button" @click="setOutputOrientation('portrait')"
                                class="py-1.5 px-2 rounded-lg border text-[11px] font-bold transition cursor-pointer"
                                :class="outputOrientation === 'portrait' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            Portrait
                        </button>
                        <button type="button" @click="setOutputOrientation('landscape')"
                                class="py-1.5 px-2 rounded-lg border text-[11px] font-bold transition cursor-pointer"
                                :class="outputOrientation === 'landscape' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            Paysage
                        </button>
                    </div>

                    <!-- Dimensions manuelles en mm -->
                    <div x-show="outputPreset !== 'original'" class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400">Largeur (mm)</label>
                            <input type="number" step="0.5" min="20" max="600" x-model.number="outputWidthMm" @input="outputPreset = 'custom'"
                                   class="w-full rounded-lg border border-slate-200 px-2 py-1 font-mono text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400">Hauteur (mm)</label>
                            <input type="number" step="0.5" min="20" max="600" x-model.number="outputHeightMm" @input="outputPreset = 'custom'"
                                   class="w-full rounded-lg border border-slate-200 px-2 py-1 font-mono text-xs font-bold">
                        </div>
                    </div>
                </div>

                <!-- Gabarits enregistrés -->
                <template x-if="presets.length > 0">
                    <div class="pt-2 border-t border-slate-100 space-y-1.5">
                        <p class="text-[11px] font-bold text-slate-500">Gabarits enregistrés :</p>
                        <div class="space-y-1 max-h-36 overflow-y-auto pr-1">
                            <template x-for="preset in presets" :key="preset.id">
                                <div class="flex items-center justify-between gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200/70 text-xs">
                                    <button @click="applyPreset(preset)" type="button" class="text-left flex-1 truncate font-semibold text-slate-700 hover:text-[#4B6A95] cursor-pointer">
                                        <span class="uppercase text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 mr-1" x-text="preset.type"></span>
                                        <span x-text="preset.name"></span>
                                    </button>
                                    <button @click="deletePreset(preset.id)" type="button" class="text-slate-400 hover:text-rose-600 p-0.5 cursor-pointer" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- 2. SOURCE DE DONNÉES EXCEL / CSV / SÉMINAIRE -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#4B6A95] text-white text-[10px] flex items-center justify-center">2</span>
                        Données (Excel / BDD)
                    </h2>
                    <span x-show="rows.length > 0" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"
                          x-text="rows.length + ' lignes'"></span>
                </div>

                <!-- Upload Excel / CSV -->
                <label class="block border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-xl p-3.5 text-center cursor-pointer transition bg-slate-50/60 hover:bg-emerald-50/30">
                    <input type="file" accept=".xlsx,.xls,.csv" @change="handleExcelUpload($event)" class="hidden">
                    <i data-lucide="file-spreadsheet" class="w-6 h-6 text-emerald-600 mx-auto mb-1.5"></i>
                    <p class="text-xs font-bold text-slate-700" x-text="excelFileName || 'Importer Excel ou CSV'"></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">.xlsx, .xls ou .csv structuré</p>
                </label>

                <!-- Ou importer depuis un séminaire PFPT -->
                <div class="pt-2 border-t border-slate-100">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Ou charger depuis un séminaire PFPT :</label>
                    <select @change="loadFromSeminar($event.target.value)"
                            class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 bg-white text-slate-700 focus:border-[#4B6A95] focus:outline-none">
                        <option value="">-- Sélectionner un séminaire --</option>
                        @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}">
                                {{ Str::limit($seminar->title, 42) }} ({{ $seminar->registrations_count }} validés)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre rapide & Contrôle du nombre à télécharger -->
                <div x-show="rows.length > 0" class="pt-2 border-t border-slate-100 space-y-2.5">
                    <div class="relative">
                        <input type="text" x-model="rowSearch" placeholder="Filtrer par pays, nom, thème..."
                               class="w-full text-xs rounded-xl border border-slate-200 pl-8 pr-3 py-1.5 focus:border-[#4B6A95] focus:outline-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2"></i>
                    </div>

                    <!-- Plage / Nombre exact -->
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-slate-600">Sélection par plage :</span>
                            <strong class="text-[#4B6A95]" x-text="selectedFilteredRows.length + ' / ' + filteredRows.length + ' cochés'"></strong>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="text-[11px] text-slate-500">De</span>
                            <input type="number" min="1" :max="filteredRows.length" x-model.number="rangeFrom"
                                   class="w-14 rounded-lg border border-slate-200 bg-white px-2 py-1 text-center font-bold text-xs">
                            <span class="text-[11px] text-slate-500">à</span>
                            <input type="number" min="1" :max="filteredRows.length" x-model.number="rangeTo"
                                   class="w-14 rounded-lg border border-slate-200 bg-white px-2 py-1 text-center font-bold text-xs">
                            <button @click="selectRange()" type="button"
                                    class="flex-1 px-2.5 py-1 rounded-lg bg-[#4B6A95] hover:bg-[#354e70] text-white text-[11px] font-bold transition cursor-pointer">
                                Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. COLONNES EXCEL À PLACER -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-3">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4B6A95] text-white text-[10px] flex items-center justify-center">3</span>
                    Zones à ajouter
                </h2>

                <div x-show="columns.length === 0" class="text-xs text-slate-400 bg-slate-50 rounded-xl p-3 text-center">
                    Importez un fichier Excel ou cliquez sur « Charger Exemple PFPT 2026 » pour voir les colonnes.
                </div>

                <div x-show="columns.length > 0" class="flex flex-wrap gap-1.5">
                    <template x-for="col in columns" :key="col">
                        <button @click="addField('text', col)" type="button"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-[#4B6A95] text-[#4B6A95] hover:text-white text-xs font-bold transition cursor-pointer border border-blue-100">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                            <span x-text="col"></span>
                        </button>
                    </template>
                </div>

                <!-- Éléments spéciaux -->
                <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2">
                    <button @click="addField('qrcode', columns[0] || 'QR')" type="button"
                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-800 text-slate-700 hover:text-white text-xs font-bold transition cursor-pointer">
                        <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                        <span>+ QR Code</span>
                    </button>
                    <button @click="addField('static', 'Texte libre')" type="button"
                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-800 text-slate-700 hover:text-white text-xs font-bold transition cursor-pointer">
                        <i data-lucide="type" class="w-3.5 h-3.5"></i>
                        <span>+ Texte fixe</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- =========================================================
             COLONNE CENTRALE (6/12) : STUDIO CANVAS INTERACTIF
             ========================================================= -->
        <div class="lg:col-span-6 space-y-3">
            <!-- Barre d'outils d'aperçu -->
            <div class="bg-white rounded-2xl px-4 py-3 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-3">
                <!-- Sélecteur de ligne -->
                <div class="flex items-center gap-1.5">
                    <button @click="prevRow()" :disabled="previewIndex <= 0" type="button"
                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 disabled:opacity-40 flex items-center justify-center text-slate-700 transition cursor-pointer">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>

                    <select x-model.number="previewIndex" :disabled="filteredRows.length === 0"
                            class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 max-w-[230px] focus:outline-none focus:border-[#4B6A95]">
                        <template x-if="filteredRows.length === 0">
                            <option value="0">Aucune donnée chargée</option>
                        </template>
                        <template x-for="(row, idx) in filteredRows" :key="idx">
                            <option :value="idx" x-text="(idx + 1) + '/' + filteredRows.length + ' — ' + getRowSummary(row)"></option>
                        </template>
                    </select>

                    <button @click="nextRow()" :disabled="previewIndex >= filteredRows.length - 1" type="button"
                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 disabled:opacity-40 flex items-center justify-center text-slate-700 transition cursor-pointer">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Contrôles de vue -->
                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer">
                        <input type="checkbox" x-model="showGuides" class="rounded text-[#4B6A95]">
                        <span>Guides</span>
                    </label>
                    <div class="h-4 w-px bg-slate-200"></div>
                    <button @click="zoom = Math.max(0.5, +(zoom - 0.1).toFixed(2)); renderPdfCanvas()" type="button"
                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold cursor-pointer">-</button>
                    <span class="text-xs font-bold text-slate-600 w-10 text-center" x-text="Math.round(zoom * 100) + '%'"></span>
                    <button @click="zoom = Math.min(2.0, +(zoom + 0.1).toFixed(2)); renderPdfCanvas()" type="button"
                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold cursor-pointer">+</button>
                </div>
            </div>

            <!-- Zone Canvas -->
            <div class="canvas-stage rounded-3xl border border-slate-200 p-6 min-h-[600px] flex items-center justify-center overflow-auto relative"
                 @mousedown.self="selectedFieldId = null">

                <!-- État vide -->
                <div x-show="!hasPdf" class="text-center max-w-sm space-y-3 py-12">
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm border border-slate-200 flex items-center justify-center mx-auto text-[#4B6A95]">
                        <i data-lucide="id-card" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-800">Aucun modèle PDF chargé</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Importez votre template PDF dans la colonne de gauche ou testez instantanément avec le modèle officiel <strong>Badge PFPT 2026</strong> et vos 45 participants.
                    </p>
                    <button @click="loadPfptSample()" type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#4B6A95] hover:bg-[#354e70] text-white text-xs font-bold shadow-sm transition cursor-pointer">
                        <i data-lucide="wand-2" class="w-4 h-4"></i>
                        <span>Tester avec le Badge PFPT 2026</span>
                    </button>
                </div>

                <!-- Conteneur du PDF + Zones superposées -->
                <div x-show="hasPdf"
                     x-ref="stageWrapper"
                     class="relative bg-white shadow-2xl ring-1 ring-slate-300/80 select-none"
                     :style="'width:' + canvasCssWidth + 'px; height:' + canvasCssHeight + 'px;'"
                     @mousedown.self="selectedFieldId = null">

                    <!-- Rendu PDF -->
                    <canvas x-ref="pdfCanvas" class="block w-full h-full pointer-events-none"></canvas>

                    <!-- Guides de centrage -->
                    <div x-show="showGuides" class="guide-center-v"></div>
                    <div x-show="showGuides" class="guide-center-h"></div>

                    <!-- Zones de texte & QR superposées -->
                    <template x-for="field in fields" :key="field.id">
                        <div class="field-box flex items-center"
                             :class="{ 'selected': selectedFieldId === field.id }"
                             :style="getFieldBoxStyle(field)"
                             @mousedown.stop="startDrag($event, field)">

                            <!-- Étiquette du champ -->
                            <span x-show="selectedFieldId === field.id" class="field-tag"
                                  x-text="field.type === 'qrcode' ? ('QR: ' + field.column) : (field.type === 'static' ? 'Texte fixe' : field.column)"></span>

                            <!-- Contenu Texte -->
                            <template x-if="field.type !== 'qrcode'">
                                <div class="w-full overflow-hidden leading-tight px-1"
                                     :style="getFieldTextStyle(field)"
                                     x-text="getFieldDisplayValue(field)"></div>
                            </template>

                            <!-- Contenu QR Code -->
                            <template x-if="field.type === 'qrcode'">
                                <div class="w-full h-full flex items-center justify-center bg-white/95 p-1">
                                    <img :src="qrPreviews[field.id] || ''" class="w-full h-full object-contain pointer-events-none" alt="QR">
                                </div>
                            </template>

                            <!-- Poignées de redimensionnement -->
                            <template x-if="selectedFieldId === field.id">
                                <div>
                                    <div class="resize-handle e"  @mousedown.stop="startResize($event, field, 'e')"></div>
                                    <div class="resize-handle w"  @mousedown.stop="startResize($event, field, 'w')"></div>
                                    <div class="resize-handle s"  @mousedown.stop="startResize($event, field, 's')"></div>
                                    <div class="resize-handle se" @mousedown.stop="startResize($event, field, 'se')"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Aperçu tabulaire interactif avec sélection par cases à cocher -->
            <div x-show="rows.length > 0" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200/80 flex flex-wrap items-center justify-between gap-2">
                    <span class="text-xs font-extrabold text-slate-700">
                        Sélection (<span class="text-[#4B6A95]" x-text="selectedFilteredRows.length"></span> / <span x-text="filteredRows.length"></span> cochés)
                    </span>
                    <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                        <button @click="selectAllFiltered()" type="button"
                                class="px-2.5 py-1 rounded-lg bg-white hover:bg-blue-50 border border-slate-200 text-slate-700 font-bold transition cursor-pointer">
                            Tout cocher
                        </button>
                        <button @click="deselectAll()" type="button"
                                class="px-2.5 py-1 rounded-lg bg-white hover:bg-rose-50 border border-slate-200 text-slate-700 font-bold transition cursor-pointer">
                            Tout décocher
                        </button>
                        <button @click="selectOnlyCurrent()" type="button"
                                class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-[#4B6A95] text-[#4B6A95] hover:text-white border border-blue-200 font-bold transition cursor-pointer">
                            Uniquement l'aperçu (1)
                        </button>
                    </div>
                </div>
                <div class="max-h-56 overflow-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 sticky top-0 text-[11px] text-slate-500 uppercase z-10">
                            <tr>
                                <th class="px-3 py-2 border-b border-slate-200 w-9 text-center">
                                    <input type="checkbox"
                                           :checked="allFilteredSelected"
                                           @change="toggleAllFiltered()"
                                           class="rounded text-[#4B6A95] cursor-pointer"
                                           title="Tout cocher / décocher">
                                </th>
                                <th class="px-2 py-2 border-b border-slate-200 w-8">#</th>
                                <template x-for="col in columns.slice(0, 4)" :key="col">
                                    <th class="px-3 py-2 border-b border-slate-200 font-bold" x-text="col"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(entry, idx) in filteredEntries" :key="entry.origIdx">
                                <tr @click="previewIndex = idx"
                                    class="cursor-pointer transition"
                                    :class="[
                                        previewIndex === idx ? 'bg-blue-50 text-[#4B6A95] font-bold' : 'hover:bg-slate-50 text-slate-700',
                                        !isRowSelected(entry.origIdx) ? 'opacity-50' : ''
                                    ]">
                                    <td class="px-3 py-1.5 text-center" @click.stop>
                                        <input type="checkbox"
                                               :checked="isRowSelected(entry.origIdx)"
                                               @change="toggleRow(entry.origIdx)"
                                               class="rounded text-[#4B6A95] cursor-pointer">
                                    </td>
                                    <td class="px-2 py-1.5 text-slate-400" x-text="idx + 1"></td>
                                    <template x-for="col in columns.slice(0, 4)" :key="col">
                                        <td class="px-3 py-1.5 max-w-[175px] truncate" x-text="entry.row[col] ?? ''"></td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- =========================================================
             COLONNE DROITE (3/12) : PROPRIÉTÉS DE LA ZONE SÉLECTIONNÉE
             ========================================================= -->
        <div class="lg:col-span-3 space-y-4">
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i data-lucide="sliders" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Propriétés de la zone</span>
                    </h2>
                    <span class="text-[11px] font-bold text-slate-400" x-text="fields.length + ' zone(s)'"></span>
                </div>

                <!-- Aucune zone sélectionnée -->
                <div x-show="!selectedField" class="text-center py-6 space-y-2">
                    <i data-lucide="mouse-pointer-click" class="w-7 h-7 text-slate-300 mx-auto"></i>
                    <p class="text-xs text-slate-500">Cliquez sur une zone sur le modèle pour modifier sa police, couleur, alignement ou position.</p>
                </div>

                <!-- Formulaire de la zone sélectionnée -->
                <template x-if="selectedField">
                    <div class="space-y-3.5 text-xs">
                        <!-- Source / Colonne -->
                        <div x-show="selectedField.type !== 'static'">
                            <label class="block font-bold text-slate-600 mb-1" x-text="selectedField.type === 'qrcode' ? 'Colonne encodée dans le QR' : 'Colonne Excel liée'"></label>
                            <select x-model="selectedField.column" @change="refreshQrCodes()"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 bg-white text-slate-800 font-semibold focus:border-[#4B6A95] focus:outline-none">
                                <template x-for="col in columns" :key="col">
                                    <option :value="col" x-text="col"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Texte fixe -->
                        <div x-show="selectedField.type === 'static'">
                            <label class="block font-bold text-slate-600 mb-1">Texte fixe à afficher</label>
                            <input type="text" x-model="selectedField.staticText"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 font-semibold focus:border-[#4B6A95] focus:outline-none">
                        </div>

                        <!-- Position & Centrage rapide -->
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-600">Position & Taille (%)</span>
                                <button @click="centerSelectedField()" type="button"
                                        class="text-[11px] font-bold text-[#4B6A95] hover:underline cursor-pointer">
                                    Centrer horizontalement
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-slate-400 font-bold">X (%)</label>
                                    <input type="number" step="0.5" x-model.number="selectedField.xPct"
                                           class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-mono text-xs">
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-400 font-bold">Y (%)</label>
                                    <input type="number" step="0.5" x-model.number="selectedField.yPct"
                                           class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-mono text-xs">
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-400 font-bold">Largeur (%)</label>
                                    <input type="number" step="1" min="5" max="100" x-model.number="selectedField.wPct"
                                           class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-mono text-xs">
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-400 font-bold">Hauteur (%)</label>
                                    <input type="number" step="1" min="3" max="100" x-model.number="selectedField.hPct"
                                           class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-mono text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Typographie (uniquement si texte) -->
                        <template x-if="selectedField.type !== 'qrcode'">
                            <div class="space-y-3 pt-2 border-t border-slate-100">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Police</label>
                                        <select x-model="selectedField.fontFamily"
                                                class="w-full rounded-lg border border-slate-200 px-2 py-1.5 bg-white text-xs font-semibold">
                                            <option value="Helvetica">Sans-Serif (Helvetica)</option>
                                            <option value="Times">Serif (Times)</option>
                                            <option value="Courier">Monospace (Courier)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Taille (pt PDF)</label>
                                        <input type="number" min="5" max="72" step="0.5" x-model.number="selectedField.fontSize"
                                               class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-bold">
                                    </div>
                                </div>

                                <!-- Style & Alignement -->
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1">
                                        <button @click="selectedField.bold = !selectedField.bold" type="button"
                                                class="w-8 h-8 rounded-lg border text-xs font-extrabold transition cursor-pointer"
                                                :class="selectedField.bold ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200'">B</button>
                                        <button @click="selectedField.italic = !selectedField.italic" type="button"
                                                class="w-8 h-8 rounded-lg border text-xs italic font-serif transition cursor-pointer"
                                                :class="selectedField.italic ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200'">I</button>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <button @click="selectedField.align = 'left'" type="button"
                                                class="w-8 h-8 rounded-lg border flex items-center justify-center transition cursor-pointer"
                                                :class="selectedField.align === 'left' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200'">
                                            <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button @click="selectedField.align = 'center'" type="button"
                                                class="w-8 h-8 rounded-lg border flex items-center justify-center transition cursor-pointer"
                                                :class="selectedField.align === 'center' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200'">
                                            <i data-lucide="align-center" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button @click="selectedField.align = 'right'" type="button"
                                                class="w-8 h-8 rounded-lg border flex items-center justify-center transition cursor-pointer"
                                                :class="selectedField.align === 'right' ? 'bg-[#4B6A95] text-white border-[#4B6A95]' : 'bg-white text-slate-600 border-slate-200'">
                                            <i data-lucide="align-right" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Casse & Couleur -->
                                <div class="grid grid-cols-2 gap-2 items-end">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Casse</label>
                                        <select x-model="selectedField.textTransform"
                                                class="w-full rounded-lg border border-slate-200 px-2 py-1.5 bg-white text-xs">
                                            <option value="none">Tel quel</option>
                                            <option value="uppercase">MAJUSCULES</option>
                                            <option value="lowercase">minuscules</option>
                                            <option value="capitalize">Capitale</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Couleur texte</label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="color" x-model="selectedField.color" class="w-8 h-8 rounded border border-slate-200 cursor-pointer p-0.5">
                                            <input type="text" x-model="selectedField.color" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 font-mono text-[11px]">
                                        </div>
                                    </div>
                                </div>

                                <!-- Palettes rapides PFPT -->
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] text-slate-400 font-bold">Couleurs PFPT :</span>
                                    <button @click="selectedField.color = '#3b5998'" type="button" class="w-5 h-5 rounded-full bg-[#3b5998] border border-slate-300 cursor-pointer" title="Bleu PFPT"></button>
                                    <button @click="selectedField.color = '#1e3a5f'" type="button" class="w-5 h-5 rounded-full bg-[#1e3a5f] border border-slate-300 cursor-pointer" title="Bleu Nuit"></button>
                                    <button @click="selectedField.color = '#0f172a'" type="button" class="w-5 h-5 rounded-full bg-[#0f172a] border border-slate-300 cursor-pointer" title="Noir ardoise"></button>
                                    <button @click="selectedField.color = '#ffffff'" type="button" class="w-5 h-5 rounded-full bg-white border border-slate-300 cursor-pointer" title="Blanc"></button>
                                </div>

                                <!-- Option Magique : Masquer le texte d'origine du PDF -->
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="selectedField.maskBackground" class="rounded text-[#4B6A95]">
                                        <span class="font-bold text-slate-700 text-[11px]">Masquer le texte d'origine du PDF</span>
                                    </label>
                                    <div x-show="selectedField.maskBackground" class="flex items-center gap-2 pl-5">
                                        <input type="color" x-model="selectedField.backgroundColor" class="w-6 h-6 rounded border border-slate-200 cursor-pointer">
                                        <span class="text-[10px] text-slate-500">Couleur du fond de masquage (ex: #f6f8fc ou #ffffff)</span>
                                    </div>
                                </div>

                                <!-- Auto-shrink -->
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="selectedField.autoShrink" class="rounded text-[#4B6A95]">
                                    <span class="text-[11px] font-semibold text-slate-600">Réduire la police auto si texte long</span>
                                </label>
                            </div>
                        </template>

                        <!-- Actions Zone -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button @click="duplicateSelectedField()" type="button"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition cursor-pointer">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                <span>Dupliquer</span>
                            </button>
                            <button @click="removeSelectedField()" type="button"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition cursor-pointer">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Liste des calques / zones actives -->
            <div x-show="fields.length > 0" class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-2">
                <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Zones actives sur le design</h3>
                <div class="space-y-1">
                    <template x-for="f in fields" :key="f.id">
                        <div @click="selectedFieldId = f.id"
                             class="flex items-center justify-between px-2.5 py-2 rounded-xl cursor-pointer transition text-xs"
                             :class="selectedFieldId === f.id ? 'bg-[#4B6A95] text-white font-bold' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold'">
                            <span class="truncate" x-text="f.type === 'qrcode' ? ('QR Code (' + f.column + ')') : (f.type === 'static' ? f.staticText : f.column)"></span>
                            <span class="text-[10px] opacity-75" x-text="f.type === 'qrcode' ? 'QR' : (f.fontSize + 'pt')"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SAUVEGARDE GABARIT -->
    <div x-show="saveModalOpen" x-transition style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div @click.outside="saveModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="text-lg font-extrabold text-slate-900">Enregistrer ce gabarit</h3>
            <p class="text-xs text-slate-500">Sauvegardez ce modèle PDF et le placement exact de ses zones pour vos prochaines éditions.</p>
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom du gabarit</label>
                    <input type="text" x-model="presetForm.name" placeholder="Ex: Badge Séminariste PFPT 2026"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#4B6A95] focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catégorie</label>
                    <select x-model="presetForm.type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 bg-white">
                        <option value="badge">Badge d'identification</option>
                        <option value="carte">Carte de membre / institutionnelle</option>
                        <option value="attestation">Attestation / Certificat</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button @click="saveModalOpen = false" type="button" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold cursor-pointer">Annuler</button>
                <button @click="confirmSavePreset()" type="button" class="px-4 py-2 rounded-xl bg-[#4B6A95] hover:bg-[#354e70] text-white text-xs font-bold cursor-pointer">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Bibliothèques Studio PDF & Excel (chargées en parallèle sans bloquer l'affichage) -->
<script defer src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
}

// Stockage non-réactif hors Proxy Alpine.js (indispensable pour PDF.js et pdf-lib)
const studioRaw = {
    pdfBytes: null,
    pdfDocJs: null,
    pdfFileObj: null,
};

function cardStudio() {
    return {
        // PDF state (réactif uniquement pour l'UI)
        hasPdf: false,
        pdfSourceUrl: null,
        pdfFileName: '',
        pdfMeta: { widthPt: 0, heightPt: 0, widthMm: 0, heightMm: 0 },
        outputPreset: 'original',
        outputOrientation: 'auto',
        outputWidthMm: 70,
        outputHeightMm: 100,
        canvasCssWidth: 420,
        canvasCssHeight: 600,
        zoom: 1.0,
        showGuides: true,

        // Data state
        excelFileName: '',
        columns: [],
        rows: [],
        selectedIds: [],
        rangeFrom: 1,
        rangeTo: 10,
        rowSearch: '',
        previewIndex: 0,

        // Fields / Zones
        fields: [],
        selectedFieldId: null,
        qrPreviews: {},

        // Presets
        presets: @json($presets),
        saveModalOpen: false,
        presetForm: { name: '', type: 'badge' },

        // Generation state
        generating: false,
        progressCurrent: 0,
        progressTotal: 0,
        progressLabel: '',

        // Drag/Resize internal
        dragState: null,

        init() {
            window.addEventListener('mousemove', (e) => this.onMouseMove(e));
            window.addEventListener('mouseup', () => this.onMouseUp());
            this.$watch('previewIndex', () => this.refreshQrCodes());
            this.$watch('rowSearch', () => { this.previewIndex = 0; });
        },

        get filteredEntries() {
            const q = this.rowSearch.toLowerCase().trim();
            const entries = [];
            for (let i = 0; i < this.rows.length; i++) {
                const r = this.rows[i];
                if (!q || Object.values(r).some(v => String(v || '').toLowerCase().includes(q))) {
                    entries.push({ origIdx: i, row: r });
                }
            }
            return entries;
        },

        get filteredRows() {
            return this.filteredEntries.map(e => e.row);
        },

        get selectedFilteredRows() {
            const set = new Set(this.selectedIds);
            return this.filteredEntries.filter(e => set.has(e.origIdx)).map(e => e.row);
        },

        get allFilteredSelected() {
            if (this.filteredEntries.length === 0) return false;
            const set = new Set(this.selectedIds);
            return this.filteredEntries.every(e => set.has(e.origIdx));
        },

        isRowSelected(origIdx) {
            return this.selectedIds.includes(origIdx);
        },

        toggleRow(origIdx) {
            const pos = this.selectedIds.indexOf(origIdx);
            if (pos >= 0) this.selectedIds.splice(pos, 1);
            else this.selectedIds.push(origIdx);
        },

        toggleAllFiltered() {
            if (this.allFilteredSelected) {
                const removeSet = new Set(this.filteredEntries.map(e => e.origIdx));
                this.selectedIds = this.selectedIds.filter(id => !removeSet.has(id));
            } else {
                this.selectAllFiltered();
            }
        },

        selectAllFiltered() {
            const set = new Set(this.selectedIds);
            this.filteredEntries.forEach(e => set.add(e.origIdx));
            this.selectedIds = Array.from(set);
        },

        deselectAll() {
            this.selectedIds = [];
        },

        selectRange() {
            const total = this.filteredEntries.length;
            if (total === 0) return;
            const from = Math.max(1, Math.min(total, Number(this.rangeFrom) || 1));
            const to = Math.max(from, Math.min(total, Number(this.rangeTo) || total));
            this.rangeFrom = from;
            this.rangeTo = to;
            this.selectedIds = this.filteredEntries.slice(from - 1, to).map(e => e.origIdx);
            this.previewIndex = from - 1;
        },

        selectOnlyCurrent() {
            const entry = this.filteredEntries[this.previewIndex];
            if (!entry) return;
            this.selectedIds = [entry.origIdx];
        },

        get currentRow() {
            return this.filteredRows[this.previewIndex] || this.rows[0] || {};
        },

        get selectedField() {
            return this.fields.find(f => f.id === this.selectedFieldId) || null;
        },

        getRowSummary(row) {
            const vals = Object.values(row).filter(v => v && String(v).trim() !== '');
            if (row['Nom et prénom']) {
                return row['Nom et prénom'] + (row['Pays'] ? ' (' + row['Pays'] + ')' : '');
            }
            return vals.slice(0, 2).join(' — ') || 'Ligne vide';
        },

        // ============================================================
        // CHARGEMENT EXEMPLE PFPT 2026 (BADGE 70x100mm + 45 CANDIDATS)
        // ============================================================
        async loadPfptSample() {
            try {
                // 1. Charger le template PDF officiel
                const pdfUrl = @json($samplePdfUrl);
                const pdfResp = await fetch(pdfUrl);
                if (!pdfResp.ok) throw new Error('Modèle PDF introuvable');
                const arrayBuf = await pdfResp.arrayBuffer();
                studioRaw.pdfFileObj = null;
                this.pdfSourceUrl = pdfUrl;
                this.pdfFileName = 'badge_pfpt_2026_template.pdf';
                await this.loadPdfBytes(new Uint8Array(arrayBuf));

                // 2. Charger la liste CSV fournie
                const csvUrl = @json($sampleCsvUrl);
                const csvResp = await fetch(csvUrl);
                if (!csvResp.ok) throw new Error('Fichier participants introuvable');
                const csvBuf = await csvResp.arrayBuffer();
                this.excelFileName = 'participants_pfpt_2026.csv';
                this.parseWorkbookBuffer(csvBuf);

                // 3. Pré-calibrer les 3 zones exactes sur le Badge PFPT 2026
                this.fields = [
                    {
                        id: 'f-nom',
                        type: 'text',
                        column: 'Nom et prénom',
                        xPct: 6,
                        yPct: 28,
                        wPct: 88,
                        hPct: 12,
                        fontFamily: 'Helvetica',
                        fontSize: 13,
                        bold: true,
                        italic: false,
                        align: 'center',
                        textTransform: 'uppercase',
                        color: '#3b5998',
                        maskBackground: true,
                        backgroundColor: '#f8fafc',
                        autoShrink: true,
                    },
                    {
                        id: 'f-theme',
                        type: 'text',
                        column: 'Thème',
                        xPct: 8,
                        yPct: 62.5,
                        wPct: 84,
                        hPct: 13,
                        fontFamily: 'Helvetica',
                        fontSize: 8.5,
                        bold: false,
                        italic: false,
                        align: 'center',
                        textTransform: 'none',
                        color: '#2c4a70',
                        maskBackground: true,
                        backgroundColor: '#f8fafc',
                        autoShrink: true,
                    },
                    {
                        id: 'f-pays',
                        type: 'text',
                        column: 'Pays',
                        xPct: 12,
                        yPct: 86,
                        wPct: 76,
                        hPct: 6.5,
                        fontFamily: 'Helvetica',
                        fontSize: 10.5,
                        bold: true,
                        italic: false,
                        align: 'center',
                        textTransform: 'uppercase',
                        color: '#1b365d',
                        maskBackground: true,
                        backgroundColor: '#f6f8fc',
                        autoShrink: true,
                    }
                ];
                this.selectedFieldId = 'f-nom';
                this.previewIndex = 0;
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            } catch (err) {
                alert('Erreur chargement exemple : ' + err.message);
            }
        },

        // ============================================================
        // UPLOAD PDF & RENDU PDF.JS
        // ============================================================
        async handlePdfUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            try {
                studioRaw.pdfFileObj = file;
                this.pdfSourceUrl = null;
                this.pdfFileName = file.name;
                const buf = await file.arrayBuffer();
                await this.loadPdfBytes(new Uint8Array(buf));
            } catch (err) {
                console.error('Erreur import PDF:', err);
                alert('Impossible d\'afficher le modèle PDF : ' + (err.message || err));
            }
        },

        async loadPdfBytes(uint8) {
            if (typeof pdfjsLib === 'undefined') {
                throw new Error('La bibliothèque PDF.js n\'est pas chargée (vérifiez votre connexion Internet).');
            }
            if (!pdfjsLib.GlobalWorkerOptions.workerSrc) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
            }
            studioRaw.pdfBytes = uint8.slice();

            // Cloner le buffer car PDF.js détache l'ArrayBuffer dans son worker
            let doc;
            try {
                doc = await pdfjsLib.getDocument({ data: uint8.slice() }).promise;
            } catch (workerErr) {
                console.warn('PDF.js worker fallback (mode sans worker):', workerErr);
                doc = await pdfjsLib.getDocument({ data: uint8.slice(), disableWorker: true }).promise;
            }

            studioRaw.pdfDocJs = doc;
            const page = await studioRaw.pdfDocJs.getPage(1);
            const vp1 = page.getViewport({ scale: 1 });
            this.pdfMeta = {
                widthPt: +vp1.width.toFixed(2),
                heightPt: +vp1.height.toFixed(2),
                widthMm: +(vp1.width * 25.4 / 72).toFixed(1),
                heightMm: +(vp1.height * 25.4 / 72).toFixed(1),
            };
            if (this.outputPreset === 'original') {
                this.outputWidthMm = this.pdfMeta.widthMm;
                this.outputHeightMm = this.pdfMeta.heightMm;
            }
            this.hasPdf = true;
            await this.$nextTick();
            await this.renderPdfCanvas();
        },

        async renderPdfCanvas() {
            if (!studioRaw.pdfDocJs) return;
            const page = await studioRaw.pdfDocJs.getPage(1);
            const vp1 = page.getViewport({ scale: 1 });

            // Adapter la taille d'affichage de base pour tenir confortablement dans le studio
            const maxBaseW = 540;
            const maxBaseH = 620;
            const baseScale = Math.min(maxBaseW / vp1.width, maxBaseH / vp1.height);
            const displayScale = baseScale * this.zoom;

            this.canvasCssWidth = Math.round(vp1.width * displayScale);
            this.canvasCssHeight = Math.round(vp1.height * displayScale);

            const dpr = Math.min(window.devicePixelRatio || 1, 2) * 1.5;
            const renderViewport = page.getViewport({ scale: displayScale * dpr });

            const canvas = this.$refs.pdfCanvas;
            if (!canvas) return;
            canvas.width = renderViewport.width;
            canvas.height = renderViewport.height;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            await page.render({ canvasContext: ctx, viewport: renderViewport }).promise;
        },

        // ============================================================
        // UPLOAD EXCEL / CSV & IMPORT SÉMINAIRE BDD
        // ============================================================
        async handleExcelUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.excelFileName = file.name;
            const buf = await file.arrayBuffer();
            this.parseWorkbookBuffer(buf);
        },

        parseWorkbookBuffer(arrayBuffer) {
            const wb = XLSX.read(arrayBuffer, { type: 'array', codepage: 65001 });
            const firstSheet = wb.Sheets[wb.SheetNames[0]];
            const jsonRows = XLSX.utils.sheet_to_json(firstSheet, { defval: '' });

            // Filtrer les lignes de sous-titres vides si un CSV contient des sections
            const cleaned = jsonRows.filter(row => {
                const values = Object.values(row).map(v => String(v || '').trim()).filter(Boolean);
                return values.length >= 2;
            });

            if (cleaned.length === 0) {
                alert('Aucune ligne de données valide trouvée dans ce fichier.');
                return;
            }

            this.columns = Object.keys(cleaned[0]).filter(c => !c.startsWith('__EMPTY'));
            this.rows = cleaned;
            this.selectedIds = cleaned.map((_, i) => i);
            this.rangeFrom = 1;
            this.rangeTo = cleaned.length;
            this.previewIndex = 0;
            this.refreshQrCodes();
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        async loadFromSeminar(seminarId) {
            if (!seminarId) return;
            try {
                const url = "{{ url('admin/badges-generator/seminar') }}/" + seminarId;
                const resp = await fetch(url);
                const data = await resp.json();
                if (!data.rows || data.rows.length === 0) {
                    alert('Aucun candidat validé trouvé pour ce séminaire.');
                    return;
                }
                this.columns = data.columns;
                this.rows = data.rows;
                this.selectedIds = data.rows.map((_, i) => i);
                this.rangeFrom = 1;
                this.rangeTo = data.rows.length;
                this.excelFileName = 'Séminaire : ' + data.seminar;
                this.previewIndex = 0;
                this.refreshQrCodes();
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            } catch (e) {
                alert('Erreur de chargement du séminaire : ' + e.message);
            }
        },

        // ============================================================
        // GESTION DES ZONES (AJOUT, DRAG, RESIZE, CLAVIER)
        // ============================================================
        addField(type, column) {
            const id = 'f-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
            const isQr = type === 'qrcode';
            const field = {
                id,
                type,
                column: column || '',
                staticText: type === 'static' ? 'Texte personnalisé' : '',
                xPct: isQr ? 38 : 10,
                yPct: 40 + (this.fields.length * 5) % 40,
                wPct: isQr ? 24 : 80,
                hPct: isQr ? 17 : 10,
                fontFamily: 'Helvetica',
                fontSize: 11,
                bold: true,
                italic: false,
                align: 'center',
                textTransform: 'none',
                color: '#1e3a5f',
                maskBackground: false,
                backgroundColor: '#ffffff',
                autoShrink: true,
            };
            this.fields.push(field);
            this.selectedFieldId = id;
            if (isQr) this.refreshQrCodes();
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        duplicateSelectedField() {
            if (!this.selectedField) return;
            const clone = JSON.parse(JSON.stringify(this.selectedField));
            clone.id = 'f-' + Date.now();
            clone.yPct = Math.min(90, +(clone.yPct + 5).toFixed(1));
            this.fields.push(clone);
            this.selectedFieldId = clone.id;
            if (clone.type === 'qrcode') this.refreshQrCodes();
        },

        removeSelectedField() {
            if (!this.selectedFieldId) return;
            this.fields = this.fields.filter(f => f.id !== this.selectedFieldId);
            this.selectedFieldId = this.fields[0]?.id || null;
        },

        centerSelectedField() {
            if (!this.selectedField) return;
            this.selectedField.xPct = +((100 - this.selectedField.wPct) / 2).toFixed(2);
        },

        startDrag(e, field) {
            this.selectedFieldId = field.id;
            this.dragState = {
                mode: 'drag',
                field,
                startX: e.clientX,
                startY: e.clientY,
                origX: field.xPct,
                origY: field.yPct,
            };
        },

        startResize(e, field, dir) {
            this.selectedFieldId = field.id;
            this.dragState = {
                mode: 'resize',
                dir,
                field,
                startX: e.clientX,
                startY: e.clientY,
                origX: field.xPct,
                origY: field.yPct,
                origW: field.wPct,
                origH: field.hPct,
            };
        },

        onMouseMove(e) {
            if (!this.dragState) return;
            const dxPct = ((e.clientX - this.dragState.startX) / this.canvasCssWidth) * 100;
            const dyPct = ((e.clientY - this.dragState.startY) / this.canvasCssHeight) * 100;
            const f = this.dragState.field;

            if (this.dragState.mode === 'drag') {
                f.xPct = +Math.max(0, Math.min(100 - f.wPct, this.dragState.origX + dxPct)).toFixed(2);
                f.yPct = +Math.max(0, Math.min(100 - f.hPct, this.dragState.origY + dyPct)).toFixed(2);
            } else if (this.dragState.mode === 'resize') {
                const dir = this.dragState.dir;
                if (dir.includes('e')) {
                    f.wPct = +Math.max(5, Math.min(100 - f.xPct, this.dragState.origW + dxPct)).toFixed(2);
                }
                if (dir.includes('w')) {
                    const newX = Math.max(0, Math.min(this.dragState.origX + this.dragState.origW - 5, this.dragState.origX + dxPct));
                    f.wPct = +(this.dragState.origW + (this.dragState.origX - newX)).toFixed(2);
                    f.xPct = +newX.toFixed(2);
                }
                if (dir.includes('s')) {
                    f.hPct = +Math.max(3, Math.min(100 - f.yPct, this.dragState.origH + dyPct)).toFixed(2);
                }
            }
        },

        onMouseUp() {
            this.dragState = null;
        },

        handleKeydown(e) {
            if (!this.selectedField) return;
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) return;
            const step = e.shiftKey ? 2 : 0.4;
            if (e.key === 'ArrowLeft')  { e.preventDefault(); this.selectedField.xPct = +Math.max(0, this.selectedField.xPct - step).toFixed(2); }
            if (e.key === 'ArrowRight') { e.preventDefault(); this.selectedField.xPct = +Math.min(100 - this.selectedField.wPct, this.selectedField.xPct + step).toFixed(2); }
            if (e.key === 'ArrowUp')    { e.preventDefault(); this.selectedField.yPct = +Math.max(0, this.selectedField.yPct - step).toFixed(2); }
            if (e.key === 'ArrowDown')  { e.preventDefault(); this.selectedField.yPct = +Math.min(100 - this.selectedField.hPct, this.selectedField.yPct + step).toFixed(2); }
            if (e.key === 'Delete')     { this.removeSelectedField(); }
        },

        prevRow() { if (this.previewIndex > 0) this.previewIndex--; },
        nextRow() { if (this.previewIndex < this.filteredRows.length - 1) this.previewIndex++; },

        // ============================================================
        // FORMATAGE & AFFICHAGE TEMPS RÉEL SUR LE CANVAS
        // ============================================================
        formatValue(field, row) {
            let raw = field.type === 'static' ? (field.staticText || '') : String(row[field.column] ?? '');
            raw = raw.trim();
            if (field.textTransform === 'uppercase') return raw.toUpperCase();
            if (field.textTransform === 'lowercase') return raw.toLowerCase();
            if (field.textTransform === 'capitalize') {
                return raw.toLowerCase().replace(/(?:^|\s|['"-])\S/g, m => m.toUpperCase());
            }
            return raw;
        },

        getFieldDisplayValue(field) {
            const val = this.formatValue(field, this.currentRow);
            return val || '[' + (field.column || 'Texte') + ']';
        },

        getFieldBoxStyle(field) {
            const bg = field.maskBackground ? field.backgroundColor : 'transparent';
            return `left:${field.xPct}%; top:${field.yPct}%; width:${field.wPct}%; height:${field.hPct}%; background:${bg};`;
        },

        getFieldTextStyle(field) {
            const scaleRatio = this.pdfMeta.widthPt > 0 ? (this.canvasCssWidth / this.pdfMeta.widthPt) : 1;
            const cssFontSize = +(field.fontSize * scaleRatio).toFixed(1);
            const fam = field.fontFamily === 'Times' ? 'Georgia, serif' : (field.fontFamily === 'Courier' ? 'monospace' : 'Helvetica, Arial, sans-serif');
            return `font-family:${fam}; font-size:${cssFontSize}px; font-weight:${field.bold ? '700' : '400'}; font-style:${field.italic ? 'italic' : 'normal'}; text-align:${field.align}; color:${field.color};`;
        },

        async refreshQrCodes() {
            for (const f of this.fields) {
                if (f.type === 'qrcode') {
                    const txt = this.formatValue(f, this.currentRow) || 'PFPT-CFPT';
                    this.qrPreviews[f.id] = await QRCode.toDataURL(txt, { margin: 1, width: 200 });
                }
            }
        },

        // ============================================================
        // SAUVEGARDE & CHARGEMENT DES GABARITS (PRESETS)
        // ============================================================
        openSavePresetModal() {
            this.presetForm = { name: this.pdfFileName.replace(/\.pdf$/i, ''), type: 'badge' };
            this.saveModalOpen = true;
        },

        async confirmSavePreset() {
            if (!this.presetForm.name.trim()) return alert('Veuillez saisir un nom pour ce gabarit.');
            const fd = new FormData();
            fd.append('name', this.presetForm.name.trim());
            fd.append('type', this.presetForm.type);
            fd.append('fields', JSON.stringify(this.fields));
            if (studioRaw.pdfFileObj) {
                fd.append('pdf_file', studioRaw.pdfFileObj);
            } else if (this.pdfSourceUrl) {
                fd.append('existing_pdf_url', this.pdfSourceUrl);
            }

            const resp = await fetch("{{ route('admin.badges.save-preset') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd,
            });
            const data = await resp.json();
            if (data.success) {
                this.presets = data.presets;
                this.saveModalOpen = false;
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            }
        },

        async applyPreset(preset) {
            if (preset.pdf_url) {
                const resp = await fetch(preset.pdf_url);
                const buf = await resp.arrayBuffer();
                this.pdfSourceUrl = preset.pdf_url;
                studioRaw.pdfFileObj = null;
                this.pdfFileName = preset.name + '.pdf';
                await this.loadPdfBytes(new Uint8Array(buf));
            }
            this.fields = JSON.parse(JSON.stringify(preset.fields || []));
            this.selectedFieldId = this.fields[0]?.id || null;
            this.refreshQrCodes();
        },

        async deletePreset(id) {
            if (!confirm('Supprimer ce gabarit ?')) return;
            const resp = await fetch("{{ url('admin/badges-generator/presets') }}/" + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            const data = await resp.json();
            if (data.success) this.presets = data.presets;
        },

        // ============================================================
        // MOTEUR DE GÉNÉRATION PDF VECTORIEL (PDF-LIB)
        // ============================================================
        sanitizeWinAnsi(str) {
            return String(str || '')
                .replace(/[\u2018\u2019]/g, "'")
                .replace(/[\u201C\u201D]/g, '"')
                .replace(/[\u2013\u2014]/g, '-')
                .replace(/\u00A0/g, ' ')
                .replace(/[^\x20-\x7E\xA0-\xFF\u0152\u0153\u20AC]/g, '');
        },

        hexToRgb(hex) {
            const clean = (hex || '#000000').replace('#', '');
            const r = parseInt(clean.substring(0, 2), 16) / 255;
            const g = parseInt(clean.substring(2, 4), 16) / 255;
            const b = parseInt(clean.substring(4, 6), 16) / 255;
            return PDFLib.rgb(isNaN(r) ? 0 : r, isNaN(g) ? 0 : g, isNaN(b) ? 0 : b);
        },

        wrapLines(text, font, fontSize, maxWidth) {
            const words = text.split(/\s+/);
            const lines = [];
            let current = '';
            for (const word of words) {
                const test = current ? (current + ' ' + word) : word;
                if (font.widthOfTextAtSize(test, fontSize) <= maxWidth) {
                    current = test;
                } else {
                    if (current) lines.push(current);
                    current = word;
                }
            }
            if (current) lines.push(current);
            return lines;
        },

        async drawFieldsOnPage(pdfDoc, page, row, fontsCache) {
            const { width: pageW, height: pageH } = page.getSize();

            for (const f of this.fields) {
                const boxX = (f.xPct / 100) * pageW;
                const boxW = (f.wPct / 100) * pageW;
                const boxH = (f.hPct / 100) * pageH;
                const boxTopFromBottom = pageH - ((f.yPct / 100) * pageH);
                const boxBottomY = boxTopFromBottom - boxH;

                // 1. Masquage opaque optionnel (recouvre l'ancien placeholder du PDF)
                if (f.maskBackground && f.type !== 'qrcode') {
                    page.drawRectangle({
                        x: boxX,
                        y: boxBottomY,
                        width: boxW,
                        height: boxH,
                        color: this.hexToRgb(f.backgroundColor || '#ffffff'),
                    });
                }

                // 2. QR Code
                if (f.type === 'qrcode') {
                    const txt = this.formatValue(f, row) || 'PFPT-CFPT';
                    const dataUrl = await QRCode.toDataURL(txt, { margin: 1, width: 300 });
                    const pngBytes = await fetch(dataUrl).then(r => r.arrayBuffer());
                    const qrImg = await pdfDoc.embedPng(pngBytes);
                    const side = Math.min(boxW, boxH);
                    page.drawImage(qrImg, {
                        x: boxX + (boxW - side) / 2,
                        y: boxBottomY + (boxH - side) / 2,
                        width: side,
                        height: side,
                    });
                    continue;
                }

                // 3. Texte dynamique avec Word Wrap & Auto-Shrink
                const text = this.sanitizeWinAnsi(this.formatValue(f, row));
                if (!text) continue;

                const fontKey = `${f.fontFamily}-${f.bold ? 'B' : 'N'}-${f.italic ? 'I' : 'N'}`;
                if (!fontsCache[fontKey]) {
                    fontsCache[fontKey] = await pdfDoc.embedFont(this.resolveStandardFont(f));
                }
                const font = fontsCache[fontKey];

                let size = Number(f.fontSize) || 11;
                let lines = this.wrapLines(text, font, size, boxW - 4);
                const lineHeightFactor = 1.22;

                if (f.autoShrink) {
                    while (size > 5.5 && (lines.length * size * lineHeightFactor) > boxH) {
                        size -= 0.5;
                        lines = this.wrapLines(text, font, size, boxW - 4);
                    }
                }

                const totalTextHeight = lines.length * size * lineHeightFactor;
                const startY = boxBottomY + (boxH + totalTextHeight) / 2 - (size * 0.9);
                const color = this.hexToRgb(f.color);

                lines.forEach((line, i) => {
                    const lineW = font.widthOfTextAtSize(line, size);
                    let drawX = boxX + 2;
                    if (f.align === 'center') drawX = boxX + (boxW - lineW) / 2;
                    else if (f.align === 'right') drawX = boxX + boxW - lineW - 2;

                    const drawY = startY - (i * size * lineHeightFactor);
                    page.drawText(line, { x: drawX, y: drawY, size, font, color });
                });
            }
        },

        resolveStandardFont(f) {
            const SF = PDFLib.StandardFonts;
            if (f.fontFamily === 'Times') {
                if (f.bold && f.italic) return SF.TimesRomanBoldItalic;
                if (f.bold) return SF.TimesRomanBold;
                if (f.italic) return SF.TimesRomanItalic;
                return SF.TimesRoman;
            }
            if (f.fontFamily === 'Courier') {
                if (f.bold && f.italic) return SF.CourierBoldOblique;
                if (f.bold) return SF.CourierBold;
                if (f.italic) return SF.CourierOblique;
                return SF.Courier;
            }
            if (f.bold && f.italic) return SF.HelveticaBoldOblique;
            if (f.bold) return SF.HelveticaBold;
            if (f.italic) return SF.HelveticaOblique;
            return SF.Helvetica;
        },

        get effectiveOutputSize() {
            let wMm = this.outputPreset === 'original' ? (this.pdfMeta.widthMm || 70) : (Number(this.outputWidthMm) || 70);
            let hMm = this.outputPreset === 'original' ? (this.pdfMeta.heightMm || 100) : (Number(this.outputHeightMm) || 100);

            if (this.outputOrientation === 'portrait' && wMm > hMm) {
                [wMm, hMm] = [hMm, wMm];
            } else if (this.outputOrientation === 'landscape' && hMm > wMm) {
                [wMm, hMm] = [hMm, wMm];
            }

            return {
                wMm: +wMm.toFixed(1),
                hMm: +hMm.toFixed(1),
                wPt: +(wMm * 72 / 25.4).toFixed(2),
                hPt: +(hMm * 72 / 25.4).toFixed(2),
            };
        },

        applyOutputPreset() {
            const map = {
                original: [this.pdfMeta.widthMm || 70, this.pdfMeta.heightMm || 100],
                cr80: [85.6, 54],
                badge70: [70, 100],
                badge90: [90, 125],
                a5: [148, 210],
                a4: [210, 297],
            };
            if (map[this.outputPreset]) {
                let [w, h] = map[this.outputPreset];
                if (this.outputOrientation === 'portrait' && w > h) [w, h] = [h, w];
                if (this.outputOrientation === 'landscape' && h > w) [w, h] = [h, w];
                this.outputWidthMm = w;
                this.outputHeightMm = h;
            }
        },

        setOutputOrientation(orient) {
            this.outputOrientation = orient;
            if (this.outputPreset !== 'original') {
                if (orient === 'portrait' && this.outputWidthMm > this.outputHeightMm) {
                    [this.outputWidthMm, this.outputHeightMm] = [this.outputHeightMm, this.outputWidthMm];
                } else if (orient === 'landscape' && this.outputHeightMm > this.outputWidthMm) {
                    [this.outputWidthMm, this.outputHeightMm] = [this.outputHeightMm, this.outputWidthMm];
                }
            }
        },

        async createOutputPageFromTemplate(targetDoc, templateDoc) {
            const target = this.effectiveOutputSize;
            const isNative = this.outputPreset === 'original' && this.outputOrientation === 'auto';
            if (isNative) {
                const [copiedPage] = await targetDoc.copyPages(templateDoc, [0]);
                targetDoc.addPage(copiedPage);
                return copiedPage;
            }
            const page = targetDoc.addPage([target.wPt, target.hPt]);
            const embedded = await targetDoc.embedPage(templateDoc.getPage(0));
            page.drawPage(embedded, { x: 0, y: 0, width: target.wPt, height: target.hPt });
            return page;
        },

        async generateBatch(mode) {
            const targetRows = mode === 'single' ? [this.currentRow] : this.selectedFilteredRows;
            if (!studioRaw.pdfBytes || targetRows.length === 0) {
                alert('Veuillez cocher au moins un participant avant de générer.');
                return;
            }

            this.generating = true;
            this.progressCurrent = 0;
            this.progressTotal = targetRows.length;
            this.progressLabel = mode === 'zip'
                ? 'Génération des fichiers PDF individuels...'
                : (mode === 'a4sheet' ? 'Assemblage des planches d\'impression A4...' : (mode === 'single' ? 'Génération du document unique...' : 'Fusion du document PDF multipages...'));

            try {
                const templateDoc = await PDFLib.PDFDocument.load(studioRaw.pdfBytes);
                const outSize = this.effectiveOutputSize;

                if (mode === 'multipage' || mode === 'single') {
                    const outDoc = await PDFLib.PDFDocument.create();
                    const fontsCache = {};

                    for (let i = 0; i < targetRows.length; i++) {
                        const page = await this.createOutputPageFromTemplate(outDoc, templateDoc);
                        await this.drawFieldsOnPage(outDoc, page, targetRows[i], fontsCache);
                        this.progressCurrent = i + 1;
                    }

                    const outBytes = await outDoc.save();
                    let filename = `PFPT_Documents_${targetRows.length}.pdf`;
                    if (mode === 'single') {
                        const rawName = targetRows[0]['Nom et prénom'] || Object.values(targetRows[0])[0] || 'Document';
                        const safeName = String(rawName).normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9_-]+/g, '_').replace(/^_+|_+$/g, '');
                        filename = `${safeName}.pdf`;
                    }
                    this.downloadBlob(new Blob([outBytes], { type: 'application/pdf' }), filename);
                }
                else if (mode === 'a4sheet') {
                    const singleDoc = await PDFLib.PDFDocument.create();
                    const fontsCache = {};
                    for (let i = 0; i < targetRows.length; i++) {
                        const page = await this.createOutputPageFromTemplate(singleDoc, templateDoc);
                        await this.drawFieldsOnPage(singleDoc, page, targetRows[i], fontsCache);
                        this.progressCurrent = i + 1;
                    }
                    const singleBytes = await singleDoc.save();
                    const finishedDoc = await PDFLib.PDFDocument.load(singleBytes);

                    const sheetDoc = await PDFLib.PDFDocument.create();
                    // Choisir automatiquement A4 Paysage ou Portrait selon le meilleur rendement
                    const isLandscapeSheet = outSize.wPt > outSize.hPt;
                    const A4_W = isLandscapeSheet ? 841.89 : 595.28;
                    const A4_H = isLandscapeSheet ? 595.28 : 841.89;
                    const cardW = outSize.wPt;
                    const cardH = outSize.hPt;
                    const margin = 18;
                    const gap = 10;

                    const cols = Math.max(1, Math.floor((A4_W - margin * 2 + gap) / (cardW + gap)));
                    const rowsPerPage = Math.max(1, Math.floor((A4_H - margin * 2 + gap) / (cardH + gap)));
                    const perSheet = cols * rowsPerPage;

                    const totalGridW = cols * cardW + (cols - 1) * gap;
                    const totalGridH = rowsPerPage * cardH + (rowsPerPage - 1) * gap;
                    const offsetX = Math.max(0, (A4_W - totalGridW) / 2);
                    const offsetY = Math.max(0, (A4_H - totalGridH) / 2);

                    const embeddedPages = await sheetDoc.embedPdf(finishedDoc, finishedDoc.getPageIndices());

                    for (let i = 0; i < embeddedPages.length; i++) {
                        const idxInSheet = i % perSheet;
                        if (idxInSheet === 0) sheetDoc.addPage([A4_W, A4_H]);
                        const sheetPage = sheetDoc.getPages()[sheetDoc.getPageCount() - 1];

                        const col = idxInSheet % cols;
                        const rowIdx = Math.floor(idxInSheet / cols);
                        const x = offsetX + col * (cardW + gap);
                        const y = A4_H - offsetY - (rowIdx + 1) * cardH - rowIdx * gap;

                        sheetPage.drawPage(embeddedPages[i], { x, y, width: cardW, height: cardH });
                        sheetPage.drawRectangle({
                            x, y, width: cardW, height: cardH,
                            borderColor: PDFLib.rgb(0.75, 0.78, 0.82),
                            borderWidth: 0.5,
                        });
                    }

                    const sheetBytes = await sheetDoc.save();
                    this.downloadBlob(new Blob([sheetBytes], { type: 'application/pdf' }), 'PFPT_Planches_A4_Impression.pdf');
                }
                else if (mode === 'zip') {
                    const zip = new JSZip();
                    for (let i = 0; i < targetRows.length; i++) {
                        const oneDoc = await PDFLib.PDFDocument.create();
                        const page = await this.createOutputPageFromTemplate(oneDoc, templateDoc);
                        await this.drawFieldsOnPage(oneDoc, page, targetRows[i], {});
                        const bytes = await oneDoc.save();

                        const rawName = targetRows[i]['Nom et prénom'] || Object.values(targetRows[i])[0] || ('Document_' + (i + 1));
                        const safeName = String(rawName).normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9_-]+/g, '_').replace(/^_+|_+$/g, '');
                        zip.file(`${String(i + 1).padStart(2, '0')}_${safeName}.pdf`, bytes);
                        this.progressCurrent = i + 1;
                    }
                    const zipBlob = await zip.generateAsync({ type: 'blob' });
                    this.downloadBlob(zipBlob, 'PFPT_Documents_Individuels.zip');
                }
            } catch (err) {
                console.error(err);
                alert('Erreur pendant la génération : ' + err.message);
            } finally {
                this.generating = false;
            }
        },

        downloadBlob(blob, filename) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 5000);
        },
    };
}
</script>
@endpush
