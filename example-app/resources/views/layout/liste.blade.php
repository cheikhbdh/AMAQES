@extends('layout.layout-index')

@section('content')
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Évaluation des Champs et Critères</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-container {
          max-width: 1200px;
          margin: auto;
          padding: 20px;
        }
        .champ-box {
          background-size: cover;
          border: 1px solid #ddd;
          border-radius: 8px;
          padding: 40px 20px;
          margin-bottom: 20px;
          position: relative;
          overflow: hidden;
          transition: transform 0.3s, box-shadow 0.3s;
          cursor: pointer;
          height: 150px;
        }
        .champ-box:hover {
          transform: translateY(-5px);
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .champ-box:hover:before {
          content: '';
          position: absolute;
          width: 200px;
          height: 200px;
          background-color: rgba(0, 123, 255, 0.1);
          border-radius: 50%;
          top: -50px;
          right: -50px;
        }
        .champ-box .btn-evaluer {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          opacity: 0;
          transition: opacity 0.3s;
        }
        .champ-box:hover .btn-evaluer {
          opacity: 1;
        }
        .critere-box {
          background-color: #f8f9fa;
          border: 1px solid #ddd;
          border-radius: 8px;
          margin-bottom: 10px;
          padding: 10px;
        }
        .preuve-options {
          display: flex;
          align-items: center;
          justify-content: space-between;
          margin-top: 5px;
        }
        .hidden {
          display: none;
        }
        body {
          background-color: #edf2f4;
        }
        .hidden-section {
          display: none;
        }
        .champ-box-hover::before {
          content: '';
          position: absolute;
          width: 200px;
          height: 200px;
          background-color: rgba(0, 123, 255, 0.1);
          border-radius: 50%;
          top: -50px;
          right: -50px;
          transition: opacity 0.3s;
          opacity: 0;
        }
        .champ-box-hover:hover::before {
          opacity: 1;
        }
        .progress-bar {
        ```css
          display: flex;
          flex-direction: row; /* Disposer les éléments en ligne */
          height: 20px;
          width: 100%;
          background-color: #ddd;
          border-radius: 5px;
          overflow: hidden;
          margin-bottom: 20px;
        }
        
        .progress-bar div {
          flex: 1; /* Chaque segment occupe une part égale de la largeur */
          transition: background-color 0.3s;
        }
        
        .progress-bar div.completed {
          background-color: green;
        }
        .snackbar {
          visibility: hidden;
          min-width: 250px;
          background-color: #333;
          color: #fff;
          text-align: center;
          border-radius: 2px;
          padding: 16px;
          position: fixed;
          z-index: 1;
          left: 50%;
          transform: translateX(-50%);
          bottom: 30px;
          font-size: 17px;
        }
        .snackbar.show {
          visibility: visible;
        }
        </style>
</head>
<body>
    <main id="main" class="main">
        <div class="custom-container">
            @if($hasActiveInvitation)
                <div class="progress-bar" id="progress-bar">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="row" id="champs-non-evaluer-container">
                    <h3 class="display-4"> Évaluation en cours:</h3>
                    @foreach($champsNonEvaluer as $champ)
                        <div class="col-md-4">
                            <div class="champ-box champ-box-hover" id="champ-{{ $champ->id }}" data-champ-id="{{ $champ->id }}">
                                <h4>{{ $champ->name }}</h4>
                                <button class="btn btn-secondary btn-evaluer">Évaluer</button>
                            </div>
                        </div>
                    @endforeach
                    <div id="snackbar"></div>
                </div>

                <div id="evaluation-section" class="hidden-section">
                    <h2 class="mb-4" id="evaluation-title"></h2>
                    <form action="{{ route('evaluate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="criteres-container" class="list-group"></div>
                        <button type="submit" class="btn btn-primary">Soumettre</button>
                        <button type="button" class="btn btn-secondary" id="btn-retour">Retour</button>
                    </form>
                </div>
            @else
            <div class="alert alert-info text-center">
                <h4>Vous n'avez aucune invitation active pour évaluer des champs.</h4>
              </div>
            @endif
        </div>
    </main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const champsNonEvaluer = @json($champsNonEvaluer);
    const totalChamps = 5;
    let evaluatedChamps = 5 - champsNonEvaluer.length;

    document.querySelectorAll('.champ-box').forEach(box => {
        box.addEventListener('click', () => {
            let champId = box.getAttribute('data-champ-id');
            let champ = champsNonEvaluer.find(c => c.id == champId);

            document.getElementById('evaluation-title').innerText = 'Évaluer Champ: ' + champ.name;
            let criteresContainer = document.getElementById('criteres-container');
            criteresContainer.innerHTML = '';
            champ.criteres.forEach((critere, index) => {
                let critereBox = document.createElement('div');
                critereBox.className = 'critere-box';
                critereBox.innerHTML = `
                    <h5>Critère ${index + 1}: ${critere.nom}</h5>
                    <div class="preuves">
                        ${critere.preuves.map(preuve => `
                            <div class="d-flex flex-column align-items-start">
                                <p class="flex-grow-1">${preuve.description}</p>
                                <div class="preuve-options">
                                    <label class="mx-2">
                                        <input type="radio" name="evaluations[${preuve.id}][value]" value="oui" data-preuve-id="${preuve.id}" required>
                                        Oui
                                    </label>
                                    <label class="mx-2">
                                        <input type="radio" name="evaluations[${preuve.id}][value]" value="non" data-preuve-id="${preuve.id}" required> Non
                                    </label>
                                    <label class="mx-2">
                                        <input type="radio" name="evaluations[${preuve.id}][value]" value="na" data-preuve-id="${preuve.id}" required> N/A
                                    </label>
                                </div>
                                <input type="file" name="file-${preuve.id}" class="hidden mt-2" id="file-${preuve.id}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <textarea name="evaluations[${preuve.id}][commentaire]" class="form-control hidden mt-2" id="comment-${preuve.id}" placeholder="Ajouter un commentaire"></textarea>
                                <input type="hidden" name="evaluations[${preuve.id}][idcritere]" value="${critere.id}">
                                <input type="hidden" name="evaluations[${preuve.id}][idpreuve]" value="${preuve.id}">
                            </div>
                        `).join('')}
                    </div>
                `;
                criteresContainer.appendChild(critereBox);
            });

            document.getElementById('champs-non-evaluer-container').classList.add('hidden');
            document.getElementById('evaluation-section').classList.remove('hidden-section');
            window.scrollTo(0, document.getElementById('evaluation-section').offsetTop);
        });
    });

    document.getElementById('criteres-container').addEventListener('change', function(event) {
        if (event.target.matches('input[type="radio"]')) {
            let preuveId = event.target.getAttribute('data-preuve-id');
            let fileInput = document.getElementById(`file-${preuveId}`);
            let commentInput = document.getElementById(`comment-${preuveId}`);
            if (event.target.value === 'oui') {
                fileInput.classList.remove('hidden');
                commentInput.classList.add('hidden');
            } else if (event.target.value === 'na') {
                commentInput.classList.remove('hidden');
                fileInput.classList.add('hidden');
            } else {
                fileInput.classList.add('hidden');
                commentInput.classList.add('hidden');
            }
        }
    });

    document.getElementById('btn-retour').addEventListener('click', () => {
        document.getElementById('champs-non-evaluer-container').classList.remove('hidden');
        document.getElementById('evaluation-section').classList.add('hidden-section');
        updateSnackbar();
        updateProgressBar();
    });

    function updateSnackbar() {
        const percentage = (evaluatedChamps / totalChamps) * 100;
        const snackbar = document.getElementById('snackbar');
        snackbar.innerText = `${percentage.toFixed(2)}% des champs sont évalués`;
        snackbar.classList.add('show');

        setTimeout(() => {
            snackbar.classList.remove('show');
        }, 3000);
    }

    function updateProgressBar() {
        const progressBar = document.getElementById('progress-bar');
        const stepCount = progressBar.children.length;
        const completedSteps = Math.floor((evaluatedChamps / totalChamps) * stepCount);
        for (let i = 0; i < stepCount; i++) {
            progressBar.children[i].classList.toggle('completed', i < completedSteps);
        }
    }

    updateProgressBar();
});
</script>
</body>
@endsection
