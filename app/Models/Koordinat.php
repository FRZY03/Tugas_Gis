<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Koordinat;

class KoordinatApi extends Controller
{
    public function all()
    {
        $result['status'] = "sukses";
        $result['pesan'] = "data Koordinat";
        $result['data'] = Koordinat::get();
        return $result;
    }
    public function post(Request $req)
    {
            $Koordinat['id_wilayah']=$req->id_wilayah;
            $Koordinat['longitude']=$req->longitude;
            $Koordinat['latitude']=$req->latitude;
            $result['status'] = "sukses";
            $result['pesan'] = "Data Koordinat Disimpan";
            $result['data'] = Koordinat::create($Koordinat);
            return $result;
    }
   
}