<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création du VRAI compte Admin
        User::create([
            'name' => 'Admin Nexacom',
            'email' => 'admin@nexacom.com',
            'password' => Hash::make('admin123'), // Ton mot de passe
            'is_admin' => true, // <--- C'EST CA QUI MANQUAIT !
        ]);

        // Création d'un compte Client pour tes tests
        User::create([
            'name' => 'Client Demo',
            'email' => 'client@test.com',
            'password' => Hash::make('client123'),
            'is_admin' => false,
        ]);
    }
}