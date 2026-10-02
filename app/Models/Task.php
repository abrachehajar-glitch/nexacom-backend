<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // On autorise TOUT l'enregistrement
    protected $guarded = []; 
    
    // On force le nom de la table
   protected $fillable = ['label', 'is_completed'];
}