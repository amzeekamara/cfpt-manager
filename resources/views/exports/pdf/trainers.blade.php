<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Formateurs / Formateurs</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #1e4b3e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
            border: none;
        }
        .header td {
            border: none;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .republique { font-weight: bold; font-size: 12px; margin-bottom: 3px; }
        .ministere { font-size: 10px; margin-bottom: 3px; }
        .cfpt { font-weight: bold; font-size: 14px; color: #1e4b3e; }
        
        .resource-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #1e4b3e;
            padding: 10px;
            background-color: #f3f4f8;
            border: 1px solid #ddd;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: left;
        }
        table.data-table th {
            background-color: #1e4b3e;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .footer {
            position: fixed; 
            bottom: -30px; 
            left: 0px; 
            right: 0px;
            height: 30px; 
            font-size: 9px;
            color: #64748b;
            border-top: 1px solid #cbd5e1;
            padding-top: 5px;
        }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; }
    </style>
</head>
<body>

    @php
        $path = public_path('images/LOGO PFPT.png');
        if(file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = '';
        }
    @endphp

    <div class="header">
        <table>
            <tr>
                <td width="30%" class="text-left">
                    <div class="republique">République du Sénégal</div>
                    <div class="ministere">Un Peuple - Un But - Une Foi</div>
                </td>
                <td width="40%" class="text-center">
                    <div class="cfpt">CFPT Sénégal-Japon</div>
                    <div class="ministere">Centre de Formation Professionnelle et Technique</div>
                </td>
                <td width="30%" class="text-right">
                    @if($base64)
                        <img src="{{ $base64 }}" style="max-height: 50px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="resource-title">Liste des Formateurs / Formateurs</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom Complet</th>
                <th>Spécialité</th>
                <th>Téléphone</th>
                <th>Rémunération</th>
                <th>Édition(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item[0] }}</td>
                <td><strong>{{ $item[1] }}</strong> {{ $item[2] }}</td>
                <td>{{ $item[5] }}</td>
                <td>{{ $item[6] }}</td>
                <td>{{ $item[9] }} FCFA</td>
                <td>{{ $item[12] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer for page numbers using domPDF script -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("Helvetica");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>
    
    <div class="footer">
        <table>
            <tr>
                <td width="50%" class="text-left">Généré le : {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</td>
                <td width="50%" class="text-right">Plateforme de Gestion - CFPT SJ</td>
            </tr>
        </table>
    </div>

</body>
</html>
