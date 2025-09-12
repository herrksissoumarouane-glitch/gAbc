<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Filiere extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function efp()
{
    return $this->belongsTo(Efp::class);
}
public function groupes()
{
    return $this->hasMany(Groupe::class);
}
}
