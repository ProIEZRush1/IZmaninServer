<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZmanimController;

Route::get('/zmanim', [ZmanimController::class, 'getZmanim']);
Route::get('/locations', [ZmanimController::class, 'getLocations']);