<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {

            // Primeiro remove a FOREIGN KEY
            if (Schema::hasColumn('questions', 'topic_id')) {
                $table->dropForeign(['topic_id']);  
                $table->dropColumn('topic_id');      
            }

        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id')->nullable();

            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onDelete('set null');
        });
    }
};

