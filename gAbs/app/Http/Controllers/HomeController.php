<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Absence;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
         $today = Carbon::today()->toDateString();
         $yesterday = Carbon::yesterday()->toDateString();
         $user = Auth::user();
     
         // Get group IDs assigned to the logged-in user
         $groupeIds = DB::table('user_groupe')
             ->where('user_id', $user->id)
             ->pluck('groupe_id');
     
         // Get absences for today and yesterday, grouped by stagiaire
         $stagiaires = DB::table('users')
             ->join('absences', 'users.user_id_number', '=', 'absences.stagiaire_user_id_number')
             ->join('groupes', 'absences.groupId', '=', 'groupes.id')
             ->whereIn('absences.groupId', $groupeIds)
             ->whereIn('absences.date', [$today, $yesterday])
             ->where('users.role', 'stagiaire')
             ->select(
                 'users.name',
                 'users.user_id_number',
                 'groupes.groupe as group_name',
                 'groupes.num_groupe',
                 'groupes.annee_formation',
                 'absences.seance_number',
                 'absences.statut_abcence'
             )
             ->get()
             ->groupBy('user_id_number')
             ->map(function ($items) {
                 $timeSlots = [
                     1 => '8h30 - 11h00',
                     2 => '11h00 - 13h30',
                     3 => '13h30 - 16h00',
                     4 => '16h00 - 18h30',
                 ];
     
                 $getSlot = function ($seance) use ($timeSlots) {
                     $position = ($seance - 1) % 4 + 1;
                     return $timeSlots[$position];
                 };
     
                 $seances = $items->pluck('seance_number')
                     ->sort()
                     ->unique()
                     ->map($getSlot)
                     ->unique()
                     ->implode(' || ');
     
                 $statuts = $items->pluck('statut_abcence')
    ->filter()
    ->map(fn($s) => trim($s))
    ->unique()
    ->implode(' || ');

                 return [
                     'name' => $items->first()->name,
                     'group_name' => $items->first()->group_name,
                     'num_groupe' => $items->first()->num_groupe,
                     'annee_formation' => $items->first()->annee_formation,
                     'seances' => $seances,
                     'statuts' => $statuts,
                 ];
             });
     
         return view('home', compact('stagiaires'));
     }
     

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
