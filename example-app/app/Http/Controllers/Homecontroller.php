<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Champ;
use App\Models\User;
use App\Models\evaluationinterne;
use App\Models\Invitation;
use App\Models\fichies;
use Illuminate\Support\Facades\Storage;
class Homecontroller extends Controller
{
    public function indexevaluation()
    {
        $user = auth()->user();
        $idFiliere = $user->filières_id;
        $isUserInvited = $user->invitation == 1;
        $hasActiveInvitation = $isUserInvited && Invitation::where('statue', 1)->exists();
        $champs = Champ::with('references.criteres.preuves')->get();
        $champsEvaluer = $champs->filter(function($champ) use ($idFiliere) {
            foreach ($champ->references as $reference) {
                foreach ($reference->criteres as $critere) {
                    foreach ($critere->preuves as $preuve) {
                        if (EvaluationInterne::where('idpreuve', $preuve->id)->where('idfiliere', $idFiliere)->exists()) {
                            return true;
                        }
                    }
                }
            }
            return false;
        });
        $CHNEV = $champs->diff($champsEvaluer);
        $champNonEvaluer = $CHNEV->first();
    
        return view('layout.liste', compact('CHNEV','champNonEvaluer', 'hasActiveInvitation'));
    }
    
    public function evaluate(Request $request)
    {
        $data = $request->all();
    
        foreach ($data['evaluations'] as $evaluation) {
            $score = 0;
            if ($evaluation['value'] === 'oui') {
                $score = 2;
            } elseif ($evaluation['value'] === 'non') {
                $score = -1;
            }
            $user = Auth::user();
            $result = evaluationinterne::create([
                'idcritere' => $evaluation['idcritere'],
                'idpreuve' => $evaluation['idpreuve'],
                'idfiliere' => $user->filières_id,
                'idchamps' => $data['idchamps'], // Ajouter idchamps ici
                'score' => $score,
                'commentaire' => $evaluation['commentaire'] ?? null,
            ]);
           
            if ($request->hasFile('file-' . $evaluation['idpreuve'])) {
                $filePath = $request->file('file-' . $evaluation['idpreuve'])->store('preuves');
    
                fichies::create([
                    'fichier' => $filePath,
                    'idpreuve' => $evaluation['idpreuve'],
                    'idfiliere' => $user->filières_id,
                ]);
            }
        }
    
        return redirect('/scores_champ');
    }
    
    public function getScores()
    {
        $user = auth()->user();
        $idFiliere = $user->filières_id;
    
        // Récupérer les champs évalués
        $champsEvaluer = EvaluationInterne::where('idfiliere', $idFiliere)
                                          ->groupBy('idchamps')
                                          ->pluck('idchamps');
    
        $result = [];
    
        // Vérifier s'il n'y a aucun champ évalué pour cet utilisateur
        if ($champsEvaluer->isEmpty()) {
            $message = "Vous n'avez pas encore évalué de champs.";
            return response()->json(['message' => $message], 200);
        }
    
        foreach ($champsEvaluer as $idchamps) {
            $champ = Champ::with(['references.criteres'])->find($idchamps);
            $criteresScores = [];
    
            foreach ($champ->references as $reference) {
                foreach ($reference->criteres as $critere) {
                    $score = EvaluationInterne::where('idcritere', $critere->id)
                                              ->where('idchamps', $idchamps)
                                              ->where('idfiliere', $idFiliere)
                                              ->sum('score');
                    $criteresScores[] = [
                        'critere' => $critere->signature, // assuming 'nom' is the name of the critere
                        'score' => $score
                    ];
                }
            }
    
            // Calcul du taux de conformité
            $totalEvaluations = EvaluationInterne::where('idchamps', $idchamps)
                                                 ->where('idfiliere', $idFiliere)
                                                 ->count();
            $positiveEvaluations = EvaluationInterne::where('idchamps', $idchamps)
                                                    ->where('idfiliere', $idFiliere)
                                                    ->where('score', 2)
                                                    ->count();
            $tauxConformite = ($totalEvaluations > 0) ? ($positiveEvaluations * 100 / $totalEvaluations) : 0;
    
            $result[] = [
                'champ' => $champ->name, // assuming 'nom' is the name of the champ
                'criteres' => $criteresScores,
                'tauxConformite' => $tauxConformite
            ];
        }
    
        return response()->json($result, 200);
    }
    

}