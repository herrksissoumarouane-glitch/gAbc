<?php
namespace App\Http\Controllers;

use App\Imports\StagiaireImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StagiaireController extends Controller
{
    public function import(Request $request)
    {
        
        $user = auth()->user();
    if($user->hasAnyPermission(['stagiaire.add'])){
        
        $import = new StagiaireImport($request->group_id);

        Excel::import($import, $request->file('file'));
       
        return redirect()->back()->with([
            'success' => 'Importation réussie!',
        ]);
    }else{
        return response()->view('Errors.404', ['message' => "Vous n'êtes pas autorisé pour ajouter un GROUPE !"], 404);
    }
    }
    public function index()
{
    $complexes = \App\Models\Complexe::all();
    return view('Stagiaires.all_stagiaires', compact('complexes'));
}

   
public function getByGroup($groupId)
{
    $stagiaires = \App\Models\Stagiaire::where('group_id', $groupId)->get();
    return response()->json($stagiaires);
}

public function stagiaireByGroup($groupId, Request $request)
{
    $user = auth()->user();
    $selectedWeek = $request->query('selectedWeek');
    $startOfWeek = Carbon::createFromFormat('d/m/Y', $selectedWeek)->startOfWeek();
    $endOfWeek = Carbon::createFromFormat('d/m/Y', $selectedWeek)->endOfWeek();

    // Subquery to count all absences per stagiaire
    $absenceCounts = DB::table('absences')
        ->select('stagiaire_id', DB::raw('COUNT(*) as total_absences'))
        ->groupBy('stagiaire_id');

    $records = DB::table('users')
        ->join('user_groupe', 'users.id', '=', 'user_groupe.user_id')
        ->leftJoin('absences', function ($join) use ($groupId, $startOfWeek, $endOfWeek) {
            $join->on('users.id', '=', 'absences.stagiaire_id')
                 ->where('absences.groupId', '=', $groupId)
                 ->whereBetween('absences.date', [$startOfWeek, $endOfWeek]);
        })
        ->leftJoinSub($absenceCounts, 'absence_totals', function ($join) {
            $join->on('users.id', '=', 'absence_totals.stagiaire_id');
        })
        ->where('user_groupe.groupe_id', $groupId)
        ->where('users.role', 'stagiaire')
        ->select(
            'users.id as stagiaire_id',
            'users.name',
            'users.user_id_number',
            'users.type',
            'absences.id as absence_id',
            'absences.date',
            'absences.seance_number',
            DB::raw('COALESCE(absence_totals.total_absences, 0) as total_absences')
        )
        ->orderBy('users.name')
        ->get();

    $stagiaires = [];

    foreach ($records as $record) {
        $id = $record->stagiaire_id;

        if (!isset($stagiaires[$id])) {
            $stagiaires[$id] = [
                'id' => $id,
                'name' => $record->name,
                'user_id_number' => $record->user_id_number,
                'type' => $record->type,
                'absences' => [],
                'total_absences' => $record->total_absences,
            ];
        }

        if ($record->absence_id) {
            $stagiaires[$id]['absences'][] = [
                'id' => $record->absence_id,
                'date' => $record->date,
                'seance_number' => $record->seance_number,
            ];
        }
    }

    $stagiaires = array_values($stagiaires);

    return response()->json([
        'userPermissions' => $user->getAllPermissions()->pluck('name'),
        'groupId' => $groupId,
        'user_id' => $user->id,
        'user_id_number' => $user->user_id_number,
        'stagiaires' => $stagiaires
    ]);
}

public function stagiaireByGroupe($groupId)
{
    $absenceCounts = DB::table('absences')
        ->where('groupId', $groupId)
        ->select('stagiaire_id', DB::raw('COUNT(*) as total_absences'))
        ->groupBy('stagiaire_id');

    $stagiaires = DB::table('users')
        ->join('user_groupe', 'users.id', '=', 'user_groupe.user_id')
        ->leftJoinSub($absenceCounts, 'absence_totals', function ($join) {
            $join->on('users.id', '=', 'absence_totals.stagiaire_id');
        })
        ->where('user_groupe.groupe_id', $groupId)
        ->where('users.role', 'stagiaire')
        ->select(
            'users.user_id_number',
            'users.name',
            DB::raw('COALESCE(absence_totals.total_absences, 0) as total_absences')
        )
        ->orderBy('users.name')
        ->get();

    return response()->json(['stagiaires' => $stagiaires]);
}


}

