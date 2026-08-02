<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Formateurs</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Liste des Formateurs / Intervenants</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Édition</th>
                <th>Séminaire</th>
                <th>Rémunération</th>
                <th>Statut Paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $intervenant)
            <tr>
                <td>{{ $intervenant->id }}</td>
                <td>{{ $intervenant->name }}</td>
                <td>{{ $intervenant->seminar->edition->title ?? '' }}</td>
                <td>{{ $intervenant->seminar->title ?? '' }}</td>
                <td>{{ $intervenant->remuneration }}</td>
                <td>{{ $intervenant->payment_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
