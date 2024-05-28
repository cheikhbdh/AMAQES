@extends('dashadmin.home')

@section('content')
<head>
  <link rel="stylesheet" href="{{ asset('assets/css/ajout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/edit.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
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
          
              <h5 class="card-title">Gestion des utilisateurs ytgh</h5>
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
                      <form id="ajouterForm" action="{{ route('store_userIn') }}" method="POST">
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
                          <label for="password">Confirmer le mot de passe:</label>
                          <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                          <br><br>
                          <label for="role">Rôle:</label>
                          <select id="role" name="role" class="form-control" required>
                            <option value="evaluateur_i">évaluateur_In</option>
                          </select>
                          <br><br>
                          <label for="editFil">Filiéres:</label>
                          <select id="editFil" name="fil" class="form-control" required>
                            @foreach($filieres as $filiere)
                            <option value="{{$filiere->id}}">{{$filiere->nom}}</option>
                            @endforeach
                          </select>
                          <br><br>
                          <button type="submit" class="btn btn-success">Soumettre</button>
                      </form>
                  </div>
              </div>
              <table class="table datatable"  id="tabledatabase">
                <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th colspan="2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                    <tr>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->role }}</td>
                      <td data-filiere-id="{{ $user->filières_id }}">
                        <button class="btn btn-info modifierBtn" data-id="{{ $user->id }}">Modifier</button>
                      </td>
                      <td>
                        <form action="{{ route('destroy_userIn', $user->id) }}" method="POST" class="supprimerForm">
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
              @method('PUT')
              <input type="hidden" id="editUserId" name="userId">
              <label for="editName">Nom:</label>
              <input type="text" id="editName" name="name" required>
              <br><br>
              <label for="editEmail">Email:</label>
              <input type="email" id="editEmail" name="email" required>
              <br><br>
              <label for="editPassword">Mot de passe:</label>
              <input type="password" id="editPassword" name="password">
              <br><br>
              <label for="editRole">Rôle:</label>
              <select id="editRole" name="role" class="form-control" required>
                  <option value="evaluateur_i">évaluateur_In</option>
              </select>
              <br><br>
              <label for="editFil">Filiéres:</label>
              <select id="editFil" name="fil" class="form-control" required>
                  @foreach($filieres as $filiere)
                      <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                  @endforeach
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

    const modifierBtns = document.querySelectorAll('.modifierBtn');
    const supprimerForms = document.querySelectorAll('.supprimerForm');
    const editModal = document.getElementById('editModal');
    const closeModalBtn = editModal.querySelector('.close');
    const editForm = editModal.querySelector('form');

    modifierBtns.forEach((button) => {
        button.addEventListener('click', () => {
            const userId = button.getAttribute('data-id');
            const row = button.closest('tr');
            const name = row.cells[0].innerText;
            const email = row.cells[1].innerText;
            const role = row.cells[2].innerText;
            const fil = row.cells[3].innerText;

            openEditModal(userId, name, email, role, fil);
        });
    });

    function openEditModal(userId, name, email, role, fil) {
        document.getElementById('editUserId').value = userId;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role;
        document.getElementById('editFil').value = fil;
        document.getElementById('editForm').action = "/userIn/" + userId + "/modifier";
        editModal.style.display = "block";
    }

    supprimerForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this user?')) {
                form.submit();
            }
        });
    });

    closeModalBtn.addEventListener('click', () => {
        editModal.style.display = "none";
    });

    window.addEventListener('click', (event) => {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
    });
});
</script>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

@endsection