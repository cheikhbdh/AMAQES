<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use App\Models\Etablissement;
use App\Models\département;
use App\Models\Filière;
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
            $institution = Institution::findOrFail($id);
            $institution->delete();
            return redirect()->route('institutions.index')->with('success', 'L\'institution a été supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression de l\'institution. Veuillez réessayer.');
        }
    }
    public function indexEtablissement()
    {
        $etablissements = Etablissement::with('Institution:id,nom')->get();
        $institutions = Institution::all();
        return view('dashadmin.etablissement', compact('etablissements', 'institutions'));
    }
    public function updateEtablissement(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'institution' => 'nullable|integer|exists:institutions,id', // Permettre institution d'être null et vérifier qu'elle existe
        ]);
    
        try {
            // Récupérer l'établissement à mettre à jour
            $etablissement = Etablissement::findOrFail($id);
            
            // Mettre à jour les champs de l'établissement
            $etablissement->nom = $request->nom;
            $etablissement->institution_id = $request->institution; // Permettre null ici
    
            // Sauvegarder les modifications
            $etablissement->save();
    
            // Redirection avec un message de succès
            return redirect()->route('etablissement.index')->with('success', 'Établissement mis à jour avec succès!');
        } catch (\Exception $e) {
            // Redirection avec un message d'erreur en cas d'échec
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour de l\'établissement. '.$e->getMessage());
        }
    }
    public function storeEtablissement(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'institution' => 'nullable|exists:etablissements,id',
        ]);
        $etablissement = new Etablissement();
        $etablissement->nom = $validated['nom'];
        $etablissement->institution_id = $validated['institution']; 
        $etablissement->save();

        return redirect()->route('etablissement.index')->with('success', 'Établissement ajouté avec succès');
    }
public function destroyEtablissement($id)
    {
        try {
            $etablissement = Etablissement::findOrFail($id);
            $etablissement->delete();
            return redirect()->route('etablissement.index')->with('success', 'L\'Établissement a été supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression de l\'Établissement. Veuillez réessayer.');
        }
    }
    public function indexDepartement()
    {
        $departements = Département::with('etablissement.institution')->get();
        $etablissements = Etablissement::all();
        return view('dashadmin.departement', compact('departements','etablissements'));
    }
    public function updateDepartement(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'etablissement' => 'nullable|exists:etablissements,id', // Permettre institution d'être null et vérifier qu'elle existe
        ]);
    
        try {
            // Récupérer l'établissement à mettre à jour
            $département = Département::findOrFail($id);
            
            // Mettre à jour les champs de l'établissement
            $département->nom = $request->nom;
            $département->etablissements_id  = $request->etablissement; // Permettre null ici
    
            // Sauvegarder les modifications
            $département->save();
    
            // Redirection avec un message de succès
            return redirect()->route('departement.index')->with('success', 'Établissement mis à jour avec succès!');
        } catch (\Exception $e) {
            // Redirection avec un message d'erreur en cas d'échec
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour de l\'établissement. '.$e->getMessage());
        }
    }
    public function storeDepartement(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'etablissement' => 'nullable|exists:etablissements,id',
        ]);
        $etablissement = new Département();
        $etablissement->nom = $validated['nom'];
        $etablissement->etablissements_id = $validated['etablissement']; 
        $etablissement->save();

        return redirect()->route('departement.index')->with('success', 'Établissement ajouté avec succès');
    }
public function destroyDepartement($id)
    {
        try {
            $departement = Département::findOrFail($id);
            $departement->delete();
            return redirect()->route('departement.index')->with('success', 'L\'Établissement a été supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression de l\'Établissement. Veuillez réessayer.');
        }
    }
    public function indexFiliere()
    {
        $filieres = Filière::with('departement.etablissement.institution')->get();
        $departements = département::all();
        return view('dashadmin.filiere', compact('filieres','departements'));
    }
    public function updateFiliere(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'departements' => 'nullable|exists:départements,id', // Permettre institution d'être null et vérifier qu'elle existe
        ]);
    
        try {
            // Récupérer l'établissement à mettre à jour
            $filiere = Filière::findOrFail($id);
            
            // Mettre à jour les champs de l'établissement
            $filiere->nom = $request->nom;
            $filiere->départements_id  = $request->departements; // Permettre null ici
    
            // Sauvegarder les modifications
            $filiere->save();
    
            // Redirection avec un message de succès
            return redirect()->route('filiere.index')->with('success', 'la Filière mis à jour avec succès!');
        } catch (\Exception $e) {
            // Redirection avec un message d'erreur en cas d'échec
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour de la Filière. '.$e->getMessage());
        }
    }
    public function storeFiliere(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'departements' => 'nullable|exists:départements,id',
        ]);
        $filiere = new Filière();
        $filiere->nom = $validated['nom'];
        $filiere->départements_id = $validated['departements']; 
        $filiere->save();

        return redirect()->route('filiere.index')->with('success', 'la Filière ajouté avec succès');
    }
public function destroyFiliere($id)
    {
        try {
            $departement = Filière::findOrFail($id);
            $departement->delete();
            return redirect()->route('filiere.index')->with('success', 'La Filièrea été supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la suppression de la Filière. Veuillez réessayer.'.$id);
        }
    }
}
