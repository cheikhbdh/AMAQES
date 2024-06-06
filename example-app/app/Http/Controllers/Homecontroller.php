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
        $isUserInvited = $user->invitation == 1;
        $hasActiveInvitation = $isUserInvited && Invitation::where('statue', 1)->exists();
        $champs = Champ::with('criteres.preuves')->get();
        $champsEvaluer = $champs->filter(function($champ) {
            foreach ($champ->criteres as $critere) {
                foreach ($critere->preuves as $preuve) {
                    if (Evaluationinterne::where('idpreuve', $preuve->id)->exists()) {
                        return true;
                    }
                }
            }
            return false;
        });
        $champsNonEvaluer = $champs->diff($champsEvaluer);
    
        return view('layout.liste', compact( 'champsNonEvaluer', 'hasActiveInvitation'));
    }
    
    public function evaluate(Request $request)
    {
        $data = $request->all();

        foreach ($data['evaluations'] as $evaluation) {
            $score = 0;
            if ($evaluation['value'] === 'oui') {
                $score = 1;
            } elseif ($evaluation['value'] === 'non') {
                $score = -1;
            }
            $user = Auth::user();
            $result = evaluationinterne::create([
                'idcritere' => $evaluation['idcritere'],
                'idpreuve' => $evaluation['idpreuve'],
                'idfiliere'=>$user->filières_id,
                'score' => $score,
                'commentaire' => $evaluation['commentaire'] ?? null,
            ]);
           
            if ($request->hasFile('file-' . $evaluation['idpreuve'])) {
                $filePath = $request->file('file-' . $evaluation['idpreuve'])->store('preuves');

                fichies::create([
                    'fichier' => $filePath,
                    'idpreuve' => $evaluation['idpreuve'],
                    'idfiliere'=>$user->filières_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Evaluation saved successfully.');
    }
    
    public function evaluation_interne()
    {
        $filieres = User::distinct()->pluck('filières_id'); // Récupérer toutes les filières distinctes des utilisateurs
    
        $resultats = [];
    
        foreach ($filieres as $filiere) {
            $champs = Champ::with(['criteres' => function($query) use ($filiere) {
                $query->whereHas('evaluations', function($q) use ($filiere) {
                    $q->where('idfiliere', $filiere);
                });
            }])->get();
    
            $filiereResult = [
                'filiere' => $filiere,
                'champs' => [],
            ];
    
            foreach ($champs as $champ) {
                $champScore = 0;
                $criteresData = [];
    
                foreach ($champ->criteres as $critere) {
                    $critereScore = Evaluationinterne::where('idcritere', $critere->id)
                                                      ->where('idfiliere', $filiere)
                                                      ->sum('score');
                    $champScore += $critereScore;
    
                    $criteresData[] = [
                        'critere' => $critere->nom,
                        'score' => $critereScore,
                    ];
                }
    
                $filiereResult['champs'][] = [
                    'champ' => $champ->name,
                    'score' => $champScore,
                    'criteres' => $criteresData,
                ];
            }
    
            $resultats[] = $filiereResult;
        }
    
        return view('dashadmin.resultat_evin', compact('resultats'));
    }
}