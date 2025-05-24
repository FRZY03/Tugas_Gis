<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\YourController;

// Panggil method 'index' di WilayahController
Route::get('/', function () {
    return redirect()->route('wilayah.index');
});
Route::get('/wilayah/manage', [WilayahController::class, 'manage'])->name('wilayah.manage');
Route::delete('/wilayah/hapus-semua', [WilayahController::class, 'hapusSemua'])->name('wilayah.hapusSemua');
Route::resource('wilayah', WilayahController::class);


// Panggil method 'handleMapClick' di YourController
Route::post('/your-endpoint', [YourController::class, 'handleMapClick'])->name('map.handleClick');
