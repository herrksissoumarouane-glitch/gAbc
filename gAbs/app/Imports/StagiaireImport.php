<?php
namespace App\Imports;

use App\Models\Stagiaire;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StagiaireImport implements ToModel, WithHeadingRow
{
    public $groupId;

    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

    public function model(array $row)
    {

        $user = User::updateOrCreate(
            ['user_id_number' => $row['cef']],
            [
                'name' => $row['nom'].' '.$row['prenom'],
                'email' => $row['cef'].'@ofppt-edu.ma',
                'password' => Hash::make($row['cef']),
                'email_verified_at' => now(),
                'type' => 'Stagiaire',
                'role' => 'Stagiaire',
            ]
        );

         // Insert into user_groupe table
        DB::table('user_groupe')->updateOrInsert(
            [
                'user_id' => $user->id,
                'groupe_id' => $this->groupId,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
