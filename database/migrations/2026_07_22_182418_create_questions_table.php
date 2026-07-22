<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['mcq', 'free_response']);
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->boolean('is_diagnostic')->default(false);
            $table->text('prompt');
            $table->string('image_path')->nullable();
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->json('desmos_expressions')->nullable();
            $table->string('source')->default('seed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
