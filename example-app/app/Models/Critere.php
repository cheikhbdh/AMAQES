<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'champ_id',
    ];

    public function champ()
    {
        return $this->belongsTo(Champ::class);
    }

    public function preuves()
    {
        return $this->hasMany(Preuve::class);
    }
}
