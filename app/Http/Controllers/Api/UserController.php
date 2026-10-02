<?php

namespace App\Http\Controllers\Api; // <--- Add \Api here to match your route

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project; // <--- Add this import
use Illuminate\Support\Facades\DB;

class UserController extends Controller 
{
    // ... keep the rest of your code the same
    public function destroy(Request $request)
    {
        $user = $request->user();

        // 1. Vérifier les projets en cours
        $hasWorkInProgress = $user->projects()->where('status', 'confirmed')->exists();

        if ($hasWorkInProgress) {
            return response()->json([
                'message' => 'Action impossible : Un projet est en cours de réalisation.'
            ], 403);
        }

        try {
            DB::transaction(function () use ($user) {
                // 2. Anonymiser les projets livrés (Mis à NULL)
                // On utilise update(['user_id' => null])
                $user->projects()->where('status', 'delivered')->update(['user_id' => null]);

                // 3. Supprimer l'utilisateur
                // Les projets 'pending' ou 'accepted' seront supprimés par la cascade SQL
                $user->delete();
            });

            return response()->json(['message' => 'Compte supprimé avec succès']);

        } catch (\Exception $e) {
            // C'est ici que l'erreur 500 est capturée pour le debug
            return response()->json([
                'error' => 'Erreur SQL',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}