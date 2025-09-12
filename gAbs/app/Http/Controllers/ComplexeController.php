<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complexe;
use Illuminate\Support\Facades\Auth;

class ComplexeController extends Controller
{
    function AllComplexes()
{
    $userId = Auth::id();
    $user = auth()->user();

    if (!$user->hasAnyPermission(['complexe.list'])) {
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à la page COMPLEXES !"], 404);
    }

    if ($user->role === 'admin') {
        // Admins with permission see all complexes
        $complexes = Complexe::all();
    } else {
        // Other users with permission see only their linked complexes
        $complexes = Complexe::join('efps', 'complexes.id', '=', 'efps.complexe_id')
            ->join('filieres', 'efps.id', '=', 'filieres.efp_id')
            ->join('groupes', 'filieres.id', '=', 'groupes.filiere_id')
            ->join('user_groupe', 'groupes.id', '=', 'user_groupe.groupe_id')
            ->where('user_groupe.user_id', $userId)
            ->select('complexes.*')
            ->distinct()
            ->get();
    }

    return view('Complexes/all_complexes', compact('complexes'));
}

}
