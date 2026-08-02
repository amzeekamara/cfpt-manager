<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Candidats</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Liste des Candidats</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Email</th>
                <th>Sexe</th>
                <th>Nationalité</th>
                <th>Édition(s)</th>
                <th>Séminaire(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $candidate)
            @php
                $editions = [];
                $seminars = [];
                if ($candidate->user && $candidate->user->enrollments) {
                    foreach ($candidate->user->enrollments as $enrollment) {
                        if ($enrollment->seminar) {
                            if ($enrollment->seminar->edition) {
                                $editions[] = $enrollment->seminar->edition->title;
                            }
                            $seminars[] = $enrollment->seminar->title;
                        }
                    }
                }
            @endphp
            <tr>
                <td>{{ $candidate->id }}</td>
                <td>{{ $candidate->last_name ?? '' }}</td>
                <td>{{ $candidate->first_names ?? '' }}</td>
                <td>{{ $candidate->user->email ?? '' }}</td>
                <td>{{ $candidate->gender ?? '' }}</td>
                <td>{{ $candidate->nationality ?? '' }}</td>
                <td>{{ implode(', ', array_unique(array_filter($editions))) }}</td>
                <td>{{ implode(', ', array_unique(array_filter($seminars))) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
