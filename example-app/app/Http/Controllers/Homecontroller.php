<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Homecontroller extends Controller
{
    public function login()
    {
        // Valider les données du formulaire
       
            $user = Auth::user();
    
            if ($user->role === 'admin') {
                return redirect()->intended(route('dashadmin'));
            } elseif ($user->role === 'evaluateur_i') {
                return redirect()->intended(route('dash'));
            } else {
                return redirect()->intended(route('register'));
            }
      
    }
    
}
