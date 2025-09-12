<?php

namespace App\Http\Controllers;
use App\Models\Complexe;
use App\Models\Efp;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GroupeController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    $user = auth()->user();

    if (!$user->hasAnyPermission(['group.list'])) {
        return response()->view('Errors.404', ['message' => "Vous n'êtes pas autorisé à la page GROUPES !"], 404);
    }

    if ($user->role === 'admin') {
        // Admin: get all groups with full info
        $groups = DB::table('groupes')
            ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
            ->join('efps', 'filieres.efp_id', '=', 'efps.id')
            ->join('complexes', 'efps.complexe_id', '=', 'complexes.id')
            ->select('groupes.id', 'groupes.groupe', 'groupes.code_groupe', 'groupes.num_groupe', 'groupes.annee_formation', 'efps.efp', 'efps.code')
            ->orderBy('efps.efp', 'asc')
            ->orderBy('groupes.code_groupe', 'asc')
            ->orderBy('groupes.num_groupe', 'asc')
            ->get();
    } else {
        // Non-admins: only their related groups
        $groups = DB::table('groupes')
            ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
            ->join('efps', 'filieres.efp_id', '=', 'efps.id')
            ->join('complexes', 'efps.complexe_id', '=', 'complexes.id')
            ->join('user_groupe', 'groupes.id', '=', 'user_groupe.groupe_id')
            ->where('user_groupe.user_id', $userId)
            ->select('groupes.id', 'groupes.groupe', 'groupes.code_groupe', 'groupes.num_groupe', 'groupes.annee_formation', 'efps.efp', 'efps.code')
            ->orderBy('efps.efp', 'asc')
            ->orderBy('groupes.code_groupe', 'asc')
            ->orderBy('groupes.num_groupe', 'asc')
            ->get();
    }

    return view('Groupes/all_groups', compact('groups'));
}



}
