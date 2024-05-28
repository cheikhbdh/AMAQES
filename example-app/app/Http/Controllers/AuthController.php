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
use App\Models\Filière;

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
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Optionally add additional logic, such as sending verification emails

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
    
    
        public function logout(Request $request)
        {
            Auth::logout();
            return redirect()->route('login');
        }
    
        public function store_admin(Request $request)
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
        
        return redirect()->back()->with('success', 'User added successfully');
        }
    
        public function update_admin(Request $request, $id)
        {
            $user = User::find($id);
    
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }
    
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role' => 'required|string|in:evaluateur_i,evaluateur,admin',
            ]);
    
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
            ]);
    
            return redirect()->back()->with('success', 'User updated successfully');
        }
    
        public function destroy_admin($id)
        {
            $user = User::find($id);
    
            if ($user) {
                $user->delete();
                return redirect()->back()->with('success', 'User deleted successfully');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }


    public function ajouter_champ(Request $request)
    {
        // Validate data
        $request->validate([
            'name' => 'required|string|max:255|unique:champs',
        ]);

        // Create a new field
        $champ = Champ::create([
            'name' => $request->name,
        ]);

        return redirect()->route('champ');
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

    public function showCriteres($champId)
    {
        $champ = Champ::with('criteres')->findOrFail($champId);
        return view('dashadmin.critere', compact('champ'));
    }


    public function ajouter_critere(Request $request)
    {
        // Validate form data
        $request->validate([
            'name' => 'required',
            'preuve' => 'required',  // Use 'preuve' to match the form field name
        ]);

        // Create a new criterion
        Critere::create([
            'nom' => $request->name,
            'preves_critere' => $request->preuve,
            'champ_id' => $request->champ_id, // Ensure the associated field ID is passed
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', 'Critère ajouté avec succès.');
    }

    public function modifier_critere(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'name' => 'required',
            'preuve' => 'required',
        ]);

        // Find the criterion to modify
        $critere = Critere::findOrFail($id);

        // Update the criterion data
        $critere->update([
            'nom' => $request->name,
            'preves_critere' => $request->preuve,
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', 'Critère modifié avec succès.');
    }

    public function supprimer_critere($id)
    {
        // Find the criterion to delete
        $critere = Critere::findOrFail($id);

        // Delete the criterion
        $critere->delete();

        // Redirect with success message
        return redirect()->back()->with('success', 'Critère supprimé avec succès.');
    }

    public function adminIndex()
    {
        $users = User::where('role', 'admin')->get();
        return view('dashadmin.admin_users', compact('users'));
    }

    public function user()
    {
        $utilisateurs = User::all();
        return view('dashadmin.users', compact('utilisateurs'));
    }

    public function userInIndex()
    {
        $users = User::where('role', 'evaluateur_i')->get();
        $filieres = Filière::all();
        return view('dashadmin.interne_users', compact('users','filieres'));
    }

    public function userExIndex()
    {
        $users = User::where('role', 'evaluateur_e')->get();
        return view('dashadmin.externe_users', compact('users'));
    
    }

    public function store_userEx(Request $request)
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
        
        return redirect()->back()->with('success', 'User added successfully');
        }
    
        public function update_userEx(Request $request, $id)
        {
            $user = User::find($id);
    
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }
    
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role' => 'required|string|in:evaluateur_i,evaluateur,admin',
            ]);
    
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
            ]);
    
            return redirect()->back()->with('success', 'User updated successfully');
        }
    
        public function destroy_userEx($id)
        {
            $user = User::find($id);
    
            if ($user) {
                $user->delete();
                return redirect()->back()->with('success', 'User deleted successfully');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }


        public function store_userIn(Request $request)
        {
            // Validation des données
            $validated=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin', // Assurez-vous que le rôle est valide
            'fil' => 'nullable|exists:filières,id',
        ]);
        $utilisateur = new User();
        $utilisateur->name= $validated['name'];
        $utilisateur->email = $validated['email']; 
        $utilisateur->password = Hash::make($validated['password']);
        $utilisateur->role = $validated['role'];
        $utilisateur->filières_id = $validated['fil'];
        $utilisateur->save();
        // Création d'un nouvel utilisateur
      
        
        return redirect()->back()->with('success', 'User added successfully');
        }
    
        public function update_userIn(Request $request, $id)
        {
           // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8',
        'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin',
        'fil' => 'nullable|exists:filières,id',
    ]);

    // Trouver l'utilisateur à modifier
    $utilisateur = User::find($id);

    // Mise à jour des informations de l'utilisateur
    $utilisateur->name = $request->name;
    $utilisateur->email = $request->email;
    if ($request->password) {
        $utilisateur->password = Hash::make($request->password);
    }
    $utilisateur->role = $request->role;
    $utilisateur->filières_id = $request->fil;

    $utilisateur->save();

    return redirect()->back()->with('success', 'User updated successfully');
        }
    
        public function destroy_userIn($id)
        {
            $user = User::find($id);
    
            if ($user) {
                $user->delete();
                return redirect()->back()->with('success', 'User deleted successfully');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }








}


