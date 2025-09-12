<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complexe;
use App\Models\Efp;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function index()
{
    $schoolYearStart = 2024;
    $schoolYearEnd = 2025;

    $start = Carbon::createFromDate($schoolYearStart, 9, 1)->next(Carbon::MONDAY);
    $endLimit = Carbon::createFromDate($schoolYearEnd, 7, 31);

    $weeks = [];
    $weekNumber = 1;
    $today = Carbon::today();

    while ($start->lessThanOrEqualTo($endLimit)) {
        $end = $start->copy()->addDays(5);

        if ($end->greaterThan($endLimit)) {
            break;
        }

        $weeks[] = [
            'label' => 's' . $weekNumber . ' (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . ')',
            'start' => $start->format('d/m/Y'),
            'end' => $end->format('d/m/Y'),
            'number' => $weekNumber,
            'current' => $today->between($start->copy()->startOfDay(), $end->copy()->endOfDay()),
        ];

        $start->addWeek();
        $weekNumber++;
    }

    $userId = Auth::id();
    $user = auth()->user();

    if (!$user->hasAnyPermission(['group.list'])) {
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à la page GROUPES !"], 404);
    }

    if ($user->role === 'admin') {
        // Admin: get all groups
        $groups = DB::table('groupes')
            ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
            ->join('efps', 'filieres.efp_id', '=', 'efps.id')
            ->select(
                'groupes.id',
                'groupes.groupe',
                'groupes.code_groupe',
                'groupes.num_groupe',
                'groupes.annee_formation',
                'efps.id as efp_id',
                'efps.efp',
                'efps.code'
            )
            ->orderBy('efps.efp')
            ->orderBy('groupes.code_groupe')
            ->orderBy('groupes.num_groupe')
            ->get();
    } else {
        // Non-admins: get assigned groups only
        $groups = DB::table('user_groupe')
            ->join('groupes', 'user_groupe.groupe_id', '=', 'groupes.id')
            ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
            ->join('efps', 'filieres.efp_id', '=', 'efps.id')
            ->where('user_groupe.user_id', $userId)
            ->select(
                'groupes.id',
                'groupes.groupe',
                'groupes.code_groupe',
                'groupes.num_groupe',
                'groupes.annee_formation',
                'efps.id as efp_id',
                'efps.efp',
                'efps.code'
            )
            ->orderBy('efps.efp')
            ->orderBy('groupes.code_groupe')
            ->orderBy('groupes.num_groupe')
            ->get();
    }

    return view('Absences/all_absences', compact('groups', 'weeks'));
}



public function addAbsences(Request $request)
{
    if (auth()->user()->can('absence.add')) {
    $inputs = $request->all();

    if (!isset($inputs['absences']) || !is_array($inputs['absences'])) {
        return response()->view('errors.404', ['message' => "Aucune absence prévue !"], 404);
    }

    $weekStart = \Carbon\Carbon::createFromFormat('d/m/Y', $inputs['week'])->startOfWeek(); // Monday
    $weekEnd = $weekStart->copy()->endOfWeek(); // Sunday
    $absences = $inputs['absences'];

    // If user has the permission to edit absences, delete old ones for the same week and group
    if (auth()->user()->can('absence.edit')) {
        // Assuming absences belong to a group, we extract groupId from first absence string
        [$seance_number, $stagiaire_id, $stagiaire_user_id_number, $inserted_by_user_id, $inserted_by_user_id_number, $groupId] = explode('-', $absences[0]);

        DB::table('absences')
            ->where('groupId', $groupId)
            ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->delete();
    }

    // Re-insert absences
    foreach ($absences as $absence) {
        [$seance_number, $stagiaire_id, $stagiaire_user_id_number, $inserted_by_user_id, $inserted_by_user_id_number, $groupId] = explode('-', $absence);

        $seanceInt = (int) $seance_number;
        $dayOffset = intdiv($seanceInt - 1, 4); // 4 sessions/day
        $absenceDate = $weekStart->copy()->addDays($dayOffset)->format('Y-m-d');

        DB::table('absences')->updateOrInsert(
            [
                'stagiaire_id' => $stagiaire_id,
                'stagiaire_user_id_number' => $stagiaire_user_id_number,
                'groupId' => $groupId,
                'date' => $absenceDate,
                'seance_number' => $seance_number,
            ],
            [
                'inserted_by_user_id' => $inserted_by_user_id,
                'inserted_by_user_id_number' => $inserted_by_user_id_number,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    return redirect()->back()->with([
        'success' => 'Absences ajoutées avec succès !',
    ]);
}else{
    return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à ajouter des absences !"], 404);
}
}

}
