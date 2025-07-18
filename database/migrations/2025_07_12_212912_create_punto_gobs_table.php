<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('punto_gobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('capacity')->nullable(); // Ej: 'Baja', 'Media', 'Alta'
            $table->string('status')->default('Activo'); // Ej: 'Activo', 'Inactivo', 'Mantenimiento'
            // !!! ESTA COLUMNA NO DEBE ESTAR AQUÍ PARA LA RELACIÓN MANY-TO-MANY !!!
            // $table->foreignId('institution_id')->constrained()->onDelete('cascade'); // ¡ELIMINA ESTA LÍNEA SI ESTÁ!
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punto_gobs');
    }
};