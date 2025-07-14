<?php use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint; 
 use Illuminate\Support\Facades\Schema; 
 
 return new class extends Migration { 
    public function up(): void { Schema::create('services', function (Blueprint $table) { $table->id();
         $table->string('name'); $table->text('description')->nullable(); 
         $table->foreignId('institution_id')->constrained()->onDelete('cascade');
          $table->boolean('is_active')->default(true); $table->timestamps();
           $table->unique(['name', 'institution_id']); }); 
        }
         public function down(): void { Schema::dropIfExists('services'); 
        } 
    };


