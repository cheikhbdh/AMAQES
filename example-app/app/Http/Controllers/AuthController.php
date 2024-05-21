<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requestlogin;
use App\Models\User;
use App\Models\Champ;
use App\Models\Critere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'register']);
    }
    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Requestlogin $request)
{
    // Valider les données du formulaire
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Vérifier l'authentification de l'utilisateur
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended(route('dashadmin'));
        } elseif ($user->role === 'evaluateur_i') {
            return redirect()->intended(route('dash'));
        } else {
            return redirect()->intended(route('register'));
        }
    } else {
        return redirect()->back()->with('error', 'Adresse email ou mot de passe incorrect.');
    }
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', // Validate password confirmation
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect(route('login'));
}

public function updateRole(Request $request, $userId)
{
    $request->validate([
        'role' => 'required|string|in:admin,evaluateur_i,evaluateur_I', // Assurez-vous que le rôle est valide
    ]);

    $user = User::find($userId);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->role = $request->role;
    $user->save();

    return response()->json(['user' => $user, 'message' => 'User role updated successfully'], 200);
}
public function delete(Request $request, $userId)
{
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted successfully'], 200);
}



public function ajouter_user(Request $request)
{
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin', // Assurez-vous que le rôle est valide
    ]);

    // Création d'un nouvel utilisateur
    $utilisateur = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);
    
    

    return redirect()->route('user');
}

public function ajouter_champ(Request $request)
{
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255|unique:champs',
    ]);

    // Création d'un nouvel utilisateur
    $champ = Champ::create([
        'name' => $request->name,
    ]);
    
    

    return redirect()->route('champ');
}



public function modifier_user(Request $request, $userId)
{
    // Récupérer l'utilisateur par ID
    $utilisateur = User::find($userId);

    // Vérifier si l'utilisateur existe
    if (!$utilisateur) {
        return redirect()->back()->with('error', 'Utilisateur introuvable');
    }

    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $utilisateur->id,
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin', // Assurez-vous que le rôle est valide
    ]);

    try {
        // Mettre à jour les attributs de l'utilisateur
        $utilisateur->name = $request->name;
        $utilisateur->email = $request->email;
        $utilisateur->password = Hash::make($request->password);
        $utilisateur->role = $request->role;
        $utilisateur->save();

        return redirect()->back()->with('success', 'Utilisateur modifié avec succès');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}

public function modifier_champ(Request $request, $userId)
{
    // Récupérer l'utilisateur par ID
    $champ = Champ::find($userId);

    // Vérifier si l'utilisateur existe
    if (!$champ) {
        return redirect()->back()->with('error', 'Champ introuvable');
    }

    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255|unique:champs,name,'. $champ->id,
    ]);

    try {
        // Mettre à jour les attributs de l'utilisateur
        $champ->name = $request->name;
        $champ->save();

        return redirect()->back()->with('success', 'Champ modifié avec succès');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}



    public function supprimer_user($id)
    {
        // Suppression de l'utilisateur
        $utilisateur = User::find($id);
        if ($utilisateur) {
            $utilisateur->delete();
            return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
        } else {
            return redirect()->back()->with('error', 'Utilisateur introuvable');
        }
    
    }

    public function supprimer_champ($id)
    {
        // Suppression de l'utilisateur
        $champ = Champ::find($id);
        if ($champ) {
            $champ->delete();
            return redirect()->back()->with('success', 'Champ supprimé avec succès');
        } else {
            return redirect()->back()->with('error', 'Champ introuvable');
        }
    }


    public function showCriteres($champId)
    {
        $champ = Champ::with('criteres')->findOrFail($champId);
        return view('dashadmin.critere', compact('champ'));
    }

public function logout()
{
    Session::flush();
    Auth::logout();


    return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
}


public function ajouter_critere(Request $request)
{
    // Valider les données du formulaire
    $request->validate([
        'name' => 'required',
        'preuve' => 'required',  // Utilisez 'preuve' pour correspondre au nom du champ du formulaire
    ]);

    // Créer un nouveau critère
    Critere::create([
        'nom' => $request->name,
        'preves_critere' => $request->preuve,
        'champ_id' => $request->champ_id, // Assurez-vous que l'ID du champ associé est bien passé
    ]);

    // Rediriger avec un message de succès
    return redirect()->back()->with('success', 'Critère ajouté avec succès.');
}

public function modifier_critere(Request $request, $id)
{
    // Valider les données du formulaire
    $request->validate([
        'name' => 'required',
        'preuve' => 'required',
    ]);

    // Trouver le critere à modifier
    $critere = Critere::findOrFail($id);

    // Mettre à jour les données du critere
    $critere->update([
        'nom' => $request->name,
        'preves_critere' => $request->preuve,
    ]);

    // Rediriger avec un message de succès
    return redirect()->back()->with('success', 'Critère modifié avec succès.');
}


    public function supprimer_critere($id)
    {
        // Trouver le critere à supprimer
        $critere = Critere::findOrFail($id);

        // Supprimer le critere
        $critere->delete();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Critère supprimé avec succès.');
    }


    public function adminIndex()
{
    $utilisateurs = User::where('role', 'admin')->get();
    return view('dashadmin.admin_users', compact('utilisateurs'));
}

    public function userInIndex()
    {
        $utilisateurs = User::where('role', 'evaluateur_in')->get();
        return view('dashadmin.admin_users', compact('utilisateurs'));
    }


    public function userExIndex()
    {
        $utilisateurs = User::where('role', 'evaluateur_ex')->get();
        return view('dashadmin.admin_users', compact('utilisateurs'));
    }

    // AuthController.php

// AuthController.php

public function store_admin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', // Validate password confirmation
        'role' => 'required|string|in:admin',
    ]);

    if ($validator->fails()) {
        return redirect()->route('admin.utilisateurs')
                         ->withErrors($validator)
                         ->withInput();
    }

    try {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur ajouté avec succès');
    } catch (\Exception $e) {
        return redirect()->route('admin.utilisateurs')->with('error', 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.');
    }
}

public function update_admin(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        'password' => 'sometimes|nullable|string|min:8|confirmed',
        'role' => 'required|string|in:admin', // Ajout de la validation du champ "Rôle"
    ]);

    if ($validator->fails()) {
        return redirect()->route('useradmin.modifier', $id)
                         ->withErrors($validator)
                         ->withInput();
    }

    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }
    $user->role = $request->role; // Mettre à jour le champ "Rôle"
    $user->save();

    return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur modifié avec succès');
}

public function destroy_admin($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur supprimé avec succès');
}


}