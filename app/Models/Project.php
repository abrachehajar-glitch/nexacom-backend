<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // AJOUTE 'status' ET 'price' ICI :
  protected $fillable = [
    'title',
    'description',
    'contact_name',
    'contact_email',
    'contact_phone',
    'audio_path',
    'status', // INDISPENSABLE
    'price',  // INDISPENSABLE
    'user_id',
    'work_file_path'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}