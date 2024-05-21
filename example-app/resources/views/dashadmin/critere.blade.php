@extends('dashadmin.home')

@section('content')
<head>
  <link rel="stylesheet" href="{{ asset('assets/css/ajout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/edit.css') }}">
</head>
<main id="main" class="main">
  <h1>Critères pour le champ : {{ $champ->name }}</h1>
  <a href="{{ route('champ') }}">Retour à la liste des champs</a>
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
  
            <h5 class="card-title">Gestion des critères</h5>
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
                <form id="ajouterForm" action="{{ route('critere.ajouter', ['champ_id' => $champ->id]) }}" method="POST">
                  @csrf
                  <input type="hidden" name="champ_id" value="{{ $champ->id }}"> <!-- Champ_id caché -->
                  <label for="name">Nom:</label>
                  <input type="text" id="name" name="name" required>
                  <br><br>
                  <label for="preuve">Preuves du critère:</label>
                  <input type="text" id="preuve" name="preuve" required>
                  <br><br>
                  <button type="submit" class="btn btn-success">Soumettre</button>
                </form>
              </div>
            </div>
            <table class="table data-table">
              <tr>
                <th>Nom</th>
                <th>Preuves du critère</th>
                <th>Actions</th>
              </tr>
              <tbody>
                @foreach($champ->criteres as $critere)
                <tr>
                  <td>{{ $critere->nom }}</td>
                  <td>{{ $critere->preves_critere }}</td>
                  <td>
                    <button class="btn btn-info modifierBtn" data-id="{{ $critere->id }}">Modifier</button>
                  </td>
                  <td>
                    <form action="{{ route('critere.supprimer', $critere->id) }}" method="POST" class="supprimerForm">
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
    <form id="editForm" method="POST">
      @csrf
      @method('PUT') <!-- Utilisez la méthode PUT pour la modification -->
      <input type="hidden" id="editCritereId" name="critereId">
      <label for="editName">Nom:</label>
      <input type="text" id="editName" name="name" required>
      <br><br>
      <label for="editPreuve">Preuves du critère:</label>
      <input type="text" id="editPreuve" name="preuve" required>
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

  const modifierBtns = document.querySelectorAll('.modifierBtn');
  const editModal = document.getElementById('editModal');
  const closeModalBtn = editModal.querySelector('.close');
  const editForm = editModal.querySelector('form');

  // Event listener for edit buttons
  modifierBtns.forEach((button) => {
    button.addEventListener('click', () => {
      const critereId = button.getAttribute('data-id');
      const row = button.closest('tr');
      const name = row.cells[0].innerText;
      const preves_critere = row.cells[1].innerText;

      openEditModal(critereId, name, preves_critere);
    });
  });

  // Function to open the edit modal
  function openEditModal(critereId, name, preves_critere) {
    document.getElementById('editCritereId').value = critereId;
    document.getElementById('editName').value = name;
    document.getElementById('editPreuve').value = preves_critere;
    document.getElementById('editForm').action = "/critere/" + critereId + "/modifier"; // Set the action of the form with critere ID
    editModal.style.display = "block";
  }

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
