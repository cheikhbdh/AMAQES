@extends('dashadmin.home')

@section('content')
<head>
  <link rel="stylesheet" href="{{ asset('assets/css/ajout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/edit.css') }}">

</head>
<main id="main" class="main">


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
  
            <h5 class="card-title">Gestion des utilisateurs</h5>
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
                <form id="ajouterForm" action="{{ route('utilisateur.ajouter') }}" method="POST">
                  @csrf
                  <label for="name">Nom:</label>
                  <input type="text" id="name" name="name" required>
                  <br><br>
                  <label for="email">Email:</label>
                  <input type="email" id="email" name="email" required>
                  <br><br>
                  <label for="password">Mot de passe:</label>
                  <input type="password" id="password" name="password" required>
                  <br><br>
                  <label for="password_confirmation">Confirmer le mot de passe:</label>
                  <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                  <br><br>
                  <label for="role">Rôle:</label>
                  <select id="role" name="role" class="form-control" required>
                    <option value="evaluateur_i">évaluateur_In</option>
                    <option value="evaluateur_e">évaluateur_Ex</option>
                    <option value="admin">admin</option>
                  </select>
                  <br><br>
                  <button type="submit" class="btn btn-success">Soumettre</button>
                </form>
              </div>
            </div>
            <table class="table data-table">
              <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Mot de passe</th>
                <th>Rôle</th>
                <th>Action</th>
              </tr>
              <tbody>
                @php
                  $utilisateurs = App\Models\User::all();
                @endphp
                @foreach($utilisateurs as $utilisateur)
                  <tr>
                    <td>{{ $utilisateur->name }}</td>
                    <td>{{ $utilisateur->email }}</td>
                    <td>{{ $utilisateur->password }}</td>
                    <td>{{ $utilisateur->role }}</td>
                    <td>
                      <button class="btn btn-info modifierBtn" data-id="{{ $utilisateur->id }}">Modifier</button>
                    </td>
                    <td>
                      <form action="{{ route('utilisateur.supprimer', $utilisateur->id) }}" method="POST" class="supprimerForm">
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
        <input type="hidden" id="editUserId" name="userId">
        <label for="editName">Nom:</label>
        <input type="text" id="editName" name="name" required>
        <br><br>
        <label for="editEmail">Email:</label>
        <input type="email" id="editEmail" name="email" required>
        <br><br>
        <label for="editPassword">Mot de passe:</label>
        <input type="password" id="editPassword" name="password" required>
        <br><br>
        <label for="editRole">Rôle:</label>
        <select id="editRole" name="role" class="form-control" required>
          <option value="evaluateur_i">évaluateur_In</option>
          <option value="evaluateur_e">évaluateur_Ex</option>
          <option value="admin">admin</option>
        </select>
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
                const userId = button.getAttribute('data-id');
                const row = button.closest('tr');
                const name = row.cells[0].innerText;
                const email = row.cells[1].innerText;
                const role = row.cells[3].innerText;

                // Call the function to open the edit form with selected user data
                openEditModal(userId, name, email, role);
            });
        });

        // Function to open the edit modal
        function openEditModal(userId, name, email, role) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editForm').action = "/utilisateur/" + userId + "/modifier"; // Set the action of the form with user ID
            editModal.style.display = "block";
        }

        // Event listener for delete forms
        supprimerForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this user?')) {
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

