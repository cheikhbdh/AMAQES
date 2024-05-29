@extends('dashadmin.home')

@section('content')
<head>
  <link rel="stylesheet" href="{{ asset('assets/css/ajout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/edit.css') }}">
</head>
<main id="main" class="main">
  <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashadmin') }}">dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('show.referent') }}">Les référentiels</a></li>
        <li class="breadcrumb-item">les champs</li>
    </ol>
  </nav>
  <h2>Champs pour le référentiel : {{ $referentiel->name }}</h2>
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h5 class="card-title">Gestion des champs</h5>
            <!-- Button to open the modal -->
            <button id="ajouterBtn" class="btn btn-primary mb-3">Ajouter</button>

            <!-- Modal for the form -->
            <div id="formModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <form id="ajouterForm" action="{{ route('champ.ajouter', ['referentielId' => $referentiel->id]) }}" method="POST">
                  @csrf
                  <label for="name">Nom:</label>
                  <input type="text" id="name" name="name" required>
                  <br><br>
                  <button type="submit" class="btn btn-success">Soumettre</button>
                </form>
              </div>
            </div>
            <table class="table data-table">
              <tr>
                <th>Les critères</th>
                <th>Nom</th>
                <th style="text-align: center">Action</th>
              </tr>
              <tbody>
                @foreach($referentiel->champs as $champ)
    <tr>
        <td>
            <a href="{{ route('champs.criteres', ['referentielId' => $referentiel->id,'champId' => $champ->id]) }}" class="btn btn-info">View</a>
        </td>
        <td>{{ $champ->name }}</td>
        <td>
            <button class="btn btn-info modifierBtn" data-id="{{ $champ->id }}">Modifier</button>
        </td>
        <td>
            <form action="{{ route('champ.supprimer', $champ->id) }}" method="POST" class="supprimerForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </td>
    </tr>
@endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal for the edit form -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form id="editForm" action="" method="POST">
        @csrf
        @method('PUT') <!-- Utilisez la méthode PUT pour la modification -->
        <input type="hidden" id="editChampId" name="champId">
        <label for="editName">Nom:</label>
        <input type="text" id="editName" name="name" required>
        <br><br>
        <button type="submit" class="btn btn-success">Modifier</button>
      </form>
    </div>
  </div>
</main><!-- End #main -->

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("formModal");
    const ajouterBtn = document.getElementById("ajouterBtn");
    const span = document.getElementsByClassName("close")[0];

    ajouterBtn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

document.addEventListener('DOMContentLoaded', (event) => {
    const modifierBtns = document.querySelectorAll('.modifierBtn');
    const supprimerForms = document.querySelectorAll('.supprimerForm');
    const editModal = document.getElementById('editModal');
    const closeModalBtn = editModal.querySelector('.close');
    const editForm = editModal.querySelector('form');

    // Event listener for edit buttons
    modifierBtns.forEach((button) => {
        button.addEventListener('click', () => {
            const champId = button.getAttribute('data-id');
            const row = button.closest('tr');
            const name = row.cells[1].innerText;

            // Call the function to open the edit form with selected user data
            openEditModal(champId, name);
        });
    });

    // Function to open the edit modal
    function openEditModal(champId, name) {
        document.getElementById('editChampId').value = champId;
        document.getElementById('editName').value = name;
        document.getElementById('editForm').action = "/champs/" + champId + "/modifier"; // Set the action of the form with user ID
        editModal.style.display = "block";
    }

    // Event listener for delete forms
    supprimerForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this champ?')) {
                form.submit();
            }
        });
    });

    // Event listener for close button of edit form
    closeModalBtn.addEventListener('click', () => {
        editModal.style.display = "none";
    });

    // Event listener for closing edit form when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
    });
});
</script>

@endsection
