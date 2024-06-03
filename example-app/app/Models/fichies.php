<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fichies extends Model
{
    use HasFactory;
    protected $fillable = ['fichier','idfiliere', 'idpreuve'];
}
