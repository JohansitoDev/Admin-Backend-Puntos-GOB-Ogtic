<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoGOB;    
use App\Models\Institution; 

class PuntoGOBSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
       
        $minterpol = Institution::where('slug', 'minterpol')->first();
        $pasaportes = Institution::where('slug', 'pasaportes')->first();
        $jce = Institution::where('slug', 'jce')->first();

       
        if ($minterpol) {
            PuntoGOB::firstOrCreate([
                'name' => 'Punto GOB Zona Oriental',
                'address' => 'Av. San Vicente de Paul, Plaza Megacentro',
                'phone' => '809-555-0010',
                'email' => 'zonaoriental@gob.do',
                'institution_id' => $minterpol->id,
                'is_active' => true,
            ]);
        }

        if ($pasaportes) {
            PuntoGOB::firstOrCreate([
                'name' => 'Punto GOB Sede Central Pasaportes',
                'address' => 'Av. George Washington #100',
                'phone' => '809-555-0011',
                'email' => 'sedepasaportes@gob.do',
                'institution_id' => $pasaportes->id,
                'is_active' => true,
            ]);
        }
        
        if ($jce) {
            PuntoGOB::firstOrCreate([
                'name' => 'Punto GOB Santiago',
                'address' => 'Av. Estrella Sadhala, Santiago',
                'phone' => '809-555-0012',
                'email' => 'santiago@gob.do',
                'institution_id' => $jce->id,
                'is_active' => true,
            ]);
        }

        \Illuminate\Support\Facades\Log::info('Puntos GOB base creados exitosamente.');
    }
}



