// Dentro de database/migrations/xxxx_xx_xx_xxxxxx_create_users_table_with_relations.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('identification_number', 20)->unique()->nullable();
            $table->enum('sex', ['Masculino', 'Femenino']);
            $table->enum('role', ['SuperAdmin', 'Admin', 'Citizen'])->default('Citizen');
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->onDelete('set null');
            $table->foreignId('punto_gob_id')->nullable()->constrained('punto_gobs')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};