<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;    
use App\Models\Institution;

class InstitutionSeeder extends Seeder
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
            Service::firstOrCreate([
                'name' => 'Renovación de Cédula',
                'description' => 'Proceso para renovar su documento de identidad.',
                'institution_id' => $minterpol->id,
                'is_active' => true,
            ]);
            Service::firstOrCreate([
                'name' => 'Solicitud de Acta de Nacimiento',
                'description' => 'Trámite para obtener una copia de su acta de nacimiento.',
                'institution_id' => $minterpol->id,
                'is_active' => true,
            ]);
        }

        if ($pasaportes) {
            Service::firstOrCreate([
                'name' => 'Solicitud de Pasaporte Nuevo',
                'description' => 'Proceso para solicitar un pasaporte por primera vez.',
                'institution_id' => $pasaportes->id,
                'is_active' => true,
            ]);
            Service::firstOrCreate([
                'name' => 'Renovación de Pasaporte',
                'description' => 'Proceso para renovar un pasaporte existente.',
                'institution_id' => $pasaportes->id,
                'is_active' => true,
            ]);
        }

        if ($jce) {
            Service::firstOrCreate([
                'name' => 'Duplicado de Cédula',
                'description' => 'Solicitud de un duplicado de la cédula de identidad.',
                'institution_id' => $jce->id,
                'is_active' => true,
            ]);
        }

        \Illuminate\Support\Facades\Log::info('Servicios base creados exitosamente.');
    }
}


