<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('players.index');
});

// Forma corta: crea automáticamente 7 rutas
Route::resource('players', PlayerController::class);