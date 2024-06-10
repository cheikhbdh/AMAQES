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
        <li class="breadcrumb-item">Les référentiels</li>
        
    </ol>
</nav>

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
  
            <h5 class="card-title">Gestion des référentiels</h5>
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
                <form id="ajouterForm" action="{{ route('referentiel.ajouter') }}" method="POST">
                  @csrf
                  <label for="name">Nom:</label>
                  <input type="text" id="name" name="name" required>
                  <br><br>
                  <button type="submit" class="btn btn-success">Soumettre</button>
                </form>
              </div>
            </div>
            <table class="table data-table">
              <thead>
                <tr>
                  <th>Les champs</th>
                  <th>Nom</th>
                  <th style="text-align: center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($referentiels as $referentiel)
                  <tr>
                    <td>
                        <a href="{{ route('referents.champs', $referentiel->id) }}" class="btn btn-info">View</a>
                    </td>
                    <td>{{ $referentiel->name }}</td>
                    <td>
                      <button class="btn btn-info modifierBtn" data-id="{{ $referentiel->id }}" data-name="{{ $referentiel->name }}">Modifier</button>
                      <form action="{{ route('referentiel.supprimer', $referentiel->id) }}" method="POST" class="d-inline supprimerForm">
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
        @method('PUT')
        <input type="hidden" id="editReferentielId" name="referentielId">
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
                const referentielId = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                // Call the function to open the edit form with selected referentiel data
                openEditModal(referentielId, name);
            });
        });

        // Function to open the edit modal
        function openEditModal(referentielId, name) {
            document.getElementById('editReferentielId').value = referentielId;
            document.getElementById('editName').value = name;
            editForm.action = `/referentiels/${referentielId}/modifier`; // Set the action of the form with referentiel ID
            editModal.style.display = "block";
        }

        // Event listener for delete forms
        supprimerForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this référentiel?')) {
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
