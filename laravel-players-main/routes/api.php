<?php

use App\Http\Controllers\Api\PlayerApiController;
use Illuminate\Support\Facades\Route;

// Forma corta: crea automáticamente 5 rutas API (sin create y edit)
Route::apiResource('players', PlayerApiController::class);