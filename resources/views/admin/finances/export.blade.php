<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Rapport Financier' }} - {{ $cohort->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800;900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Roboto', sans-serif; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
            background-color: #f1f5f9;
            color: #1e293b;
        }
        h1, h2, h3, .font-headline { 
            font-family: 'Montserrat', sans-serif; 
        }

        /* Cadre & Mise en page stricte A4 */
        .a4-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            box-sizing: border-box;
            position: relative;
        }

        @media print {
            .no-print { display: none !important; }
            body { 
                padding: 0 !important; 
                margin: 0 !important; 
                background: white !important; 
            }
            .a4-sheet { 
                width: 100% !important; 
                min-height: auto !important; 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 !important; 
                padding: 0 !important; 
            }
            @page { 
                size: A4 portrait; 
                margin: 8mm 10mm 10mm 10mm; 
            }
            .page-break-avoid {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            thead {
                display: table-header-group !important;
            }
        }

        .page-break-avoid {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .brand-blue { color: #4B6A95; }
        .bg-brand-blue { background-color: #4B6A95; }
        .border-brand-blue { border-color: #4B6A95; }
    </style>
</head>
<body class="py-6 px-4 min-h-screen flex flex-col items-center">

    <!-- Screen Toolbar (Barre d'outils interactive pour l'administrateur) -->
    <div class="no-print w-full max-w-[210mm] bg-white rounded-2xl p-4 mb-6 shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-grow max-w-lg">
            <label class="text-xs font-bold text-slate-600 whitespace-nowrap">Titre du rapport :</label>
            <input type="text" id="reportTitleInput" value="{{ $reportTitle }}" 
                oninput="document.getElementById('docTitleHeading').innerText = this.value; document.title = this.value + ' - {{ $cohort->title }}';"
                placeholder="Ex: RAPPORT JICA, RAPPORT D'EXÉCUTION FINANCIÈRE..." 
                class="w-full px-3.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-[#4B6A95] focus:bg-white transition">
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.finances') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                Retour
            </a>
            <button onclick="downloadPDF()" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Télécharger PDF
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-[#4B6A95] hover:bg-[#395376] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Imprimer (A4)
            </button>
        </div>
    </div>

    <!-- Document Print Container (A4 Formatté) -->
    <div id="report-content" class="a4-sheet shadow-xl rounded-none flex flex-col justify-between">
        
        <div>
            <!-- Top Flush Brand Bar -->
            <div class="w-full h-3 bg-[#4B6A95]"></div>

            <div class="px-10 py-6">
                <!-- Logo & Institution Header -->
                <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT CFPT" class="h-14 object-contain">
                        <div>
                            <h4 class="font-extrabold text-xs text-slate-900 leading-tight">CENTRE DE FORMATION PROFESSIONNELLE ET TECHNIQUE</h4>
                            <p class="text-[10px] text-slate-500 font-medium">SÉNÉGAL - JAPON (CFPT-SJ) | PROGRAMME DE FORMATION EN PAYS TIERS (PFPT)</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Généré le</span>
                        <span class="text-xs font-black text-slate-800">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- Header Info (Edition & Dynamic Title) -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#4B6A95] block mb-1">Édition de Formation</span>
                        <h1 class="text-xl font-headline font-black text-slate-900">{{ $cohort->title }}</h1>
                        <div class="mt-2 space-y-0.5 text-xs text-slate-600 font-medium">
                            <p><span class="text-slate-400">Période :</span> Du {{ $cohort->start_date ? $cohort->start_date->format('d/m/Y') : 'N/A' }} au {{ $cohort->end_date ? $cohort->end_date->format('d/m/Y') : 'N/A' }}</p>
                            <p><span class="text-slate-400">Statut :</span> 
                                <span class="font-bold text-slate-800">
                                    {{ strtolower($cohort->status) === 'active' ? 'En cours' : (strtolower($cohort->status) === 'completed' ? 'Terminé' : 'Brouillon') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right max-w-sm">
                        <h2 id="docTitleHeading" class="text-2xl font-headline font-black text-slate-900 uppercase tracking-tight leading-tight">
                            {{ $reportTitle }}
                        </h2>
                        <span class="inline-block mt-1 text-[11px] font-bold text-[#4B6A95] bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-100">
                            Synthèse Financière, Logistique & Pédagogique
                        </span>
                    </div>
                </div>

                <!-- Line separator -->
                <div class="w-full border-t-2 border-[#4B6A95] mb-6"></div>

                <!-- Section 1: Rémunérations Formateurs -->
                <div class="page-break-avoid mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#4B6A95]"></span>
                            1. Rémunérations des Formateurs
                        </h3>
                        <span class="text-[11px] font-bold text-slate-500">{{ $trainers->count() }} Formateur(s)</span>
                    </div>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-bold border-y border-slate-200">
                                <th class="py-2 px-3 w-10 text-center">N°</th>
                                <th class="py-2 px-3">Nom du Formateur</th>
                                <th class="py-2 px-3">Séminaire / Module</th>
                                <th class="py-2 px-3 text-right">Montant Prévu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $trainerIndex = 1; @endphp
                            @foreach($trainers as $trainer)
                                @foreach($trainer->seminars as $seminarPivot)
                                <tr class="{{ $trainerIndex % 2 == 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                    <td class="py-2 px-3 text-center text-slate-400 font-bold">{{ $trainerIndex++ }}</td>
                                    <td class="py-2 px-3 text-slate-900 font-bold">{{ $trainer->name }}</td>
                                    <td class="py-2 px-3 text-slate-600">{{ $seminarPivot->title }}</td>
                                    <td class="py-2 px-3 text-right font-black text-slate-900">{{ number_format($seminarPivot->pivot->remuneration, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @endforeach
                            @endforeach
                            @if($trainers->isEmpty())
                            <tr>
                                <td colspan="4" class="py-3 text-center text-slate-400 italic">Aucune rémunération enregistrée pour cette édition.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Section 2: Logistique & Frais Divers -->
                <div class="page-break-avoid mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#4B6A95]"></span>
                            2. Logistique & Dépenses Opérationnelles
                        </h3>
                        <span class="text-[11px] font-bold text-slate-500">{{ $expenses->count() }} Ligne(s)</span>
                    </div>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-bold border-y border-slate-200">
                                <th class="py-2 px-3 w-10 text-center">N°</th>
                                <th class="py-2 px-3">Description de la dépense</th>
                                <th class="py-2 px-3 w-28">Date</th>
                                <th class="py-2 px-3 text-right w-36">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($expenses as $index => $expense)
                            <tr class="{{ $index % 2 == 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                <td class="py-2 px-3 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-2 px-3 text-slate-900 font-medium">{{ $expense->description }}</td>
                                <td class="py-2 px-3 text-slate-600">{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : 'N/A' }}</td>
                                <td class="py-2 px-3 text-right font-black text-slate-900">{{ number_format($expense->amount, 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                            @if($expenses->isEmpty())
                            <tr>
                                <td colspan="4" class="py-3 text-center text-slate-400 italic">Aucune dépense logistique enregistrée.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Section 3: Liste des Participants Sélectionnés -->
                <div class="page-break-avoid mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#4B6A95]"></span>
                            3. Participants & Bénéficiaires du Programme
                        </h3>
                        <span class="text-[11px] font-bold text-slate-500">{{ $participants->count() }} Participant(s)</span>
                    </div>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-bold border-y border-slate-200">
                                <th class="py-2 px-3 w-10 text-center">N°</th>
                                <th class="py-2 px-3">Nom & Prénom</th>
                                <th class="py-2 px-3">Nationalité & Organisme</th>
                                <th class="py-2 px-3">Séminaire</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($participants as $index => $registration)
                            <tr class="{{ $index % 2 == 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                <td class="py-2 px-3 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="py-2 px-3 text-slate-900 font-bold">{{ $registration->user->name }}</td>
                                <td class="py-2 px-3 text-slate-600">
                                    {{ $registration->user->candidateProfile->nationality ?? 'N/A' }} 
                                    @if(isset($registration->user->candidateProfile->current_employer))
                                        <span class="text-[10px] text-slate-400">({{ $registration->user->candidateProfile->current_employer }})</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-slate-800">{{ $registration->seminar->title }}</td>
                            </tr>
                            @endforeach
                            @if($participants->isEmpty())
                            <tr>
                                <td colspan="4" class="py-3 text-center text-slate-400 italic">Aucun participant enregistré pour cette édition.</td>
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

                <!-- Totals & Financial Summary Block -->
                <div class="page-break-avoid mt-6 pt-4 border-t border-slate-200">
                    <div class="flex justify-between items-start gap-6">
                        <div class="w-1/2">
                            <h4 class="text-xs font-black uppercase text-slate-900 mb-1">Visa & Certifications</h4>
                            <p class="text-[10px] text-slate-500 leading-relaxed mb-4">
                                Ce document fait foi de récapitulatif d'exécution financière et logistique pour l'édition {{ $cohort->title }}. Les dépenses sont conformes aux procédures administratives du CFPT Sénégal-Japon et de la JICA.
                            </p>
                            <div class="mt-4 pt-4 border-t border-dashed border-slate-200">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Signature & Cachet de l'Administration</span>
                            </div>
                        </div>

                        <div class="w-1/2 max-w-sm">
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 space-y-2 text-xs">
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Budget Alloué :</span>
                                    <span class="font-bold text-slate-900">{{ number_format($cohort->total_budget, 0, ',', ' ') }} CFA</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Total Dépenses :</span>
                                    <span class="font-bold text-rose-600">- {{ number_format($totalSpent, 0, ',', ' ') }} CFA</span>
                                </div>
                                <div class="pt-2 border-t border-slate-200 flex justify-between items-center text-sm font-black text-slate-900 bg-[#4B6A95] text-white p-2.5 rounded-lg">
                                    <span>SOLDE RESTANT :</span>
                                    <span>{{ number_format($balance, 0, ',', ' ') }} CFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Flush Bottom Footer -->
        <div class="w-full bg-[#4B6A95] px-10 py-3 mt-8 flex justify-between items-center text-white text-[10px]">
            <div>
                <p class="font-bold">CFPT Sénégal-Japon / Programme PFPT</p>
                <p class="opacity-80 text-[9px]">Document officiel d'exécution de session</p>
            </div>
            <div class="text-right">
                <span class="opacity-90 font-medium">Page générée automatiquement</span>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const element = document.getElementById('report-content');
            const customTitle = document.getElementById('reportTitleInput').value.trim() || 'Rapport_Financier';
            const cleanFileName = customTitle.replace(/[^a-zA-Z0-9_\-]/g, '_') + '.pdf';
            
            const opt = {
                margin:       [0, 0, 0, 0],
                filename:     cleanFileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
