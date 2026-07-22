<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diagnostic_result_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('context', ['practice', 'quiz', 'diagnostic']);
            $table->string('selected_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('time_spent_seconds')->nullable();
            $table->unsignedSmallInteger('xp_earned')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_attempts');
    }
};
