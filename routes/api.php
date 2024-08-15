<?php

use App\Http\Controllers\api\personaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/personas', [PersonaController::class, 'index']);
Route::get('/personas/{id}', [PersonaController::class, 'show']);
Route::post('/personas', [PersonaController::class, 'store']);
Route::put('/personas/{id}', [PersonaController::class, 'update']);
Route::patch('/personas/{id}', [PersonaController::class, 'updatePartial']);
Route::delete('/personas/{id}', [PersonaController::class, 'destroy']);
