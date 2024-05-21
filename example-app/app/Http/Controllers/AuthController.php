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
        // Validate the login form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Authenticate the user
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
            'role' => 'required|string|in:admin,evaluateur_i,evaluateur_I', // Ensure the role is valid
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
        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin', // Ensure the role is valid
        ]);

        // Create a new user
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

    public function modifier_user(Request $request, $userId)
    {
        // Retrieve user by ID
        $utilisateur = User::find($userId);

        // Check if user exists
        if (!$utilisateur) {
            return redirect()->back()->with('error', 'Utilisateur introuvable');
        }

        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $utilisateur->id,
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:evaluateur_i,evaluateur_e,admin', // Ensure the role is valid
        ]);

        try {
            // Update user attributes
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

    public function supprimer_user($id)
    {
        // Delete user
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

    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin', // Add validation for "role" field
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
        $user->role = $request->role; // Update "role" field
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
