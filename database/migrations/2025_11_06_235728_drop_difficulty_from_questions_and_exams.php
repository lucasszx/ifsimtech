<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions','difficulty')) {
                $table->dropColumn('difficulty');
            }
        });
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams','difficulty')) {
                $table->dropColumn('difficulty');
            }
        });
    }
    public function down(): void {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('difficulty', 1)->nullable(); // se precisar restaurar
        });
        Schema::table('exams', function (Blueprint $table) {
            $table->string('difficulty', 1)->nullable();
        });
    }
};
