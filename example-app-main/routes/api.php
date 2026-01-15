<?php

use App\Http\Controllers\PlayerApiController;
use App\Http\Controllers\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/player',[PlayerApiController::class, "list"]);