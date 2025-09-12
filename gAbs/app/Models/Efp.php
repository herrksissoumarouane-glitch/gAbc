<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Efp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function complexe()
    {
        return $this->belongsTo(Complexe::class);
    }

    public function filieres()
{
    return $this->hasMany(Filiere::class);
}
}
