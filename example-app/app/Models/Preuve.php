<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preuve extends Model
{
    use HasFactory;

    protected $fillable = ['critere_id', 'description'];

    public function critere()
    {
        return $this->belongsTo(Critere::class);
    }
}
