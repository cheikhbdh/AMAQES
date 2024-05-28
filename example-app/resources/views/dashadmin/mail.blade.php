<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject }}</title>
</head>
<body>
    <h4>{{ $subject }}</h4>
    <p>Nom: {{ $invitation->nom }}</p>
    <p>Description: {{ $invitation->description }}</p>
    <p>Date de fin: {{ $invitation->date_fin }}</p>
    <a href="http://127.0.0.1:8000">Cliquez ici pour accéder au site</a>
</body>
</html>
