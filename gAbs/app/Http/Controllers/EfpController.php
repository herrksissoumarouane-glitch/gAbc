<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Efp;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EfpsImport;


class EfpController extends Controller
{
    function AllEfps()
{
    $user = auth()->user();

    if (!$user->hasAnyPermission(['efp.list'])) {
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à la page EFPs !"], 404);
    }

    if ($user->role === 'admin') {
        // Admin with permission sees all EFPs
        $efps = Efp::all();
    } else {
        // Others with permission see only related EFPs
        $efps = Efp::whereIn('id', function ($query) use ($user) {
            $query->select('filieres.efp_id')
                ->from('filieres')
                ->join('groupes', 'filieres.id', '=', 'groupes.filiere_id')
                ->join('user_groupe', 'groupes.id', '=', 'user_groupe.groupe_id')
                ->where('user_groupe.user_id', $user->id);
        })->get();
    }

    return view('Efps/all_efps', compact('efps'));
}


    public function import(Request $request, $complexe_id)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new EfpsImport($complexe_id), $request->file('excel_file'));

        return back()->with('success', 'Importation réussie!');
    }

}
