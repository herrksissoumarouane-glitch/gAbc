<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Groupe;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function download(Request $request)
     {
         $groupIds = $request->input('groups', []);
         $weekStart = $request->input('week');
         $weekEnd = Carbon::parse($weekStart)->addDays(6)->toDateString();
     
         if (empty($groupIds)) {
             return redirect()->back()->with('error', 'Aucun groupe sélectionné.');
         }
     
         $zipFileName = 'FeuillesAbsence-' . now()->format('YmdHis') . '.zip';
         $zipFilePath = storage_path("app/public/{$zipFileName}");
         $zip = new ZipArchive;
     
         if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
             foreach ($groupIds as $groupId) {
                 // Récupérer le groupe
                 $group = DB::table('groupes')
                     ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
                     ->join('efps', 'filieres.efp_id', '=', 'efps.id')
                     ->select(
                         'groupes.*',
                         'filieres.filiere',
                         'filieres.annee_formation',
                         'efps.efp'
                     )
                     ->where('groupes.id', $groupId)
                     ->first();
     
                 if (!$group) continue;
     
                 // Stagiaires
                 $stagiaires = DB::table('users')
                     ->join('user_groupe', 'users.id', '=', 'user_groupe.user_id')
                     ->where('user_groupe.groupe_id', $groupId)
                     ->where('users.role', 'stagiaire')
                     ->orderBy('users.name')
                     ->select('users.*')
                     ->get();
     
                 // Absences
                 $absences = DB::table('absences')
                     ->where('groupId', $groupId)
                     ->whereBetween('date', [$weekStart, $weekEnd])
                     ->get();
     
                 $absenceMap = [];
                 foreach ($absences as $abs) {
                     $absenceMap[$abs->stagiaire_id][$abs->seance_number] = true;
                 }
     
                 $formateurIds = $absences->pluck('inserted_by_user_id')->unique()->toArray();
                 $formateurNames = DB::table('users')
                     ->whereIn('id', $formateurIds)
                     ->pluck('name', 'id');
     
                 $formateurs = [];
                 foreach ($absences as $abs) {
                     $formateurs[$abs->seance_number][$abs->inserted_by_user_id] = $formateurNames[$abs->inserted_by_user_id] ?? '';
                 }
     
                 // Génération du PDF
                 $pdf = PDF::loadView('Download.downloadTemplate', [
                     'group' => $group,
                     'stagiaires' => $stagiaires,
                     'absenceMap' => $absenceMap,
                     'formateurs' => $formateurs,
                     'weekStart' => $weekStart,
                     'weekEnd' => $weekEnd,
                 ])->setPaper('A4', 'portrait');
     
                 $fileName = "Absence-{$group->code_groupe}{$group->num_groupe}-{$weekStart}.pdf";
                 $pdfPath = storage_path("app/temp/{$fileName}");
     
                 // Crée le répertoire temporaire si non existant
                 if (!file_exists(storage_path('app/temp'))) {
                     mkdir(storage_path('app/temp'), 0777, true);
                 }
     
                 file_put_contents($pdfPath, $pdf->output());
     
                 $zip->addFile($pdfPath, $fileName);
             }
     
             $zip->close();
     
             // Supprimer les fichiers PDF temporaires
             $files = glob(storage_path('app/temp/*.pdf'));
             foreach ($files as $file) {
                 @unlink($file);
             }
     
             return response()->download($zipFilePath)->deleteFileAfterSend(true);
         }
     
         return redirect()->back()->with('error', 'Erreur lors de la création de l’archive ZIP.');
     }

     
    public function index(Request $request)
    {
        $user = Auth::user();
    
        // Groupes de l'utilisateur
        $groupIds = \DB::table('user_groupe')
                    ->where('user_id', Auth::id())
                    ->pluck('groupe_id');
    
        $groups = \DB::table('groupes')
                    ->join('filieres', 'groupes.filiere_id', '=', 'filieres.id')
                    ->join('efps', 'filieres.efp_id', '=', 'efps.id')
                    ->whereIn('groupes.id', $groupIds)
                    ->select(
                        'groupes.id as group_id',
                        'groupes.groupe',
                        'groupes.code_groupe',
                        'groupes.num_groupe',
                        'groupes.annee_formation',
                        'filieres.code_filiere',
                        'efps.efp'
                    )
                    ->get();
    
        // Supposons que tu récupères l’année à utiliser :
        $selectedYear = $request->get('annee') ?? '2024/2025'; // Valeur par défaut
    
        // Extraire les deux années
        preg_match('/(\d{4})[\/\-](\d{4})/', $selectedYear, $matches);
        $yearStart = (int) $matches[1];
        $yearEnd = (int) $matches[2];
    
        // Génération des semaines : Sept -> Juillet
        $start = Carbon::create($yearStart, 9, 2)->startOfWeek(Carbon::MONDAY);
        $end = Carbon::create($yearEnd, 7, 31)->endOfWeek(Carbon::SATURDAY);
    
        $weeks = [];
        $weekNumber = 1;
    
        while ($start->lte($end)) {
            $weekStart = $start->copy();
            $weekEnd = $start->copy()->addDays(5); // Lundi à Samedi
    
            $weeks[] = [
                'start' => $weekStart->toDateString(),
                'end' => $weekEnd->toDateString(),
                'label' => 's' . $weekNumber . ' (' . $weekStart->format('d/m/Y') . ' - ' . $weekEnd->format('d/m/Y') . ')',
                'current' => now()->between($weekStart, $weekEnd),
            ];
    
            $start->addWeek();
            $weekNumber++;
        }
    
        return view('Download/download', compact('groups', 'weeks', 'selectedYear'));
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
