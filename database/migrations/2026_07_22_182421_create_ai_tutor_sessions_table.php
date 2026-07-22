<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_tutor_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('input_type', ['text', 'image']);
            $table->text('input_text')->nullable();
            $table->string('input_image_path')->nullable();
            $table->json('ai_response')->nullable();
            $table->json('desmos_state')->nullable();
            $table->string('driver')->default('mock');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_tutor_sessions');
    }
};
