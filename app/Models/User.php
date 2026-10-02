<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
// Add this at the very top of app/Models/User.php
use App\Models\Project;// <--- 1. IMPORT THIS

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <--- 2. ADD IT HERE

protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin', // Add this line
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Your relationship from earlier
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}