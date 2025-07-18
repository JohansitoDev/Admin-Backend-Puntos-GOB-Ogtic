<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        // 1. Limpiar caché de permisos al inicio (fundamental)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Log::info('Caché de permisos limpiada al inicio del seeder.');

        // 2. Definir todos los permisos en un array
        $permissionsToCreate = [
            'manage-all' => 'Permite gestionar todos los aspectos del sistema.',
            'view-dashboard-data' => 'Permite ver datos agregados y resúmenes del dashboard.',
            'manage-profile' => 'Permite gestionar su propio perfil y contraseña.',
            'manage-support-tickets' => 'Permite gestionar tickets de soporte.',
            'view-history' => 'Permite ver historial de actividades y citas.',
            'view-reports' => 'Permite generar y ver reportes.',
            'manage-institutions' => 'Permite crear, leer, actualizar y eliminar instituciones.',
            'manage-services' => 'Permite crear, leer, actualizar y eliminar servicios.',
            'manage-users' => 'Permite crear, leer, actualizar y eliminar usuarios administrativos.',
            'manage-roles' => 'Permite crear, leer, actualizar y eliminar roles.',
            'view-permissions' => 'Permite ver la lista de permisos disponibles.',
            'manage-punto-gobs' => 'Permite crear, leer, actualizar y eliminar Puntos GOB.',
            'is-admin' => 'Indica si un usuario es un administrador de institución.',
            'manage-own-institutions' => 'Permite gestionar instituciones a las que está asignado.',
            'manage-own-punto-gobs' => 'Permite gestionar Puntos GOB de su institución.',
            'manage-appointments' => 'Permite gestionar citas (aprobar, cancelar, etc.).',
        ];

        // 3. Crear/encontrar todos los permisos
        // Usamos una transacción para asegurar que la creación sea atómica y que Spatie
        // lo vea todo junto.
        \DB::transaction(function () use ($permissionsToCreate) {
            foreach ($permissionsToCreate as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'sanctum'],
                    ['description' => $description] 
                );
                // Log::info("Permiso '{$name}' (sanctum) creado/encontrado."); // Para depuración adicional si es necesario
            }
        });
        Log::info('Todos los permisos han sido procesados para creación en una transacción.');

        // 4. Limpiar caché de permisos por segunda vez, justo antes de asignar (CRÍTICO)
        // Esto es para forzar a Spatie a recargar los permisos de la DB.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Log::info('Caché de permisos refrescada por segunda vez antes de asignar.');

        // 5. Creación de Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'sanctum']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        Log::info('Roles superadmin y admin creados/encontrados.');

        // 6. Asignar Permisos al Rol 'superadmin'
        // Verificación de existencia para depuración (opcional, pero útil si falla)
        $manageAllPermissionCheck = Permission::where('name', 'manage-all')->where('guard_name', 'sanctum')->first();
        if ($manageAllPermissionCheck) {
            Log::info("VERIFICACIÓN: 'manage-all' SÍ EXISTE en la DB. ID: " . $manageAllPermissionCheck->id);
        } else {
            Log::error("VERIFICACIÓN: ¡CRÍTICO! 'manage-all' NO FUE ENCONTRADO en la DB antes de asignación. Abortando seeder.");
            // Si esto se activa, el problema es que firstOrCreate NO ESTÁ INSERTANDO EL PERMISO.
            // dd("ERROR FATAL: 'manage-all' no se creó en la DB.");
        }

        // Asignar todos los permisos al superadmin
        $superAdminRole->givePermissionTo(array_keys($permissionsToCreate)); 
        Log::info('Permisos asignados a superadmin.');

        // 7. Asignar Permisos al Rol 'admin'
        $adminRole->givePermissionTo([
            'is-admin',
            'view-dashboard-data',
            'manage-profile',
            'manage-support-tickets',
            'view-history',
            'view-reports',
            'manage-own-institutions',
            'manage-own-punto-gobs',
            'manage-appointments',
        ]);
        Log::info('Permisos asignados a admin.');

        Log::info('Permisos y roles base creados/actualizados exitosamente. Seeder finalizado.');
    }

    // No necesitamos el helper createPermission, lo hemos inlined en run()
}