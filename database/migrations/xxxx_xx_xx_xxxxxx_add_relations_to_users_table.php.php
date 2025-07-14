<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::table('users', function (Blueprint $table) {
           
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