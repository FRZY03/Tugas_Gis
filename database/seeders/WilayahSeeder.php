<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        
        $data['nama']='wilayah1';
        $data['deskripsi']='-';
        Wilayah::create($data);
    }
}
