<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Institution;
use App\Models\PuntoGOB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
      
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'sanctum']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);

        
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@agendagob.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Super',
                'password' => Hash::make('password'),
                'identification_number' => '000-0000000-0',
                'sex' => 'Masculino',
                'role' => 'SuperAdmin',
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole($superAdminRole); 

        $institution = Institution::firstOrCreate(
            ['name' => 'Ministerio de Prueba'],
            [
                'slug' => 'ministerio-de-prueba', 
                'phone' => '809-111-2222',
                'institutional_email' => 'info@minprueba.gob.do',
                'contact_person_name' => 'Juan Perez',
                'status' => 'Activo',
                'is_active' => true, 
            ]
        );

        $puntoGob = PuntoGOB::firstOrCreate(
            ['name' => 'Punto GOB Principal'],
            [
                'location' => 'Av. Siempre Viva 742',
                'capacity' => 'Alta',
                'status' => 'Activo',
            ]
        );

      
        if (!$institution->puntoGobs->contains($puntoGob->id)) {
            $institution->puntoGobs()->attach($puntoGob->id);
            $this->command->info('Punto GOB Principal asociado a Ministerio de Prueba.');
        }

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@agendagob.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Test',
                'password' => Hash::make('password'),
                'identification_number' => '000-0000000-1',
                'sex' => 'Femenino',
                'role' => 'Admin',
                'is_active' => true,
                'institution_id' => $institution->id, 
                'punto_gob_id' => $puntoGob->id,   
            ]
        );
        $adminUser->assignRole($adminRole); 

       
        User::firstOrCreate(
            ['email' => 'citizen@agendagob.com'],
            [
                'first_name' => 'Ciudadano',
                'last_name' => 'Test',
                'password' => Hash::make('password'),
                'identification_number' => '000-0000000-2',
                'sex' => 'Masculino',
                // 'role' => 'Citizen', // Esta línea ya no es necesaria
                'is_active' => true,
            ]
        );

        $this->command->info('SuperAdmin, Admin, Ciudadano, Institución y Punto GOB de prueba creados/actualizados.');
    }
}