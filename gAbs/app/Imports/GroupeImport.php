<?php

namespace App\Imports;

use App\Models\Groupe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupeImport implements ToModel, WithHeadingRow
{
    protected $filiere_id;

    public function __construct($filiere_id)
    {
        $this->filiere_id = $filiere_id;
    }
    public function model(array $row)
    {
            // Define the attributes to check for existence
        $attributes = [
            'groupe' => $row['groupe'] ?? null,
            'code_groupe' => $row['codegroupe'] ?? null,
            'num_groupe' => $row['numgroupe'] ?? null,
            'annee_formation' => $row['annee_de_formation'] ?? null,
            'filiere_id' => $this->filiere_id,
        ];

        // Use updateOrCreate to check for existence and update or create the record
        return Groupe::updateOrCreate(
            $attributes, // Check for these attributes
            $attributes   // If not found, create the record with these attributes
        );
        }
}
