<?php

namespace App\Http\Controllers\Api\Admin; // Vérifie bien que ton fichier est dans app/Http/Controllers/Api/Admin/

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller 
{
    public function index() 
    { 
        return response()->json(Task::latest()->get()); 
    }

    public function store(Request $request)
    {
        try {
            // Validation simple
            $validated = $request->validate([
                'label' => 'required|string|max:255',
            ]);

            $task = Task::create([
                'label' => $validated['label'],
                'is_completed' => false
            ]);

            return response()->json($task, 201);
            
        } catch (\Exception $e) {
            // C'est ce message que tu verras dans l'onglet Network (F12)
            return response()->json([
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Task $task) 
{
    // On inverse l'état actuel ou on prend la valeur envoyée
    $task->update([
        'is_completed' => $request->is_completed
    ]);

    return response()->json($task);
}

    public function destroy(Task $task) 
    {
        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }
}