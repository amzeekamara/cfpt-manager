<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport JICA - {{ $cohort->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700;900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
            background-color: #f3f4f6; /* bg-gray-100 */
        }
        h1, h2, h3, .font-headline { 
            font-family: 'Montserrat', sans-serif; 
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; background-color: white; }
            .print-container { 
                border: none !important; 
                box-shadow: none !important; 
                max-width: 100% !important; 
                padding: 0 !important; 
                margin: 0 !important;
            }
            @page { margin: 0; } /* No margins to allow full width header/footer */
        }
        
        /* Brand Color from Charter (Blue) */
        .text-brand-primary { color: #00355f; }
        .bg-brand-primary { background-color: #00355f; }
        .border-brand-primary { border-color: #00355f; }
    </style>
</head>
<body class="py-10 px-4 min-h-screen flex justify-center no-print-bg">

    <!-- Container must have relative for absolute positioning of top/bottom bars if needed -->
    <div class="print-container w-full max-w-[210mm] min-h-[297mm] bg-white shadow-2xl relative flex flex-col">
        
        <!-- Top Red Bar -->
        <div class="w-full h-4 bg-brand-primary mt-8"></div>

        <div class="px-12 py-8 flex-grow">
            <!-- Logo area -->
            <div class="text-center mb-10">
                <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT CFPT" class="h-16 mx-auto">
            </div>

            <!-- Header Info -->
            <div class="flex justify-between items-start mb-6">
                <!-- Left Info -->
                <div>
                    <h2 class="text-2xl font-headline font-bold text-gray-900 mb-2 uppercase">ÉDITION</h2>
                    <p class="font-bold text-gray-800 text-lg">{{ $cohort->title }}</p>
                    <div class="mt-4 space-y-1 text-xs text-gray-600">
                        <p class="flex items-center gap-2">
                            Début: {{ $cohort->start_date ? $cohort->start_date->format('d/m/Y') : 'Non défini' }}
                        </p>
                        <p class="flex items-center gap-2">
                            Fin: {{ $cohort->end_date ? $cohort->end_date->format('d/m/Y') : 'Non défini' }}
                        </p>
                        <p class="flex items-center gap-2">
                            Statut: {{ strtolower($cohort->status) === 'active' ? 'En cours' : (strtolower($cohort->status) === 'completed' ? 'Terminé' : (strtolower($cohort->status) === 'draft' ? 'Brouillon' : ucfirst($cohort->status))) }}
                        </p>
                    </div>
                </div>

                <!-- Right Info -->
                <div class="text-right">
                    <table class="text-xs text-gray-600 mb-6 ml-auto">
                        <tr>
                            <td class="text-left pr-4 pb-1">Généré le</td>
                            <td class="font-bold pb-1">{{ now()->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-left pr-4">Resource</td>
                            <td class="font-bold">Rapport JICA (Dépenses & Participants)</td>
                        </tr>
                    </table>
                    
                    <h2 class="text-4xl font-headline font-bold text-gray-800 uppercase tracking-tighter">RAPPORT JICA</h2>
                </div>
            </div>

            <!-- Red line separator -->
            <div class="w-full border-t-2 border-brand-primary mb-8"></div>

            <!-- Section 1: Rémunérations Formateurs -->
            <div class="mb-10">
                <h3 class="text-sm font-bold text-gray-800 uppercase mb-3">Rémunérations Formateurs</h3>
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300 w-12 text-center">N°</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Nom</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Séminaire</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $trainerIndex = 1; @endphp
                        @foreach($trainers as $trainer)
                            @foreach($trainer->seminars as $seminarPivot)
                            <tr class="{{ $trainerIndex % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="py-2 px-3 text-center text-gray-500 border-x border-gray-100">{{ $trainerIndex++ }}</td>
                                <td class="py-2 px-3 text-gray-800 font-medium border-r border-gray-100">{{ $trainer->name }}</td>
                                <td class="py-2 px-3 text-gray-600 border-r border-gray-100">{{ $seminarPivot->title }}</td>
                                <td class="py-2 px-3 text-right text-gray-800 border-r border-gray-100">{{ number_format($seminarPivot->pivot->remuneration, 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        @endforeach
                        @if($trainers->isEmpty())
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-400 italic border-x border-gray-100">Aucune rémunération.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Section 2: Logistique & Frais Divers -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase mb-3">Logistique & Frais Divers</h3>
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300 w-12 text-center">N°</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Description</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Date</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $index => $expense)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="py-2 px-3 text-center text-gray-500 border-x border-gray-100">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 text-gray-800 font-medium border-r border-gray-100">{{ $expense->description }}</td>
                            <td class="py-2 px-3 text-gray-600 border-r border-gray-100">{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td class="py-2 px-3 text-right text-gray-800 border-r border-gray-100">{{ number_format($expense->amount, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @endforeach
                        @if($expenses->isEmpty())
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-400 italic border-x border-gray-100">Aucun frais logistique.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Section 3: Liste des Participants -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase mb-3">Liste des Participants</h3>
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300 w-12 text-center">N°</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Nom & Prénom</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Pays (Profession)</th>
                            <th class="py-2 px-3 text-brand-primary font-bold uppercase border-b border-gray-300">Séminaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $index => $registration)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="py-2 px-3 text-center text-gray-500 border-x border-gray-100">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 text-gray-800 font-medium border-r border-gray-100">{{ $registration->user->name }}</td>
                            <td class="py-2 px-3 text-gray-600 border-r border-gray-100">
                                {{ $registration->user->candidateProfile->nationality ?? 'N/A' }} 
                                <span class="text-xs text-gray-400">({{ $registration->user->candidateProfile->profession ?? 'N/A' }})</span>
                            </td>
                            <td class="py-2 px-3 text-gray-800 border-r border-gray-100">{{ $registration->seminar->title }}</td>
                        </tr>
                        @endforeach
                        @if($participants->isEmpty())
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-400 italic border-x border-gray-100">Aucun participant enregistré.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @php
                $trainerTotal = 0;
                foreach($trainers as $trainer) {
                    $trainerTotal += $trainer->seminars->sum('pivot.remuneration');
                }
                $totalSpent = $expenses->sum('amount') + $trainerTotal;
                $balance = $cohort->total_budget - $totalSpent;
            @endphp

            <!-- Totals Section -->
            <div class="flex justify-between items-start mb-12">
                <!-- Left: Terms -->
                <div class="w-1/2 pt-4">
                    <h4 class="font-headline font-bold italic text-gray-800 mb-1">Informations</h4>
                    <p class="text-xs text-gray-500">Resource généré automatiquement depuis la plateforme de gestion PFPT CFPT. Les montants sont exprimés en Francs CFA.</p>
                </div>
                
                <!-- Right: Totals -->
                <div class="w-2/5">
                    <table class="w-full text-sm text-gray-700">
                        <tr>
                            <td class="py-2 text-right pr-4">Budget Total</td>
                            <td class="py-2 text-right font-medium">{{ number_format($cohort->total_budget, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-right pr-4 border-b border-gray-200">Total Dépenses</td>
                            <td class="py-2 text-right font-medium border-b border-gray-200">{{ number_format($totalSpent, 0, ',', ' ') }}</td>
                        </tr>
                        <tr class="bg-brand-primary text-white font-bold text-lg">
                            <td class="py-3 px-4 text-right">SOLDE</td>
                            <td class="py-3 px-4 text-right">{{ number_format($balance, 0, ',', ' ') }}</td>
                        </tr>
                    </table>
                    <!-- Thin red line under total -->
                    <div class="w-full border-t-2 border-gray-800 mt-1"></div>
                </div>
            </div>

            <!-- Gray Bar -->
            <div class="bg-gray-300 py-3 px-6 rounded text-xs font-bold text-gray-700 flex justify-center gap-12 mb-12">
                <span>Rapport Financier</span>
                <span>Édition: {{ $cohort->title }}</span>
            </div>

            <!-- Thanks Text -->
            <div class="text-center mb-8">
                <h3 class="font-headline italic text-2xl text-gray-800">Résumé financier de l'édition</h3>
            </div>
        </div>

        <!-- Red Footer Block -->
        <div class="w-full bg-brand-primary p-8 mt-auto flex justify-between items-center text-white text-xs">
            <div>
                <p class="font-bold mb-1">Plateforme PFPT CFPT</p>
                <p class="opacity-80">Système de gestion interne</p>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="opacity-80">Resource Confidentiel</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <div class="no-print fixed bottom-8 right-8 flex items-center gap-4">
        <!-- Optional back button for convenience -->
        <a href="{{ route('admin.finances') }}" class="bg-white text-gray-700 px-4 py-3 rounded-full shadow-lg border border-gray-200 hover:bg-gray-50 transition-all font-medium text-sm flex items-center gap-2">
            Retour
        </a>
        <button onclick="downloadPDF()" class="bg-amber-500 text-white px-6 py-3 rounded-full shadow-xl hover:shadow-2xl hover:bg-opacity-90 hover:-translate-y-1 transition-all flex items-center gap-2 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Télécharger
        </button>
        <button onclick="window.print()" class="bg-brand-primary text-white px-6 py-3 rounded-full shadow-xl hover:shadow-2xl hover:bg-opacity-90 hover:-translate-y-1 transition-all flex items-center gap-2 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer
        </button>
    </div>

    <script>
        function downloadPDF() {
            const element = document.querySelector('.print-container');
            const opt = {
              margin:       0,
              filename:     'rapport_financier.pdf',
              image:        { type: 'jpeg', quality: 0.98 },
              html2canvas:  { scale: 2, useCORS: true },
              jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
