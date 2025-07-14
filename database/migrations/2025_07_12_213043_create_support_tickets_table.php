<?php use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint; 
 use Illuminate\Support\Facades\Schema;

  return new class extends Migration {
     public function up(): void { Schema::create('support_tickets', function (Blueprint $table) { $table->id(); 
        $table->text('description'); 
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
         $table->enum('priority', ['Urgente', 'Ordinaria', 'Prioritaria'])->default('Ordinaria'); 
         $table->enum('status', ['Abierto', 'Asignado', 'Escalado', 'Cerrado'])->default('Abierto');
          $table->timestamps(); 
          $table->timestamp('closed_at')->nullable(); });
         } public function down(): void { Schema::dropIfExists('support_tickets'); 
        }
     };


     