<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FormateurImport;
use App\Models\Complexe;
use App\Models\Filiere;
use App\Models\Efp;
use App\Models\Groupe;
use App\Models\UserGroupe;
use Illuminate\Support\Facades\DB;

class FormateurController extends Controller
{
    public function index()
    { 
        $user = auth()->user();
    if($user->hasAnyPermission(['formateur.list'])){
        $vacataires = User::where('type', 'vacataire')->where('role', 'formateur')->get();
 
        $permanents = User::where('type', 'permanent')->where('role', 'formateur')->get();
        $gStagiaires = User::where('type', 'gestionnaire')->where('role', 'gestionnaire')->get();
        $directeurs = User::where('type', 'directeur')->where('role', 'directeur')->get();
 
        return view('Formateurs.all_formateurs', compact('vacataires', 'permanents','gStagiaires','directeurs'));
    }else{
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à lapage FORMATEUR !"], 404);
    }
    }

    public function import(Request $request)
{ 
    
    $user = auth()->user();
    if($user->hasAnyPermission(['formateur.add'])){
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
        'type' => 'required|in:vacataire,permanent,gestionnaire,directeur',  
    ]);

    Excel::import(new FormateurImport($request->type), $request->file('file'));

    return redirect()->back()->with('success', 'Importation réussie!');
}else{
    return response()->view('Errors.404', ['message' => "Vous n'êtes pas autorisé à ajouter un FORMATEUR !"], 404);
}
}

public function affecterGroupes($id){

    $idFormateur = $id;
    return view('Formateurs.affecterGroupes', compact('idFormateur'));
}

public function getComplexes(Request $request)
{
    try {
        $query = $request->get('query');

        

        $complexes = Complexe::where('complexe', 'LIKE', '%' . $query . '%')
                            ->select('id', 'complexe')
                            ->get();

        return response()->json($complexes);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function getEfps($complexId, $idFormateur)
{
    // Get EFPs
    $efps = Efp::where('complexe_id', $complexId)->get(['id', 'efp']);

    // Get Filieres for all EFPs
    $filiereData = [];
    $groupData = [];

    foreach ($efps as $efp) {
        $filieres = Filiere::where('efp_id', $efp->id)->get(['id', 'filiere']);
        $filiereData[$efp->id] = $filieres;

        foreach ($filieres as $filiere) {
            $groupes = Groupe::where('filiere_id', $filiere->id)->get(['id', 'groupe', 'code_groupe', 'num_groupe', 'annee_formation']);
            $groupData[$filiere->id] = $groupes;
        }
    }

    // Assigned groups for the formateur
    $assignedGroupIds = UserGroupe::where('user_id', $idFormateur)->pluck('groupe_id')->toArray();

    return response()->json([
        'efps' => $efps,
        'filieres' => $filiereData,
        'groupes' => $groupData,
        'assignedGroupIds' => $assignedGroupIds
    ]);
}



public function AffectGroups(Request $request)
{
    $formateurId = $request->input('idFormateur');
    $filiereId = $request->input('filiere_id');
    $groupes = $request->input('groupes', []);

    // Récupérer les ID des groupes de cette filière
    $groupesFiliere = DB::table('groupes')
        ->where('filiere_id', $filiereId)
        ->pluck('id')
        ->toArray();

    // Supprimer uniquement les associations du formateur avec ces groupes
    DB::table('user_groupe')
        ->where('user_id', $formateurId)
        ->whereIn('groupe_id', $groupesFiliere)
        ->delete();

    // Insérer les nouveaux groupes cochés
    $now = now();
    $insertData = [];

    foreach ($groupes as $groupeId) {
        $insertData[] = [
            'user_id' => $formateurId,
            'groupe_id' => $groupeId,
            'created_at' => $now,
            'updated_at' => $now
        ];
    }

    if (!empty($insertData)) {
        DB::table('user_groupe')->insert($insertData);
    }

    return redirect()->back()->with('success', 'Groupes mis à jour avec succès.');
}

}
