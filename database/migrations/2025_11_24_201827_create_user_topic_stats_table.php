<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_topic_stats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->constrained()
                ->cascadeOnDelete();

            // total de questões respondidas nesse tópico
            $table->unsignedInteger('total_attempts')->default(0);

            // total de acertos nesse tópico
            $table->unsignedInteger('correct_attempts')->default(0);

            $table->timestamps();

            // um registro por user + topic
            $table->unique(['user_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_topic_stats');
    }
};
