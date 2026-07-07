<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Financier - {{ $edition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="bg-white p-10 font-sans">
    <div class="max-w-4xl mx-auto border p-12">
        <!-- Header -->
        <div class="flex justify-between items-start mb-12 border-b pb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">PFPT - CFPT</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Rapport Financier & Budgétaire</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase">Édition</p>
                <p class="text-lg font-black text-blue-900">{{ $edition->title }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Généré le {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-3 gap-8 mb-12 bg-slate-50 p-6 rounded-xl border border-slate-100">
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Budget Total</p>
                <p class="text-xl font-black text-slate-900">{{ number_format($edition->total_budget, 0, ',', ' ') }} <span class="text-[10px]">CFA</span></p>
            </div>
            <div class="text-center border-x border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Dépenses</p>
                @php
                    $totalSpent = $expenses->sum('amount') + $intervenants->sum('remuneration');
                @endphp
                <p class="text-xl font-black text-blue-800">{{ number_format($totalSpent, 0, ',', ' ') }} <span class="text-[10px]">CFA</span></p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Solde Restant</p>
                <p class="text-xl font-black {{ ($edition->total_budget - $totalSpent) < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                    {{ number_format($edition->total_budget - $totalSpent, 0, ',', ' ') }} <span class="text-[10px]">CFA</span>
                </p>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-12">
            <!-- Intervenants -->
            <div>
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-4 border-l-4 border-blue-600 pl-3">Rémunérations Intervenants</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b">Nom</th>
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b">Séminaire</th>
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($intervenants as $intervenant)
                        <tr>
                            <td class="py-3 px-3 text-sm font-bold text-slate-700">{{ $intervenant->name }}</td>
                            <td class="py-3 px-3 text-xs text-slate-500">{{ $intervenant->seminar->title }}</td>
                            <td class="py-3 px-3 text-sm text-right font-black text-slate-900">{{ number_format($intervenant->remuneration, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Other Expenses -->
            <div>
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-4 border-l-4 border-slate-400 pl-3">Logistique & Frais Divers</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b">Description</th>
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b">Date</th>
                            <th class="py-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($expenses as $expense)
                        <tr>
                            <td class="py-3 px-3 text-sm font-bold text-slate-700">{{ $expense->description }}</td>
                            <td class="py-3 px-3 text-xs text-slate-500">{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 text-sm text-right font-black text-slate-900">{{ number_format($expense->amount, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-20 border-t pt-8 flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">
            <p>Document Confidentiel - Système PFPT CFPT</p>
            <p>Page 1 / 1</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer le rapport
        </button>
    </div>
</body>
</html>
