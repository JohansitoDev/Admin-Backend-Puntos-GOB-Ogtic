<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('institution_punto_gob', function (Blueprint $table) {
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('punto_gob_id')->constrained()->onDelete('cascade');
            $table->primary(['institution_id', 'punto_gob_id']); 
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('institution_punto_gob');
    }
};