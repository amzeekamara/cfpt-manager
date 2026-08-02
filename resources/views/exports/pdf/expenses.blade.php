<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Dépenses</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Liste des Dépenses</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Édition</th>
                <th>Séminaire</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $expense)
            <tr>
                <td>{{ $expense->id }}</td>
                <td>{{ $expense->description }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->edition->title ?? '' }}</td>
                <td>{{ $expense->seminar->title ?? '' }}</td>
                <td>{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
