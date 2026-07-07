<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }} — PFPT CFPT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <meta name="description" content="{{ $form->description }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { headline: ['Manrope'], body: ['Inter'] },
                    colors: { primary: '#00355f', 'primary-dark': '#0f4c81' }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .form-card { box-shadow: 0 4px 24px rgba(0,53,95,0.08), 0 1px 4px rgba(0,53,95,0.05); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">

    {{-- Background decoration --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-80 h-80 rounded-full bg-indigo-100/30 blur-3xl"></div>
    </div>

    <div class="relative min-h-screen flex flex-col">

        {{-- Top bar --}}
        <div class="py-4 px-6 flex items-center gap-3 border-b border-slate-200/60 bg-white/70 backdrop-blur-sm">
            <div class="w-9 h-9 bg-gradient-to-br from-[#0f4c81] to-[#00355f] rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1">school</span>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-800 font-headline leading-tight">CFPT Saint-Jean de Dieu</div>
                <div class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">Portail PFPT</div>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex-1 flex items-start justify-center py-10 px-4">
            <div class="w-full max-w-2xl space-y-6">

                {{-- Form header card --}}
                <div class="bg-gradient-to-br from-[#0f4c81] to-[#00355f] rounded-2xl p-8 text-white form-card">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1">dynamic_form</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-headline font-bold mb-2 leading-tight">{{ $form->title }}</h1>
                            @if($form->description)
                            <p class="text-blue-100 text-sm leading-relaxed">{{ $form->description }}</p>
                            @endif
                            @if($form->seminar)
                            <div class="mt-3 inline-flex items-center gap-1.5 bg-white/15 rounded-lg px-3 py-1 text-xs font-semibold">
                                <span class="material-symbols-outlined text-[14px]">event</span>
                                {{ $form->seminar->title }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 pt-5 border-t border-white/20 flex items-center gap-2 text-blue-100 text-xs">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Les champs marqués d'un <span class="text-rose-300 font-bold mx-0.5">*</span> sont obligatoires.
                    </div>
                </div>

                {{-- Flash messages --}}
                @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4">
                    <div class="flex items-center gap-2 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined text-rose-500 text-[18px]">error</span>
                        Veuillez corriger les erreurs suivantes :
                    </div>
                    <ul class="text-sm space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Form card --}}
                <div class="bg-white rounded-2xl form-card overflow-hidden">
                    <form action="{{ route('public.form.store', $form->share_token) }}" method="POST" class="p-8 space-y-6">
                        @csrf

                        @foreach($form->fields_json ?? [] as $field)
                        @php
                            $key = $field['key'];
                            $label = $field['label'];
                            $required = $field['required'] ?? false;
                            $placeholder = $field['placeholder'] ?? '';
                            $type = $field['type'];
                            $dataSource = $field['data_source'] ?? '';
                            // Prefer resolved DB options over static options
                            $options = !empty($resolvedOptions[$key])
                                ? $resolvedOptions[$key]
                                : ($field['options'] ?? []);
                            $hasError = $errors->has('answers.' . $key);
                        @endphp

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700" for="field_{{ $key }}">
                                {{ $label }}
                                @if($required)<span class="text-rose-500 ml-0.5">*</span>@endif
                            </label>

                            @if($type === 'radio' && count($options) > 0)
                                <div class="flex flex-wrap gap-3">
                                    @foreach($options as $option)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio"
                                               name="answers[{{ $key }}]"
                                               value="{{ $option }}"
                                               {{ old('answers.' . $key) === $option ? 'checked' : '' }}
                                               {{ $required ? 'required' : '' }}
                                               class="w-4 h-4 text-[#00355f] border-slate-300 focus:ring-[#00355f]/30">
                                        <span class="text-sm text-slate-700 group-hover:text-[#00355f] transition-colors">{{ $option }}</span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif($type === 'checkbox' && count($options) > 0)
                                <div class="space-y-2">
                                    @foreach($options as $option)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox"
                                               name="answers[{{ $key }}][]"
                                               value="{{ $option }}"
                                               class="w-4 h-4 text-[#00355f] border-slate-300 rounded focus:ring-[#00355f]/30">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif($type === 'select')
                                <select name="answers[{{ $key }}]"
                                        id="field_{{ $key }}"
                                        {{ $required ? 'required' : '' }}
                                        class="w-full border {{ $hasError ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#00355f]/30 focus:border-[#00355f] outline-none bg-white text-slate-800 transition-all">
                                    <option value="">— Choisissez une option —</option>
                                    @foreach($options as $option)
                                    <option value="{{ $option }}" {{ old('answers.' . $key) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>

                            @elseif($type === 'textarea')
                                <textarea name="answers[{{ $key }}]"
                                          id="field_{{ $key }}"
                                          rows="4"
                                          {{ $required ? 'required' : '' }}
                                          placeholder="{{ $placeholder }}"
                                          class="w-full border {{ $hasError ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#00355f]/30 focus:border-[#00355f] outline-none bg-white transition-all resize-none">{{ old('answers.' . $key) }}</textarea>

                            @else
                                {{-- text, email, tel, date, number, file --}}
                                <input type="{{ $type }}"
                                       name="answers[{{ $key }}]"
                                       id="field_{{ $key }}"
                                       value="{{ old('answers.' . $key) }}"
                                       {{ $required ? 'required' : '' }}
                                       placeholder="{{ $placeholder }}"
                                       class="w-full border {{ $hasError ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#00355f]/30 focus:border-[#00355f] outline-none bg-white transition-all">
                            @endif

                            @error('answers.' . $key)
                            <p class="text-xs text-rose-600 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        @endforeach

                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit"
                                    class="w-full py-3.5 text-white font-bold rounded-xl text-sm shadow-lg hover:opacity-90 transition-all active:scale-[0.99]"
                                    style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                                <span class="flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">send</span>
                                    Soumettre le formulaire
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="text-center text-xs text-slate-400 pb-6">
                    Formulaire sécurisé par <strong class="text-slate-500">PFPT CFPT</strong> · Vos données sont protégées
                </div>
            </div>
        </div>
    </div>
</body>
</html>
