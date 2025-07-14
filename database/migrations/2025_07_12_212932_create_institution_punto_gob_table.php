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
        Schema::create('institution_punto_gob', function (Blueprint $table) {
            // ¡ELIMINA LA LÍNEA $table->id(); DE AQUÍ!
            // $table->id(); // <--- ¡BORRA ESTA LÍNEA!

            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->foreignId('punto_gob_id')->constrained('punto_gobs')->onDelete('cascade');

            // Define la clave primaria compuesta para estas dos columnas
            $table->primary(['institution_id', 'punto_gob_id']);

            $table->timestamps(); // Opcional, pero buena práctica para tablas pivote
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_punto_gob');
    }
};