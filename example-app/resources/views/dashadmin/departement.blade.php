@extends('dashadmin.home')
@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Les Départements</h1>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashadmin') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Les Départements</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Tableau des départements</h5>
                        <button type="button" class="btn btn-primary btn-sm" id="addDepartementBtn">
                          <i class="bi bi-plus-lg">ajouter</i>
                      </button>
                    </div>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nom du Département</th>
                          <th scope="col">Établissement</th>
                          <th scope="col">Institution</th>
                          <th scope="col">Actions</th>
                      
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($departements as $departement)
                        <tr>
                          <th scope="row">{{ $loop->iteration }}</th>
                          <td>{{ $departement->nom }}</td>
                          <td>{{ $departement->etablissement ? $departement->etablissement->nom : 'N/A' }}</td>
                          <td>{{ $departement->etablissement && $departement->etablissement->institution ? $departement->etablissement->institution->nom : 'N/A' }}</td>
                          <td>
                            <button type="button" class="btn btn-sm transparent-button mr-2 editButton"
                            data-id="{{ $departement->id }}"
                            data-nom="{{ $departement->nom }}"
                            data-etablissement-id="{{ $departement->etablissement ? $departement->etablissement->id : null }}"
                            data-etablissement-nom="{{ $departement->etablissement ? $departement->etablissement->nom : 'N/A' }}">
                            <i class="bi bi-pencil-fill text-warning"></i> Modifier
                        </button>
                            <button type="button" class="btn btn-sm transparent-button mr-2 deleteButton" data-toggle="modal" data-target="#confirmDeleteModal" data-departement-id="{{$departement->id}}">
                                <i class="bi bi-trash-fill text-danger"></i> Supprimer
                            </button>
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
</main>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de suppression</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              Êtes-vous sûr de vouloir supprimer cet établissement ?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
              <form action="{{ route('departement.destroy', $departement->id) }}" method="POST" id="deleteForm{{$departement->id}}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Supprimer</button>
              </form>
          </div>
      </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
   $('.editButton').on('click', function () {
        var id = $(this).data('id');
        var nom = $(this).data('nom').replace(/'/g, "&apos;");
        var etablissementId = $(this).data('etablissement-id');
        var etablissementNom = $(this).data('etablissement-nom').replace(/'/g, "&apos;");

        var etablissementList = "<select id='etablissementSelect' name='etablissement' class='form-control'>";
          if (etablissementId) {
            etablissementList += `<option value='${etablissementId}' selected>${etablissementNom}</option>`;
        } else {
            institutionsList += `<option value='' selected>N/A</option>`;
        }
        @foreach($etablissements as $etab)
        etablissementList += `<option value='{{ $etab->id }}'>{{ $etab->nom }}</option>`;
        @endforeach
        etablissementList += "</select>";
       

        Swal.fire({
            title: 'Modifier Établissement',
            html: `
                <form id="editForm" action="/departement/${id}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class='form-group'>
                        <label for='nomInput'>Nom:</label>
                        <input type='text' class='form-control' id='nomInput' name='nom' value='${nom}'>
                    </div>
                    <div class='form-group'>
                        <label for='etablissementSelect'>Institution:</label>
                        ${etablissementList}
                    </div>
                </form>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Modifier',
            cancelButtonText: 'Annuler',
            preConfirm: () => {
                document.getElementById('editForm').submit();
            }
        });
    });
  $('#addDepartementBtn').on('click', function () {
    var etablissementList = "<select id='etablissementSelect' name='etablissement' class='form-control'>";
    @foreach($etablissements as $etab)
    etablissementList += `<option value='{{ $etab->id }}'>{{ $etab->nom }}</option>`;
    @endforeach
    etablissementList += "</select>";

    Swal.fire({
        title: 'Ajouter Établissement',
        html: `
            <form id="addForm" action="{{ route('departement.store') }}" method="POST">
                @csrf
                <div class='form-group'>
                    <label for='nomInput'>Nom:</label>
                    <input type='text' class='form-control' id='nomInput' name='nom' required>
                </div>
                <div class='form-group'>
                    <label for='etablissementSelect'>Etablissement:</label>
                    ${etablissementList}
                </div>
            </form>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Ajouter',
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            document.getElementById('addForm').submit();
        }
    });
});
</script>
@if(session('success'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "{{ session('success') }}", // Encadrez la session('success') dans des guillemets
            showConfirmButton: false,
            timer: 3000,
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "{{ session('error') }}", // Encadrez la session('error') dans des guillemets
            showConfirmButton: false,
            timer: 70000
        });
    </script>
@endif

@endsection
