<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToCollection, ToModel
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        
    }

    public function model(array $row)
    {
        dd($row);
    }
}
