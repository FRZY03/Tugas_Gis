<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\YourController;

// Panggil method 'index' di WilayahController
Route::get('/', [WilayahController::class, 'index'])->name('wilayah.index');

// Panggil method 'handleMapClick' di YourController
Route::post('/your-endpoint', [YourController::class, 'handleMapClick'])->name('map.handleClick');
