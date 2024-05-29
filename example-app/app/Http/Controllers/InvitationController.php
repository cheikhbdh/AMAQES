<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\InvitEmail;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::all();
        return view('dashadmin.invit', compact('invitations'));
    }

    public function invite($id)
{
    $invitation = Invitation::findOrFail($id);
    $users = User::where('role', 'evaluateur_i')->get(); // Only show internal users
    return view('dashadmin.invite', compact('invitation', 'users'));
}


public function sendInvitations(Request $request, $id)
{
    // Retrieve the invitation corresponding to the given ID
    $invitation = Invitation::findOrFail($id);

    // Validate the request data
    $request->validate([
        'emails' => 'required|array|min:1',
        'emails.*' => 'email',
    ]);

    // Retrieve the email addresses from the request
    $emails = $request->input('emails');
    $subject = 'Invitation à la campagne';

    // Send the invitations via email to all selected emails
    foreach ($emails as $email) {
        Mail::to($email)->send(new InvitEmail($invitation, $subject));
    }

    // Redirect with a success message
    return redirect()->route('invitations.invite', ['invitation' => $invitation->id])->with('success', 'Invitations envoyées avec succès.');
}




    public function store(Request $request)
    {
        // Vérifier s'il existe déjà une campagne active
        $existingActiveInvitation = Invitation::where('statue', true)->exists();

        // Si une campagne active existe déjà et que la nouvelle campagne est active, ajouter une erreur au système de messages de session
        if ($existingActiveInvitation && $request->input('statue')) {
            return redirect()->back()->withErrors(['error' => 'Une campagne active existe déjà.'])->withInput();
        }

        // Vérifier si la date de début est postérieure à la date actuelle ou égale à la date de création de la campagne
        if ($request->input('date_debut') < date('Y-m-d')) {
            return redirect()->back()->withErrors(['error' => 'La date de début doit être postérieure à la date actuelle.'])->withInput();
        }

        // Valider les données de la requête
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'date_debut' => 'required|date|before:date_fin',
            'date_fin' => 'required|date',
            'statue' => 'required|boolean'
        ]);

        // Créer la nouvelle campagne
        Invitation::create($request->all());

        // Rediriger avec un message de succès
        return redirect()->route('invitations.index')->with('success', 'Invitation créée avec succès.');
    }

    public function update(Request $request, Invitation $invitation)
{
    // Vérifier s'il existe déjà une campagne active autre que celle que l'on est en train de modifier
    $existingActiveInvitations = Invitation::where('statue', true)
                                            ->where('id', '!=', $invitation->id)
                                            ->exists();

    // Si une autre campagne active existe déjà et que la nouvelle campagne est active, ajouter une erreur au système de messages de session
    if ($existingActiveInvitations && $request->input('statue')) {
        return redirect()->back()->withErrors(['error' => 'Une autre campagne active existe déjà.'])->withInput();
    }

    // Vérifier que la date de fin est ultérieure à la date de début
    if ($request->input('date_fin') <= $request->input('date_debut')) {
        return redirect()->back()->withErrors(['error' => 'La date de fin doit être ultérieure à la date de début.'])->withInput();
    }

    $request->validate([
        'nom' => 'required',
        'description' => 'required',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date',
        'statue' => 'required|boolean',
    ]);

    $invitation->update($request->all());
    return redirect()->route('invitations.index')->with('success', 'Invitation mise à jour avec succès.');
}

}
