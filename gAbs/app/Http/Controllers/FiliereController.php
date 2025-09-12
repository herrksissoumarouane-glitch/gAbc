<?php

namespace App\Http\Controllers;


use App\Imports\GroupeImport;
use Illuminate\Http\Request;
use App\Models\Complexe;
use Maatwebsite\Excel\Facades\Excel;

class FiliereController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if (!$user->hasAnyPermission(['filiere.list', 'group.add'])) {
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à la page FILIERES !"], 404);
    }

    if ($user->role === 'admin') {
        // Admin with permission sees all complexes with nested efps, filieres, and groupes
        $complexes = Complexe::with('efps.filieres.groupes')->get();
    } else {
        // Other users with permission see only related complexes
        $complexes = Complexe::whereHas('efps.filieres.groupes.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->with('efps.filieres.groupes')->get();
    }

    return view('Filieres.all_filieres', compact('complexes'));
}


    public function import(Request $request){

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new GroupeImport($request->input('filiere_id')), $request->file('excel_file'));

        return back()->with('success', 'Groupes importés avec succès.');
    }
}

