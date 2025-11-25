<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('question_topic')) {
            Schema::create('question_topic', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->foreignId('topic_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['question_id', 'topic_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('question_topic');
    }
};

