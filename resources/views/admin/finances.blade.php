@extends('layouts.admin')

@section('header_title', 'Gestion Budgétaire')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Finances & Budgets</h1>
                    @if($selectedCohort)
                        <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Suivi de consommation pour : <span class="font-bold text-[#4B6A95]">{{ $selectedCohort->title }}</span></p>
                    @else
                        <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Aucun cycle de formation sélectionné.</p>
                    @endif
                </div>
            </div>

            </header>
        
        <!-- Filters Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4 bg-gray-50/50 p-4 rounded-md border border-gray-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-primary">filter_alt</span>
                <span class="text-sm font-bold text-gray-700">Filtres</span>
            </div>
            <form action="{{ route('admin.finances') }}" method="GET" id="filterForm" class="m-0 flex flex-wrap items-center gap-3">
                
                <!-- Cohort -->
                <select name="cohort_id" onchange="document.getElementById('filterForm').submit()" 
                        class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary cursor-pointer shadow-sm">
                    <option value="">Toutes les éditions</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" {{ (isset($selectedCohort) && $selectedCohort->id == $cohort->id) ? 'selected' : '' }}>
                            {{ $cohort->title }} {{ $cohort->status === 'active' ? '(Actuelle)' : '' }}
                        </option>
                    @endforeach
                </select>

                <!-- Seminar -->
                <select name="seminar_id" onchange="document.getElementById('filterForm').submit()" 
                        class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary cursor-pointer shadow-sm">
                    <option value="">Tous les séminaires</option>
                    @foreach($seminars as $seminar)
                        <option value="{{ $seminar->id }}" {{ request('seminar_id') == $seminar->id ? 'selected' : '' }}>
                            {{ Str::limit($seminar->title, 30) }}
                        </option>
                    @endforeach
                </select>

                <!-- Start Date -->
                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('filterForm').submit()" 
                       class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary shadow-sm"
                       title="Date de début">
                       
                <!-- End Date -->
                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('filterForm').submit()" 
                       class="text-sm border border-gray-200 rounded-full px-4 py-2 bg-white outline-none text-gray-600 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary shadow-sm"
                       title="Date de fin">
                       
                <a href="{{ route('admin.finances') }}" class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full transition-colors" title="Réinitialiser les filtres">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
            </form>
        </div>

        @php
            $consumptionPerc = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
            $budgetStatusColor = $consumptionPerc > 90 ? 'rose' : ($consumptionPerc > 70 ? 'amber' : 'emerald');
        @endphp

                <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Budget Global -->
            <x-stat-card 
                title="Budget Alloué" icon="payments" :showProgress="true"
                value="{{ number_format($totalBudget, 0, ',', ' ') }}" 
                total="{{ $totalBudget }}" 
                unit="FCFA"
                description="Enveloppe globale édition"
                color="blue">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-40"><span class="material-symbols-outlined text-8xl text-gray-300" style="font-variation-settings: 'FILL' 1;">account_balance</span></div>
                </x-slot>
            </x-stat-card>

            <!-- Dépenses Totales -->
            <x-stat-card 
                title="Total Dépenses" icon="receipt_long" :showProgress="true"
                value="{{ number_format($totalSpent, 0, ',', ' ') }}" 
                total="{{ $totalBudget }}" 
                unit="FCFA"
                description="{{ number_format($consumptionPerc, 1) }}% du budget consommé"
                color="{{ $consumptionPerc > 90 ? 'red' : ($consumptionPerc > 70 ? 'yellow' : 'green') }}">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl {{ $consumptionPerc > 90 ? 'text-red-300' : ($consumptionPerc > 70 ? 'text-yellow-300' : 'text-green-300') }}" style="font-variation-settings: 'FILL' 1;">trending_up</span></div>
                </x-slot>
            </x-stat-card>

            <!-- Rémunérations Formateurs -->
            <x-stat-card 
                title="Formateurs" icon="group" :showProgress="true"
                value="{{ number_format($trainerPay, 0, ',', ' ') }}" 
                total="{{ $totalBudget }}" 
                unit="FCFA"
                description="Honoraires et primes"
                color="orange">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl text-orange-300" style="font-variation-settings: 'FILL' 1;">groups</span></div>
                </x-slot>
            </x-stat-card>

            <!-- Budget Restant -->
            <x-stat-card 
                title="Solde Restant" icon="account_balance_wallet" :showProgress="true"
                value="{{ number_format($remainingBudget, 0, ',', ' ') }}" 
                total="{{ $totalBudget }}" 
                unit="FCFA"
                :description="$remainingBudget < 0 ? 'Dépassement Budgétaire !' : 'Disponible pour l\'édition'"
                color="{{ $remainingBudget < 0 ? 'red' : 'green' }}">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 opacity-30"><span class="material-symbols-outlined text-8xl {{ $remainingBudget < 0 ? 'text-red-300' : 'text-green-300' }}" style="font-variation-settings: 'FILL' 1;">savings</span></div>
                </x-slot>
            </x-stat-card>
        </div>

        
        <!-- BEGIN: Charts Section -->
        <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-100 rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">Répartition du Budget Alloué</h3>
                <div class="h-64 w-full flex justify-center">
                    <canvas id="financeDoughnutChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white border border-gray-100 rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">Dépenses par Séminaire</h3>
                <div class="h-64 w-full">
                    <canvas id="financeBarChart"></canvas>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">Répartition par Thème</h3>
                <div class="h-64 w-full">
                    <canvas id="financeThemeChart"></canvas>
                </div>
            </div>
        </div>
        <!-- END: Charts Section -->

<!-- Modal: Gérer les Catégories -->
<div id="categoryModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('categoryModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-headline font-bold text-gray-900 tracking-tight">Types de service</h3>
                    <button type="button" onclick="closeModal('categoryModal')" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form action="{{ route('admin.finances.categories.store') }}" method="POST" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="name" required placeholder="Nouvelle catégorie..." class="flex-1 bg-gray-50 border border-gray-200 rounded-md px-4 py-2 text-sm focus:ring-1 focus:ring-brand-primary outline-none">
                    <button type="submit" class="bg-brand-primary text-white rounded-lg px-4 py-2 font-bold text-sm hover:opacity-90 shadow-sm">Ajouter</button>
                </form>

                <div class="max-h-60 overflow-y-auto pr-2 space-y-2">
                    @forelse($categories as $category)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <span class="text-sm font-bold text-gray-700">{{ $category->name }}</span>
                            <form action="{{ route('admin.finances.categories.destroy', $category->id) }}" method="POST" class="m-0" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 p-1 bg-white rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-400 italic py-4">Aucune catégorie disponible.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Budget Consumption Chart / Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg p-8 border border-gray-100 shadow-sm">
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
                            <div class="p-6 rounded-md bg-gray-50 border border-gray-100">
                                <h3 class="text-xs font-bold text-gray-500 uppercase mb-3 text-center tracking-wider">Rémunérations Formateurs</h3>
                                <div class="text-2xl font-black text-gray-900 text-center mb-1">{{ number_format($trainerPay, 0, ',', ' ') }} FCFA</div>
                                <div class="text-[11px] text-center text-gray-500 font-medium">{{ number_format($totalSpent > 0 ? ($trainerPay / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                            </div>
                            <div class="p-6 rounded-md bg-gray-50 border border-gray-100">
                                <h3 class="text-xs font-bold text-gray-500 uppercase mb-3 text-center tracking-wider">Frais de Logistique & Matériel</h3>
                                <div class="text-2xl font-black text-gray-900 text-center mb-1">{{ number_format($otherExpenses, 0, ',', ' ') }} FCFA</div>
                                <div class="text-[11px] text-center text-gray-500 font-medium">{{ number_format($totalSpent > 0 ? ($otherExpenses / $totalSpent) * 100 : 0, 1) }}% des dépenses totales</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses Table -->
                <div class="bg-white rounded-lg border border-gra                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <h2 class="font-headline font-bold text-lg text-gray-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-brand-primary">history</span> Journal des Dépenses
                            </h2>
                            @if($selectedCohort)
                                <a href="{{ route('admin.finances.export', ['cohort_id' => $selectedCohort->id]) }}" target="_blank" class="text-xs font-bold text-brand-primary hover:bg-brand-light px-4 py-2 rounded-full border border-brand-primary/20 transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-outlined text-sm">download</span> Exporter
                                </a>
                            @endif
                        </div>
                        
                        <!-- Local Filters for Journal (Dynamique AJAX) -->
                        <form id="expenses-filter-form" action="{{ route('admin.finances') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                            <input type="hidden" name="cohort_id" value="{{ request('cohort_id', $selectedCohort->id ?? '') }}">
                            <!-- Keep global filters if any, so they don't reset -->
                            @if(request('seminar_id')) <input type="hidden" name="seminar_id" value="{{ request('seminar_id') }}"> @endif
                            @if(request('start_date')) <input type="hidden" name="start_date" value="{{ request('start_date') }}"> @endif
                            @if(request('end_date')) <input type="hidden" name="end_date" value="{{ request('end_date') }}"> @endif

                            <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mr-2 uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px]">filter_list</span> Filtrer :
                            </div>

                            <select name="expense_category_id" class="text-xs border border-gray-200 rounded-md px-3 py-1.5 bg-white text-gray-600 outline-none focus:border-brand-primary cursor-pointer">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('expense_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="expense_seminar_id" class="text-xs border border-gray-200 rounded-md px-3 py-1.5 bg-white text-gray-600 outline-none focus:border-brand-primary cursor-pointer">
                                <option value="">Tous les séminaires</option>
                                @foreach($seminars as $seminar)
                                    <option value="{{ $seminar->id }}" {{ request('expense_seminar_id') == $seminar->id ? 'selected' : '' }}>
                                        {{ Str::limit($seminar->title, 25) }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="expense_sort" class="text-xs border border-gray-200 rounded-md px-3 py-1.5 bg-white text-gray-600 outline-none focus:border-brand-primary cursor-pointer">
                                <option value="">Trier par Date (Récent)</option>
                                <option value="highest" {{ request('expense_sort') === 'highest' ? 'selected' : '' }}>Prix : Plus élevés</option>
                                <option value="lowest" {{ request('expense_sort') === 'lowest' ? 'selected' : '' }}>Prix : Moins élevés</option>
                            </select>

                            <div class="flex items-center gap-2 border border-gray-200 rounded-md bg-white px-2 overflow-hidden focus-within:border-brand-primary">
                                <input type="date" name="expense_start_date" value="{{ request('expense_start_date') }}" class="text-xs py-1.5 outline-none text-gray-600 cursor-pointer" title="Date de début">
                                <span class="text-gray-300">-</span>
                                <input type="date" name="expense_end_date" value="{{ request('expense_end_date') }}" class="text-xs py-1.5 outline-none text-gray-600 cursor-pointer" title="Date de fin">
                            </div>
                            
                            <button type="button" id="clear-expenses-filter" class="text-xs font-bold text-red-500 hover:text-red-600 flex items-center transition-colors cursor-pointer {{ request()->hasAny(['expense_category_id', 'expense_seminar_id', 'expense_sort', 'expense_start_date', 'expense_end_date']) ? '' : 'hidden' }}">
                                <span class="material-symbols-outlined text-[16px]">close</span> Effacer
                            </button>
                        </form>
                    </div>

                    <!-- Scrollable Expenses Table Container -->
                    <div class="overflow-x-auto max-h-[500px] overflow-y-auto relative border-t border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-gray-50/95 backdrop-blur z-10 border-b border-gray-200 shadow-2xs">
                                <tr>
                                    <th class="py-3.5 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Description</th>
                                    <th class="py-3.5 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Catégorie</th>
                                    <th class="py-3.5 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date</th>
                                    <th class="py-3.5 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Justificatif</th>
                                    <th class="py-3.5 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody id="expenses-table-body" class="divide-y divide-gray-50 transition-opacity duration-200">
                                @include('admin.finances.partials.expenses_rows')
                            </tbody>
                        </table>
                    </div>
                </div>
                    </div>
                </div>

                <!-- Journal des Paiements Formateurs -->
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm mt-8">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-headline font-bold text-gray-900 tracking-tight">Journal des Paiements (Formateurs)</h2>
                            <p class="text-[11px] text-gray-500 font-medium uppercase tracking-wider mt-1">Historique des rémunérations</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/30">
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Formateur</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Séminaire</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Date d'Ajout</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Statut</th>
                                    <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentTrainerPayments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-bold text-gray-800">{{ $payment->trainer_name }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-xs text-gray-600">{{ $payment->seminar_title }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500">{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6">
                                        @if($payment->payment_status === 'paid')
                                            <span class="text-xs font-bold text-emerald-600">Payé</span>
                                        @else
                                            <span class="text-xs font-bold text-amber-600">En attente</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-gray-900 text-sm">{{ number_format($payment->remuneration, 0, ',', ' ') }} <span class="text-[10px] text-gray-500">CFA</span></td>
                                </tr>
                                @empty
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td colspan="5" class="py-12 text-center text-gray-400 italic text-sm">Aucun paiement de formateur enregistré pour le moment.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                @if(auth()->user()->role->name !== 'Agent JICA')
                <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Action Rapide</h2>
                    <div class="space-y-4">
                        <button onclick="openModal('expenseModal')" class="w-full flex items-center gap-3 p-4 rounded-md bg-brand-primary text-white hover:opacity-90 transition-all shadow-lg shadow-brand-primary/10">
                            <span class="material-symbols-outlined">add_circle</span>
                            <div class="text-left">
                                <div class="text-sm font-bold tracking-tight">Ajouter une Dépense</div>
                                <div class="text-[11px] opacity-90 font-medium">Logistique, matériel, divers</div>
                            </div>
                        </button>
                        <button onclick="openModal('trainerModal')" class="w-full flex items-center gap-3 p-4 rounded-md bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-amber-500">person_add</span>
                            <div class="text-left">
                                <div class="text-sm font-bold tracking-tight text-gray-900">Payer un Formateur</div>
                                <div class="text-[11px] text-gray-500 font-medium">Honoraires de session</div>
                            </div>
                        </button>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-5">Résumé Édition</h2>
                    <div class="space-y-4">
                        @if($selectedCohort)
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-3">
                                <span class="text-gray-500">Lancement</span>
                                <span class="font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded-full">{{ $selectedCohort->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-3">
                                <span class="text-gray-500">Fin prévue</span>
                                <span class="font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded-full">{{ $selectedCohort->end_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-1">
                                <span class="text-gray-500">Thèmes</span>
                                <span class="font-bold text-gray-900 line-clamp-1 max-w-[150px] text-right">{{ $selectedCohort->topics }}</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic text-center py-4 bg-gray-50 rounded-md border border-dashed border-gray-200">Sélectionnez une édition pour voir le résumé.</p>
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
                <form action="{{ route('admin.finances.expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <input type="hidden" name="cohort_id" value="{{ $selectedCohort->id ?? '' }}">
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                        <input type="text" name="description" required placeholder="Ex: Achat fournitures bureau" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-end">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Type de Service / Catégorie</label>
                            <button type="button" onclick="openModal('categoryModal')" class="text-xs text-brand-primary hover:underline flex items-center gap-1 font-bold">
                                <span class="material-symbols-outlined text-[14px]">settings</span> Gérer
                            </button>
                        </div>
                        <select name="expense_category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                            <option value="">Sélectionnez une catégorie...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Justificatif (Optionnel)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <span class="material-symbols-outlined text-gray-400 text-3xl mb-2">cloud_upload</span>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Cliquez pour ajouter</span> ou glissez-déposez</p>
                                    <p class="text-xs text-gray-400">PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                                <input type="file" name="receipt" class="hidden" accept=".pdf,image/*" />
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Montant (CFA)</label>
                            <input type="number" name="amount" required class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all text-gray-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Séminaire Lié (Optionnel)</label>
                        <select name="seminar_id" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all cursor-pointer text-gray-700">
                            <option value="">Non spécifié (Dépense globale)</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button type="button" onclick="closeModal('expenseModal')" class="flex-1 px-4 py-3 border border-gray-200 rounded-lg font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-brand-primary text-white rounded-lg font-bold shadow-lg shadow-brand-primary/10 hover:opacity-90 transition-all">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Payer un Formateur -->
<div id="trainerModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('trainerModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="p-8">
                <h3 class="text-2xl font-headline font-bold text-gray-900 mb-6 tracking-tight">Rémunération Formateur</h3>
                <form action="{{ route('admin.finances.trainers.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nom du formateur</label>
                        <input type="text" name="name" list="trainer-list" required placeholder="Sélectionner ou saisir un nom complet" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                        <datalist id="trainer-list">
                            @foreach($allTrainers as $trainerOption)
                                <option value="{{ $trainerOption->name }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Séminaire Concerné</label>
                        <select id="trainer_seminar_id" name="seminar_id" required onchange="updateTrainerTopics()" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all cursor-pointer text-gray-700">
                            <option value="">Choisir un séminaire</option>
                            @foreach($seminars as $seminar)
                                <option value="{{ $seminar->id }}">{{ $seminar->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Thèmes abordés</label>
                            <div id="topics_container" class="w-full bg-gray-50 border border-gray-200 rounded-md p-3 max-h-32 overflow-y-auto space-y-2">
                                <p class="text-xs text-gray-400 italic">Sélectionnez d'abord un séminaire.</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre d'heures</label>
                            <input type="number" name="hours_count" min="0" placeholder="Ex: 15" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Montant Honoraire (CFA)</label>
                        <input type="number" name="remuneration" required class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-3 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all font-bold">
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button type="button" onclick="closeModal('trainerModal')" class="flex-1 px-4 py-3 border border-gray-200 rounded-lg font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Annuler</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-lg font-bold shadow-lg shadow-amber-500/10 hover:opacity-90 transition-all">Confirmer Paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const seminarsData = @json($seminars);

    function updateTrainerTopics() {
        const seminarId = document.getElementById('trainer_seminar_id').value;
        const container = document.getElementById('topics_container');
        
        container.innerHTML = '';
        
        if (!seminarId) {
            container.innerHTML = '<p class="text-xs text-gray-400 italic">Sélectionnez d\'abord un séminaire.</p>';
            return;
        }

        const seminar = seminarsData.find(s => s.id == seminarId);
        
        if (!seminar || !seminar.topics || seminar.topics.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 italic">Aucun thème disponible pour ce séminaire.</p>';
            return;
        }

        seminar.topics.forEach(topic => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="checkbox" name="topic_ids[]" value="${topic.id}" id="topic_${topic.id}" class="rounded text-brand-primary focus:ring-brand-primary cursor-pointer w-4 h-4">
                <label for="topic_${topic.id}" class="text-sm text-gray-700 cursor-pointer flex-1">${topic.name}</label>
            `;
            container.appendChild(div);
        });
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colors = ['#f59e0b', '#ef4444', '#26db63', '#3b82f6', '#8b5cf6'];
        
        // 1. Finance Doughnut Chart
        const dCtx = document.getElementById('financeDoughnutChart');
        if (dCtx) {
            new Chart(dCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($financeChartData['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($financeChartData['data'] ?? []) !!},
                        backgroundColor: ['#f59e0b', '#ef4444', '#26db63'],
                        borderWidth: 2,
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '65%'
                }
            });
        }

        // 2. Finance Bar Chart
        const bCtx = document.getElementById('financeBarChart');
        if (bCtx) {
            new Chart(bCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($barChartData['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Total Dépenses (FCFA)',
                        data: {!! json_encode($barChartData['data'] ?? []) !!},
                        backgroundColor: '#00355f',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // 3. Theme Bar Chart
        const tCtx = document.getElementById('financeThemeChart');
        if (tCtx) {
            new Chart(tCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($themeChartData['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Budget Estimé (FCFA)',
                        data: {!! json_encode($themeChartData['data'] ?? []) !!},
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f3f4f6' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // 4. AJAX Filtering for Journal des Dépenses
        const filterForm = document.getElementById('expenses-filter-form');
        const tableBody = document.getElementById('expenses-table-body');
        const clearBtn = document.getElementById('clear-expenses-filter');

        if (filterForm && tableBody) {
            function updateClearBtnVisibility() {
                if (!clearBtn) return;
                const hasValue = Array.from(filterForm.elements).some(el => {
                    return (el.tagName === 'SELECT' || el.type === 'date') && el.value !== '';
                });
                clearBtn.classList.toggle('hidden', !hasValue);
            }

            function fetchExpenses() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams();
                for (let [k, v] of formData.entries()) {
                    if (v !== '') params.append(k, v);
                }
                params.append('ajax_filter', '1');

                const url = filterForm.getAttribute('action') + '?' + params.toString();
                tableBody.style.opacity = '0.4';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    tableBody.innerHTML = data.table_html;
                    tableBody.style.opacity = '1';
                    updateClearBtnVisibility();
                })
                .catch(err => {
                    console.error('Erreur de filtrage:', err);
                    tableBody.style.opacity = '1';
                });
            }

            filterForm.querySelectorAll('select, input[type="date"]').forEach(input => {
                input.addEventListener('change', function(e) {
                    e.preventDefault();
                    fetchExpenses();
                });
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterForm.querySelectorAll('select').forEach(s => s.value = '');
                    filterForm.querySelectorAll('input[type="date"]').forEach(i => i.value = '');
                    fetchExpenses();
                });
            }
        }
    });
</script>

@endsection
