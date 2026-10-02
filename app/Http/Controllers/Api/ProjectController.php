<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index() {
        return Project::orderBy('created_at', 'desc')->get();
    }

    public function userProjects(Request $request) {
        return $request->user()->projects()->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'audio'        => 'nullable|file|mimes:mp3,wav,ogg,webm|max:10240', 
            'contact_name' => 'required|string|max:100',
        ]);

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('audio', 'public');
        }

        return auth()->user()->projects()->create([
            'title'         => $request->title,
            'description'   => $request->description,
            'contact_name'  => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'audio_path'    => $audioPath,
            'status'        => 'pending',
            'price'         => 0
        ]);
    }

    public function adminSetPrice(Request $request, $id) {
        $request->validate(['price' => 'required|numeric|min:0']);
        $project = Project::findOrFail($id);
        $project->update(['status' => 'accepted', 'price' => $request->price]);
        return response()->json(['message' => 'Quote sent!']);
    }

    public function clientAccept($id) {
        $project = Project::findOrFail($id);
        if ($project->user_id !== auth()->id()) return response()->json(['message' => 'Unauthorized'], 403);
        $project->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Project confirmed']);
    }
  
    public function deliver(Request $request, $id) {
        if (!auth()->user()->is_admin) return response()->json(['message' => 'Unauthorized'], 403);
        
        $request->validate([
            'work_file' => 'required|file|mimes:zip,pdf,jpg,jpeg,png|max:20480'
        ]);
        
        $project = Project::findOrFail($id);
        
        if ($request->hasFile('work_file')) {
            $path = $request->file('work_file')->store('deliverables', 'local');
            $project->update(['work_file_path' => $path, 'status' => 'delivered']);
            return response()->json(['message' => 'Work delivered!', 'path' => $path]);
        }
        return response()->json(['message' => 'No file'], 400);
    }

    public function downloadDesign($id) {
        $project = Project::findOrFail($id);
        $user = auth()->user();

        if ($user->id !== $project->user_id && !$user->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $filePath = $project->work_file_path;

        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            return response()->json(['error' => 'Fichier introuvable'], 404);
        }

        if (ob_get_level()) ob_end_clean(); 

        return Storage::disk('local')->download($filePath);
    }

    // --- FONCTION DESTROY CORRIGÉE ---
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $user = auth()->user();

        // 1. Vérification des permissions de base
        if (!$user->is_admin && $project->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 2. Vérification du statut pour le client
        if (!$user->is_admin && !in_array($project->status, ['pending', 'accepted'])) {
            return response()->json(['message' => 'Cannot cancel a confirmed or delivered project'], 403);
        }

        // 3. Suppression des fichiers physiques
        if ($project->audio_path) {
            Storage::disk('public')->delete($project->audio_path);
        }
        if ($project->work_file_path) {
            Storage::disk('local')->delete($project->work_file_path);
        }

        // 4. Suppression de la base de données
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}