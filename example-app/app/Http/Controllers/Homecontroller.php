<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Champ;
use App\Models\evaluationinterne;
use App\Models\fichies;
use Illuminate\Support\Facades\Storage;
class Homecontroller extends Controller
{
    public function indexevaluation()
    {
        $champs = Champ::with('criteres.preuves')->get();

        $champsEvaluer = $champs->filter(function($champ) {
            foreach ($champ->criteres as $critere) {
                foreach ($critere->preuves as $preuve) {
                    if (evaluationinterne::where('idpreuve', $preuve->id)->exists()) {
                        return true;
                    }
                }
            }
            return false;
        });

        $champsNonEvaluer = $champs->diff($champsEvaluer);

        return view('layout.liste', compact('champsEvaluer', 'champsNonEvaluer'));
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
}
    

