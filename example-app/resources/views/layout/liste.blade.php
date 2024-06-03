@extends('layout.layout-index')
@section('content')
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .custom-container {
            max-width: 600px; 
            margin-left: auto; 
            margin-right: auto; 
            
        }
        body{
            background-color: #edf2f4;
        }
    </style>
</head>
<body>
<div class="custom-container">

        <h2 class="mb-1">Évaluation</h2>
        <form>
            <div class="mb-3">
                <label for="institut" class="form-label">Institut</label>
                <select class="form-select" id="institut">
                    <option selected>Choisissez l'institut</option>
                    <option value="1">Institut A</option>
                    <option value="2">Institut B</option>
                    <option value="3">Institut C</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="domaine" class="form-label">Domaine</label>
                <select class="form-select" id="domaine">
                    <option selected>Choisissez le domaine</option>
                    <option value="1">Domaine A</option>
                    <option value="2">Domaine B</option>
                    <option value="3">Domaine C</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="filiere" class="form-label">filiere</label>
                <select class="form-select" id="domaine">
                    <option selected>Choisissez le domaine</option>
                    <option value="1">filiere A</option>
                    <option value="2">filiere B</option>
                    <option value="3">filiere C</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">comencer l'evaluation</button>
            </div>
        </form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
@endsection
