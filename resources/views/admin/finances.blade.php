@extends('layouts.admin')

@section('header_title', 'Gestion Budgétaire')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-[1400px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Finances & Budgets</h1>
                    @if($selectedEdition)
                        <p class="text-gray-500 text-sm mt-1">Suivi de consommation pour : <span class="font-bold text-brand-primary">{{ $selectedEdition->title }}</span></p>
                    @else
                        <p class="text-gray-500 text-sm mt-1">Aucun cycle de formation sélectionné.</p>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 p-2 rounded-full border border-gray-200 flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-brand-primary ml-3">filter_alt</span>
                <form action="{{ route('admin.finances') }}" method="GET" id="editionFilterForm" class="flex items-center m-0">
                    <select name="edition_id" onchange="document.getElementById('editionFilterForm').submit()" 
                        class="bg-transparent border-none focus:ring-0 text-sm font-semibold text-gray-800 pr-8 cursor-pointer outline-none w-48">
                        @foreach($editions as $edition)
                            <option value="{{ $edition->id }}" {{ (isset($selectedEdition) && $selectedEdition->id == $edition->id) ? 'selected' : '' }}>
                                {{ $edition->title }} {{ $edition->status === 'active' ? '(Actuelle)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </header>

        @php
            $consumptionPerc = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
            $budgetStatusColor = $consumptionPerc > 90 ? 'rose' : ($consumptionPerc > 70 ? 'amber' : 'emerald');
        @endphp

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Budget Global -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 group hover:-translate-y-1 transition-transform">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center text-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Budget Alloué</h3>
                </div>
                <div class="text-2xl font-headline font-black text-gray-900">{{ number_format($totalBudget, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
                <div class="mt-2 text-xs font-medium text-gray-400">Enveloppe globale édition</div>
            </div>

            <!-- Dépenses Totales -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 group hover:-translate-y-1 transition-transform">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Dépenses</h3>
                </div>
                <div class="text-2xl font-headline font-black text-blue-800">{{ number_format($totalSpent, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
                <div class="mt-2 text-xs font-semibold text-{{ $budgetStatusColor }}-600">{{ number_format($consumptionPerc, 1) }}% du budget consommé</div>
            </div>

            <!-- Rémunérations Intervenants -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 group hover:-translate-y-1 transition-transform">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Intervenants</h3>
                </div>
                <div class="text-2xl font-headline font-black text-gray-900">{{ number_format($intervenantPay, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></div>
                <div class="mt-2 text-xs font-medium text-gray-400">Honoraires et primes</div>
            </div>

            <!-- Budget Restant -->
            <div class="bg-{{ $budgetStatusColor }}-50 rounded-3xl p-6 shadow-sm border-2 border-{{ $budgetStatusColor }}-200 group hover:-translate-y-1 transition-transform relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $budgetStatusColor }}-500/10 rounded-full blur-2xl"></div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-full bg-{{ $budgetStatusColor }}-500 flex items-center justify-center text-white shadow-lg">
                        <span class="material-symbols-outlined">{{ $remainingBudget >= 0 ? 'account_balance_wallet' : 'error' }}</span>
                    </div>
                    <h3 class="text-xs font-bold text-{{ $budgetStatusColor }}-900 uppercase tracking-wider">Solde Restant</h3>
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
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-headline font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-primary">analytics</span> Répartition de la consommation
                    </h2>
                    
                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-800">Budget Consommé Totale</span>
                                <span class="text-sm font-black text-brand-primary">{{ number_format($consumptionPerc, 1) }}%</span>
                            </div>
                            <div class="w-full h-4 bg-gray-100 rounded-full overflow-hidden flex">
                                <div class="h-full bg-brand-primary transition-all duration-1000" style="width: {{ $consumptionPerc }}%">
                                    <div class="w-full h-full bg-gradient-to-r from-transparent to-white/20"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
                                <h3 class="text-xs font-bold text-gray-500 uppercase mb-3 text-center tracking-wider">Rémunérations Intervenants</h3>
                                <div class="text-2xl font-black text-gray-900 text-center mb-1">{{ number_format($intervenantPay, 0, ',', ' ') }} FCFA</div>
                                <div class="text-[11px] text-center text-gray-500 font-medium">{{ number_format($totalSpent > 0 ? ($intervenantPay / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                            </div>
                            <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
                                <h3 class="text-xs font-bold text-gray-500 uppercase mb-3 text-center tracking-wider">Frais de Logistique & Matériel</h3>
                                <div class="text-2xl font-black text-gray-900 text-center mb-1">{{ number_format($otherExpenses, 0, ',', ' ') }} FCFA</div>
                                <div class="text-[11px] text-center text-gray-500 font-medium">{{ number_format($totalSpent > 0 ? ($otherExpenses / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses Table -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-headline font-bold text-lg text-gray-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-primary">history</span> Journal des Dépenses
                        </h2>
                        @if($selectedEdition)
                            <a href="{{ route('admin.finances.export', ['edition_id' => $selectedEdition->id]) }}" target="_blank" class="text-xs font-bold text-brand-primary hover:bg-brand-light px-4 py-2 rounded-full border border-brand-primary/20 transition-all flex items-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-sm">download</span> Exporter
                            </a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/30">
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Description</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Catégorie</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Date</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentExpenses as $expense)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-bold text-gray-800">{{ $expense->description }}</div>
                                        @if($expense->seminar)
                                            <div class="text-[11px] text-brand-primary font-medium mt-0.5">Séminaire : {{ $expense->seminar->title }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider border border-gray-200 shadow-sm">Logistique</span>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500">{{ $expense->expense_date->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6 text-right font-black text-gray-900 text-sm">{{ number_format($expense->amount, 0, ',', ' ') }} <span class="text-[10px] text-gray-500">CFA</span></td>
                                </tr>
                                @empty
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td colspan="4" class="py-12 text-center text-gray-400 italic text-sm">Aucune dépense enregistrée sur cette édition.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Action Rapide</h2>
                    <div class="space-y-4">
                        <button onclick="openModal('expenseModal')" class="w-full flex items-center gap-3 p-4 rounded-2xl bg-brand-primary text-white hover:opacity-90 transition-all shadow-lg shadow-brand-primary/30">
                            <span class="material-symbols-outlined">add_circle</span>
                            <div class="text-left">
                                <div class="text-sm font-bold tracking-tight">Ajouter une Dépense</div>
                                <div class="text-[11px] opacity-90 font-medium">Logistique, matériel, divers</div>
                            </div>
                        </button>
                        <button onclick="openModal('intervenantModal')" class="w-full flex items-center gap-3 p-4 rounded-2xl bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-amber-500">person_add</span>
                            <div class="text-left">
                                <div class="text-sm font-bold tracking-tight text-gray-900">Payer un Intervenant</div>
                                <div class="text-[11px] text-gray-500 font-medium">Honoraires de session</div>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-5">Résumé Édition</h2>
                    <div class="space-y-4">
                        @if($selectedEdition)
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-3">
                                <span class="text-gray-500">Lancement</span>
                                <span class="font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded-full">{{ $selectedEdition->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-3">
                                <span class="text-gray-500">Fin prévue</span>
                                <span class="font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded-full">{{ $selectedEdition->end_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-1">
                                <span class="text-gray-500">Thèmes</span>
                                <span class="font-bold text-gray-900 line-clamp-1 max-w-[150px] text-right">{{ $selectedEdition->themes }}</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">Sélectionnez une édition pour voir le résumé.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter une Dépense -->
<div id="expenseModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('expenseModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="p-8">
                <h3 class="text-2xl font-headline font-bold text-gray-900 mb-6 tracking-tight">Enregistrer une Dépense</h3>
                <form action="{{ route('admin.finances.expenses.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="edition_id" value="{{ $selectedEdition->id ?? '' }}">
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                        <input type="text" name="description" required placeholder="Ex: Achat fournitures bureau" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Montant (CFA)</label>
                            <input type="number" name="amount" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all text-gray-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Séminaire Lié (Optionnel)</label>
                        <select name="seminar_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all cursor-pointer text-gray-700">
                            <option value="">Non spécifié (Dépense globale)</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button type="button" onclick="closeModal('expenseModal')" class="flex-1 px-4 py-3 border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-brand-primary text-white rounded-xl font-bold shadow-lg shadow-brand-primary/30 hover:opacity-90 transition-all">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Payer un Intervenant -->
<div id="intervenantModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('intervenantModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="p-8">
                <h3 class="text-2xl font-headline font-bold text-gray-900 mb-6 tracking-tight">Rémunération Intervenant</h3>
                <form action="{{ route('admin.finances.intervenants.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nom de l'intervenant</label>
                        <input type="text" name="name" required placeholder="Nom complet" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Séminaire Concerné</label>
                        <select name="seminar_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all cursor-pointer text-gray-700">
                            <option value="">Choisir un séminaire</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Montant Honoraire (CFA)</label>
                        <input type="number" name="remuneration" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all font-bold">
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button type="button" onclick="closeModal('intervenantModal')" class="flex-1 px-4 py-3 border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:opacity-90 transition-all">Confirmer Paiement</button>
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
