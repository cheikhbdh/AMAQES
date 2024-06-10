<?php

namespace App\Http\Controllers;
use App\Models\Champ;
use App\Models\Critere;
use App\Models\Referentiel;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitEmail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Preuve;



class InvitationController extends Controller
{
    private function checkAndDisableExpiredInvitations()
{
    // Obtenir la date actuelle
    $currentDate = Carbon::now()->toDateString();
    
    // Récupérer les invitations expirées
    $expiredInvitations = Invitation::where('statue', true)
        ->whereDate('date_fin', '<', $currentDate)
        ->get();
    
    // Si des invitations expirées ont été trouvées
    if ($expiredInvitations->isNotEmpty()) {
        // Désactiver les invitations expirées
        $expiredInvitations->each(function ($invitation) {
            $invitation->update(['statue' => false]);
        });

        // Mettre à jour le champ 'invitation' de tous les utilisateurs ayant une invitation active
        User::where('invitation', 1)->update(['invitation' => 0]);
    }
}


    public function index()
    {
        $this->checkAndDisableExpiredInvitations(); // Vérifier et désactiver les campagnes expirées

        $invitations = Invitation::all();
        return view('dashadmin.invit', compact('invitations'));
    }

    public function invite($id)
    {
        $invitation = Invitation::findOrFail($id);
        $users = User::where('role', 'evaluateur_i')->get();
        return view('dashadmin.invite', compact('invitation', 'users'));
    }

    public function sendInvitations(Request $request, $id)
{
    $invitation = Invitation::findOrFail($id);

    // Obtenez l'heure actuelle
    $currentDateTime = Carbon::now();

    $request->validate([
        'emails' => 'required|array|min:1',
        'emails.*' => 'email',
    ]);

    $emails = $request->input('emails');
    $subject = 'Invitation à la campagne';

    foreach ($emails as $email) {
        // Attendez quelques secondes entre chaque envoi pour éviter d'être bloqué pour spam
        sleep(5);

        // Envoyer l'e-mail avec l'heure actuelle
        Mail::to($email)->later($currentDateTime, new InvitEmail($invitation, $subject, $currentDateTime)); 

        // Mettre à jour le champ 'invitation' à 1 pour cet utilisateur
        User::where('email', $email)->update(['invitation' => 1]);
    }


    return redirect()->route('invitations.invite', ['invitation' => $invitation->id])->with('success', 'Invitations envoyées avec succès.');
}

    public function store(Request $request)
    {
        $this->checkAndDisableExpiredInvitations();// Vérifier et désactiver les campagnes expirées

        $existingActiveInvitation = Invitation::where('statue', true)->exists();

        if ($existingActiveInvitation && $request->input('statue')) {
            return redirect()->back()->withErrors(['error' => 'Une campagne active existe déjà.'])->withInput();
        }

        if ($request->input('date_debut') < date('Y-m-d')) {
            return redirect()->back()->withErrors(['error' => 'La date de début doit être postérieure à la date actuelle.'])->withInput();
        }
        if ($request->input('date_fin') <= $request->input('date_debut')) {
            return redirect()->back()->withErrors(['error' => 'La date de fin doit être ultérieure à la date de début.'])->withInput();
        }

        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'statue' => 'required|boolean'
        ]);

        Invitation::create($request->all());

        return redirect()->route('invitations.index')->with('success', 'Invitation créée avec succès.');
    }

    public function update(Request $request, Invitation $invitation)
    {
        $this->checkAndDisableExpiredInvitations(); // Vérifier et désactiver les campagnes expirées

        $existingActiveInvitations = Invitation::where('statue', true)
                                                ->where('id', '!=', $invitation->id)
                                                ->exists();

        if ($existingActiveInvitations && $request->input('statue')) {
            return redirect()->back()->withErrors(['error' => 'Une autre campagne active existe déjà.'])->withInput();
        }

        if ($request->input('date_fin') <= $request->input('date_debut')) {
            return redirect()->back()->withErrors(['error' => 'La date de fin doit être ultérieure à la date de début.'])->withInput();
        }

        if ($request->input('date_debut') < date('Y-m-d')) {
            return redirect()->back()->withErrors(['error' => 'La date de début doit être postérieure à la date actuelle.'])->withInput();
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


    public function referent()
    {
        $referentiels = Referentiel::all();
        return view('dashadmin.referentiel', compact('referentiels'));
    }

    public function ajouter_referentiel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:referentiels',
        ]);

        Referentiel::create(['name' => $request->name]);

        return redirect()->route('show.referent')->with('success', 'Référentiel ajouté avec succès');
    }

    public function modifier_referentiel(Request $request, $referentielId)
    {
        $referentiel = Referentiel::findOrFail($referentielId);

        $request->validate([
            'name' => 'required|string|max:255|unique:referentiels,name,' . $referentiel->id,
        ]);

        $referentiel->name = $request->name;
        $referentiel->save();

        return redirect()->route('show.referent')->with('success', 'Référentiel modifié avec succès');
    }

    public function supprimer_referentiel($id)
    {
        $referentiel = Referentiel::findOrFail($id);
        $referentiel->delete();

        return redirect()->route('show.referent')->with('success', 'Référentiel supprimé avec succès');
    }

    public function showChamps($referentielId)
    {
        $referentiel = Referentiel::with('champs')->findOrFail($referentielId);
        return view('dashadmin.champ', compact('referentiel'));
    }

    public function ajouter_champ(Request $request, $referentielId)
    {
        // Validate data
        $request->validate([
            'name' => 'required|string|max:255|unique:champs',
        ]);

        // Create a new field
        $champ = Champ::create([
            'name' => $request->name,
            'referentiel_id' => $referentielId,
        ]);

        return redirect()->route('referents.champs', ['referentielId' => $referentielId]);
    }

    public function modifier_champ(Request $request, $champId)
    {
        // Retrieve field by ID
        $champ = Champ::find($champId);

        // Check if field exists
        if (!$champ) {
            return redirect()->back()->with('error', 'Champ introuvable');
        }

        // Validate data
        $request->validate([
            'name' => 'required|string|max:255|unique:champs,name,' . $champ->id,
        ]);

        try {
            // Update field attributes
            $champ->name = $request->name;
            $champ->save();

            return redirect()->back()->with('success', 'Champ modifié avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function supprimer_champ($id)
    {
        // Delete field
        $champ = Champ::find($id);
        if ($champ) {
            $champ->delete();
            return redirect()->back()->with('success', 'Champ supprimé avec succès');
        } else {
            return redirect()->back()->with('error', 'Champ introuvable');
        }
    }

    public function showCriteres($referentielId,$champId)
    {
        $referentiel = Referentiel::with('champs')->findOrFail($referentielId);
        $champ = Champ::with('criteres')->findOrFail($champId);
        return view('dashadmin.critere', compact('champ','referentiel'));
    }

    public function showPreuves($referentielId, $champId, $critereId)
{
    $referentiel = Referentiel::with('champs')->findOrFail($referentielId);
    $champ = Champ::with('criteres')->findOrFail($champId);
    $criteres = Critere::findOrFail($critereId);
    return view('dashadmin.preuve', compact('referentiel', 'champ', 'criteres'));
}

public function ajouter_critere(Request $request, $champId)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    Critere::create([
        'nom' => $request->name,
        'champ_id' => $champId,
    ]);

    return redirect()->back()->with('success', 'Critère ajouté avec succès.');
}

public function modifier_critere(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $critere = Critere::findOrFail($id);
    $critere->update([
        'nom' => $request->name,
    ]);

    return redirect()->back()->with('success', 'Critère modifié avec succès.');
}

public function supprimer_critere($id)
{
    $critere = Critere::findOrFail($id);
    $critere->delete();

    return redirect()->back()->with('success', 'Critère supprimé avec succès.');
}


public function store_preuve(Request $request, $critereId)
{
    $request->validate([
        'element' => 'required|string|max:255',
    ]);

    Preuve::create([
        'critere_id' => $critereId,
        'description' => $request->element,
    ]);

    return redirect()->back()->with('success', 'Preuve ajouté avec succès.');
}

public function update_preuve(Request $request, $critereId, $preuveId)
{
    $request->validate([
        'description' => 'required|string|max:255',
    ]);

    $preuve = Preuve::findOrFail($preuveId);
    $preuve->update([
        'description' => $request->description,
    ]);

    return redirect()->back()->with('success', 'Preuve modifiée avec succès.');
}


public function destroy_preuve($critereId, $preuveId)
{
    $preuve = Preuve::findOrFail($preuveId);
    $preuve->delete();

    return redirect()->back()->with('success', 'success', 'Preuve supprimée avec succès.');
}

}
