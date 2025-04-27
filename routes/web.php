<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\YourController;

Route::get('/', [WilayahController::class, 'index']);
Route::post('/your-endpoint', [YourController::class, 'handleMapClick']);