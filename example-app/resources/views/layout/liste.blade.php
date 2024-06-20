@extends('layout.layout-index')

@section('content')
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Évaluation des Champs et Critères</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-container {
          max-width: 1200px;
          margin: auto;
          padding: 20px;
        }


  .critere-list {
    margin-left: 20px; 
  }
  .reference-arrow {
        margin-right: 5px;
        font-size: 20px;
        vertical-align: middle; /* Ajout de cette propriété */
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
        .reference-arrow {
    margin-right: 5px; 
    font-size: 20px; 
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
        .hidden-section {
    display: none;
}

  #statistics-container {
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      width: 100%;
      max-width: 600px;
      margin: 20px auto;
  }

  .statistic {
      position: relative;
      height: 400px;
  }

  .statistic-details {
      margin-top: 20px;
  }

  .statistic-details h4 {
      margin: 5px 0;
  }

        .progress-bar {
          display: flex;
          flex-direction: row;
          height: 20px;
          width: 100%;
          background-color: #ddd;
          border-radius: 5px;
          overflow: hidden;
          margin-bottom: 20px;
        }
        
        .progress-bar div {
          flex: 1;
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
        #download-stats, #go-home {
       margin-top: 20px;
       margin-right: 10px;
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
                    
                    @if($champNonEvaluer)
                        <div class="col-md-4">
                            <div class="champ-box champ-box-hover" id="champ-{{ $champNonEvaluer->id }}" data-champ-id="{{ $champNonEvaluer->id }}">
                                <h4>{{ $champNonEvaluer->name }}</h4>
                                <button class="btn btn-secondary btn-evaluer">Évaluer</button>
                            </div>
                        </div>
                    @endif
                    <div id="snackbar"></div>
                </div>
                <div id="statistics-container" class="hidden-section">
                  <h3 style="color: 	#bf00ff;">Statistiques d'évaluation</h3>
                  <div class="statistic">
                      <canvas id="evaluationChart" width="400" height="400"></canvas>
                  </div>
                  <div class="statistic-details">
                      <h3>Taux de Conformité  aux critéres du <span style="color: blue;">{{ $champNonEvaluer->name }} </span>est de: <span id="taux-conformite"></span>%</h3>
                  </div>
                  <button id="download-stats" class="btn btn-primary mt-3">Télécharger les Statistiques</button>
                    <button id="go-home" class="btn btn-secondary mt-3">Retour à l'accueil</button>
              </div>
              
                <div id="evaluation-section" class="hidden-section">
                  <h2 class="mb-4 text-center" id="evaluation-title"></h2>
                  <span id="error-${preuve.id}" class="text-danger"></span>
                  <form action="{{ route('evaluate') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <input type="hidden" name="idchamps" value="{{ $champNonEvaluer->id }}">
                      <div id="references-container" class="list-group"></div>
                     
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const champsNonEvaluer = @json($champNonEvaluer);
    const chnev = @json($CHNEV);
    const totalChamps = 5;
    let evaluatedChamps = 5 - chnev.length;

    document.querySelectorAll('.champ-box').forEach(box => {
        box.addEventListener('click', () => {
            let champId = box.getAttribute('data-champ-id');
            let champ = champsNonEvaluer;

            document.getElementById('evaluation-title').innerText = champ.name;
            document.getElementById('champs-non-evaluer-container').classList.add('hidden');
            document.getElementById('evaluation-section').classList.remove('hidden-section');

            let referencesContainer = document.getElementById('references-container');
            referencesContainer.innerHTML = '';

            champ.references.forEach(reference => {
                let referenceBox = document.createElement('div');
                referenceBox.className = 'reference-box';
                referenceBox.innerHTML = `
                    <div class="reference-box" style="display: inline-block;">
                        <span class="reference-arrow">&#10148;</span>${reference.signature} : ${reference.nom}
                    </div>
                    <div class="criteres-container critere-list">
                        ${reference.criteres.map(critere => `
                            <div class="critere-box">
                                <h5 style="color: blue;">${critere.signature} : ${critere.nom}</h5>
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
                                            <span id="error-${preuve.id}" class="text-danger"></span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                referencesContainer.appendChild(referenceBox);
            });

            document.getElementById('champs-non-evaluer-container').classList.add('hidden');
            document.getElementById('evaluation-section').classList.remove('hidden-section');
            window.scrollTo(0, document.getElementById('evaluation-section').offsetTop);
        });
    });

    document.getElementById('references-container').addEventListener('change', function(event) {
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

    const form = document.querySelector('form');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const preuves = document.querySelectorAll('input[type="radio"][name^="evaluations"]');
        let allPreuvesFilled = true;
        const evaluations = {
            oui: 0,
            non: 0,
            na: 0,
            total: 0
        };

        preuves.forEach((preuve) => {
            if (preuve.checked) {
                evaluations.total++;
                const preuveId = preuve.getAttribute('data-preuve-id');
                const fileTypeInput = document.getElementById(`file-${preuveId}`);
                const commentInput = document.getElementById(`comment-${preuveId}`);
                const errorSpan = document.getElementById(`error-${preuveId}`);

                if (preuve.value === 'na' && commentInput.value === '') {
                    allPreuvesFilled = false;
                    errorSpan.innerText = `Vous devez ajouter un commentaire pour la preuve .`;
                    return;
                }
                if (preuve.value === 'oui' && fileTypeInput.value === '') {
                    allPreuvesFilled = false;
                    errorSpan.innerText = `Vous devez sélectionner un fichier pour la preuve .`;
                    return;
                }

                // Incrementing the counts for statistics
                evaluations[preuve.value] += 1;

                // Reset error message if conditions are met
                errorSpan.innerText = ''; 
            }
        });

        if (allPreuvesFilled) {
            form.submit();
   
        }
  
    });

    function displayStatistics(evaluations) {
        const ouiPercentage = (evaluations.oui / evaluations.total) * 100;
        const nonPercentage = (evaluations.non / evaluations.total) * 100;
        const naPercentage = (evaluations.na / evaluations.total) * 100;
        const tauxConformite = (evaluations.oui / evaluations.total) * 100;

        document.getElementById('taux-conformite').innerText = tauxConformite.toFixed(2);

        document.getElementById('statistics-container').classList.remove('hidden-section');

        const ctx = document.getElementById('evaluationChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Oui', 'Non', 'NA'],
                datasets: [{
                    data: [ouiPercentage, nonPercentage, naPercentage],
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    document.getElementById('download-stats').addEventListener('click', () => {
        const statisticsContainer = document.getElementById('statistics-container');
        html2canvas(statisticsContainer).then(canvas => {
            const link = document.createElement('a');
            link.download = 'statistiques.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });

    document.getElementById('go-home').addEventListener('click', () => {
        window.location.href = '/indexevaluation'; // Assurez-vous que '/' est l'URL correcte pour la page d'accueil de votre application
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