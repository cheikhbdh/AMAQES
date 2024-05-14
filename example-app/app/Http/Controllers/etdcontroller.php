<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class etdcontroller extends Controller
{
    public function list_etudiant(){
        return view('etudiant.liste');
    }
    public function ajouter_etudiant(){
        return view('etudiant.ajouter');
    }
}
