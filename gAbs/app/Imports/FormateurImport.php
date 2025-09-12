<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class FormateurImport implements ToModel, WithHeadingRow
{
    protected $typeFormateur;

    public function __construct($typeFormateur)
    {
        $this->typeFormateur = $typeFormateur;
    }
    public function model(array $row)
    {
        $connecteUser = auth()->user();
        $role = $this->typeFormateur == 'gestionnaire' ? 'gestionnaire' :($this->typeFormateur == 'directeur' ? 'directeur' : 'formateur');
    if($connecteUser->hasAnyPermission(['formateur.add'])){
        $user = User::updateOrCreate(
            ['user_id_number' => $row['mle']],
            [
                'name' => $row['nom_et_prenom'],
                'email' => $row['email_edu'],
                'type' => $this->typeFormateur,
                'role' => $role,
                'password' => Hash::make('1111'),
                'email_verified_at' => '2025-02-14 16:00:00',
            ]
        );
        
        // Get the role ID
        $roleId = Role::where('name', $role)->value('id');
        
        if ($roleId && $user) {
            DB::table('model_has_roles')->updateOrInsert(
                [
                    'role_id' => $roleId,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id
                ],
                [] // no additional values needed
            );
        }
    }else{
        return response()->view('errors.404', ['message' => "Vous n'êtes pas autorisé à ajouter un FORMATEUR !"], 404);
    }
    }
}
