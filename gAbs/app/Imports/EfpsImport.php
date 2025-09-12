<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Filiere;

class EfpsImport implements ToModel, WithHeadingRow
{
    protected $complexe_id;

    public function __construct($complexe_id)
    {
        $this->complexe_id = $complexe_id;
    }
    public function model(array $row)
{
    // dd($row);
    return new Filiere([
        'code_secteur' => $row['code_secteur'] ?? null,
        'secteur' => $row['secteur'] ?? null,
        'niv' => $row['niv'] ?? null,
        'code_filiere' => $row['codefiliere'] ?? null,
        'filiere' => $row['filiere'] ?? null,
        'annee_formation' => $row['anneedeformation'] ?? null,
        'efp_id' => $this->complexe_id,
    ]);
}
}
   
