<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,    
            SuperAdminSeeder::class,    
            InstitutionSeeder::class,   
            ServiceSeeder::class,       
            PuntoGOBSeeder::class,      
        ]);
    }
}


