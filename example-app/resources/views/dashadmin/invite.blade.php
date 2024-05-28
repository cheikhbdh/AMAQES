@extends('dashadmin.home')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Inviter des utilisateurs à la campagne: {{ $invitation->nom }}</h1>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashadmin') }}">dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invitations.index') }}">Les campagnes</a></li>
                <li class="breadcrumb-item active">Inviter des utilisateurs</li>
            </ol>
        </nav>
    </div>
  
    <section class="section">
    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-2">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger mt-2">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form action="{{ route('invitations.sendInvitations', $invitation->id) }}" method="POST">
        @csrf
        <table class="table table-bordered mt-2">
            <tr>
                <th>Sélectionner</th>
                <th>Email</th>
            </tr>
            @foreach ($users as $user)
            <tr>
                <td>
                    <input type="checkbox" name="emails[]" value="{{ $user->email }}">
                </td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </table>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>    
    </section>
</main>
@endsection
