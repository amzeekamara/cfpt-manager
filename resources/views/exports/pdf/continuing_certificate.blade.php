<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Formation Continue — CFPT Sénégal-Japon</title>
    <style>
        @page {
            margin: 18px;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #162536;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .outer-frame {
            border: 5px solid #162536;
            padding: 8px;
            height: 92%;
        }
        .inner-frame {
            border: 2px solid #4B6A95;
            padding: 28px 40px;
            text-align: center;
            position: relative;
        }
        .inst-header {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #4B6A95;
            margin-bottom: 4px;
        }
        .inst-name {
            font-size: 20px;
            font-weight: bold;
            color: #162536;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .dept-line {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 22px;
        }
        .cert-title {
            font-size: 30px;
            font-weight: bold;
            color: #162536;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 14px 0 8px;
            border-bottom: 2px solid #4B6A95;
            display: inline-block;
            padding-bottom: 6px;
        }
        .cert-subtitle {
            font-size: 12px;
            color: #475569;
            margin-bottom: 18px;
        }
        .beneficiary-name {
            font-size: 26px;
            font-weight: bold;
            color: #4B6A95;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .company-tag {
            font-size: 13px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 18px;
        }
        .training-details {
            font-size: 13px;
            line-height: 1.7;
            color: #334155;
            max-width: 85%;
            margin: 0 auto 24px;
        }
        .theme-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 20px;
            margin: 14px auto 22px;
            width: 80%;
        }
        .theme-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .signatures-table {
            width: 100%;
            margin-top: 30px;
        }
        .signatures-table td {
            width: 33.3%;
            text-align: center;
            vertical-align: top;
            font-size: 11px;
        }
        .cert-meta {
            margin-top: 25px;
            font-size: 9.5px;
            color: #64748b;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="outer-frame">
        <div class="inner-frame">
            <div class="inst-header">République du Sénégal • Ministère de la Formation Professionnelle et Technique</div>
            <div class="inst-name">Centre de Formation Professionnelle et Technique (CFPT) Sénégal-Japon</div>
            <div class="dept-line">
                Direction des Études • {{ $registration->seminar?->departments->pluck('name')->join(', ') ?: 'Service Formation Continue' }}
            </div>

            <div class="cert-title">ATTESTATION DE FORMATION CONTINUE</div>
            <div class="cert-subtitle">Le Directeur des Études du CFPT Sénégal-Japon atteste que :</div>

            <div class="beneficiary-name">{{ $registration->user->name }}</div>
            <div class="company-tag">
                Entreprise / Partenaire : {{ $registration->company_name ?: ($registration->seminar?->partner_company ?: ($registration->user->candidateProfile?->current_employer ?: 'Professionnel')) }}
            </div>

            <div class="training-details">
                a suivi avec assiduité et succès la session de <strong>Formation Continue Professionnelle</strong>
                d'une durée de <strong>{{ $registration->seminar?->duration_label ?: 'Courte durée' }}</strong>
                (du {{ $registration->seminar?->start_date ? $registration->seminar->start_date->format('d/m/Y') : '-' }}
                au {{ $registration->seminar?->end_date ? $registration->seminar->end_date->format('d/m/Y') : '-' }}), portant sur :
            </div>

            <div class="theme-box">
                <div class="theme-title">« {{ $registration->seminar?->title }} »</div>
                <div style="font-size: 12px; color: #4B6A95; font-weight: bold; margin-top: 4px;">
                    Thème technique : {{ $registration->seminar?->specific_theme ?: ($registration->topic?->name ?: 'Perfectionnement Professionnel') }}
                </div>
            </div>

            <div style="font-size: 11.5px; color: #475569; margin-bottom: 10px;">
                En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit.
            </div>

            <table class="signatures-table">
                <tr>
                    <td>
                        <strong>Le Chef de Département</strong><br>
                        <span style="color: #64748b; font-size: 10px;">{{ $registration->seminar?->departments->first()?->name ?? 'Département Technique' }}</span>
                        <div style="height: 50px;"></div>
                    </td>
                    <td>
                        <strong>Fait à Dakar, le {{ now()->format('d/m/Y') }}</strong><br>
                        <span style="color: #64748b; font-size: 10px;">Cachet Officiel du CFPT</span>
                    </td>
                    <td>
                        <strong>Le Directeur des Études</strong><br>
                        <span style="color: #64748b; font-size: 10px;">CFPT Sénégal-Japon</span>
                        <div style="height: 50px;"></div>
                    </td>
                </tr>
            </table>

            <div class="cert-meta">
                N° Attestation : <strong>{{ $registration->certificate_number }}</strong> • Année Académique : {{ $registration->seminar?->cohort?->title ?? date('Y') }}
            </div>
        </div>
    </div>
</body>
</html>
