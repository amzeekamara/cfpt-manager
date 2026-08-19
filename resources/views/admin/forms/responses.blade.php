@extends('layouts.admin')

@section('header_title', 'Réponses — ' . $form->title)

@section('content')
<div class="p-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.forms.index') }}"
               class="w-9 h-9 rounded-lg flex items-center justify-center bg-surface-container hover:bg-surface-container-high border border-outline-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-headline font-bold text-on-surface">Réponses reçues</h1>
                <p class="text-sm text-on-surface-variant">{{ $form->title }} — <span class="font-semibold">{{ $submissions->total() }}</span> réponse(s)</p>
            </div>
        </div>
        <a href="{{ route('admin.forms.export', $form->id) }}" target="_blank"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-all text-sm shadow">
            <span class="material-symbols-outlined text-[20px]">download</span>
            Exporter CSV
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2 text-sm">
        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg flex items-center gap-2 text-sm">
        <span class="material-symbols-outlined text-rose-500">error</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats rapides --}}
    @php
    $allSubmissions = \App\Models\FormSubmission::where('form_template_id', $form->id)->with('registration')->get();
    $statPending  = $allSubmissions->filter(fn($s) => $s->registration && $s->registration->status === 'pending')->count();
    $statApproved = $allSubmissions->filter(fn($s) => $s->registration && $s->registration->status === 'approved')->count();
    $statRejected = $allSubmissions->filter(fn($s) => $s->registration && $s->registration->status === 'rejected')->count();
    $statWaiting  = $allSubmissions->filter(fn($s) => $s->registration && $s->registration->status === 'waiting_list')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Total" icon="inbox" :showProgress="false"
            value="{{ $allSubmissions->count() }}" 
            total="{{ $allSubmissions->count() }}" 
            unit=""
            description=""
            color="blue">
            <x-slot name="decoration">
                <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-gray-300" style="font-variation-settings: 'FILL' 1;">inbox</span></div>
            </x-slot>
        </x-stat-card>
        
        <x-stat-card 
            title="En attente" icon="pending" :showProgress="false"
            value="{{ $statPending }}" 
            total="{{ $statPending }}" 
            unit=""
            description=""
            color="yellow">
            <x-slot name="decoration">
                <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-yellow-300" style="font-variation-settings: 'FILL' 1;">pending</span></div>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Acceptés" icon="check_circle" :showProgress="false"
            value="{{ $statApproved }}" 
            total="{{ $statApproved }}" 
            unit=""
            description=""
            color="green">
            <x-slot name="decoration">
                <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-emerald-300" style="font-variation-settings: 'FILL' 1;">check_circle</span></div>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Refusés" icon="cancel" :showProgress="false"
            value="{{ $statRejected }}" 
            total="{{ $statRejected }}" 
            unit=""
            description=""
            color="red">
            <x-slot name="decoration">
                <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-rose-300" style="font-variation-settings: 'FILL' 1;">cancel</span></div>
            </x-slot>
        </x-stat-card>
    </div>

    @if($submissions->isEmpty())
    <div class="bg-surface-container-lowest rounded-md border border-outline-variant p-16 text-center">
        <span class="material-symbols-outlined text-6xl text-outline mb-4 block">inbox</span>
        <h3 class="text-lg font-headline font-semibold text-on-surface mb-2">Aucune réponse pour le moment</h3>
        <p class="text-on-surface-variant text-sm mb-4">Partagez le lien du formulaire pour recevoir des candidatures.</p>
        <div class="inline-flex items-center gap-2 bg-primary/5 border border-primary/20 rounded-lg px-4 py-2">
            <span class="material-symbols-outlined text-primary text-[16px]">link</span>
            <span class="text-sm font-mono text-primary">{{ $form->share_url }}</span>
        </div>
    </div>
    @else

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-visible">
        <div class="overflow-visible">
            <table class="w-full text-left border-collapse" style="min-width: 900px;">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 w-12">#</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Date</th>
                        @foreach($form->fields_json ?? [] as $field)
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 whitespace-nowrap">
                            {{ $field['label'] }}@if($field['required'] ?? false)<span class="text-rose-500">*</span>@endif
                        </th>
                        @endforeach
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-center">Statut</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($submissions as $i => $sub)
                    @php
                        $registration = $sub->registration;
                        $status = $registration?->status ?? 'no_registration';
                        $statusConfig = [
                            'pending'      => ['label' => 'En attente',   'class' => 'text-amber-600 font-bold',    'dot' => 'bg-amber-500'],
                            'approved'     => ['label' => 'Accepté',      'class' => 'text-emerald-600 font-bold', 'dot' => 'bg-emerald-500'],
                            'rejected'     => ['label' => 'Refusé',       'class' => 'text-rose-600 font-bold',       'dot' => 'bg-rose-500'],
                            'waiting_list' => ['label' => 'Liste attente','class' => 'text-primary font-bold',        'dot' => 'bg-blue-500'],
                            'no_registration'=> ['label' => 'Non traité',   'class' => 'text-slate-500 font-bold',    'dot' => 'bg-slate-400'],
                        ];
                        $sc = $statusConfig[$status] ?? $statusConfig['no_registration'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="py-4 px-6 text-sm text-gray-500 font-mono">{{ $submissions->firstItem() + $i }}</td>
                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                            {{ $sub->submitted_at->format('d/m/Y H:i') }}
                        </td>
                        @foreach($form->fields_json ?? [] as $field)
                        <td class="py-4 px-6 text-sm text-gray-700" style="max-width:180px">
                            @php
                                $ans = $sub->answers_json[$field['key']] ?? '—';
                                if (is_array($ans)) {
                                    $ans = '(Données complexes)';
                                }
                            @endphp
                            <span class="block truncate" title="{{ $ans }}">
                                {{ $ans }}
                            </span>
                        </td>
                        @endforeach

                        {{-- Status Text --}}
                        <td class="py-4 px-6 text-center">
                            <span class="text-xs {{ $sc['class'] }}">
                                {{ $sc['label'] }}
                            </span>
                            @if($registration && $registration->seminar)
                            <div class="text-[10px] text-on-surface-variant mt-0.5">
                                <span class="material-symbols-outlined align-middle" style="font-size: inherit;">push_pin</span> {{ Str::limit($registration->seminar->title, 20) }}
                            </div>
                            @endif
                        </td>

                        {{-- Action buttons --}}
                        <td class="py-4 px-6 text-right">
                            @if($registration)
                                <div class="flex items-center justify-end gap-2">
                                    @if($status !== 'approved')
                                        <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-3 py-1.5 bg-[#44b09e] hover:bg-[#349e8d] text-white text-[11px] font-bold rounded-md shadow-sm flex items-center gap-1 transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">check</span> Approuver
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <div class="relative group inline-block">
                                        <button class="p-1.5 text-gray-400 hover:text-gray-700 rounded-md hover:bg-gray-100 transition-colors flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                        </button>
                                        
                                        <div class="absolute right-0 top-full mt-1 w-44 bg-white border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-md py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 flex flex-col">
                                            <a href="{{ route('admin.candidates.show', $registration->id) }}" class="px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 font-medium flex items-center gap-2 text-left w-full">
                                                <span class="material-symbols-outlined text-[14px]">visibility</span> Voir dossier
                                            </a>
                                            
                                            @if($status !== 'waiting_list')
                                            <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST" class="m-0 w-full border-t border-gray-50">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="waiting_list">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-amber-600 hover:bg-amber-50 font-medium flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[14px]">schedule</span> En attente
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($status !== 'rejected')
                                            <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST" class="m-0 w-full border-t border-gray-50">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[14px]">close</span> Refuser
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic text-center block">Aucune candidature</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($submissions->hasPages())
        <div class="px-5 py-4 border-t border-outline-variant">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
