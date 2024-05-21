<!-- resources/views/admin/utilisateurs.blade.php -->
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
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
            <h5 class="card-title">Gestion des utilisateurs admin</h5>
            <button id="ajouterBtn" class="btn btn-primary mb-3">Ajouter</button>
            <div id="formModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <form id="ajouterForm" action="{{ route('useradmin.ajouter') }}" method="POST">
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
                  <input type="password" name="confirm_password" required>
                  <br><br>
                  <label for="role">Rôle:</label>
                  <select id="role" name="role" class="form-control" required>
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
                <th>Action</th>
              </tr>
              <tbody>
                @php
                  $admins = $utilisateurs->where('role', 'admin');
                @endphp
                @foreach($admins as $admin)
                  <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                      <button class="btn btn-info modifierBtn" data-id="{{ $admin->id }}">Modifier</button>
                      <form action="{{ route('useradmin.supprimer', $admin->id) }}" method="POST" class="d-inline supprimerForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
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
        <button type="submit" class="btn btn-success">Modifier</button>
    </form>
    
    </div>
  </div>
</main>
<script>
  document.addEventListener('DOMContentLoaded', () => {
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

      modifierBtns.forEach(button => {
          button.addEventListener('click', () => {
              const userId = button.getAttribute('data-id');
              const row = button.closest('tr');
              const name = row.cells[0].innerText;
              const email = row.cells[1].innerText;
              openEditModal(userId, name, email);
          });
      });

      function openEditModal(userId, name, email) {
          document.getElementById('editUserId').value = userId;
          document.getElementById('editName').value = name;
          document.getElementById('editEmail').value = email;
          document.getElementById('editForm').action = "/utilisateur/" + userId + "/modifier";
          editModal.style.display = "block";
      }

      supprimerForms.forEach(form => {
          form.addEventListener('submit', event => {
              event.preventDefault();
              if (confirm('Are you sure you want to delete this admin?')) {
                  form.submit();
              }
          });
      });

      closeModalBtn.addEventListener('click', () => {
          editModal.style.display = "none";
      });

      window.addEventListener('click', event => {
          if (event.target === editModal) {
              editModal.style.display = "none";
          }
      });
  });
</script>
@endsection
