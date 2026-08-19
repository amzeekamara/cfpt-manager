@props([
    'name' => 'items[]',
    'id' => null,
    'label' => null,
    'options' => [], // Array of ['id' => ..., 'label' => ...] or Eloquent collection
    'selected' => [], // Array of selected IDs
    'placeholder' => 'Sélectionner des éléments...',
    'emptyText' => 'Aucun élément disponible',
    'required' => false,
    'helpText' => null,
])

@php
    $id = $id ?? preg_replace('/[^a-zA-Z0-9_]/', '_', $name) . '_' . uniqid();
    
    // Normalize options into [['id' => '...', 'label' => '...']]
    $formattedOptions = [];
    foreach ($options as $opt) {
        if (is_object($opt)) {
            $optId = $opt->id ?? $opt->value ?? '';
            $optLabel = $opt->name ?? $opt->title ?? $opt->label ?? (string)$optId;
        } elseif (is_array($opt)) {
            $optId = $opt['id'] ?? $opt['value'] ?? '';
            $optLabel = $opt['name'] ?? $opt['title'] ?? $opt['label'] ?? (string)$optId;
        } else {
            $optId = $opt;
            $optLabel = (string)$opt;
        }
        $formattedOptions[] = [
            'id' => (string)$optId,
            'label' => (string)$optLabel,
        ];
    }

    $initialSelected = array_map('strval', is_array($selected) ? $selected : (is_null($selected) ? [] : [$selected]));
@endphp

<div class="space-y-1.5" 
     x-data="{
        open: false,
        search: '',
        selected: {{ json_encode($initialSelected) }},
        options: {{ json_encode($formattedOptions) }},
        get selectedOptions() {
            return this.options.filter(o => this.selected.includes(String(o.id)));
        },
        get filteredOptions() {
            if (!this.search.trim()) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },
        isSelected(id) {
            return this.selected.includes(String(id));
        },
        toggle(id) {
            id = String(id);
            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(i => i !== id);
            } else {
                this.selected.push(id);
            }
        },
        selectAll() {
            const visibleIds = this.filteredOptions.map(o => String(o.id));
            this.selected = Array.from(new Set([...this.selected, ...visibleIds]));
        },
        deselectAll() {
            const visibleIds = this.filteredOptions.map(o => String(o.id));
            this.selected = this.selected.filter(id => !visibleIds.includes(id));
        },
        remove(id) {
            this.selected = this.selected.filter(i => i !== String(id));
        }
     }"
     @click.outside="open = false"
     @keydown.escape.window="open = false">

    @if($label)
        <label for="{{ $id }}_btn" class="block text-sm font-bold text-slate-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <!-- Dropdown Trigger Button -->
    <div class="relative">
        <button type="button" 
                id="{{ $id }}_btn"
                @click="open = !open" 
                class="w-full min-h-[46px] px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-300 rounded-xl flex items-center justify-between text-left transition-all shadow-xs focus:ring-2 focus:ring-[#4B6A95]/30 focus:border-[#4B6A95] focus:bg-white cursor-pointer"
                :class="open ? 'ring-2 ring-[#4B6A95]/20 border-[#4B6A95] bg-white' : ''">
            
            <div class="flex flex-wrap items-center gap-1.5 flex-1 pr-2 overflow-hidden">
                <!-- Case 0 selected -->
                <template x-if="selected.length === 0">
                    <span class="text-slate-400 text-sm font-medium">{{ $placeholder }}</span>
                </template>

                <!-- Case <= 2 selected: show badged chips -->
                <template x-if="selected.length > 0 && selected.length <= 2">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <template x-for="opt in selectedOptions" :key="opt.id">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 border border-blue-200 text-[#4B6A95] font-semibold text-xs rounded-lg">
                                <span x-text="opt.label"></span>
                                <span @click.stop="remove(opt.id)" class="hover:text-red-600 transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </span>
                        </template>
                    </div>
                </template>

                <!-- Case > 2 selected: show summary count with preview -->
                <template x-if="selected.length > 2">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center px-2.5 py-1 bg-[#4B6A95] text-white text-xs font-bold rounded-lg shadow-xs" x-text="selected.length + ' sélectionnés'"></span>
                        <span class="text-xs text-slate-600 font-medium truncate max-w-[240px]" x-text="selectedOptions.map(o => o.label).join(', ')"></span>
                    </div>
                </template>
            </div>

            <!-- Chevron Icon -->
            <div class="flex items-center gap-1 text-slate-400">
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180 text-[#4B6A95]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </button>

        <!-- Dropdown Menu Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1 scale-98"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-1 scale-98"
             class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden"
             style="display: none;">
            
            <!-- Search & Quick Actions -->
            <div class="p-2.5 bg-slate-50 border-b border-slate-100 space-y-2">
                <div class="relative">
                    <input type="text" 
                           x-model="search" 
                           placeholder="Rechercher..." 
                           class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#4B6A95]/20 focus:border-[#4B6A95] focus:outline-none transition">
                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center justify-between px-1 text-[11px] text-slate-500 font-semibold">
                    <button type="button" @click="selectAll()" class="text-[#4B6A95] hover:underline cursor-pointer">
                        Tout cocher
                    </button>
                    <span class="text-slate-300">•</span>
                    <button type="button" @click="deselectAll()" class="text-slate-500 hover:text-red-600 hover:underline cursor-pointer">
                        Tout décocher
                    </button>
                </div>
            </div>

            <!-- Checkbox Items List -->
            <div class="max-h-56 overflow-y-auto p-1.5 divide-y divide-slate-50 scrollbar-thin">
                <template x-for="option in filteredOptions" :key="option.id">
                    <label class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition cursor-pointer select-none"
                           :class="isSelected(option.id) ? 'bg-blue-50/80 text-[#4B6A95] font-semibold' : 'text-slate-700 hover:bg-slate-100/70'">
                        
                        <input type="checkbox" 
                               name="{{ $name }}" 
                               :value="option.id" 
                               :checked="isSelected(option.id)" 
                               @change="toggle(option.id)" 
                               class="w-4 h-4 rounded border-slate-300 text-[#4B6A95] focus:ring-[#4B6A95] cursor-pointer">
                        
                        <span class="flex-1" x-text="option.label"></span>

                        <template x-if="isSelected(option.id)">
                            <svg class="w-4 h-4 text-[#4B6A95]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                    </label>
                </template>

                <template x-if="filteredOptions.length === 0">
                    <div class="py-6 text-center text-xs text-slate-400 font-medium">
                        Aucun résultat pour "<span x-text="search"></span>"
                    </div>
                </template>
            </div>
        </div>
    </div>

    @if($helpText)
        <p class="text-xs text-slate-500">{{ $helpText }}</p>
    @endif
</div>
