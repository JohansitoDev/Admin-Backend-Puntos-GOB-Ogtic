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
        // Limpiar caché de permisos (importante)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Intenta crear el primer permiso directamente ---
        try {
            Permission::firstOrCreate(
                ['name' => 'manage-all', 'guard_name' => 'sanctum'],
                ['description' => 'Permite gestionar todos los aspectos del sistema.']
            );
            Log::info('Permiso "manage-all" creado o ya existe.');
        } catch (\Exception $e) {
            Log::error("Error crítico al crear 'manage-all': " . $e->getMessage());
        }

        // --- Ahora, si 'manage-all' se creó, puedes llamar a tu helper para los demás ---
        $this->createPermission('view-dashboard-data', 'sanctum', 'Permite ver datos agregados y resúmenes del dashboard.');
        $this->createPermission('manage-profile', 'sanctum', 'Permite gestionar su propio perfil y contraseña.');
        $this->createPermission('manage-support-tickets', 'sanctum', 'Permite gestionar tickets de soporte.');
        $this->createPermission('view-history', 'sanctum', 'Permite ver historial de actividades y citas.');
        $this->createPermission('view-reports', 'sanctum', 'Permite generar y ver reportes.');
        // ... (el resto de tus llamadas a $this->createPermission) ...

        // --- Creación de Roles ---
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'sanctum']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);

        // --- Asignar Permisos al Rol 'superadmin' ---
        // Aquí es donde suele fallar si el permiso no se creó.
        $superAdminRole->givePermissionTo([
            'manage-all', // Esta línea es la que da el error
            // ... (el resto de tus asignaciones) ...
        ]);

        // ... (el resto del seeder) ...
    }

    // El método createPermission no cambia
    protected function createPermission(string $name, string $guardName, ?string $description = null)
    {
        try {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guardName],
                ['description' => $description] 
            );
        } catch (\Exception $e) {
            Log::error("Error al crear permiso '{$name}': " . $e->getMessage());
        }
    }
}

