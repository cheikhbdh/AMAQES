<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'reference_id','signature'];

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }

    public function preuves()
    {
        return $this->hasMany(Preuve::class);
    }
}