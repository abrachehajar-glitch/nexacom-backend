<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\Admin\TaskController; 
use App\Http\Controllers\Api\UserController; // Correction du Namespace ici


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/user/delete', [UserController::class, 'destroy']);
    Route::get('/projects', [ProjectController::class, 'index']); 
    Route::get('/my-projects', [ProjectController::class, 'userProjects']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::post('/projects/{id}/quote', [ProjectController::class, 'adminSetPrice']);
    Route::post('/projects/{id}/confirm', [ProjectController::class, 'clientAccept']);
    Route::post('/projects/{id}/deliver', [ProjectController::class, 'deliver']);
    Route::get('/projects/{id}/download', [ProjectController::class, 'downloadDesign']);
    Route::get('/admin/tasks', [TaskController::class, 'index']);
    Route::post('/admin/tasks', [TaskController::class, 'store']);
    Route::patch('/admin/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy']);
});