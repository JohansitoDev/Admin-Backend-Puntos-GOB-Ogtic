<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ¡IMPORTANTE! Debe ser Schema::table, no Schema::create
        Schema::table('users', function (Blueprint $table) {
            // Añadir las columnas solo si no existen para evitar errores en fresh
            if (!Schema::hasColumn('users', 'institution_id')) {
                $table->foreignId('institution_id')->nullable()->constrained('institutions')->onDelete('set null')->after('role');
            }
            if (!Schema::hasColumn('users', 'punto_gob_id')) {
                $table->foreignId('punto_gob_id')->nullable()->constrained('punto_gobs')->onDelete('set null')->after('institution_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar las claves foráneas primero antes de eliminar las columnas
            if (Schema::hasColumn('users', 'institution_id')) {
                $table->dropForeign(['institution_id']);
                $table->dropColumn('institution_id');
            }
            if (Schema::hasColumn('users', 'punto_gob_id')) {
                $table->dropForeign(['punto_gob_id']);
                $table->dropColumn('punto_gob_id');
            }
        });
    }
};