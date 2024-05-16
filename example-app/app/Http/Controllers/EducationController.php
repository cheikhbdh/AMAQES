<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use App\Models\Etablissement;
use App\Models\Departement;
use App\Models\Filiere;
class EducationController extends Controller
{
    public function indexInstitutions()
    {
        $institutions = Institution::all();
        return view('dashadmin.institution', compact('institutions'));
    }
    public function storeInstitution(Request $request)
{
    // Valider les données du formulaire
    $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    // Créer une nouvelle instance d'institution
    $institution = new Institution();
    $institution->nom = $request->nom;
    $institution->save();

    // Rediriger avec un message de succès
    return redirect()->route('institutions.index')->with('success', 'L\'institution a été ajoutée avec succès.');
}
public function updateInstitution(Request $request, $id)
{
    // Validation des données du formulaire
    $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    try {
        // Recherche de l'institution à mettre à jour dans la base de données
        $institution = Institution::findOrFail($id);
        
        // Mise à jour des informations de l'institution
        $institution->nom = $request->nom;
        $institution->save();

        // Redirection avec un message de succès
        return redirect()->route('institutions.index')->with('success', 'L\'institution a été mise à jour avec succès.');
    } catch (\Exception $e) {
        // En cas d'erreur, redirection avec un message d'erreur
        return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour de l\'institution. Veuillez réessayer.');
    }

}
public function destroyInstitution($id)
    {
        try {
            // Recherche de l'institution à supprimer dans la base de données
            $institution = Institution::findOrFail($id);
            
            // Suppression de l'institution
            $institution->delete();

            // Redirection avec un message de succès
            return redirect()->route('institutions.index')->with('success', 'L\'institution a été supprimée avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression de l\'institution. Veuillez réessayer.');
        }
    }
}
