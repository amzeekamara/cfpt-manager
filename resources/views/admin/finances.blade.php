@extends('layouts.admin')

@section('header_title', 'Gestion Budgétaire')

@section('content')
<div class="p-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Finances & Budgets</h1>
            @if($selectedEdition)
                <p class="text-on-surface-variant font-body">Suivi de consommation pour : <span class="font-bold text-primary">{{ $selectedEdition->title }}</span></p>
            @else
                <p class="text-on-surface-variant font-body">Aucun cycle de formation sélectionné.</p>
            @endif
        </div>

        <div class="bg-surface-container-low p-2 rounded-2xl border border-outline-variant flex items-center gap-3">
            <span class="material-symbols-outlined text-primary ml-2">filter_alt</span>
            <form action="{{ route('admin.finances') }}" method="GET" id="editionFilterForm" class="flex items-center">
                <select name="edition_id" onchange="document.getElementById('editionFilterForm').submit()" 
                    class="bg-transparent border-none focus:ring-0 text-sm font-bold text-on-surface pr-8 cursor-pointer">
                    @foreach($editions as $edition)
                        <option value="{{ $edition->id }}" {{ (isset($selectedEdition) && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                            {{ $edition->title }} {{ $edition->status === 'active' ? '(Actuelle)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @php
        $consumptionPerc = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
        $budgetStatusColor = $consumptionPerc > 90 ? 'rose' : ($consumptionPerc > 70 ? 'amber' : 'emerald');
    @endphp

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Budget Global -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors shadow-sm">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">Budget Alloué</h3>
            </div>
            <div class="text-2xl font-headline font-black text-on-surface">{{ number_format($totalBudget, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
            <div class="mt-2 text-xs font-medium text-on-surface-variant">Enveloppe globale édition</div>
        </div>

        <!-- Dépenses Totales -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">Total Dépenses</h3>
            </div>
            <div class="text-2xl font-headline font-black text-blue-800">{{ number_format($totalSpent, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
            <div class="mt-2 text-xs font-medium text-{{ $budgetStatusColor }}-600">{{ number_format($consumptionPerc, 1) }}% du budget consommé</div>
        </div>

        <!-- Rémunérations Intervenants -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-outline-variant group hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-sm">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">Intervenants</h3>
            </div>
            <div class="text-2xl font-headline font-black text-on-surface">{{ number_format($intervenantPay, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
            <div class="mt-2 text-xs font-medium text-on-surface-variant">Honoraires et primes</div>
        </div>

        <!-- Budget Restant -->
        <div class="bg-{{ $budgetStatusColor }}-50 rounded-2xl p-6 shadow-md border-2 border-{{ $budgetStatusColor }}-200 group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $budgetStatusColor }}-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-{{ $budgetStatusColor }}-500 flex items-center justify-center text-white shadow-lg">
                    <span class="material-symbols-outlined">{{ $remainingBudget >= 0 ? 'account_balance_wallet' : 'error' }}</span>
                </div>
                <h3 class="text-sm font-bold text-{{ $budgetStatusColor }}-900 uppercase tracking-wider">Solde Restant</h3>
            </div>
            <div class="text-2xl font-headline font-black text-{{ $budgetStatusColor }}-900 relative z-10">{{ number_format($remainingBudget, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
            <div class="mt-2 text-xs font-bold text-{{ $budgetStatusColor }}-700 relative z-10 italic">
                {{ $remainingBudget < 0 ? 'Dépassement Budgétaire !' : 'Disponible pour l\'édition' }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Budget Consumption Chart / Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant">
                <h2 class="text-lg font-headline font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">analytics</span> Répartition de la consommation
                </h2>
                
                <div class="space-y-8">
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-on-surface">Budget Consommé Totale</span>
                            <span class="text-sm font-black text-primary">{{ number_format($consumptionPerc, 1) }}%</span>
                        </div>
                        <div class="w-full h-4 bg-surface-container rounded-full overflow-hidden flex">
                            <div class="h-full bg-blue-600 transition-all duration-1000" style="width: {{ $consumptionPerc }}%">
                                <div class="w-full h-full bg-gradient-to-r from-transparent to-white/20"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                            <h3 class="text-xs font-bold text-on-surface-variant uppercase mb-3 text-center">Rémunérations Intervenants</h3>
                            <div class="text-2xl font-black text-on-surface text-center mb-1">{{ number_format($intervenantPay, 0, ',', ' ') }} FCFA</div>
                            <div class="text-[10px] text-center text-on-surface-variant font-medium">{{ number_format($totalSpent > 0 ? ($intervenantPay / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                            <h3 class="text-xs font-bold text-on-surface-variant uppercase mb-3 text-center">Frais de Logistique & Matériel</h3>
                            <div class="text-2xl font-black text-on-surface text-center mb-1">{{ number_format($otherExpenses, 0, ',', ' ') }} FCFA</div>
                            <div class="text-[10px] text-center text-on-surface-variant font-medium">{{ number_format($totalSpent > 0 ? ($otherExpenses / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses Table -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low/30 flex justify-between items-center">
                    <h2 class="font-headline font-bold text-lg text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">history</span> Journal des Dépenses
                    </h2>
                    @if($selectedEdition)
                        <a href="{{ route('admin.finances.export', ['edition_id' => $selectedEdition->id]) }}" target="_blank" class="text-xs font-bold text-primary hover:bg-primary/5 px-3 py-1.5 rounded-full border border-primary/20 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">download</span> Exporter
                        </a>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low/50">
                                <th class="py-4 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Description</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Catégorie</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant">Date</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/40">
                            @forelse($recentExpenses as $expense)
                            <tr class="hover:bg-surface-container-low/30 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="text-sm font-bold text-on-surface">{{ $expense->description }}</div>
                                    @if($expense->seminar)
                                        <div class="text-[10px] text-primary font-medium">Séminaire : {{ $expense->seminar->title }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase">Logistique</span>
                                </td>
                                <td class="py-4 px-6 text-xs text-on-surface-variant">{{ $expense->expense_date->format('d/m/Y') }}</td>
                                <td class="py-4 px-6 text-right font-black text-on-surface text-sm">{{ number_format($expense->amount, 0, ',', ' ') }} <span class="text-[10px]">CFA</span></td>
                            </tr>
                            @empty
                            <tr class="hover:bg-surface-container-low/30 transition-colors">
                                <td colspan="4" class="py-12 text-center text-on-surface-variant italic text-sm">Aucune dépense enregistrée sur cette édition.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant shadow-sm">
                <h2 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-6">Action Rapide</h2>
                <div class="space-y-4">
                    <button onclick="openModal('expenseModal')" class="w-full flex items-center gap-3 p-4 rounded-xl bg-primary text-on-primary hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined">add_circle</span>
                        <div class="text-left">
                            <div class="text-sm font-bold tracking-tight">Ajouter une Dépense</div>
                            <div class="text-[10px] opacity-80">Logistique, matériel, divers</div>
                        </div>
                    </button>
                    <button onclick="openModal('intervenantModal')" class="w-full flex items-center gap-3 p-4 rounded-xl bg-surface-container-low border border-outline-variant hover:bg-surface-container transition-all">
                        <span class="material-symbols-outlined text-secondary">person_add</span>
                        <div class="text-left">
                            <div class="text-sm font-bold tracking-tight text-on-surface">Payer un Intervenant</div>
                            <div class="text-[10px] text-on-surface-variant">Honoraires de session</div>
                        </div>
                    </button>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant shadow-sm">
                <h2 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-4">Résumé Édition</h2>
                <div class="space-y-3">
                    @if($selectedEdition)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-on-surface-variant">Lancement</span>
                            <span class="font-bold text-on-surface">{{ $selectedEdition->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-on-surface-variant">Fin prévue</span>
                            <span class="font-bold text-on-surface">{{ $selectedEdition->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-on-surface-variant">Thèmes</span>
                            <span class="font-bold text-on-surface line-clamp-1">{{ $selectedEdition->themes }}</span>
                        </div>
                    @else
                        <p class="text-xs text-on-surface-variant italic text-center">Sélectionnez une édition pour voir le résumé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter une Dépense -->
<div id="expenseModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('expenseModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-outline-variant">
            <div class="p-8">
                <h3 class="text-2xl font-black text-on-surface mb-6 tracking-tighter">Enregistrer une Dépense</h3>
                <form action="{{ route('admin.finances.expenses.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="edition_id" value="{{ $selectedEdition->id ?? '' }}">
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase">Description</label>
                        <input type="text" name="description" required placeholder="Ex: Achat fournitures bureau" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase">Montant (CFA)</label>
                            <input type="number" name="amount" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase">Date</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase">Séminaire Lié (Optionnel)</label>
                        <select name="seminar_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Non spécifié (Dépense globale)</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('expenseModal')" class="flex-1 px-4 py-3 border border-outline-variant rounded-xl font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-primary text-on-primary rounded-xl font-bold shadow-lg shadow-primary/20 hover:opacity-90 transition-all">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Payer un Intervenant -->
<div id="intervenantModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('intervenantModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-outline-variant">
            <div class="p-8">
                <h3 class="text-2xl font-black text-on-surface mb-6 tracking-tighter">Rémunération Intervenant</h3>
                <form action="{{ route('admin.finances.intervenants.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase">Nom de l'intervenant</label>
                        <input type="text" name="name" required placeholder="Nom complet" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase">Séminaire Concerné</label>
                        <select name="seminar_id" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Choisir un séminaire</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase">Montant Honoraire (CFA)</label>
                        <input type="number" name="remuneration" required class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all font-bold">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('intervenantModal')" class="flex-1 px-4 py-3 border border-outline-variant rounded-xl font-bold text-on-surface hover:bg-surface-variant transition-all">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-secondary-container text-on-secondary-container rounded-xl font-bold shadow-lg shadow-secondary/20 hover:opacity-90 transition-all">Confirmer Paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
