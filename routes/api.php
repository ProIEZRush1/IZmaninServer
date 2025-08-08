<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZmanimController;
use App\Http\Controllers\PrivacyPolicyController;

Route::get('/zmanim', [ZmanimController::class, 'getZmanim']);
Route::get('/locations', [ZmanimController::class, 'getLocations']);
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'html']);
Route::get('/privacy-policy/json', [PrivacyPolicyController::class, 'index']);