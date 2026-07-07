@extends('layouts.admin')

@section('header_title', 'Réponses — ' . $form->title)

@section('content')
<div class="p-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.forms.index') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center bg-surface-container hover:bg-surface-container-high border border-outline-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-headline font-bold text-on-surface">Réponses reçues</h1>
                <p class="text-sm text-on-surface-variant">{{ $form->title }} — <span class="font-semibold">{{ $submissions->total() }}</span> réponse(s)</p>
            </div>
        </div>
        <a href="{{ route('admin.forms.export', $form->id) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all text-sm shadow">
            <span class="material-symbols-outlined text-[20px]">download</span>
            Exporter CSV
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2 text-sm">
        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-2 text-sm">
        <span class="material-symbols-outlined text-rose-500">error</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats rapides --}}
    @php
    $allSubmissions = \App\Models\FormSubmission::where('form_template_id', $form->id)->with('enrollment')->get();
    $statPending  = $allSubmissions->filter(fn($s) => $s->enrollment && $s->enrollment->status === 'pending')->count();
    $statApproved = $allSubmissions->filter(fn($s) => $s->enrollment && $s->enrollment->status === 'approved')->count();
    $statRejected = $allSubmissions->filter(fn($s) => $s->enrollment && $s->enrollment->status === 'rejected')->count();
    $statWaiting  = $allSubmissions->filter(fn($s) => $s->enrollment && $s->enrollment->status === 'waiting_list')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-500 text-[20px]">inbox</span>
            </div>
            <div>
                <div class="text-2xl font-headline font-bold text-on-surface">{{ $allSubmissions->count() }}</div>
                <div class="text-xs text-on-surface-variant">Total</div>
            </div>
        </div>
        <div class="bg-amber-50 rounded-2xl border border-amber-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-[20px]">pending</span>
            </div>
            <div>
                <div class="text-2xl font-headline font-bold text-amber-700">{{ $statPending }}</div>
                <div class="text-xs text-amber-600">En attente</div>
            </div>
        </div>
        <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            </div>
            <div>
                <div class="text-2xl font-headline font-bold text-emerald-700">{{ $statApproved }}</div>
                <div class="text-xs text-emerald-600">Acceptés</div>
            </div>
        </div>
        <div class="bg-rose-50 rounded-2xl border border-rose-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-rose-600 text-[20px]">cancel</span>
            </div>
            <div>
                <div class="text-2xl font-headline font-bold text-rose-700">{{ $statRejected }}</div>
                <div class="text-xs text-rose-600">Refusés</div>
            </div>
        </div>
    </div>

    @if($submissions->isEmpty())
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-16 text-center">
        <span class="material-symbols-outlined text-6xl text-outline mb-4 block">inbox</span>
        <h3 class="text-lg font-headline font-semibold text-on-surface mb-2">Aucune réponse pour le moment</h3>
        <p class="text-on-surface-variant text-sm mb-4">Partagez le lien du formulaire pour recevoir des candidatures.</p>
        <div class="inline-flex items-center gap-2 bg-primary/5 border border-primary/20 rounded-xl px-4 py-2">
            <span class="material-symbols-outlined text-primary text-[16px]">link</span>
            <span class="text-sm font-mono text-primary">{{ $form->share_url }}</span>
        </div>
    </div>
    @else

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="min-width: 900px;">
                <thead>
                    <tr class="bg-surface-container-low/50 border-b border-outline-variant">
                        <th class="py-3 px-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider w-12">#</th>
                        <th class="py-3 px-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Date</th>
                        @foreach($form->fields_json ?? [] as $field)
                        <th class="py-3 px-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider whitespace-nowrap">
                            {{ $field['label'] }}@if($field['required'] ?? false)<span class="text-rose-500">*</span>@endif
                        </th>
                        @endforeach
                        <th class="py-3 px-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-center">Statut</th>
                        <th class="py-3 px-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @foreach($submissions as $i => $sub)
                    @php
                        $enrollment = $sub->enrollment;
                        $status = $enrollment?->status ?? 'no_enrollment';
                        $statusConfig = [
                            'pending'      => ['label' => 'En attente',   'class' => 'bg-amber-50 text-amber-700 border-amber-200',    'dot' => 'bg-amber-500'],
                            'approved'     => ['label' => 'Accepté',      'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                            'rejected'     => ['label' => 'Refusé',       'class' => 'bg-rose-50 text-rose-700 border-rose-200',       'dot' => 'bg-rose-500'],
                            'waiting_list' => ['label' => 'Liste attente','class' => 'bg-blue-50 text-blue-700 border-blue-200',        'dot' => 'bg-blue-500'],
                            'no_enrollment'=> ['label' => 'Non traité',   'class' => 'bg-slate-50 text-slate-500 border-slate-200',    'dot' => 'bg-slate-400'],
                        ];
                        $sc = $statusConfig[$status] ?? $statusConfig['no_enrollment'];
                    @endphp
                    <tr class="hover:bg-surface-container-low/40 transition-colors group">
                        <td class="py-3 px-4 text-xs text-on-surface-variant font-mono">{{ $submissions->firstItem() + $i }}</td>
                        <td class="py-3 px-4 text-xs text-on-surface-variant whitespace-nowrap">
                            {{ $sub->submitted_at->format('d/m/Y H:i') }}
                        </td>
                        @foreach($form->fields_json ?? [] as $field)
                        <td class="py-3 px-4 text-sm text-on-surface" style="max-width:180px">
                            <span class="block truncate" title="{{ $sub->answers_json[$field['key']] ?? '—' }}">
                                {{ $sub->answers_json[$field['key']] ?? '—' }}
                            </span>
                        </td>
                        @endforeach

                        {{-- Status Badge --}}
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $sc['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                {{ $sc['label'] }}
                            </span>
                            @if($enrollment && $enrollment->seminar)
                            <div class="text-[10px] text-on-surface-variant mt-0.5">
                                📌 {{ Str::limit($enrollment->seminar->title, 20) }}
                            </div>
                            @endif
                        </td>

                        {{-- Action buttons --}}
                        <td class="py-3 px-4">
                            @if($enrollment)
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                {{-- Accepter --}}
                                @if($status !== 'approved')
                                <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit"
                                            title="Accepter"
                                            class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center justify-center transition-colors"
                                            onclick="return confirm('Accepter cette candidature ?')">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </button>
                                </form>
                                @endif

                                {{-- Liste d'attente --}}
                                @if($status !== 'waiting_list')
                                <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="waiting_list">
                                    <button type="submit"
                                            title="Mettre en liste d'attente"
                                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    </button>
                                </form>
                                @endif

                                {{-- Refuser --}}
                                @if($status !== 'rejected')
                                <form action="{{ route('admin.forms.submissions.status', $sub->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit"
                                            title="Refuser"
                                            class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 flex items-center justify-center transition-colors"
                                            onclick="return confirm('Refuser cette candidature ?')">
                                        <span class="material-symbols-outlined text-[16px]">close</span>
                                    </button>
                                </form>
                                @endif

                                {{-- Voir dossier --}}
                                <a href="{{ route('admin.candidates.show', $enrollment->id) }}"
                                   title="Voir le dossier"
                                   class="w-8 h-8 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface border border-outline-variant flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                </a>
                            </div>
                            @else
                            <span class="text-xs text-on-surface-variant italic text-center block">Aucune candidature</span>
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
