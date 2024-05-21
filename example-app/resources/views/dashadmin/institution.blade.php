@extends('dashadmin.home')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <h1>les institutions</h1>
           
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashadmin') }}">dashboard</a></li>
                <li class="breadcrumb-item">les institutions</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
  
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Afficher le message de succès -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">les institutions</h5> <button type="button" class="btn btn-primary btn-lg" id="addInstitutionBtn">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        </div>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($institutions as $institution)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $institution->nom }}</td>
                                    <td>
                                        <!-- Boutons d'action -->
                                        <div class="d-flex justify-content-center align-items-center">
                                     
                                            <button type="button" class="btn btn-sm btn-warning mr-2"  data-toggle="modal" data-target="#editInstitutionModal{{$institution->id}}">
                                                <i class="bi bi-pencil-fill"></i> Modifier
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger mr-2 deleteButton" data-toggle="modal" data-target="#confirmDeleteModal" data-institution-id="{{$institution->id}}">
                                                <i class="bi bi-trash-fill"></i> Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editInstitutionModal{{$institution->id}}" tabindex="-1" role="dialog" aria-labelledby="editInstitutionModalLabel{{$institution->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editInstitutionModalLabel{{$institution->id}}">Modifier une institution</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Formulaire pour modifier une institution -->
                                                <form method="POST" action="{{ route('institutions.update', $institution->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nom{{$institution->id}}">Nom de l'institution</label>
                                                        <input type="text" class="form-control" id="nom{{$institution->id}}" name="nom" value="{{ $institution->nom }}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal pour ajouter une institution -->
<!-- Modal pour ajouter une institution -->
<div class="modal fade" id="addInstitutionModal" tabindex="-1" role="dialog" aria-labelledby="addInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInstitutionModalLabel">Ajouter une institution</h5>
               
            </div>
            <div class="modal-body">
                <!-- Formulaire pour ajouter une institution -->
                <form method="POST" action="{{ route('institutions.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nom">Nom de l'institution</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
                Êtes-vous sûr de vouloir supprimer cette institution ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('institutions.destroy', $institution->id) }}" method="POST" id="deleteForm{{$institution->id}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Script JavaScript pour afficher le modal -->
<script>
    // Sélectionnez le bouton "Ajouter institution"
    var addInstitutionBtn = document.getElementById('addInstitutionBtn');
    // Sélectionnez le modal "Ajouter une institution"
    var addInstitutionModal = document.getElementById('addInstitutionModal');
    
    // Ajoutez un gestionnaire d'événement de clic au bouton "Ajouter institution"
    addInstitutionBtn.addEventListener('click', function () {
        // Afficher le modal "Ajouter une institution"
        $(addInstitutionModal).modal('show');
    });
    $(document).ready(function () {
        // Afficher le modal de modification lors du clic sur le bouton "Modifier"
        $('.editInstitutionBtn').on('click', function () {
            var institutionId = $(this).data('institution-id');
            $('#editInstitutionModal'+institutionId).modal('show');
        });
    });
    $(document).ready(function () {
    // Attacher un gestionnaire d'événement au clic sur le bouton de suppression
    $('.deleteButton').on('click', function () {
        // Récupérer l'identifiant de l'institution
        var institutionId = $(this).data('institution-id');
        // Ajouter l'identifiant de l'institution au formulaire de suppression
        $('#confirmDeleteModal').find('.confirmDeleteButton').attr('data-institution-id', institutionId);
        // Afficher le modal de confirmation de suppression
        $('#confirmDeleteModal').modal('show');
    });

    // Attacher un gestionnaire d'événement au clic sur le bouton de confirmation de suppression
    $('.confirmDeleteButton').on('click', function () {
        // Récupérer l'identifiant de l'institution
        var institutionId = $(this).data('institution-id');
        // Soumettre le formulaire de suppression correspondant
        $('#deleteForm' + institutionId).submit();
    });
});

</script>

@endsection
