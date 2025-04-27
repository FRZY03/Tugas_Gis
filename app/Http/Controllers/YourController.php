<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YourController extends Controller
{
    public function handleMapClick(Request $request)
{
    // Mendapatkan data dari request
    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');
    $idWilayah = $request->input('id_wilayah');
    
    // Proses data sesuai kebutuhan kamu
    return response()->json(['message' => 'Data received successfully']);
}

}
