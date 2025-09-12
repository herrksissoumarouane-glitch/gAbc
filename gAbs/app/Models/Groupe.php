<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function filiere()
{
    return $this->belongsTo(Filiere::class);
}

public function users()
{
    return $this->belongsToMany(User::class, 'user_groupe');
}
}
