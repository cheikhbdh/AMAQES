<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluationinterne extends Model
{
    use HasFactory;
    protected $fillable = ['idcritere', 'idchamps','idpreuve','idfiliere', 'score', 'commentaire'];
}

