<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Champ extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'referentiel_id'];

    public function referentiel()
    {
        return $this->belongsTo(Referentiel::class);
    }

    // Add this method to define the relationship with Critere
    public function criteres()
    {
        return $this->hasMany(Critere::class);
    }
}

