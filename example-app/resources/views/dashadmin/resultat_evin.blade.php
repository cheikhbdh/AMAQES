@extends('dashadmin.home')

@section('content')
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Résultats de l'Évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main id="main" class="main">
        <div class="container mt-5">
            <h1 class="mb-4">Résultats de l'Évaluation Interne</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom de la filière</th>
                        <th>Champ 1</th>
                        <th>Champ 2</th>
                        <th>Score</th>
                        <th>Plus de détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultats as $resultat)
                        <tr>
                            <td>{{ $resultat['filiere'] }}</td>
                            <td>{{ $resultat['champs'][0]['champ'] }}</td>
                            <td>{{ $resultat['champs'][1]['champ'] }}</td>
                            <td>{{ $resultat['champs'][0]['score'] + $resultat['champs'][1]['score'] }}</td>
                            <td>
                                <button class="btn btn-primary">Plus de détails</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
