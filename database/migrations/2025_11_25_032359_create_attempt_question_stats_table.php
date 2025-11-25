<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('attempt_question_stats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');

            $table->string('topic_name');
            $table->integer('total_attempts')->default(0);
            $table->integer('correct_attempts')->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'topic_id']); // garante 1 linha por usuário/tópico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_question_stats');
    }
};
