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
        // 1. Crear/Encontrar Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'sanctum']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $citizenRole = Role::firstOrCreate(['name' => 'citizen', 'guard_name' => 'sanctum']);
        $this->command->info('Roles superadmin, admin y citizen creados/encontrados.');

        // 2. Crear Permisos (¡MODIFICADO!) - Asegúrate de que no haya 'description'
        $manageAllPermission = Permission::firstOrCreate(['name' => 'manage-all', 'guard_name' => 'sanctum']);
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'sanctum']);
        $manageInstitutionsPermission = Permission::firstOrCreate(['name' => 'manage-institutions', 'guard_name' => 'sanctum']);
        $managePuntosGobPermission = Permission::firstOrCreate(['name' => 'manage-puntos-gob', 'guard_name' => 'sanctum']);
        $manageServicesPermission = Permission::firstOrCreate(['name' => 'manage-services', 'guard_name' => 'sanctum']);
        $manageAdminAppointmentsPermission = Permission::firstOrCreate(['name' => 'manage-admin-appointments', 'guard_name' => 'sanctum']);
        $viewAdminDashboardPermission = Permission::firstOrCreate(['name' => 'view-admin-dashboard', 'guard_name' => 'sanctum']);
        $manageOwnAppointmentsPermission = Permission::firstOrCreate(['name' => 'manage-own-appointments', 'guard_name' => 'sanctum']);
        $createSupportTicketsPermission = Permission::firstOrCreate(['name' => 'create-support-tickets', 'guard_name' => 'sanctum']);
        $viewDashboardDataPermission = Permission::firstOrCreate(['name' => 'view-dashboard-data', 'guard_name' => 'sanctum']);
        $manageProfilePermission = Permission::firstOrCreate(['name' => 'manage-profile', 'guard_name' => 'sanctum']);
        $manageSupportTicketsPermission = Permission::firstOrCreate(['name' => 'manage-support-tickets', 'guard_name' => 'sanctum']);
        $viewHistoryPermission = Permission::firstOrCreate(['name' => 'view-history', 'guard_name' => 'sanctum']);
        $viewReportsPermission = Permission::firstOrCreate(['name' => 'view-reports', 'guard_name' => 'sanctum']);

        $this->command->info('Permisos creados/encontrados.');


        // 3. Asignar Permisos a Roles
        $superAdminRole->givePermissionTo($manageAllPermission);
        $superAdminRole->givePermissionTo($manageUsersPermission);
        $superAdminRole->givePermissionTo($manageInstitutionsPermission);
        $superAdminRole->givePermissionTo($managePuntosGobPermission);
        $superAdminRole->givePermissionTo($manageServicesPermission);
        $superAdminRole->givePermissionTo($manageAdminAppointmentsPermission);
        $superAdminRole->givePermissionTo($viewAdminDashboardPermission);
        $superAdminRole->givePermissionTo($manageOwnAppointmentsPermission);
        $superAdminRole->givePermissionTo($createSupportTicketsPermission);
        $superAdminRole->givePermissionTo($viewDashboardDataPermission);
        $superAdminRole->givePermissionTo($manageProfilePermission);
        $superAdminRole->givePermissionTo($manageSupportTicketsPermission);
        $superAdminRole->givePermissionTo($viewHistoryPermission);
        $superAdminRole->givePermissionTo($viewReportsPermission);


        $adminRole->givePermissionTo($manageAdminAppointmentsPermission);
        $adminRole->givePermissionTo($viewAdminDashboardPermission);
        $adminRole->givePermissionTo($viewDashboardDataPermission);
        $adminRole->givePermissionTo($manageProfilePermission);
        $adminRole->givePermissionTo($manageSupportTicketsPermission);
        $adminRole->givePermissionTo($viewHistoryPermission);
        $adminRole->givePermissionTo($viewReportsPermission);

        $citizenRole->givePermissionTo($manageOwnAppointmentsPermission);
        $citizenRole->givePermissionTo($createSupportTicketsPermission);
        $citizenRole->givePermissionTo($manageProfilePermission);
        $citizenRole->givePermissionTo($viewHistoryPermission);

        $this->command->info('Permisos asignados a roles.');


        // 4. Crear/Encontrar Usuarios y Asignar Roles
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@agendagob.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Super',
                'password' => Hash::make('password'),
                'identification_number' => '000-0000000-0',
                'sex' => 'Masculino',
                'role' => 'SuperAdmin', // <--- ¡ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ ASÍ Y ACTIVA!
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole($superAdminRole); // Esto asigna el rol de Spatie

        $this->command->info('SuperAdmin de prueba creado/actualizado.');


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
                'role' => 'Admin', // Ya revisamos esta línea, ¡asegúrate de que esté correcta!
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
                'role' => 'Citizen', // Ya revisamos esta línea, ¡asegúrate de que esté correcta!
                'is_active' => true,
            ]
        )->assignRole($citizenRole);

        $this->command->info('SuperAdmin, Admin, Ciudadano, Institución y Punto GOB de prueba creados/actualizados.');
    }
}