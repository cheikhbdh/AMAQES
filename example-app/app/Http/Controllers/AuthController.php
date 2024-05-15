<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requestlogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

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
        $email=$request -> input( 'email');
        $password=$request -> input('password');
        $cerdinatiel=array(
         'email'  =>$email,
         'password' =>$password
        );
        if(Auth::attempt($cerdinatiel)){
          $request -> session() ->regenerate();
          $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended(route('dashadmin'));
        } elseif ($user->role === 'evaluateur_i') {
            return redirect()->intended(route('dash'));
        } else {
            return redirect()->intended(route('register'));
        }
        }else{
            return redirect()->back()->with('error','invalid token');
        }
        
    }
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Vous pouvez ajouter d'autres logiques ici, comme l'envoi de courriels de vérification, etc.

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
public function logout()
{
    Session::flush();
    Auth::logout();


    return redirect(route('login'));
}
}
