<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de Notes — CFPT Sénégal-Japon</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #4B6A95;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #162536;
            margin: 0;
        }
        .inst-sub {
            font-size: 10px;
            color: #4B6A95;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .doc-badge {
            background-color: #4B6A95;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-align: right;
            display: inline-block;
        }
        .student-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 18px;
        }
        .student-box td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .label {
            color: #64748b;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .grades-table th {
            background-color: #162536;
            color: #ffffff;
            font-size: 10px;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #162536;
        }
        .grades-table td {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            font-size: 11px;
        }
        .grades-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .summary-card {
            background-color: #f1f5f9;
            border: 1px solid #94a3b8;
            padding: 12px;
            border-radius: 6px;
        }
        .avg-highlight {
            font-size: 18px;
            font-weight: bold;
            color: #4B6A95;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            font-size: 11px;
        }
        .footer {
            position: fixed;
            bottom: 15px;
            left: 20px;
            right: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 65%;">
                <p class="inst-title">CENTRE DE FORMATION PROFESSIONNELLE ET TECHNIQUE</p>
                <p class="inst-sub">CFPT SÉNÉGAL-JAPON — DIRECTION DES ÉTUDES</p>
                <p style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    Département : <strong>{{ $registration->seminar?->departments->pluck('name')->join(', ') ?: 'Enseignement Technique' }}</strong>
                </p>
            </td>
            <td style="width: 35%; text-align: right; vertical-align: top;">
                <span class="doc-badge">RELEVÉ / BULLETIN DE NOTES</span>
                <p style="font-size: 9.5px; color: #64748b; margin-top: 6px;">
                    Édité le {{ now()->format('d/m/Y à H:i') }}
                </p>
            </td>
        </tr>
    </table>

    <table class="student-box">
        <tr>
            <td style="width: 50%;">
                <span class="label">Étudiant(e) :</span><br>
                <strong style="font-size: 13px; color: #0f172a;">{{ $registration->user->name }}</strong>
            </td>
            <td style="width: 50%;">
                <span class="label">Filière / Cours :</span><br>
                <strong>{{ $registration->seminar?->title ?? 'Formation CFPT' }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Email / Identifiant :</span><br>
                {{ $registration->user->email }}
            </td>
            <td>
                <span class="label">Spécialité / Module :</span><br>
                {{ $registration->topic?->name ?? 'Tronc Commun' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Année Académique / Promotion :</span><br>
                {{ $registration->seminar?->cohort?->title ?? date('Y') }}
            </td>
            <td>
                <span class="label">Assiduité (Présences / Absences) :</span><br>
                <strong>{{ $presentCount }} présence(s)</strong> / <span style="color: #dc2626;">{{ $absentCount }} absence(s)</span>
            </td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th>Évaluation / Épreuve</th>
                <th>Type</th>
                <th>Matière / Module</th>
                <th style="text-align: center;">Date</th>
                <th style="text-align: center;">Coef.</th>
                <th style="text-align: center;">Note obtenue</th>
                <th style="text-align: center;">Note / 20</th>
                <th>Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                @php
                    $eval = $row['evaluation'];
                    $grade = $row['grade'];
                    $score20 = $row['score20'];
                @endphp
                <tr>
                    <td><strong>{{ $eval->title }}</strong></td>
                    <td style="text-transform: uppercase; font-size: 9.5px; font-weight: bold;">{{ $eval->type }}</td>
                    <td>{{ $eval->topic?->name ?? 'Général' }}</td>
                    <td style="text-align: center;">{{ $eval->evaluation_date ? $eval->evaluation_date->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;"><strong>{{ $eval->coefficient }}</strong></td>
                    <td style="text-align: center;">
                        @if($grade && $grade->score !== null)
                            <strong>{{ number_format($grade->score, 2) }}</strong> / {{ number_format($eval->max_score, 0) }}
                        @else
                            <span style="color: #94a3b8;">Non noté</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        @if($score20 !== null)
                            {{ number_format($score20, 2) }} / 20
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $grade?->appreciation ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">
                        Aucune évaluation enregistrée pour cette formation.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-box">
        <tr>
            <td class="summary-card" style="width: 60%;">
                <span class="label">Décision & Mention du Jury :</span><br>
                <strong style="font-size: 14px; color: #0f172a;">{{ $mention }}</strong>
                <p style="margin: 4px 0 0; font-size: 10px; color: #475569;">
                    Total des coefficients évalués : <strong>{{ $totalCoef }}</strong>
                </p>
            </td>
            <td class="summary-card" style="width: 40%; text-align: right;">
                <span class="label">MOYENNE GÉNÉRALE :</span><br>
                <span class="avg-highlight">
                    {{ $generalAverage !== null ? number_format($generalAverage, 2) . ' / 20' : 'N/A' }}
                </span>
            </td>
        </tr>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <strong>Le Chef de Département</strong><br>
                <span style="font-size: 9.5px; color: #64748b;">Visa et cachet du département</span>
                <div style="height: 55px;"></div>
            </td>
            <td style="text-align: right;">
                <strong>Le Directeur des Études</strong><br>
                <span style="font-size: 9.5px; color: #64748b;">CFPT Sénégal-Japon</span>
                <div style="height: 55px;"></div>
            </td>
        </tr>
    </table>

    <div class="footer">
        CFPT Manager — Centre de Formation Professionnelle et Technique Sénégal-Japon • Document officiel généré électroniquement.
    </div>

</body>
</html>
