<?php use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint; 
 use Illuminate\Support\Facades\Schema; 
 return new class extends Migration {
     public function up(): void { Schema::create('institutions', function (Blueprint $table) { 
        $table->id();
         $table->string('name')->unique(); 
        $table->string('phone', 50)->nullable();
         $table->string('institutional_email')->nullable(); 
         $table->string('contact_person_name')->nullable(); 
         $table->enum('status', ['Activo', 'Inactivo', 'Pendiente'])->default('Activo');
          $table->timestamps(); }); 
        } public function down(): void { Schema::dropIfExists('institutions'); } };



 